<?php

namespace App\Http\Controllers\Admin;

use App\Models\Courier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Courier::query();

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
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
        $couriers = $query->paginate($perPage)->withQueryString();


        return view('admin.courier.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/|unique:couriers,name',
            'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'address' => 'required|string'
        ],[
            'name.required' => 'Nama wajib diisi',
            'name.regex' => 'Nama hanya boleh mengandung huruf',
            'name.unique' => 'Nama supplier ini sudah ada',
            'phone.required' => 'Telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'email.required' => 'Email wajib diisi',
            'email.regex' => 'Format email tidak valid',
            'address.required' => 'Alamat wajib diisi'
        ]);

        Courier::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address']
        ]);

        return redirect()->route('courier.index')->with('success', 'Data kurir berhasil dibuat');
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
