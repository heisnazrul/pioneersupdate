<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->redirectUsersTo(function (Request $request): string {
            $user = Auth::user();

            if ($user && in_array($user->role, ['admin', 'team', 'counsellor'], true) && Route::has('admin.dashboard')) {
                return route('admin.dashboard');
            }

            return Route::has('unauthorized') ? route('unauthorized') : '/';
        });

        // Register named middleware aliases
        $middleware->alias([
            'admin.only' => \App\Http\Middleware\AdminOnly::class,
            'frontend.app' => \App\Http\Middleware\EnsureFrontendAppAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
