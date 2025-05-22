<?php

use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Route Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->name('dashboard.index');

Route::resource('/product', ProductController::class);

Route::resource('/expense', ExpenseController::class);

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

Route::get('/kasir', function () {
    return view('kasir');
});

// Route Kasir
Route::get('/kasir/dashboard', function () {
    return view('kasir.dashboard.index');
});
