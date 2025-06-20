<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier', 'items');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('status', $request->filter);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query
                ->join('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->where(function ($q) use ($request) {
                    $search = '%' . $request->search . '%';
                    $q->where('suppliers.name', 'like', $search)->orWhere('purchase_orders.code', 'like', $search);
                })
                ->select('purchase_orders.*');
        }

        // Sorting
        if ($request->filled('sort') && $request->sort === 'supplier') {
            $query
                ->join('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->orderBy('suppliers.name', $request->boolean('desc') ? 'desc' : 'asc')
                ->select('purchase_orders.*');
        } else {
            $query->orderBy($request->sort ?? 'created', $request->boolean('desc') ? 'desc' : 'asc');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $purchase_orders = $query->paginate($perPage)->withQueryString();

        return view('admin.purchase-order.index', compact('purchase_orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::select('slug', 'name')->get();
        $products = Product::select('slug', 'name')->get();

        return view('admin.purchase-order.create', compact('suppliers', 'products'));
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
