<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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
        $perPage = $request->input('per_page', 5);
        $orders = $query->paginate($perPage)->withQueryString();

        return view('admin.order.index', compact('orders'));
    }

    public function print()
    {
        $orders = Order::with(['user', 'items'])
            ->orderBy('time', 'desc')
            ->get();

        return view('admin.order.print', compact('orders'));
    }

    public function export()
    {
        return Excel::download(new OrderExport(), 'daftar_pesanan.xlsx');
    }

    public function invoice(Order $order)
    {
        $order->load(['user', 'items.product']);

        $pdf = Pdf::loadView('admin.order.invoice', compact('order'));

        // Stream di browser:
        return $pdf->stream('Invoice-' . $order->code . '.pdf');

        // Kalau mau langsung download:
        // return $pdf->download('Invoice-'.$order->code.'.pdf');
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
