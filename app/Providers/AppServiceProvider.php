<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

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
        // Force asset/storage URLs to use APP_URL
        // This fixes images when site is in a subfolder (e.g., XAMPP)
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }

        // Override Storage::url() to return full URL with APP_URL
        Storage::disk('public')->buildTemporaryUrlsUsing(function ($path, $expiration) {
            return url('/storage/' . $path);
        });
    }
}
