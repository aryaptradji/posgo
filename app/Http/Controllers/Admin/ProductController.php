<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter stok
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where(function ($q) use ($request) {
                if ($request->filter === 'habis') {
                    $q->where('stock', 0);
                } elseif ($request->filter === 'sedikit') {
                    $q->whereBetween('stock', [1, 5]);
                } elseif ($request->filter === 'banyak') {
                    $q->where('stock', '>', 5);
                }
            });
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $products = $query->paginate($perPage)->withQueryString();

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
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
