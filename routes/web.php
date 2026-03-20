<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;


Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [ShopController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/add', [ShopController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{key}', [ShopController::class, 'removeFromCart'])->name('cart.remove');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/email/resend', [AuthController::class, 'showResendForm'])->name('verification.resend.form');
    Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend.post')->middleware('throttle:6,1');
});


Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')->with('success', 'Email already verified. Please log in.');
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }

    return redirect()->route('login')->with('success', 'Email verified! You can now log in to your GLOW account.');
})->middleware(['signed'])->name('verification.verify');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.store');
    Route::get('/checkout/success/{order_number}', [ShopController::class, 'success'])->name('checkout.success');
    Route::get('/my-orders', [ShopController::class, 'myOrders'])->name('orders.my');
    Route::get('/my-profile', [ProfileController::class, 'profile'])->name('profile.show');
    Route::put('/profile-update/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar/{user}', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::patch('/orders/{order}/cancel', [ShopController::class, 'cancel'])->name('orders.cancel');
    Route::get('/reviews/create/{product}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/store/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/edit/{review}', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/update/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/destroy/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('brands', BrandController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');    
        
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.updateStatus');
        
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
});