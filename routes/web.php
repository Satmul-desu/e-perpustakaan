<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('flash-sale');

Route::get('/tentang', fn() => view('tentang'));

Route::get('/sapa/{nama}', fn($nama) =>
    "Halo, $nama! Selamat datang di Toko Online Raihan."
);

Route::get('/kategori/{nama?}', fn($nama = 'Semua') =>
    "Menampilkan kategori: $nama"
);

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Rate Limited)
|--------------------------------------------------------------------------
*/

// Rate limiting: 5 attempts per minute for login
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1')->name('login');
    
    // Rate limiting: 3 attempts per hour for registration
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('throttle:3,60')->name('register');
    
    // Rate limiting: 5 attempts per hour for password reset
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,60')->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);
});

// Logout route for authenticated users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| GOOGLE AUTH
|--------------------------------------------------------------------------
*/

Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| CATALOG (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// API Route untuk Live Search Autocomplete
Route::prefix('api')->group(function () {
    Route::get('/search/suggestions', [CatalogController::class, 'searchSuggestions'])
        ->name('api.search.suggestions');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    | PROFILE
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])
        ->name('profile.avatar.destroy');
    Route::get('/profile/google/unlink', [ProfileController::class, 'unlinkGoogle'])
        ->name('profile.google.unlink');

    /*
    | EMAIL VERIFICATION
    */
    Route::get('/email/verify', fn() =>
        view('auth.verify-email')
    )->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->middleware(['signed'])->name('verification.verify');

    /*
    | CART
    */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    /*
    | WISHLIST
    */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])
        ->name('wishlist.store');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'destroy'])
        ->name('wishlist.destroy');

    /*
    | CHECKOUT
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    /*
    | ORDERS
    */
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', AdminProductController::class);

        /*
        | COMPLAINTS
        */
        Route::resource('complaints', AdminComplaintController::class);
        Route::put('/complaints/{complaint}/quick-update', [AdminComplaintController::class, 'quickUpdate'])
            ->name('complaints.quick-update');
        Route::resource('categories', AdminCategoryController::class);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::get('reports/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');
        Route::get('reports/export-sales', [ReportController::class, 'exportSales'])
                ->name('reports.export-sales');
        Route::get('reports/export-word', [ReportController::class, 'exportWord'])
                ->name('reports.export-word');

        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');
    });

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});


use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| CS ROUTES (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/cs', [ComplaintController::class, 'index'])->name('cs.index');
    Route::post('/cs', [ComplaintController::class, 'store'])->name('cs.store');
    Route::get('/cs/{complaint}', [ComplaintController::class, 'show'])->name('cs.show');
});

/*
|--------------------------------------------------------------------------
| PAYMENT ROUTES (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/orders/{orderNumber}/snap-token', [
        PaymentController::class,
        'getSnapToken'
    ])->name('orders.snap-token');

    Route::get('/orders/{order}/success', [PaymentController::class, 'success'])
        ->name('orders.success');

    Route::get('/orders/{order}/pending', [PaymentController::class, 'pending'])
        ->name('orders.pending');

    Route::get('/orders/{order}/result/{status?}', [PaymentController::class, 'result'])
        ->name('orders.result');
});

/* MIDTRANS WEBHOOK */
use App\Http\Controllers\MidtransNotificationController;

Route::post('/midtrans/notification', [
    MidtransNotificationController::class,
    'handle'
])->name('midtrans.notification');
