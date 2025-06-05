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

        $orders = $query->paginate(10)->withQueryString();

        return view('customer.order.index', compact('orders'));
    }
}
