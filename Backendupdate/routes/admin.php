<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    UserController,
    CountryController,
    CityController,
    CategoryController,
    BlogTagController,
    TagController,
    BlogController,
    ApplicationController,
    ContactSubmissionController,
    ReviewController,
    FaqController,
    ExchangeRateController,
    ConversionFeeController,
    GalleryController,
    LanguageSchoolController,
    LanguageSchoolBranchController,
    LanguageCourseCategoryController,
    LanguageCourseSummerCampController,
    LanguageCourseTrainingCourseController,
    LanguageOnlineCourseController,
    LanguageSchoolCourseController,
    LanguageSchoolCoursePromotionController,
    LanguageSchoolPioneersDiscountController,
    LanguageSchoolPickupController,
    AccommodationTypeController,
    MealPlanController,
    BedroomTypeController,
    BathroomTypeController,
    LanguageSchoolAccommodationController,
    LanguageSchoolInsuranceController,
    AccreditationController,
    CertificationController,
    LanguageCourseWishlistController,
    LanguageCourseCompareController,
    UniversityController,
    UniversityCourseCatalogController,
    UniversityCourseController,
    LevelController,
    SubjectAreaController,
    IntakeTermController,
    ScholarshipController,
    ScholarshipApplicationController,
    UniApplicationController,
    UniversityWishlistController,
    DestinationController,
    DestinationGuideController,
    FeaturedListController,
    UniversityAccommodationRoomController,
};

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin.only'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // ─── Users ───────────────────────────────────────────────────────
    Route::get('/users',                    [UserController::class, 'index'])->name('users.index');
    Route::get('/users/role/{role}',        [UserController::class, 'index'])->name('users.role');
    Route::get('/users/create',             [UserController::class, 'create'])->name('users.create');
    Route::post('/users',                   [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',        [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',             [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status',    [UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{user}',          [UserController::class, 'destroy'])->name('users.destroy');

    // ─── Geography ───────────────────────────────────────────────────
    Route::resource('countries', CountryController::class);
    Route::resource('cities',    CityController::class);
    Route::resource('language-schools',           LanguageSchoolController::class);
    Route::resource('language-school-branches',    LanguageSchoolBranchController::class)->except('show');
    Route::resource('language-course-categories',  LanguageCourseCategoryController::class);
    Route::resource('tags',                        TagController::class)->except('show');
    Route::resource('language-online-courses',     LanguageOnlineCourseController::class)->except('show');
    Route::resource('language-course-summer-camps', LanguageCourseSummerCampController::class)->except('show');
    Route::resource('language-course-training-courses', LanguageCourseTrainingCourseController::class)->except('show');
    Route::resource('language-school-courses',     LanguageSchoolCourseController::class);
    Route::resource('language-school-course-promotions', LanguageSchoolCoursePromotionController::class);
    Route::resource('language-school-pioneers-discounts', LanguageSchoolPioneersDiscountController::class)
        ->parameters(['language-school-pioneers-discounts' => 'pioneers_discount'])
        ->except('show');
    Route::resource('language-school-pickups',    LanguageSchoolPickupController::class);
    Route::resource('accommodation-types',        AccommodationTypeController::class);
    Route::resource('meal-plans',                 MealPlanController::class);
    Route::resource('bedroom-types',               BedroomTypeController::class);
    Route::resource('bathroom-types',              BathroomTypeController::class);
    Route::resource('language-school-accommodations', LanguageSchoolAccommodationController::class);
    Route::resource('language-school-insurances', LanguageSchoolInsuranceController::class);
    Route::resource('accreditations',             AccreditationController::class)->except('show');
    Route::resource('certifications',             CertificationController::class)->except('show');
    Route::resource('wishlists',                  LanguageCourseWishlistController::class)->only(['index', 'destroy']);
    Route::resource('compares',                   LanguageCourseCompareController::class)->only(['index', 'destroy']);
    Route::resource('universities', UniversityController::class)->except('show');
    Route::resource('levels', LevelController::class)->except('show');
    Route::resource('subject-areas', SubjectAreaController::class)->except('show');
    Route::resource('intake-terms', IntakeTermController::class)->except('show');
    Route::resource('university-course-catalogs', UniversityCourseCatalogController::class)->except('show');
    Route::resource('university-courses', UniversityCourseController::class)->except('show');
    Route::resource('scholarships', ScholarshipController::class)->except('show');
    Route::resource('scholarship-applications', ScholarshipApplicationController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::resource('uni-applications', UniApplicationController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('university-wishlists', UniversityWishlistController::class)->only(['index', 'destroy']);
    Route::resource('destinations', DestinationController::class)->except('show');
    Route::resource('destination-guides', DestinationGuideController::class)->except('show');
    Route::resource('featured-lists', FeaturedListController::class)->except('show');
    Route::resource('university-accommodation-rooms', UniversityAccommodationRoomController::class)->except('show');

    // ─── Blog ────────────────────────────────────────────────────────
    Route::resource('categories', CategoryController::class);
    Route::resource('blog-tags',  BlogTagController::class);
    Route::resource('blogs',      BlogController::class);

    // ─── Applications & Contact ──────────────────────────────────────
    Route::resource('applications',       ApplicationController::class)->except('create', 'store');
    Route::resource('contact-submissions', ContactSubmissionController::class)->only('index', 'show', 'update', 'destroy');

    // ─── Reviews & FAQs ──────────────────────────────────────────────
    Route::resource('reviews', ReviewController::class);
    Route::resource('faqs',    FaqController::class);

    // ─── Finance ─────────────────────────────────────────────────────
    Route::post('exchange-rates/refresh/gbp', [ExchangeRateController::class, 'getGbpAllRates'])
        ->name('exchange-rates.refresh-gbp');
    Route::resource('exchange-rates',  ExchangeRateController::class);
    Route::resource('conversion-fees', ConversionFeeController::class);

    // ─── Media ───────────────────────────────────────────────────────
    Route::get('/galleries',           [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/galleries/upload',    [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('/galleries',          [GalleryController::class, 'store'])->name('galleries.store');
    Route::get('/galleries/search',    [GalleryController::class, 'search'])->name('galleries.search');
    Route::post('/galleries/api-store', [GalleryController::class, 'apiStore'])->name('galleries.api-store');
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    // ─── Settings ────────────────────────────────────────────────────
    Route::get('/settings',            [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings',           [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});
