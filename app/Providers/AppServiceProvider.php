<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiRateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the ApiRateLimiter as a singleton
        $this->app->singleton(ApiRateLimiter::class, function ($app) {
            // Configure with 5 requests per minute by default
            return new ApiRateLimiter(5);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
