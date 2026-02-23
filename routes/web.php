<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 1. Public Routes (Anyone can see)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');

// 2. Shopping Cart (Session-based, usually public)
Route::get('/cart', [ShopController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/add', [ShopController::class, 'addToCart'])->name('cart.add');

// 3. Customer Routes (Must be logged in to checkout)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.store');
    // Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
});

// 4. Admin Routes (Must be logged in AND have role 'admin')
// We use 'prefix' so we don't have to type 'admin/' in every route
// We use 'as' to prepend 'admin.' to all route names (e.g., admin.brands.index)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::resource('brands', BrandController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        
        // Future Admin Order Management
        // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });