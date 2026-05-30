<?php

use App\Http\Controllers\Team\BookingController as TeamBookingController;
use App\Http\Controllers\Team\DashboardController as TeamDashboardController;
use App\Http\Controllers\Team\ProfileController as TeamProfileController;
use App\Http\Controllers\Team\QuotationController as TeamQuotationController;
use App\Http\Controllers\Team\StudentController as TeamStudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'team.only'])
    ->prefix('team')
    ->name('team.')
    ->group(function () {
        Route::get('/', TeamDashboardController::class)->name('dashboard');

        Route::get('/bookings', [TeamBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{type}/{id}', [TeamBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{type}/{id}/status', [TeamBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::post('/bookings/{type}/{id}/assign', [TeamBookingController::class, 'assign'])->name('bookings.assign');
        Route::get('/bookings/{type}/{id}/pdf/student', [TeamBookingController::class, 'pdfStudent'])->name('bookings.pdf.student');
        Route::get('/bookings/{type}/{id}/pdf/school', [TeamBookingController::class, 'pdfSchool'])->name('bookings.pdf.school');

        Route::get('/students', [TeamStudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [TeamStudentController::class, 'create'])->name('students.create');
        Route::post('/students', [TeamStudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [TeamStudentController::class, 'show'])->name('students.show');
        Route::post('/students/{student}/assign', [TeamStudentController::class, 'assign'])->name('students.assign');

        Route::get('/quotations', [TeamQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [TeamQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [TeamQuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{quotation}', [TeamQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{quotation}/edit', [TeamQuotationController::class, 'edit'])->name('quotations.edit');
        Route::put('/quotations/{quotation}', [TeamQuotationController::class, 'update'])->name('quotations.update');
        Route::post('/quotations/{quotation}/send', [TeamQuotationController::class, 'send'])->name('quotations.send');
        Route::post('/quotations/{quotation}/convert', [TeamQuotationController::class, 'convert'])->name('quotations.convert');
        Route::post('/quotations/{quotation}/assign', [TeamQuotationController::class, 'assign'])->name('quotations.assign');
        Route::get('/quotations/{quotation}/pdf', [TeamQuotationController::class, 'pdf'])->name('quotations.pdf');

        Route::get('/profile', [TeamProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [TeamProfileController::class, 'update'])->name('profile.update');
    });
