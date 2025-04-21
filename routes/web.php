<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\ReturCustController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Auth\PasswordResetController;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact-us');


// Products routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products');
    Route::post('/', [ProductController::class, 'store'])->name('products.submit');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/{id}', [ProductController::class, 'show'])
        ->name('product.show');
});

// Cart routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    Route::get('/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/process', [PaymentController::class, 'process'])->name('cart.process');

    // AJAX routes for shipping calculation
    Route::get('/cities', [CartController::class, 'getCities'])->name('cart.cities');

    Route::get('/shipping-cost', [CartController::class, 'getShippingCost'])->name('cart.shipping-cost');
});
Route::post('/checkout/process-payment', [PaymentController::class, 'processPayment'])->name('checkout.process-payment');

// Order routers

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');
    // routes/web.php
    Route::post('/{order_id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])
        ->name('orders.confirm-delivery');
});

Route::post('/midtrans/webhook', [PaymentController::class, 'handleWebhook']);

// Customer Retur Routes
Route::get('/customer/retur', [ReturCustController::class, 'index'])->name('customer.retur.index');
Route::post('/customer/retur', [ReturCustController::class, 'store'])->name('customer.retur.store');


// Global Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Password reset routes
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Customer Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [AdminController::class, 'chartData'])
        ->name('admin.dashboard.chart');

    Route::get('/customer', [AdminUserController::class, 'index'])->name('customer');
    Route::get('/customer/edit', [AdminController::class, 'customer'])->name('customer.edit');
    Route::get('/customer/store', [AdminController::class, 'customer'])->name('customer.store');
    Route::get('/customer/destroy', [AdminController::class, 'customer'])->name('customer.destroy');


    // Product Routes
    Route::get('/product', [AdminProductController::class, 'index'])->name('product');
    Route::post('/product', [AdminProductController::class, 'store'])->name('product.store');
    Route::put('/product/{product}', [AdminProductController::class, 'update'])->name('product.update');
    Route::get('/product/create', [AdminProductController::class, 'create'])->name('product.create');
    Route::get('/product/{product}/edit', [AdminProductController::class, 'edit'])->name('product.edit');
    Route::delete('/product/{product}', [AdminProductController::class, 'destroy'])->name('product.destroy');

    //Admin Order Routes
    Route::get('/orders', [OrderAdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [OrderAdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/confirm', [OrderAdminController::class, 'confirmOrder'])->name('orders.confirm');
    Route::put('/orders/{order}/ship', [OrderAdminController::class, 'updateShipping'])->name('orders.ship');
    Route::put('/orders/{order}/cancel', [OrderAdminController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{id}/shipping-info', [OrderAdminController::class, 'getShippingInfo'])
        ->name('admin.orders.shipping-info');
    Route::put('/orders/{id}/complete', [OrderAdminController::class, 'completeOrder'])->name('orders.complete');
    Route::get('/orders/{id}/invoice', [OrderAdminController::class, 'generateInvoice'])->name('orders.invoice');

    Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    // inventory
    Route::resource('inventory', InventoryController::class)->names([
        'index' => 'inventory',
        'create' => 'inventory.create',
        'store' => 'inventory.store',
        'edit' => 'inventory.edit',
        'update' => 'inventory.update',
    ]);


    // Reports (Owner Only)

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('daily-transactions', [ReportController::class, 'dailyTransactions'])->name('daily-transactions');
        Route::get('low-stock', [ReportController::class, 'lowStock'])->name('low-stock');
        Route::get('monthly-profit', [ReportController::class, 'monthlyProfit'])->name('monthly-profit');
        Route::get('product-returns', [ReportController::class, 'productReturns'])->name('product-returns');
        Route::get('top-products', [ReportController::class, 'topProducts'])->name('top-products');
        Route::get('cancelled-orders', [ReportController::class, 'cancelledOrders'])->name('cancelled-orders');
        Route::get('top-rated', [ReportController::class, 'topRated'])->name('top-rated');
        Route::get('unsold-products', [ReportController::class, 'unsoldProducts'])->name('unsold-products');
        Route::get('payment-methods', [ReportController::class, 'paymentMethods'])->name('payment-methods');
        Route::get('low-rated', [ReportController::class, 'lowRated'])->name('low-rated');
    });
});
