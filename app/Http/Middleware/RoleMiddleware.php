<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan pengguna sudah terautentikasi dan role-nya sesuai
        if (Auth::check() && Auth::user()->role !== $role) {
            // Jika role pengguna tidak sesuai, arahkan ke halaman lain (misal homepage)
            return redirect('/');
        }

        return $next($request); // Lanjutkan ke request berikutnya jika role sesuai
    }
}
