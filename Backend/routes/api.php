<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CourseEnglishArticlesController;
use App\Http\Controllers\Api\CourseEnglishOffersController;
use App\Http\Controllers\Api\CourseEnglishHomeController;
use App\Http\Controllers\Api\LanguageCourseInteractionController;
use App\Http\Controllers\Api\CourseEnglishListingController;
use App\Http\Controllers\Api\CourseEnglishUtilitiesController;
use App\Http\Controllers\Api\CourseEnglishAboutController;
use App\Http\Controllers\Api\CourseEnglishUniversityAdmissionsController;
use App\Http\Controllers\Api\CourseEnglishTravelController;
use App\Http\Controllers\Api\CourseEnglishContactController;
use App\Http\Controllers\Api\CourseEnglishContactSubmissionController;
use App\Http\Controllers\Api\ScholarshipController;
use App\Http\Controllers\Api\ScholarshipApplicationController;
use App\Http\Controllers\Api\UniversityBrandingController;
use App\Http\Controllers\Api\UniversityApiController;
use App\Http\Controllers\Api\UniversityStudentApiController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\StudentController;
use App\Models\CmsPage;
use App\Models\FeaturedList;
use App\Models\Review;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('/whatsapp/send-otp', [AuthController::class, 'sendWhatsappOtp']);
    Route::post('/whatsapp/verify-otp', [AuthController::class, 'verifyWhatsappOtp']);
});

// Backward compatibility for University frontend
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('agent')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/me', [AgentController::class, 'me']);
        Route::get('/overview', [AgentController::class, 'overview']);
        Route::get('/referrals', [AgentController::class, 'referrals']);
        Route::post('/referrals', [AgentController::class, 'refreshReferral']);
        Route::post('/bookings', [AgentController::class, 'bookCourse']);
        Route::get('/students', [AgentController::class, 'students']);
        Route::post('/new-student', [AgentController::class, 'newStudent']);
    });

Route::prefix('student')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/me', [StudentController::class, 'me']);
        Route::get('/bookings', [StudentController::class, 'bookings']);
        Route::post('/update-profile', [StudentController::class, 'updateProfile']);
    });

// Public blog endpoints
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blog-categories', [BlogController::class, 'categories']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);

// University frontend public endpoints
Route::get('/home/hero', [UniversityApiController::class, 'hero']);
Route::get('/home/certificate', [UniversityApiController::class, 'certificate']);
Route::get('/home/destinations', [UniversityApiController::class, 'homeDestinations']);
Route::get('/home/universities', [UniversityApiController::class, 'homeUniversities']);
Route::get('/home/reviews', [UniversityApiController::class, 'homeReviews']);
Route::get('/home/scholarships', [UniversityApiController::class, 'homeScholarships']);
Route::get('/home/blogs', [UniversityApiController::class, 'homeBlogs']);
Route::get('/home/faqs', [UniversityApiController::class, 'homeFaqs']);
Route::get('/home/cms', [UniversityApiController::class, 'homeCms']);

Route::get('/countries', [UniversityApiController::class, 'countries']);
Route::get('/intakes', [UniversityApiController::class, 'intakes']);
Route::get('/levels', [UniversityApiController::class, 'levels']);
Route::get('/subject-areas', [UniversityApiController::class, 'subjectAreas']);
Route::get('/cities', [UniversityApiController::class, 'cities']);

