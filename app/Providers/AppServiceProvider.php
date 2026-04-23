<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Application service provider.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Nothing to register in this example.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Global cache for products with orders
        Cache::macro('productsWithOrders', function () {
            return Cache::remember('products_with_orders', 60 * 60, function () {
                return \App\Models\Product::with('orders')->get();
            });
        });

        // Ensure schema is using utf8mb4 for proper indexing
        Schema::defaultStringLength(191);
    }
}