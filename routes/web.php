<?php

use App\Models\Expense;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;

// Auth
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'cashier' => redirect()->route('kasir.dashboard'),
        'customer' => redirect()->route('customer.home'),
        default => abort(403, 'Role tidak dikenali.'),
    };
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1') // max 5 kali per menit
    ->name('auth.login');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');

// Customer
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Home
    Route::get('/home', fn() => view('customer.home.index'))->name('customer.home');
    // Produk
    Route::get('/product', [CustomerProductController::class, 'index'])->name('customer.product.index');
    Route::post('/product', [CustomerProductController::class, 'checkout'])->name('customer.product.checkout');
});

// Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->name('admin.dashboard');

Route::get('/admin/product/print', [AdminProductController::class, 'print'])->name('product.print');
Route::get('/admin/product/export', [AdminProductController::class, 'export'])->name('product.export');
Route::resource('/admin/product', AdminProductController::class);

Route::get('/admin/expense/print', [ExpenseController::class, 'print'])->name('expense.print');
Route::get('/admin/expense/export', [ExpenseController::class, 'export'])->name('expense.export');
Route::resource('/admin/expense', ExpenseController::class);

Route::get('/admin/cashier/print', [CashierController::class, 'print'])->name('cashier.print');
Route::get('/admin/cashier/export', [CashierController::class, 'export'])->name('cashier.export');
Route::resource('/admin/cashier', CashierController::class);

Route::get('/admin/supplier/print', [SupplierController::class, 'print'])->name('supplier.print');
Route::get('/admin/supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
Route::resource('/admin/supplier', SupplierController::class);

Route::get('/admin/courier/print', [CourierController::class, 'print'])->name('courier.print');
Route::get('/admin/courier/export', [CourierController::class, 'export'])->name('courier.export');
Route::resource('/admin/courier', CourierController::class);

Route::get('/admin/customer/print', [CustomerController::class, 'print'])->name('customer.print');
Route::get('/admin/customer/export', [CustomerController::class, 'export'])->name('customer.export');
Route::get('/admin/customer', [CustomerController::class, 'index'])->name('customer.index');

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

// Kasir
Route::get('/dashboard', function () {
    return view('kasir.dashboard');
});
