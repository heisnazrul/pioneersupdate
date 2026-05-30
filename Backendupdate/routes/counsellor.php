<?php

use App\Http\Controllers\Counsellor\BookingController as CounsellorBookingController;
use App\Http\Controllers\Counsellor\BookingWizardController;
use App\Http\Controllers\Counsellor\DashboardController as CounsellorDashboardController;
use App\Http\Controllers\Counsellor\ProfileController as CounsellorProfileController;
use App\Http\Controllers\Counsellor\QuotationController as CounsellorQuotationController;
use App\Http\Controllers\Counsellor\StudentController as CounsellorStudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'counsellor.only'])
    ->prefix('counsellor')
    ->name('counsellor.')
    ->group(function () {
        Route::get('/', CounsellorDashboardController::class)->name('dashboard');

        Route::get('/bookings/create', [BookingWizardController::class, 'start'])->name('bookings.create');
        Route::get('/bookings/wizard/step/{step}', [BookingWizardController::class, 'showStep'])->name('bookings.wizard.step')->where('step', '[1-9]');
        Route::post('/bookings/wizard/step/{step}', [BookingWizardController::class, 'processStep'])->name('bookings.wizard.process')->where('step', '[1-9]');
        Route::get('/bookings/wizard/back/{step}', [BookingWizardController::class, 'back'])->name('bookings.wizard.back')->where('step', '[1-9]');

        Route::get('/bookings', [CounsellorBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{type}/{id}', [CounsellorBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{type}/{id}/status', [CounsellorBookingController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/bookings/{type}/{id}/pdf/student', [CounsellorBookingController::class, 'pdfStudent'])->name('bookings.pdf.student');
        Route::get('/bookings/{type}/{id}/pdf/school', [CounsellorBookingController::class, 'pdfSchool'])->name('bookings.pdf.school');

        Route::get('/students', [CounsellorStudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [CounsellorStudentController::class, 'create'])->name('students.create');
        Route::post('/students', [CounsellorStudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [CounsellorStudentController::class, 'show'])->name('students.show');

        Route::get('/quotations', [CounsellorQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/create', [CounsellorQuotationController::class, 'create'])->name('quotations.create');
        Route::post('/quotations', [CounsellorQuotationController::class, 'store'])->name('quotations.store');
        Route::get('/quotations/{quotation}', [CounsellorQuotationController::class, 'show'])->name('quotations.show');
        Route::get('/quotations/{quotation}/edit', [CounsellorQuotationController::class, 'edit'])->name('quotations.edit');
        Route::put('/quotations/{quotation}', [CounsellorQuotationController::class, 'update'])->name('quotations.update');
        Route::post('/quotations/{quotation}/send', [CounsellorQuotationController::class, 'send'])->name('quotations.send');
        Route::post('/quotations/{quotation}/convert', [CounsellorQuotationController::class, 'convert'])->name('quotations.convert');
        Route::get('/quotations/{quotation}/pdf', [CounsellorQuotationController::class, 'pdf'])->name('quotations.pdf');

        Route::get('/profile', [CounsellorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CounsellorProfileController::class, 'update'])->name('profile.update');
    });
