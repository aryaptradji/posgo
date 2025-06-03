<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Optional: log buat debugg
        Log::info('Midtrans callback received:', $request->all());

        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        // Cek signature dulu (penting!)
        if ($signatureKey !== $request->signature_key) {
            Log::warning('Midtrans signature mismatch');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari order dari kode
        $order = Order::where('code', $request->order_id)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status
        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $order->payment_status = PaymentStatus::Dibayar;
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $order->payment_status = PaymentStatus::Batal;
                break;

            case 'pending':
            default:
                $order->payment_status = PaymentStatus::BelumDibayar;
                break;
        }

        $order->save();

        return response()->json(['message' => 'Callback processed']);
    }
}
