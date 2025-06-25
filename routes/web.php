<?php

use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Models\Order;
use Illuminate\Http\Request;
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
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Cashier\PosMenuController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Cashier\TransactionController;
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
        'cashier' => redirect()->route('pos-menu'),
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

// Cashier
Route::middleware(['auth', 'role:cashier'])
    ->prefix('cashier')
    ->group(function () {
        // POS Menu
        Route::get('/pos-menu', [PosMenuController::class, 'index'])->name('pos-menu');
        Route::post('/pos-menu/checkout', [PosMenuController::class, 'checkout'])->name('pos-menu.checkout');
        Route::get('/pos-menu/checkout/address/{order}', [PosMenuController::class, 'showCheckoutAddress'])->name('pos-menu.checkout.address');
        Route::post('/pos-menu/checkout/address/{order}', [PosMenuController::class, 'storeCheckoutAddress'])->name('pos-menu.checkout.address.store');
        Route::get('/pos-menu/checkout/recipient/{order}', [PosMenuController::class, 'showCheckoutRecipient'])->name('pos-menu.checkout.recipient');
        Route::post('/pos-menu/checkout/recipient/{order}', [PosMenuController::class, 'storeCheckoutRecipient'])->name('pos-menu.checkout.recipient.store');
        Route::get('/pos-menu/checkout/{order}/pay', [PosMenuController::class, 'pay'])->name('pos-menu.pay');
        Route::get('/pos-menu/checkout/{order}/pay-cash', [PosMenuController::class, 'payCash'])->name('pos-menu.pay-cash');
        Route::post('/pos-menu/checkout/{order}/pay-cash', [PosMenuController::class, 'storePayCash'])->name('pos-menu.pay-cash.store');
        Route::post('/pos-menu/create-user', [PosMenuController::class, 'createUser'])->name('pos-menu.create-user');

        // Riwayat
        Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction');
        Route::get('/transaction/{order}/receipt', [TransactionController::class, 'receipt'])->name('transaction.receipt');
        Route::get('/transaction/{order}/print-receipt', [TransactionController::class, 'printReceipt'])->name('transaction.print-receipt');
    });

// Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
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
        Route::get('/order/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('order.invoice');
        Route::resource('/order', AdminOrderController::class);

        // Pengiriman
        Route::get('/delivery/print', [DeliveryController::class, 'print'])->name('delivery.print');
        Route::get('/delivery/export', [DeliveryController::class, 'export'])->name('delivery.export');
        Route::get('/delivery/{delivery}/deliveryNote', [DeliveryController::class, 'deliveryNote'])->name('delivery.deliveryNote');
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

        // Kelola PO
        Route::get('/purchase-order/print', [PurchaseOrderController::class, 'print'])->name('purchase-order.print');
        Route::get('/purchase-order/export', [PurchaseOrderController::class, 'export'])->name('purchase-order.export');
        Route::resource('/purchase-order', PurchaseOrderController::class);
        Route::get('/purchase-order/{purchaseOrder}/print-invoice', [PurchaseOrderController::class, 'printInvoice'])->name('purchase-order.print-invoice');
        Route::put('/purchase-order/{purchaseOrder}/kirim', [PurchaseOrderController::class, 'kirim'])->name('purchase-order.kirim');
        Route::get('/purchase-order/{purchaseOrder}/fill-invoice', [PurchaseOrderController::class, 'fillInvoice'])->name('purchase-order.fill-invoice');
        Route::put('/purchase-order/{purchaseOrder}/save-invoice', [PurchaseOrderController::class, 'saveInvoice'])->name('purchase-order.save-invoice');
        Route::put('/purchase-order/{purchaseOrder}/pay', [PurchaseOrderController::class, 'pay'])->name('purchase-order.pay');
    });
