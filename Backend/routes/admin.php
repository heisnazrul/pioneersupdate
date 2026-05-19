<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    UserController,
    ApplicationController,
    AccreditationController,
    LanguageCourseTagController,
    LanguageCourseTypeController,
    MealPlanController,
    BedroomTypeController,
    BathroomTypeController,
    UniApplicationController,
    ContactSubmissionController,
    CountryController,
    CityController,
    UniversityController,
    CategoryController,
    BlogController,
    BlogTagController,
    DestinationController,
    DestinationGuideController,
    LevelController,
    SubjectAreaController,
    IntakeTermController,
    LanguageTestController,
    UniversityCourseTagController,
    UniversityCourseCatalogController,
    FeaturedListController,
    CampusController,
    UniversityCourseController,
    ScholarshipController,
    ScholarshipApplicationController,
    LanguageSchoolController,
    LanguageSchoolBranchController,
    LanguageSchoolCourseController,
    LanguageSchoolCourseFeeController,
    LanguageSchoolCourseMaterialFeeController,
    LanguageSchoolBranchRegistrationFeeController,
    LanguageSchoolBranchHighSeasonFeeController,
    LanguageSchoolAccommodationController,
    LanguageSchoolSupplementController,
    LanguageSchoolPickupController,
    LanguageSchoolInsuranceFeeController,
    LanguageSchoolDiscountController,
    LanguageSchoolCouponController,
    LanguageSchoolPioneersDiscountController,
    LanguageCourseOnlineCourseController,
    LanguageCourseSummerCampController,
    LanguageCourseSummerCampDetailController,
    LanguageCourseTrainingCourseController,
    ExchangeRateController,
    ConversionFeeController,
    AgentReferralController,
    BulkImportExportController,
    GalleryController,
    ReviewController,
    FaqController,
    CertificationController,
    AccommodationRoomController,
    OfficeController,
    SettingsController
};

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/role/{role}', [UserController::class, 'index'])->name('users.role');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Applications
    Route::resource('applications', ApplicationController::class);
    Route::resource('uni-applications', UniApplicationController::class);
    Route::resource('contact-submissions', ContactSubmissionController::class);

    // Content
    Route::resource('categories', CategoryController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('blog-tags', BlogTagController::class);

    // Basic reference data
    Route::resource('countries', CountryController::class);
    Route::resource('cities', CityController::class);
    Route::get('universities/get-cities/{country}', [UniversityController::class, 'getCities']);
    Route::resource('universities', UniversityController::class);

    // Destinations & guides
    Route::resource('destinations', DestinationController::class);
    Route::resource('destination-guides', DestinationGuideController::class);

    // CMS routes are kept in a dedicated file for clarity
    require __DIR__ . '/admincms.php';

    // Course attributes
    Route::resource('levels', LevelController::class);
    Route::resource('subject-areas', SubjectAreaController::class);
    Route::resource('intake-terms', IntakeTermController::class);
    Route::resource('language-tests', LanguageTestController::class);
    Route::resource('course-tags', UniversityCourseTagController::class);
    Route::resource('featured-lists', FeaturedListController::class);
    Route::resource('accreditations', AccreditationController::class);
    Route::resource('language-course-tags', LanguageCourseTagController::class);
    Route::resource('language-course-types', LanguageCourseTypeController::class);
    Route::resource('meal-plans', MealPlanController::class);
    Route::resource('bedroom-types', BedroomTypeController::class);
    Route::resource('bathroom-types', BathroomTypeController::class);
    Route::resource('uni-applications', UniApplicationController::class)->only(['index']);

    // Universities & courses
    Route::resource('campuses', CampusController::class);
    Route::resource('university-course-catalogs', UniversityCourseCatalogController::class);
    Route::resource('university-courses', UniversityCourseController::class);

    // Scholarships
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('scholarship-applications', ScholarshipApplicationController::class);

    // Language schools module
    Route::resource('language-schools', LanguageSchoolController::class);
    Route::resource('language-school-branches', LanguageSchoolBranchController::class);
    Route::resource('language-school-courses', LanguageSchoolCourseController::class);
    Route::resource('gallery', GalleryController::class);

    // Language school fees
    Route::resource('language-school-course-fees', LanguageSchoolCourseFeeController::class);
    Route::get('language-school-course-fees/fetch/{languageSchoolCourse}', [LanguageSchoolCourseFeeController::class, 'fetch'])
        ->name('language-school-course-fees.fetch');
    Route::delete('language-school-course-fees/course/{languageSchoolCourse}', [LanguageSchoolCourseFeeController::class, 'destroyByCourse'])
        ->name('language-school-course-fees.destroy-by-course');
    Route::resource('language-school-course-material-fees', LanguageSchoolCourseMaterialFeeController::class)->parameters([
        'language-school-course-material-fees' => 'ls_cm_fee'
    ]);
    Route::resource('language-school-branch-registration-fees', LanguageSchoolBranchRegistrationFeeController::class)->parameters([
        'language-school-branch-registration-fees' => 'ls_reg_fee'
    ]);
    Route::resource('language-school-branch-high-season-fees', LanguageSchoolBranchHighSeasonFeeController::class)
        ->parameters([
            'language-school-branch-high-season-fees' => 'ls_high_fee',
        ])
        ->except(['show']);
    Route::resource('language-school-accommodations', LanguageSchoolAccommodationController::class);
    Route::resource('language-school-supplements', LanguageSchoolSupplementController::class);
    Route::resource('language-school-pickups', LanguageSchoolPickupController::class);
    Route::resource('language-school-insurance-fees', LanguageSchoolInsuranceFeeController::class);
    Route::resource('language-school-discounts', LanguageSchoolDiscountController::class)->parameters([
        'language-school-discounts' => 'ls_discount',
    ]);
    Route::resource('language-school-coupons', LanguageSchoolCouponController::class)->parameters([
        'language-school-coupons' => 'ls_coupon',
    ]);
    Route::resource('language-school-pioneers-discounts', LanguageSchoolPioneersDiscountController::class)->parameters([
        'language-school-pioneers-discounts' => 'ls_p_discount',
    ]);
    // Language course products
    Route::resource('language-course-online-courses', LanguageCourseOnlineCourseController::class);
    Route::resource('language-course-summer-camps', LanguageCourseSummerCampController::class);
    Route::resource('language-course-summer-camp-details', LanguageCourseSummerCampDetailController::class)
        ->parameters(['language-course-summer-camp-details' => 'detail'])
        ->except(['show']);
    Route::resource('language-course-training-courses', LanguageCourseTrainingCourseController::class);
    Route::resource('exchange-rates', ExchangeRateController::class);
    Route::resource('conversion-fees', ConversionFeeController::class);
    Route::post('exchange-rates/refresh/gbp', [ExchangeRateController::class, 'getGbpAllRates'])
        ->name('exchange-rates.refresh-gbp');
    Route::prefix('agent-referrals')->group(function () {
        Route::get('/', [AgentReferralController::class, 'index'])->name('referrals.index');
        Route::get('/create', [AgentReferralController::class, 'create'])->name('referrals.create');
        Route::post('/', [AgentReferralController::class, 'store'])->name('referrals.store');
        Route::get('/students', [AgentReferralController::class, 'students'])->name('referrals.students');
        Route::get('/{agent}/edit', [AgentReferralController::class, 'edit'])->name('referrals.edit');
        Route::put('/{agent}', [AgentReferralController::class, 'update'])->name('referrals.update');
    });

    // Bulk import/export
    Route::prefix('bulk-import-export')->name('bulk-ie.')->group(function () {
        Route::get('/', [BulkImportExportController::class, 'index'])->name('index');
        Route::get('{resource}', [BulkImportExportController::class, 'show'])->name('show');
        Route::get('{resource}/export-blank', [BulkImportExportController::class, 'exportBlank'])->name('export-blank');
        Route::get('{resource}/export-all', [BulkImportExportController::class, 'exportAll'])->name('export-all');
        Route::post('{resource}/import-new', [BulkImportExportController::class, 'importNew'])->name('import-new');
        Route::post('{resource}/import-update', [BulkImportExportController::class, 'importUpdate'])->name('import-update');
        Route::post('{resource}/import-new/commit', [BulkImportExportController::class, 'commitImport'])->name('import-new.commit');
    });

    // Content modules
    Route::resource('reviews', ReviewController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('certifications', CertificationController::class);
    Route::resource('accommodation-rooms', AccommodationRoomController::class);

    // Offices & CMS
    Route::resource('offices', OfficeController::class);

    // Admin profile (temporary placeholder routes)
    Route::get('profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [UserController::class, 'update'])->name('profile.update');

    // Settings
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/branding', 'updateBranding')->name('branding');
        Route::post('/email', 'updateEmailVerification')->name('email');
        Route::post('/smtp', 'updateSmtp')->name('smtp');
        Route::post('/twilio', 'updateTwilio')->name('twilio');
        Route::post('/google', 'updateGoogle')->name('google');
        Route::post('/contact', 'updateContact')->name('contact');
        Route::post('/api-clients', 'updateApiClients')->name('api-clients');
    });

    // Maintenance
    Route::post('maintenance/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return back()->with('status', 'System cache cleared successfully!');
    })->name('maintenance.clear-cache');
});
