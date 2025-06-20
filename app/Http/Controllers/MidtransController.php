<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        Log::debug('🚀 Midtrans callback received', $request->all());

        // Ambil data yang diperlukan
        $serverKey = config('midtrans.server_key');
        $orderIdMidtrans = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signature = $request->signature_key;

        // Hitung signature yang valid
        $expectedSignature = hash('sha512', $orderIdMidtrans . $statusCode . $grossAmount . $serverKey);

        Log::debug('🔐 Signature debug', [
            'order_id' => $orderIdMidtrans,
            'expected' => $expectedSignature,
            'actual' => $signature,
        ]);

        if ($expectedSignature !== $signature) {
            Log::warning('❌ Invalid Midtrans signature', ['order_id' => $orderIdMidtrans]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order
        $order = Order::where('snap_order_id', $orderIdMidtrans)->first();

        if (!$order) {
            Log::warning('❌ Order not found', ['order_id' => $orderIdMidtrans]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        Log::debug('🔍 Sebelum save:', [
            'payment_status (class)' => $order->payment_status,
            'payment_status (value)' => $order->payment_status ?? null,
            'payment_status (raw)' => $order->getRawOriginal('payment_status'),
        ]);

        // Status
        $status = match ($request->transaction_status) {
            'capture', 'settlement' => 'dibayar',
            'expire' => 'kadaluwarsa',
            'deny' => 'ditolak',
            'pending' => 'belum dibayar',
            default => null,
        };

        if (!$status) {
            Log::warning('⚠️ Unhandled transaction_status from Midtrans', [
                'transaction_status' => $request->transaction_status,
                'order_id' => $orderIdMidtrans,
            ]);
            return response()->json(['message' => 'Unhandled transaction status'], 400);
        }

        // Update jika berbeda
        if ($order->payment_status !== $status) {
            $order->payment_status = $status;
            $order->snap_token = null;
            $order->snap_expires_at = null;

            Log::info('🆕 Payment status updated', [
                'order_id' => $orderIdMidtrans,
                'new_status' => $status,
            ]);
        } else {
            Log::info('ℹ️ Status unchanged', [
                'order_id' => $orderIdMidtrans,
                'status' => $status,
            ]);
        }

        $order->payment_method = $this->resolvePaymentMethod($request);

        $order->save();

        return response()->json(['message' => 'Callback processed'], 200);
    }

    protected function resolvePaymentMethod(Request $request): string
    {
        return match ($request->payment_type) {
            'bank_transfer' => $this->resolveBankName($request->va_numbers[0]['bank'] ?? null),
            'echannel' => 'Bank Mandiri',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => $this->resolveQrisIssuer($request->issuer ?? ($request->acquirer ?? null)),
            default => ucfirst(str_replace('_', ' ', $request->payment_type)),
        };
    }

    protected function resolveBankName(?string $bankCode): string
    {
        return match (strtolower($bankCode)) {
            'bca' => 'Bank BCA',
            'bni' => 'Bank BNI',
            'mandiri' => 'Bank Mandiri',
            default => 'Bank ' . strtoupper($bankCode),
        };
    }

    protected function resolveQrisIssuer(?string $issuer): string
    {
        return match (strtolower($issuer)) {
            'gopay' => 'QRIS GoPay',
            'airpay shopee', 'shopeepay' => 'QRIS ShopeePay',
            default => 'QRIS',
        };
    }
}
