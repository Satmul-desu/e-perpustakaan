<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('flash-sale');
Route::get('/tentang', fn () => view('tentang'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,60')->name('register');
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,60')->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});
Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::prefix('api')->group(function () {
    Route::get('/search/suggestions', [CatalogController::class, 'searchSuggestions'])->name('api.search.suggestions');
});
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'show'])
        ->name('verification.notice');
    Route::post('/email/verify/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/{loan}/cancel', [LoanController::class, 'cancel'])->name('loans.cancel');
    Route::get('/loans/{loan}/return', [LoanController::class, 'requestReturn'])->name('loans.return');
    Route::post('/loans/{loan}/return', [LoanController::class, 'processReturn'])->name('loans.process-return');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/cs', [ComplaintController::class, 'index'])->name('cs.index');
    Route::post('/cs', [ComplaintController::class, 'store'])->name('cs.store');
    Route::get('/cs/{complaint}', [ComplaintController::class, 'show'])->name('cs.show');
});
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('loans', AdminLoanController::class);
        Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])->name('loans.reject');
        Route::post('/loans/{loan}/mark-borrowed', [AdminLoanController::class, 'markBorrowed'])->name('loans.mark-borrowed');
        Route::post('/loans/{loan}/process-return', [AdminLoanController::class, 'processReturn'])->name('loans.process-return');
        Route::post('/loans/{loan}/extend', [AdminLoanController::class, 'extend'])->name('loans.extend');
        Route::post('/loans/mark-overdue', [AdminLoanController::class, 'markOverdue'])->name('loans.mark-overdue');
        Route::resource('complaints', AdminComplaintController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('reports/loans', [ReportController::class, 'loans'])->name('reports.loans');
        Route::get('reports/export-loans/excel', [ReportController::class, 'exportLoansExcel'])->name('reports.export-loans.excel');
        Route::get('reports/export-loans/word', [ReportController::class, 'exportLoansWord'])->name('reports.export-loans.word');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    });
