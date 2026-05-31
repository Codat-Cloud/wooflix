<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Mail\WelcomeEmail;
use App\Models\Page;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/send-test-email', function () {
    $recipientEmail = 'customer@example.com';
    $customerName = 'Alex';

    // Using the Mail facade to dispatch the mailable instantly
    Mail::to($recipientEmail)->send(new WelcomeEmail($customerName));

    return 'Test email has been dispatched successfully!';
});

Route::get('/policy/{slug}', function ($slug) {
    $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    return view('front.page', compact('page'));
})->name('front.page');



Route::get('/blogs', [FrontController::class, 'blogs'])->name('front.blogs.index');
Route::get('/blog/{slug}', [FrontController::class, 'blogsView'])->name('front.blogs.view');



Route::get('/collections', [FrontController::class, 'shop'])->name('front.shop');
Route::get('/collection/{product_slug}/{variant_slug?}', [FrontController::class, 'singleProduct'])->name('front.singleProduct');



Route::get('/cart', [FrontController::class, 'cart'])->name('front.cart');



Route::get('/wholesale', [FrontController::class, 'wholesale'])->name('front.wholesale');
Route::post('/wholesale/save', [FrontController::class, 'wholesaleSave'])->name('front.wholesale.save');



Route::get('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
Route::get('/order-success/{order}', [PaymentController::class, 'success'])->name('front.success');

Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('order.track');


// Grouping cart routes
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
});


Route::middleware('auth')->group(function () {

    Route::get('/checkout', [FrontController::class, 'checkout'])->name('front.checkout');
    // 
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/orders', [ProfileController::class, 'orders'])->name('user.orders');
    Route::get('/dashboard/wishlist', [ProfileController::class, 'wishlist'])->name('user.wishlist');

    Route::delete('/wishlist/{id}/remove', [ProfileController::class, 'wishlistRemove'])
    ->name('front.wishlistRemove');

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

    Route::get('/invoice/{order}', [ProfileController::class, 'invoice'])
        ->name('front.orderInvoice');

    Route::get('/order/{order}/shipping-label', [ProfileController::class, 'shippingLabel'])
        ->name('front.shippingLabel');

    Route::get('/invoice/{order}/pdf', [ProfileController::class, 'invoicePdf'])->name('front.orderInvoicePdf');

});

require __DIR__ . '/auth.php';
