<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;


Route::get('/', function () {
    return view('welcome');
});


Route::resource('admin/brands', BrandController::class);
Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/products', ProductController::class);

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');
