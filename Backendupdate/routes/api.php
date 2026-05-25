<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FrontendAppAuthController;
use App\Http\Controllers\Api\CourseEnglishController;
use App\Http\Controllers\Api\CourseEnglishArticlesController;
use App\Http\Controllers\Api\CourseEnglishAccountController;
use App\Http\Controllers\Api\CourseEnglishBookingController;
use App\Http\Controllers\Api\CourseEnglishAgentController;
use App\Http\Controllers\Api\CourseSatController;

// Public API auth endpoints
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:frontend-auth-register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:frontend-auth-login');
});

Route::prefix('courseenglish/auth')->group(function () {
    Route::post('/register', [FrontendAppAuthController::class, 'registerForCourseEnglish'])->middleware('throttle:frontend-auth-register');
    Route::post('/login', [FrontendAppAuthController::class, 'loginForCourseEnglish'])->middleware('throttle:frontend-auth-login');
});

Route::prefix('university/auth')->group(function () {
    Route::post('/register', [FrontendAppAuthController::class, 'registerForUniversity'])->middleware('throttle:frontend-auth-register');
    Route::post('/login', [FrontendAppAuthController::class, 'loginForUniversity'])->middleware('throttle:frontend-auth-login');
});

// Backward compatibility for current University frontend
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:frontend-auth-register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'currentUser']);
});

Route::prefix('courseenglish')->middleware(['auth:sanctum', 'frontend.app:courseenglish'])->group(function () {
    Route::get('/auth/me', [FrontendAppAuthController::class, 'meForCourseEnglish']);
    Route::post('/auth/logout', [FrontendAppAuthController::class, 'logoutForCourseEnglish']);
    Route::get('/user', [FrontendAppAuthController::class, 'currentCourseEnglishUser']);
});

Route::prefix('university')->middleware(['auth:sanctum', 'frontend.app:university'])->group(function () {
    Route::get('/auth/me', [FrontendAppAuthController::class, 'meForUniversity']);
    Route::post('/auth/logout', [FrontendAppAuthController::class, 'logoutForUniversity']);
    Route::get('/user', [FrontendAppAuthController::class, 'currentUniversityUser']);
});

Route::prefix('courseenglish')->group(function () {
    Route::get('/utilities', [CourseEnglishController::class, 'utilities']);

    Route::prefix('home')->group(function () {
        Route::get('/branding', [CourseEnglishController::class, 'branding']);
        Route::get('/online', [CourseEnglishController::class, 'homeOnline']);
        Route::get('/summer', [CourseEnglishController::class, 'homeSummer']);
        Route::get('/trainingcourse', [CourseEnglishController::class, 'homeTraining']);
        Route::get('/blogs', [CourseEnglishController::class, 'homeBlogs']);
    });

    Route::get('/certificates', [CourseEnglishController::class, 'certificates']);
    Route::get('/reviews', [CourseEnglishController::class, 'reviews']);
    Route::get('/faqs', [CourseEnglishController::class, 'faqs']);
    Route::get('/offers', [CourseEnglishController::class, 'offers']);
    Route::get('/offer', [CourseEnglishController::class, 'offerPage']);

    Route::get('/language-institutes', [CourseEnglishController::class, 'languageInstitutes']);
    Route::get('/language-institutes/{slug}', [CourseEnglishController::class, 'languageInstituteDetail']);

    Route::get('/online-courses', [CourseEnglishController::class, 'onlineCourses']);
    Route::get('/online-courses/{slug}', [CourseEnglishController::class, 'onlineCourseDetail']);

    Route::get('/summer-programs', [CourseEnglishController::class, 'summerPrograms']);
    Route::get('/summer-programs/{slug}', [CourseEnglishController::class, 'summerProgramDetail']);

    Route::get('/training-and-professional-courses', [CourseEnglishController::class, 'trainingCourses']);
    Route::get('/training-and-professional-courses/{slug}', [CourseEnglishController::class, 'trainingCourseDetail']);

    Route::prefix('articles')->group(function () {
        Route::get('/', [CourseEnglishArticlesController::class, 'index']);
        Route::get('/categories', [CourseEnglishArticlesController::class, 'categories']);
        Route::get('/category/{slug}', [CourseEnglishArticlesController::class, 'category']);
        Route::get('/{slug}', [CourseEnglishArticlesController::class, 'show']);
    });

    Route::get('/about', [CourseEnglishController::class, 'about']);
    Route::get('/university-admissions', [CourseEnglishController::class, 'universityAdmissions']);
    Route::get('/travel-and-tourism', [CourseEnglishController::class, 'travelAndTourism']);
    Route::get('/contact-us', [CourseEnglishController::class, 'contactPage']);
    Route::post('/contact-us/submit', [CourseEnglishController::class, 'contactSubmit']);

    Route::prefix('booking')->group(function () {
        Route::post('/send-otp', [CourseEnglishBookingController::class, 'sendOtp']);
        Route::post('/verify-otp', [CourseEnglishBookingController::class, 'verifyOtp']);
        Route::post('/language-course', [CourseEnglishBookingController::class, 'bookLanguageCourse']);
        Route::post('/online-course', [CourseEnglishBookingController::class, 'bookOnlineCourse']);
        Route::post('/summer-camp', [CourseEnglishBookingController::class, 'bookSummerCamp']);
        Route::post('/training-course', [CourseEnglishBookingController::class, 'bookTrainingCourse']);
    });
});

Route::prefix('coursesat')->group(function () {
    Route::get('/home', [CourseSatController::class, 'home']);
});

Route::middleware(['auth:sanctum', 'frontend.app:courseenglish'])->group(function () {
    Route::get('/student/me', [CourseEnglishAccountController::class, 'studentMe']);
    Route::post('/student/update-profile', [CourseEnglishAccountController::class, 'studentUpdateProfile']);
    Route::get('/student/bookings', [CourseEnglishAccountController::class, 'studentBookings']);

    Route::prefix('courseenglish')->group(function () {
        Route::get('/wishlist', [CourseEnglishAccountController::class, 'wishlist']);
        Route::post('/wishlist/add', [CourseEnglishAccountController::class, 'wishlistAdd']);
        Route::post('/wishlist/remove', [CourseEnglishAccountController::class, 'wishlistRemove']);

        Route::get('/compare', [CourseEnglishAccountController::class, 'compare']);
        Route::post('/compare/add', [CourseEnglishAccountController::class, 'compareAdd']);
        Route::post('/compare/remove', [CourseEnglishAccountController::class, 'compareRemove']);

        Route::get('/student-bookings', [CourseEnglishAccountController::class, 'courseEnglishStudentBookings']);
        Route::post('/booking/set-password', [CourseEnglishBookingController::class, 'setPassword']);
    });

    Route::prefix('agent')->group(function () {
        Route::get('/me', [CourseEnglishAgentController::class, 'me']);
        Route::get('/overview', [CourseEnglishAgentController::class, 'overview']);
        Route::get('/referrals', [CourseEnglishAgentController::class, 'referrals']);
        Route::post('/referrals', [CourseEnglishAgentController::class, 'refreshReferral']);
        Route::get('/students', [CourseEnglishAgentController::class, 'students']);
        Route::post('/new-student', [CourseEnglishAgentController::class, 'newStudent']);
        Route::post('/bookings', [CourseEnglishAgentController::class, 'bookings']);
    });
});
