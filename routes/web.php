<?php

use App\Http\Controllers\Admin\DeliveryController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
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
    Route::get('/product', [CustomerProductController::class, 'index'])->name('customer.product');
    Route::post('/product/checkout', [CustomerProductController::class, 'checkout'])->name('customer.checkout');
    Route::get('/product/checkout/{order}', [CustomerProductController::class, 'showCheckout'])->name('customer.product.checkout.show');
    Route::get('/order', [CustomerOrderController::class, 'index'])->name('customer.order.index');
    Route::get('/order/{order}/pay', [CustomerOrderController::class, 'pay'])->name('customer.order.pay.show');
    Route::get('/order/{order}/expire', [CustomerOrderController::class, 'expire'])->name('customer.order.expire');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');

    // Produk
    Route::get('/product/print', [AdminProductController::class, 'print'])->name('product.print');
    Route::get('/product/export', [AdminProductController::class, 'export'])->name('product.export');
    Route::resource('/product', AdminProductController::class);

    // Riwayat
    Route::get('/order/print', [AdminOrderController::class, 'print'])->name('order.print');
    Route::get('/order/export', [AdminOrderController::class, 'export'])->name('order.export');
    Route::resource('/order', AdminOrderController::class);

    // Pengiriman
    Route::get('/delivery/print', [DeliveryController::class, 'print'])->name('delivery.print');
    Route::get('/delivery/export', [DeliveryController::class, 'export'])->name('delivery.export');
    Route::put('/delivery/{delivery}/kirim', [DeliveryController::class, 'kirim'])->name('delivery.kirim');
    Route::put('/delivery/{delivery}/upload', [DeliveryController::class, 'upload'])->name('delivery.upload');
    Route::resource('/delivery', DeliveryController::class);

    // Pengeluaran
    Route::get('/expense/print', [ExpenseController::class, 'print'])->name('expense.print');
    Route::get('/expense/export', [ExpenseController::class, 'export'])->name('expense.export');
    Route::resource('/expense', ExpenseController::class);

    // Kasir
    Route::get('/cashier/print', [CashierController::class, 'print'])->name('cashier.print');
    Route::get('/cashier/export', [CashierController::class, 'export'])->name('cashier.export');
    Route::resource('/cashier', CashierController::class);

    // Supplier
    Route::get('/supplier/print', [SupplierController::class, 'print'])->name('supplier.print');
    Route::get('/supplier/export', [SupplierController::class, 'export'])->name('supplier.export');
    Route::resource('/supplier', SupplierController::class);

    // Kurir
    Route::get('/courier/print', [CourierController::class, 'print'])->name('courier.print');
    Route::get('/courier/export', [CourierController::class, 'export'])->name('courier.export');
    Route::resource('/courier', CourierController::class);

    // Customer
    Route::get('/customer/print', [CustomerController::class, 'print'])->name('customer.print');
    Route::get('/customer/export', [CustomerController::class, 'export'])->name('customer.export');
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
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

Route::get('/kasir', function () {
    return view('kasir');
});

// Kasir
Route::get('/dashboard', function () {
    return view('kasir.dashboard');
});
