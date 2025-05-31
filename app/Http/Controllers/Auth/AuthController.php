<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registrasi pengguna baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Login pengguna
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'password' => 'required|min:6',
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
            'admin' => redirect()->route('admin.dashboard'),
            'cashier' => redirect()->route('kasir.dashboard'),
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
