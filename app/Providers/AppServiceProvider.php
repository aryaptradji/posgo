<?php

namespace App\Providers;

use Midtrans\Config as Midtrans;
use Illuminate\Support\Facades\URL;
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
        Midtrans::$serverKey = env('MIDTRANS_SERVER_KEY');
        Midtrans::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Midtrans::$isProduction = env('MIDTRANS_PRODUCTION', false);
        Midtrans::$isSanitized = true;
        Midtrans::$is3ds = true;

        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}
