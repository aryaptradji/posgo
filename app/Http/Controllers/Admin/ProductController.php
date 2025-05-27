<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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
        // Validasi input
        $validated = $request->validate(
            [
                'name' => 'required|string|regex:/^[a-zA-Z0-9.()\s]+$/|unique:products,name',
                'stock' => 'required|integer|min:0',
                'pcs' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'image' => 'required|image|mimes:png|max:2048',
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Tidak boleh mengandung karakter khusus',
                'name.unique' => 'Nama produk ini sudah ada',
                'image.required' => 'Gambar wajib diisi',
                'image.image' => 'File harus berbentuk gambar',
                'image.mimes' => 'Format gambar harus .png',
                'image.max' => 'Size gambar maksimal 2 mb'
            ],
        );

        // Simpan gambar
        $imagePath = $request->file('image')->store('products', 'public');

        // Simpan ke database
        Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'stock' => $validated['stock'],
            'pcs' => $validated['pcs'],
            'price' => $validated['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan');
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
    public function edit(Product $product)
    {
        $sizeInKB = null;

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            $sizeInKB = round(Storage::disk('public')->size($product->image) / 1024);
        }

        return view('admin.product.edit', compact('product', 'sizeInKB'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validasi input
        $validated = $request->validate(
            [
                'name' => 'required|string|regex:/^[a-zA-Z0-9.()\s]+$/|unique:products,name,' . $product->id . ',id',
                'stock' => 'required|integer|min:0',
                'pcs' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:png|max:2048',
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Tidak boleh mengandung karakter khusus',
                'name.unique' => 'Nama produk ini sudah ada',
                'image.image' => 'File harus berbentuk gambar',
                'image.mimes' => 'Format gambar harus .png',
            ],
        );

        // Update data produk
        $product->name = $validated['name'];
        $product->slug = Str::slug($validated['name']);
        $product->stock = $validated['stock'];
        $product->pcs = $validated['pcs'];
        $product->price = $validated['price'];

        // Kalau upload gambar baru, ganti
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus gambar dari storage (kalau perlu)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus dari database
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus');
    }

    public function print()
    {
        $products = Product::latest()->get();

        return view('admin.product.print', compact('products'));
    }

    public function export()
    {
        return Excel::download(new ProductExport(), 'daftar_produk.xlsx');
    }
}
