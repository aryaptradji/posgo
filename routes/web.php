<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/produk', function () {
    return view('produk');
});

Route::get('/riwayat', function () {
    return view('riwayat');
});

Route::get('/retur', function () {
    return view('retur');
});

Route::get('/pemasukan', function () {
    return view('pemasukan');
});
 Route::get('/pengeluaran', function () {
     return view('pengeluaran');
 });
