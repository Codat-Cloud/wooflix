<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/collections', [FrontController::class, 'shop'])->name('front.shop');
Route::get('/collection/{product_slug}', [FrontController::class, 'singleProduct'])->name('front.singleProduct');
Route::get('/cart', [FrontController::class, 'cart'])->name('front.cart');
Route::get('/checkout', [FrontController::class, 'checkout'])->name('front.checkout');

// Grouping cart routes makes it even more scalable
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
});

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/drawer/{type}', function ($type) {

        return match ($type) {
            'profile' => view('profile.partials.edit-profile'),
            'address' => view('profile.partials.address'),
            'coupons' => view('profile.partials.coupons'),
            default => abort(404),
        };

    })->name('profile.drawer');

});

require __DIR__.'/auth.php';