Route::get('/courses', [UniversityApiController::class, 'courses'])->name('api.courses.index');
Route::get('/courses/{slug}', [UniversityApiController::class, 'courseShow'])->name('api.courses.show');
Route::get('/universities', [UniversityApiController::class, 'universities'])->name('api.universities.index');
Route::get('/universities/{slug}', [UniversityApiController::class, 'universityShow'])->name('api.universities.show');
Route::get('/destinations', [UniversityApiController::class, 'destinations']);
Route::get('/destinations/{slug}', [UniversityApiController::class, 'destinationShow']);
Route::get('/scholarships', [UniversityApiController::class, 'scholarships']);
Route::get('/scholarships/{slug}', [ScholarshipController::class, 'show']);
Route::get('/cms-pages/{slug}', [UniversityApiController::class, 'cmsPage']);
Route::get('/offices', [UniversityApiController::class, 'offices']);
Route::get('/offices/{slug}', [UniversityApiController::class, 'officeShow']);
Route::get('/navbar/destinations', [UniversityApiController::class, 'navbarDestinations']);
Route::get('/destination-guides', [UniversityApiController::class, 'destinationGuides']);
Route::post('/contact/submit', [UniversityApiController::class, 'contactSubmit']);
Route::get('/accommodation-rooms', [UniversityApiController::class, 'accommodationRooms']);
Route::get('/accommodation-rooms/{slug}', [UniversityApiController::class, 'accommodationRoomShow']);
Route::get('/faqs', [UniversityApiController::class, 'simpleFaqs']);
Route::get('/about', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_about', now()->addWeek(), fn() => CmsPage::where('slug', 'about')->where('is_active', true)->first())));
Route::get('/contact', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_contact', now()->addWeek(), fn() => \App\Support\SystemSettings::contactInfo())));
Route::get('/services', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_services', now()->addWeek(), fn() => CmsPage::where('slug', 'services')->where('is_active', true)->first())));
Route::get('/testimonials', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_testimonials', now()->addWeek(), fn() => Review::active()->latest()->take(12)->get())));
Route::get('/agent-info', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_agent_info', now()->addWeek(), fn() => FeaturedList::where('key', 'agent-info')->where('is_active', true)->get())));
Route::get('/trust-partners', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_trust_partners', now()->addWeek(), fn() => FeaturedList::where('key', 'trust-partners')->where('is_active', true)->get())));
Route::get('/process-steps', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_process_steps', now()->addWeek(), fn() => FeaturedList::where('key', 'process-steps')->where('is_active', true)->get())));
Route::get('/benefits', fn() => response()->json(\Illuminate\Support\Facades\Cache::remember('uni_benefits', now()->addWeek(), fn() => FeaturedList::where('key', 'benefits')->where('is_active', true)->get())));
Route::post('/applications', [UniversityStudentApiController::class, 'storeApplication']);
Route::post('/uni-applications', [UniversityStudentApiController::class, 'storeUniApplication']);
Route::post('/scholarship-applications', [ScholarshipApplicationController::class, 'store']);

// Public settings
Route::get('/settings/public', [SettingController::class, 'index']);
Route::get('/university/branding', [UniversityBrandingController::class, 'show']);

// CourseEnglish utilities
Route::prefix('courseenglish')->group(function () {
    Route::get('/utilities', [CourseEnglishUtilitiesController::class, 'index']);
    // Backward-compatible misspelling
    Route::get('/unitilies', [CourseEnglishUtilitiesController::class, 'index']);
    Route::get('/offers', [CourseEnglishOffersController::class, 'index']);
    Route::get('/offer', [CourseEnglishOffersController::class, 'page']);
    Route::get('/offer/cms', [CourseEnglishOffersController::class, 'cms']);
    // Backward-compatible misspelling
    Route::get('/ofers', [CourseEnglishOffersController::class, 'index']);

    Route::get('/home/online', [CourseEnglishHomeController::class, 'online']);
    Route::get('/home/summer', [CourseEnglishHomeController::class, 'summer']);
    Route::get('/home/trainingcourse', [CourseEnglishHomeController::class, 'trainingCourses']);
    // Backward-compatible misspelling
    Route::get('/home/traingcoruse', [CourseEnglishHomeController::class, 'trainingCourses']);
    Route::get('/home/blogs', [CourseEnglishHomeController::class, 'blogs']);
    Route::get('/home/branding', [CourseEnglishHomeController::class, 'branding']);
    Route::get('/home/cms', [CourseEnglishHomeController::class, 'cms']);
    Route::get('/certificates', [CourseEnglishHomeController::class, 'certificates']);
    Route::get('/reviews', [CourseEnglishHomeController::class, 'reviews']);
    Route::get('/faqs', [CourseEnglishHomeController::class, 'faqs']);

    // Listing page endpoints (paginated, for dedicated pages)
    Route::get('/language-institutes', [CourseEnglishListingController::class, 'languageInstitutes']);
    Route::get('/language-institutes/cms', [CourseEnglishListingController::class, 'cms']);
    Route::get('/language-institutes/{slug}', [CourseEnglishListingController::class, 'show'])->where('slug', '[a-z0-9-]+');
    Route::get('/online-courses', [CourseEnglishListingController::class, 'onlineCourses']);
    Route::get('/online-courses/cms', [CourseEnglishListingController::class, 'onlineCms']);
    Route::get('/online-courses/{slug}', [CourseEnglishListingController::class, 'onlineCourseDetails']);
    Route::get('/summer-programs', [CourseEnglishListingController::class, 'summerPrograms']);
    Route::get('/summer-programs/cms', [CourseEnglishListingController::class, 'summerCms']);
    Route::get('/training-and-professional-courses', [CourseEnglishListingController::class, 'trainingCourses']);
    Route::get('/training-and-professional-courses/cms', [CourseEnglishListingController::class, 'trainingCms']);

    // Articles
    Route::get('/articles', [CourseEnglishArticlesController::class, 'index']);
    Route::get('/articles/cms', [CourseEnglishArticlesController::class, 'cms']);
    Route::get('/articles/categories', [CourseEnglishArticlesController::class, 'categories']);
    Route::get('/articles/category/{slug}', [CourseEnglishArticlesController::class, 'category']);
    Route::get('/articles/{slug}', [CourseEnglishArticlesController::class, 'show']);
    Route::get('/about', [CourseEnglishAboutController::class, 'show']);
    Route::get('/university-admissions', [CourseEnglishUniversityAdmissionsController::class, 'show']);
    Route::get('/travel-and-tourism', [CourseEnglishTravelController::class, 'show']);
    Route::get('/contact-us', [CourseEnglishContactController::class, 'show']);
    Route::post('/contact-us/submit', [CourseEnglishContactSubmissionController::class, 'store']);

    // Booking Routes (Public/Verified via Token)
    Route::post('/booking/send-otp', [\App\Http\Controllers\BookingController::class, 'sendOtp']);
    Route::post('/booking/verify-otp', [\App\Http\Controllers\BookingController::class, 'verifyOtp']);
    Route::post('/booking/language-course', [\App\Http\Controllers\BookingController::class, 'storeLanguageCourse']);
    Route::post('/booking/online-course', [\App\Http\Controllers\BookingController::class, 'storeOnlineCourse']);
    Route::post('/booking/summer-camp', [\App\Http\Controllers\BookingController::class, 'storeSummerCamp']);
    Route::post('/booking/training-course', [\App\Http\Controllers\BookingController::class, 'storeTrainingCourse']);
    Route::post('/booking/set-password', [\App\Http\Controllers\BookingController::class, 'setPassword'])->middleware('auth:api');

    // Details Pages
    Route::get('/summer-programs/{slug}', [CourseEnglishListingController::class, 'summerProgramDetails']);
    Route::get('/training-and-professional-courses/{slug}', [CourseEnglishListingController::class, 'trainingCourseDetails']);

    // Student Bookings
    Route::get('/student-bookings', [StudentController::class, 'bookings'])->middleware('auth:api');
});

Route::prefix('courseenglish')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('/wishlist', [LanguageCourseInteractionController::class, 'wishlistList']);
        Route::post('/wishlist/add', [LanguageCourseInteractionController::class, 'wishlistAdd']);
        Route::post('/wishlist/remove', [LanguageCourseInteractionController::class, 'wishlistRemove']);

        // Invoice data for student bookings
        Route::get('/student-bookings/{id}/invoice-data', [StudentController::class, 'invoiceData']);

        Route::get('/compare', [LanguageCourseInteractionController::class, 'compareList']);
        Route::post('/compare/add', [LanguageCourseInteractionController::class, 'compareAdd']);
        Route::post('/compare/remove', [LanguageCourseInteractionController::class, 'compareRemove']);
    });

Route::middleware('auth:api')->group(function () {
    Route::get('/my-applications', [UniversityStudentApiController::class, 'myApplications']);
    Route::get('/my-uni-applications', [UniversityStudentApiController::class, 'myUniApplications']);
    Route::get('/my-scholarship-applications', [ScholarshipApplicationController::class, 'index']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('/wishlist', [UniversityStudentApiController::class, 'wishlist']);
    Route::post('/wishlist', [UniversityStudentApiController::class, 'addWishlist']);
    Route::delete('/wishlist/{course}', [UniversityStudentApiController::class, 'removeWishlist']);
    Route::get('/student/dashboard', [UniversityStudentApiController::class, 'dashboard']);
});

// User-specified misspelling
Route::get('/coureenglish/offers', [CourseEnglishOffersController::class, 'index']);
// User-specified misspelling for certificates
Route::get('/courseehglish/certificates', [CourseEnglishHomeController::class, 'certificates']);
