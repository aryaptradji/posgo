<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\CashierExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class CashierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('address')->where('role', 'cashier');

        // Pencarian
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->latest('created');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $cashiers = $query->paginate($perPage)->withQueryString();

        return view('admin.cashier.index', compact('cashiers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cashier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/',
                    Rule::unique('users')->where(fn ($q) => $q->where('role', 'cashier')),
                    'max:50'
                ],
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email',
                'phone_number' => 'required|regex:/^08[0-9]{8,13}$/',
                'password' => 'required|string|min:6',
                'photo' => 'nullable|image|mimes:jpg,jpeg|max:2048',
                'role' => 'required|in:cashier'
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Nama hanya boleh mengandung huruf',
                'name.unique' => 'Nama kasir ini sudah digunakan',
                'name.max' => 'Nama maksimal 50 huruf',
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
                'email.unique' => 'Email ini sudah digunakan',
                'phone_number.required' => 'Nomor handphone wajib diisi',
                'phone_number.regex' => 'Format nomor handphone tidak valid',
                'password.required' => 'Password wajib diisi',
                'photo.image' => 'File harus berbentuk gambar',
                'photo.mimes' => 'Format foto harus .jpg/.jpeg',
                'photo.max' => 'Size foto maksimal 2 mb'
            ],
        );

        // Simpan foto kalau diupload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        // Hash password dan simpan versi plain jika kasir
        $data['password'] = Hash::make($data['password']);
        $data['plaintext_password'] = $data['role'] === 'cashier' ? $request->password : null;

        // Buat user
        User::create([
            'created' => now(),
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'email' => $data['email'],
            'password' => $data['password'],
            'plaintext_password' => $data['plaintext_password'],
            'phone_number' => $data['phone_number'],
            'photo' => $data['photo'] ?? null,
            'role' => $data['role']
        ]);

        return redirect()->route('cashier.index')->with('success', 'Akun kasir berhasil dibuat');
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
    public function edit(User $cashier)
    {
        $sizeInKB = null;

        if ($cashier->image && Storage::disk('public')->exists($cashier->image)) {
            $sizeInKB = round(Storage::disk('public')->size($cashier->image) / 1024);
        }

        return view('admin.cashier.edit', compact('cashier', 'sizeInKB'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $cashier)
    {
        // Validasi input
        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z]+[a-zA-Z\s]*$/',
                    Rule::unique('users')->ignore($cashier->id)->where(fn ($q) => $q->where('role', 'cashier')),
                    'max:50'
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($cashier->id)
                ],
                'phone_number' => 'required|regex:/^08[0-9]{8,13}$/',
                'password' => 'required|string|min:6',
                'photo' => 'nullable|image|mimes:jpg,jpeg|max:2048',
                'role' => 'required|in:cashier'
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Nama hanya boleh mengandung huruf',
                'name.unique' => 'Nama kasir ini sudah digunakan',
                'name.max' => 'Nama maksimal 50 huruf',
                'email.required' => 'Email wajib diisi',
                'email.unique' => 'Email ini sudah digunakan',
                'phone_number.required' => 'Nomor handphone wajib diisi',
                'phone_number.regex' => 'Format nomor handphone tidak valid',
                'password.required' => 'Password wajib diisi',
                'photo.image' => 'File harus berbentuk gambar',
                'photo.mimes' => 'Format foto harus .jpg/.jpeg',
                'photo.max' => 'Size foto maksimal 2 mb'
            ],
        );

        // Update akun kasir
        $cashier->name = $data['name'];
        $cashier->slug = Str::slug($data['name']);
        $cashier->email = $data['email'];
        $cashier->phone_number = $data['phone_number'];
        $cashier->password = Hash::make($data['password']);
        $cashier->plaintext_password = $data['role'] === 'cashier' ? $request->password : null;

        // Jika upload gambar baru, ganti
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('cashiers', 'public');
            $cashier->photo = $imagePath;
        }

        $cashier->save();

        return redirect()->route('cashier.index')->with('success', 'Akun kasir berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $cashier)
    {
        if ($cashier->image && Storage::disk('public')->exists($cashier->image)) {
            Storage::disk('public')->delete($cashier->image);
        }

        $cashier->delete();

        return redirect()->route('cashier.index')->with('success', 'Akun kasir berhasil dihapus');
    }

    public function print()
    {
        $cashiers = User::where('role', 'cashier')->orderBy('created', 'desc')->get();

        return view('admin.cashier.print', compact('cashiers'));
    }

    public function export()
    {
        return Excel::download(new CashierExport(), 'daftar_kasir.xlsx');
    }
}
