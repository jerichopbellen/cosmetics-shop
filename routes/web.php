<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;



Route::get('/', function () {
    return view('welcome');
});


Route::resource('admin/brands', BrandController::class);
Route::resource('admin/categories', CategoryController::class);
Route::resource('admin/products', ProductController::class);
