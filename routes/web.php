<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('admin/brands', BrandController::class);