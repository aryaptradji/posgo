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
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signature = $request->signature_key;

        // Hitung signature yang valid
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        Log::debug('🔐 Signature debug', [
            'order_id' => $orderId,
            'expected' => $expectedSignature,
            'actual' => $signature,
        ]);

        if ($expectedSignature !== $signature) {
            Log::warning('❌ Invalid Midtrans signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order
        $order = Order::where('code', $orderId)->first();

        if (!$order) {
            Log::warning('❌ Order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        Log::debug('🔍 Sebelum save:', [
            'payment_status (class)' => $order->payment_status,
            'payment_status (value)' => $order->payment_status ?? null,
            'payment_status (raw)' => $order->getRawOriginal('payment_status'),
        ]);

        // Tentukan status baru
        $status = match ($request->transaction_status) {
            'capture', 'settlement' => 'dibayar',
            'cancel' => 'dibatalkan',
            'expire' => 'kadaluwarsa',
            'deny' => 'ditolak',
            'pending' => 'belum dibayar',
            default => null,
        };

        if (!$status) {
            Log::warning('⚠️ Unhandled transaction_status from Midtrans', [
                'transaction_status' => $request->transaction_status,
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Unhandled transaction status'], 400);
        }

        // Update jika berbeda
        if ($order->payment_status !== $status) {
            $order->payment_status = $status;
            $order->save();

            Log::info('🆕 Payment status updated', [
                'order_id' => $orderId,
                'new_status' => $status,
            ]);
        } else {
            Log::info('ℹ️ Status unchanged', [
                'order_id' => $orderId,
                'status' => $status,
            ]);
        }

        return response()->json(['message' => 'Callback processed'], 200);
    }
}
