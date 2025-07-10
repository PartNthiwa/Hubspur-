<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Models\Shareholder;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::bind('shareholder', function ($value) {
        \Log::info('Route binding resolving shareholder:', ['value' => $value]);
        return Shareholder::where('shareholder_number', $value)->firstOrFail();
    });
    }
}
