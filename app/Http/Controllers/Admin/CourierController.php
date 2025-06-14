<?php

namespace App\Http\Controllers\Admin;

use App\Models\Courier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\CourierExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
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
        $validated = $request->validate(
            [
                'name' => 'required|string|regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/|unique:couriers,name',
                'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Nama hanya boleh mengandung huruf',
                'name.unique' => 'Nama supplier ini sudah ada',
                'phone.required' => 'Telepon wajib diisi',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
            ],
        );

        Courier::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'phone' => $validated['phone'],
            'email' => $validated['email'],
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
    public function edit(Courier $courier)
    {
        return view('admin.courier.edit', compact('courier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Courier $courier)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/', Rule::unique('couriers')->ignore($courier->id)],
                'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Nama hanya boleh mengandung huruf',
                'name.unique' => 'Nama supplier ini sudah ada',
                'phone.required' => 'Telepon wajib diisi',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
            ],
        );

        $courier->name = $validated['name'];
        $courier->phone = $validated['phone'];
        $courier->email = $validated['email'];

        $courier->save();

        return redirect()->route('courier.index')->with('success', 'Data kurir berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier)
    {
        $courier->delete();

        return redirect()->route('courier.index')->with('success', 'Data kurir berhasil dihapus');
    }

    public function print()
    {
        $couriers = Courier::latest()->get();
        return view('admin.courier.print', compact('couriers'));
    }

    public function export()
    {
        return Excel::download(new CourierExport(), 'daftar_kurir.xlsx');
    }
}
