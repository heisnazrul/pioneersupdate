<?php

namespace App\Providers;

use App\Support\MailConfigResolver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
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
        // Shared hosting MySQL (utf8mb4) often limits index keys to 1000 bytes.
        Schema::defaultStringLength(191);

        MailConfigResolver::applyFromSettings();

        RateLimiter::for('frontend-auth-login', function (Request $request) {
            $email = (string) $request->input('email');

            return [
                Limit::perMinute(5)->by($request->ip() . '|' . strtolower($email)),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        RateLimiter::for('frontend-auth-register', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
            ];
        });

        RateLimiter::for('panel-password-reset', function (Request $request) {
            $email = strtolower((string) $request->input('email'));

            return [
                Limit::perMinute(3)->by($request->ip() . '|' . $email),
                Limit::perMinute(10)->by($request->ip()),
            ];
        });
    }
}
