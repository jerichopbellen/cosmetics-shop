<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;



Route::get('/', function () {
    return view('welcome');
});


Route::resource('admin/brands', BrandController::class);
Route::resource('admin/categories', CategoryController::class);
