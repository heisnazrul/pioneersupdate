<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect()->route('login'))->name('home');
Route::view('/unauthorized', 'auth.unauthorized')->name('unauthorized');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendOtp'])
        ->middleware('throttle:panel-password-reset')
        ->name('password.forgot.send');
    Route::get('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.reset');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
require __DIR__ . '/admin.php';
require __DIR__ . '/counsellor.php';
require __DIR__ . '/team.php';
