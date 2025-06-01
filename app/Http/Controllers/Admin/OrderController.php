<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('status', $request->filter);
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
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest('time');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage)->withQueryString();

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
