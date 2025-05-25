<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('fax', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $suppliers = $query->paginate($perPage)->withQueryString();

        return view('admin.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:suppliers,name',
            'phone' => 'required|string|regex:/^08[0-9]{8,13}$/|unique:suppliers,phone',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:suppliers,email',
            'fax' => 'nullable|string|unique:suppliers,fax',
            'address' => 'required|string'
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.unique' => 'Nama supplier ini sudah ada',
            'phone.required' => 'Telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'phone.unique' => 'Nomor telepon ini sudah ada',
            'email.required' => 'Email wajib diisi',
            'email.regex' => 'Format email tidak valid',
            'email.unique' => 'Email ini sudah digunakan',
            'fax.unique' => 'Nomor fax ini sudah digunakan',
            'address.required' => 'Alamat wajib diisi'
        ]);

        Supplier::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'fax' => $validated['fax'] ?? '-',
            'address' => $validated['address']
        ]);

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil dibuat');
    }

    /*
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        if ($supplier->fax == '-') {
            $supplier->fax = '';
        }

        return view('admin.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('suppliers')->ignore($supplier->id)
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,13}$/',
                Rule::unique('suppliers')->ignore($supplier->id)
            ],
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('suppliers')->ignore($supplier->id)
            ],
            'fax' => [
                'nullable',
                'string',
                Rule::unique('suppliers')->ignore($supplier->id)
            ],
            'address' => 'required|string'
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.unique' => 'Nama supplier ini sudah ada',
            'phone.required' => 'Telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'phone.unique' => 'Nomor telepon ini sudah ada',
            'email.required' => 'Email wajib diisi',
            'email.regex' => 'Format email tidak valid',
            'email.unique' => 'Email ini sudah digunakan',
            'fax.unique' => 'Nomor fax ini sudah digunakan',
            'address.required' => 'Alamat wajib diisi'
        ]);

        $supplier->name = $validated['name'];
        $supplier->phone = $validated['phone'];
        $supplier->email = $validated['email'];
        $supplier->fax = $validated['fax'] ?? '-';
        $supplier->address = $validated['address'];

        $supplier->save();

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil dihapus');
    }
}
