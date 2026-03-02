<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

use App\Models\Product;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register route model binding for 'product' parameter
        Route::model('product', Product::class);

        // Commented out untuk seeder - observer membutuhkan package activity log
        // Product::observe(ProductObserver::class);

    }
}
