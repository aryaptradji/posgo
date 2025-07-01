<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Revenue;
use Illuminate\Http\Request;
use App\Exports\ExpenseExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Revenue::with('product');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('category', $request->filter);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('source', 'like', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest('date');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $revenues = $query->paginate($perPage)->withQueryString();

        return view('admin.revenue.index', compact('revenues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::pluck('name')->toArray();
        return view('admin.revenue.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'date' => 'required|date',
                'source' => 'required|not_in:Pilih Salah Satu|string|max:25',
                'category' => 'required|not_in:Pilih Salah Satu',
                'total' => 'required|numeric|min:0',
            ],
            [
                'date.required' => 'Waktu wajib diisi',
                'source.required' => 'Sumber wajib diisi',
                'source.not_in' => 'Sumber wajib diisi',
                'source.max' => 'Sumber maksimal 25 huruf',
                'category.required' => 'Kategori wajib diisi',
                'category.not_in' => 'Kategori wajib diisi',
            ],
        );

        $productId = Product::where('name', $validated['source'])->value('id');

        Revenue::create([
            'product_id' => $productId,
            'date' => $validated['date'],
            'source' => $validated['source'],
            'category' => $validated['category'],
            'total' => $validated['total'],
        ]);

        return redirect()->route('revenue.index')->with('success', 'Data pemasukan berhasil ditambahkan');
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
    public function edit(Revenue $revenue)
    {
        $products = Product::pluck('name')->toArray();
        return view('admin.revenue.edit', compact('products', 'revenue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Revenue $revenue)
    {
        $validated = $request->validate(
            [
                'date' => 'required|date',
                'source' => 'required|not_in:Pilih Salah Satu|string|max:25',
                'category' => 'required|not_in:Pilih Salah Satu',
                'total' => 'required|numeric|min:0',
            ],
            [
                'date.required' => 'Waktu wajib diisi',
                'source.required' => 'Sumber wajib diisi',
                'source.not_in' => 'Sumber wajib diisi',
                'source.max' => 'Sumber maksimal 25 huruf',
                'category.required' => 'Kategori wajib diisi',
                'category.not_in' => 'Kategori wajib diisi',
            ],
        );

        $productId = Product::where('name', $validated['source'])->value('id');

        $revenue->update([
            'product_id' => $productId,
            'date' => $validated['date'],
            'source' => $validated['source'],
            'category' => $validated['category'],
            'total' => $validated['total'],
        ]);

        return redirect()->route('revenue.index')->with('success', 'Data pemasukan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        return redirect()->route('revenue.index')->with('success', 'Data pemasukan berhasil dihapus');
    }

    public function print()
    {
        $revenues = Revenue::with('product')->latest('date')->get();

        return view('admin.revenue.print', compact('revenues'));
    }

    public function export()
    {
        return Excel::download(new ExpenseExport(), 'daftar_pemasukan.xlsx');
    }
}
