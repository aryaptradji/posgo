<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])
            ->where('user_id', Auth::id())
            ->latest('time');

        // Filter Status
        if ($request->status == 'selesai') {
            $query->selesai();
        } elseif ($request->status == 'dikemas') {
            $query->dikemas();
        } elseif ($request->status == 'dikirim') {
            $query->dikirim();
        } elseif ($request->status == 'batal') {
            $query->batal();
        } else {
            $query->belumDibayar();
        }

        // Search
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage)->withQueryString();

        return view('customer.order.index', compact('orders'));
    }

    public function expire(Order $order)
    {
        $order->update([
            'payment_status' => 'kadaluwarsa',
            'snap_token' => null,
            'snap_expires_at' => null,
        ]);

        return redirect()->route('customer.order.index', ['status' => 'batal']);
    }
}
