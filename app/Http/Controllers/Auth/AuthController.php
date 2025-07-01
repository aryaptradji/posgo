<?php

namespace App\Http\Controllers\Auth;

use App\Models\City;
use App\Models\User;
use App\Models\Address;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Support\Str;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Registrasi pengguna baru
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/',
                    Rule::unique('users')->where(fn ($q) => $q->where('role', 'customer')),
                    'max:50'
                ],
                'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
                'address' => 'required|string|max:75',
                'city' => 'required|exists:cities,slug',
                'district' => 'required|exists:districts,slug',
                'sub_district' => 'required|exists:sub_districts,slug',
                'rt' => 'required|string|regex:/^[0-9]{3}$/',
                'rw' => 'required|string|regex:/^[0-9]{3}$/',
                'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                    Rule::unique('users')->where(fn ($q) => $q->where('role', 'customer'))
                ],
                'password' => 'required|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*._]).{8,}$/'
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Format nama masih salah',
                'name.unique' => 'Nama ini sudah terdaftar',
                'name.max' => 'Nama maksimal 50 huruf',
                'phone.required' => 'Nomor telepon wajib diisi',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'address.required' => 'Alamat wajib diisi',
                'address.max' => 'Alamat maksimal 75 huruf',
                'city.required' => 'Kota wajib diisi',
                'district.required' => 'Kecamatan wajib diisi',
                'sub_district.required' => 'Kelurahan wajib diisi',
                'rt.required' => 'Nomor RT wajib diisi',
                'rt.regex' => 'Format nomor RT tidak valid',
                'rw.required' => 'Nomor RW wajib diisi',
                'rw.regex' => 'Format nomor RW tidak valid',
                'postal_code.required' => 'Kode pos wajib diisi',
                'postal_code.regex' => 'Format kode pos tidak valid',
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
                'email.unique' => 'Email ini sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.regex' => 'Format password tidak valid'
            ],
        );

        if ($validator->fails()) {
            // Tentukan step berdasarkan field error
            $errors = $validator->errors();
            $step = 0;

            if ($errors->hasAny(['name', 'phone', 'address'])) {
                $step = 0;
            } else if ($errors->hasAny(['city', 'district', 'sub_district', 'rt', 'rw', 'postal_code'])) {
                $step = 1;
            } else if ($errors->hasAny(['email', 'password'])) {
                $step = 2;
            }

            return redirect()
                ->route('register', ['step' => $step])
                ->withErrors($validator)
                ->withInput();
        }

        $subDistrict = SubDistrict::where('slug', $request->sub_district)->first();

        $neighborhood = Neighborhood::create([
            'sub_district_id' => $subDistrict->id,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'postal_code' => $request->postal_code
        ]);

        $address = Address::create([
            'neighborhood_id' => $neighborhood->id,
            'street' => $request->address,
        ]);

        // Simpan user
        User::create([
            'created' => now(),
            'name' => ucwords($request->name),
            'slug' => Str::slug($request->name),
            'phone_number' => $request->phone,
            'address_id' => $address->id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect ke halaman login
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat');
    }

    // Login pengguna
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
            ],
        );

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email belum terdaftar'])
                ->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah'])
                ->withInput();
        }

        Auth::login($user);

        session()->flash('success', 'Selamat datang, ' . $user->name . '!');

        return match ($user->role) {
            'admin' => redirect()->route('dashboard'),
            'cashier' => redirect()->route('pos-menu'),
            'customer' => redirect()->route('customer.home'),
            default => abort(403, 'Role tidak dikenali.'),
        };
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout(); // keluarin user dari session

        $request->session()->invalidate(); // invalidate session biar aman
        $request->session()->regenerateToken(); // regenerate CSRF token

        return redirect()->route('login')->with('success', 'Kamu berhasil logout');
    }
}
