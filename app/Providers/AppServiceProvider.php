<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        // Forcer HTTPS en production ou si derrière un proxy HTTPS
        if (config('app.env') === 'production' ||
            request()->header('X-Forwarded-Proto') === 'https' ||
            str_starts_with(config('app.url', ''), 'https://')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);
    }
}
