<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ExpenseExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with('product');

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
        $expenses = $query->paginate($perPage)->withQueryString();

        return view('admin.expense.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::pluck('name')->toArray();
        return view('admin.expense.create', compact('products'));
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

        Expense::create([
            'product_id' => $productId,
            'date' => $validated['date'],
            'source' => $validated['source'],
            'category' => $validated['category'],
            'total' => $validated['total'],
        ]);

        return redirect()->route('expense.index')->with('success', 'Data pengeluaran berhasil ditambahkan!');
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
    public function edit(Expense $expense)
    {
        $products = Product::pluck('name')->toArray();
        return view('admin.expense.edit', compact('products', 'expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
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

        // Simpan perubahan
        $expense->update([
            'product_id' => $productId,
            'date' => $validated['date'],
            'source' => $validated['source'],
            'category' => $validated['category'],
            'total' => $validated['total'],
        ]);

        return redirect()->route('expense.index')->with('success', 'Data pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function print()
    {
        $expenses = Expense::latest('date')->get();

        return view('admin.expense.print', compact('expenses'));
    }

    public function export()
    {
        return Excel::download(new ExpenseExport(), 'daftar_pengeluaran.xlsx');
    }
}
