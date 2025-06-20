<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product')->where('category', 'offline');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('payment_status', $request->filter);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where(function ($order) use ($request) {
                    $search = '%' . $request->search . '%';
                    $order->where('users.name', 'like', $search)->orWhere('code', 'like', $search);
                })
                ->select('orders.*');
        }

        // Sorting
        if ($request->filled('sort') && $request->sort === 'name') {
            $query
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->orderBy('users.name', $request->boolean('desc') ? 'desc' : 'asc')
                ->select('orders.*');
        } elseif ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest('time');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $transactions = $query->paginate($perPage)->withQueryString();

        return view('cashier.transaction.index', compact('transactions'));
    }

    public function receipt(Order $order)
    {
        $order->load('items.product', 'user.address.neighborhood.subDistrict.district.city');

        return view('cashier.transaction.receipt', compact('order'));
    }

    public function printReceipt(Order $order)
    {
        $order->load('items.product', 'user.address.neighborhood.subDistrict.district.city');

        return view('cashier.transaction.print-receipt', ['transaction' => $order]);
    }
}
