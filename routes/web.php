<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Models\Expense;
use App\Models\Supplier;

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

Route::resource('/admin/product', ProductController::class);

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

// Route Kasir
Route::get('/kasir/dashboard', function () {
    return view('kasir.dashboard.index');
});
