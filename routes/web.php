<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Owner;
use App\Http\Controllers\Owner\ProfileController as OwnerProfile;

// ─────────────────────────────────────────
// REDIRECT ROOT
// ─────────────────────────────────────────
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
// ─────────────────────────────────────────
// OWNER ROOT
// ─────────────────────────────────────────


Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'owner'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');

        // Laporan (read only)
        Route::get('/reports/sales',  [Owner\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/sales/export', [Owner\ReportController::class, 'exportSales'])->name('reports.sales.export');
        Route::get('/reports/stock',  [Owner\ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/profit', [Owner\ReportController::class, 'profit'])->name('reports.profit');

        // Pesanan (read only)
        Route::get('/orders',       [Owner\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Owner\OrderController::class, 'show'])->name('orders.show');

        // Invoice (read only)
        Route::get('/invoices',              [Owner\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}',    [Owner\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [Owner\InvoiceController::class, 'download'])->name('invoices.download');

        // Customer (read only)
        Route::get('/customers',         [Owner\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{user}',  [Owner\CustomerController::class, 'show'])->name('customers.show');

        // Produk & Stok (read only)
        Route::get('/products', [Owner\ProductController::class, 'index'])->name('products.index');
        Route::get('/stocks',   [Owner\StockController::class, 'index'])->name('stocks.index');

        //Ganti Password
        Route::get('/profile',          [OwnerProfile::class, 'index'])->name('profile');
        Route::patch('/profile/password', [OwnerProfile::class, 'updatePassword'])->name('profile.password');
    });

// ─────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Brand
        Route::resource('brands', Admin\BrandController::class);

        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggleActive');

        // Users >> Pelanggan
        Route::patch('/users/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('users.resetPassword');
        
        // Category
        Route::resource('categories', Admin\CategoryController::class);

        // Product
        Route::resource('products', Admin\ProductController::class);

        // Stock
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/',                          [Admin\StockController::class, 'index'])->name('index');
            Route::get('/{product}/adjust',          [Admin\StockController::class, 'adjust'])->name('adjust');
            Route::post('/{product}/adjust',         [Admin\StockController::class, 'store'])->name('store');
            Route::get('/movements',                 [Admin\StockController::class, 'movements'])->name('movements');
            Route::get('/low-stock',                 [Admin\StockController::class, 'lowStock'])->name('low-stock');
        });

        // Order
        Route::resource('orders', Admin\OrderController::class)->only(['index', 'show']);
        Route::patch('/orders/{order}/status',       [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

        // Payment Verification
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/',                          [Admin\OrderController::class, 'payments'])->name('index');
            Route::patch('/{payment}/verify',        [Admin\OrderController::class, 'verifyPayment'])->name('verify');
            Route::patch('/{payment}/reject',        [Admin\OrderController::class, 'rejectPayment'])->name('reject');
            Route::patch('/{payment}/manual-verify', [Admin\OrderController::class, 'manualVerifyPayment'])->name('manual-verify');
        });

        // Invoice
        Route::get('/invoices',                      [Admin\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}',            [Admin\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download',   [Admin\InvoiceController::class, 'download'])->name('invoices.download');

        // Report
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales',                     [Admin\ReportController::class, 'sales'])->name('sales');
            Route::get('/sales/export',              [Admin\ReportController::class, 'exportSales'])->name('sales.export');
            Route::get('/stock',                     [Admin\ReportController::class, 'stock'])->name('stock');
        });
    });

// ─────────────────────────────────────────
// CUSTOMER ROUTES
// ─────────────────────────────────────────
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'customer'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Customer\DashboardController::class, 'index'])
            ->name('dashboard');

        // Browse Produk
        Route::get('/products',              [Customer\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}',    [Customer\ProductController::class, 'show'])->name('products.show');

        // Keranjang
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/',                  [Customer\CartController::class, 'index'])->name('index');
            Route::post('/add',              [Customer\CartController::class, 'add'])->name('add');
            Route::patch('/update/{item}',   [Customer\CartController::class, 'update'])->name('update');
            Route::delete('/remove/{item}',  [Customer\CartController::class, 'remove'])->name('remove');
            Route::delete('/clear',          [Customer\CartController::class, 'clear'])->name('clear');
        });

        // Checkout
        Route::get('/checkout',              [Customer\CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout',             [Customer\CheckoutController::class, 'store'])->name('checkout.store');

        // Orders
        Route::get('/orders',                [Customer\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',        [Customer\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/pay',   [Customer\OrderController::class, 'uploadPayment'])->name('orders.pay');
        Route::patch('/orders/{order}/cancel', [Customer\OrderController::class, 'cancel'])->name('orders.cancel');

        // Invoice
        Route::get('/invoices',              [Customer\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}',    [Customer\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [Customer\InvoiceController::class, 'download'])->name('invoices.download');
    });

require __DIR__.'/auth.php';