<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\BrandingController;

// Note: this file is loaded inside the admin prefix/name group (see routes/admin.php).

// App-specific CMS management by slug
Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('course-english', [CmsController::class, 'courseEnglish'])->name('course-english');
    Route::get('university', [CmsController::class, 'university'])->name('university');

    // CourseEnglish: Home page (static sections only)
    Route::prefix('course-english/home')->name('course-english.home.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishHome'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishHomeHero'])->name('hero');
        Route::patch('stats', [CmsController::class, 'updateCourseEnglishHomeStats'])->name('stats');
        Route::patch('certificates', [CmsController::class, 'updateCourseEnglishHomeCertificates'])->name('certificates');
        Route::patch('partners', [CmsController::class, 'updateCourseEnglishHomePartners'])->name('partners');
        Route::patch('summer', [CmsController::class, 'updateCourseEnglishHomeSummer'])->name('summer');
        Route::patch('online', [CmsController::class, 'updateCourseEnglishHomeOnline'])->name('online');
        Route::patch('reviews', [CmsController::class, 'updateCourseEnglishHomeReviews'])->name('reviews');
        Route::patch('destinations', [CmsController::class, 'updateCourseEnglishHomeDestinations'])->name('destinations');
        Route::patch('faq', [CmsController::class, 'updateCourseEnglishHomeFaq'])->name('faq');
        Route::patch('blogs', [CmsController::class, 'updateCourseEnglishHomeBlogs'])->name('blogs');
    });

    // CourseEnglish: Offers page (static text only)
    Route::prefix('course-english/offers')->name('course-english.offers.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishOffers'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateCourseEnglishOffers'])->name('update');
    });

    // CourseEnglish: About Us page
    Route::prefix('course-english/about-us')->name('course-english.about.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishAbout'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateCourseEnglishAbout'])->name('update');
    });

    // CourseEnglish: Language Institutes page
    Route::prefix('course-english/language-institutes')->name('course-english.language-institutes.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishLanguageInstitutes'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishLanguageInstitutesHero'])->name('hero');
        Route::patch('results', [CmsController::class, 'updateCourseEnglishLanguageInstitutesResults'])->name('results');
        Route::patch('sidebar', [CmsController::class, 'updateCourseEnglishLanguageInstitutesSidebar'])->name('sidebar');
        Route::patch('load-more', [CmsController::class, 'updateCourseEnglishLanguageInstitutesLoadMore'])->name('load-more');
    });

    // CourseEnglish: Language Institute Detail page
    Route::prefix('course-english/language-institute-detail')->name('course-english.language-institute-detail.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishLanguageInstituteDetail'])->name('edit');
        Route::patch('top-nav', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailTopNav'])->name('top-nav');
        Route::patch('course-step', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailCourseStep'])->name('course-step');
        Route::patch('accommodation-step', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailAccommodationStep'])->name('accommodation-step');
        Route::patch('additional-options', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailAdditionalOptions'])->name('additional-options');
        Route::patch('sidebar', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailSidebar'])->name('sidebar');
        Route::patch('booking-summary', [CmsController::class, 'updateCourseEnglishLanguageInstituteDetailBookingSummary'])->name('booking-summary');
    });

    // CourseEnglish: Articles page
    Route::prefix('course-english/articles')->name('course-english.articles.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishArticles'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishArticlesHero'])->name('hero');
        Route::patch('empty', [CmsController::class, 'updateCourseEnglishArticlesEmpty'])->name('empty');
        Route::patch('card', [CmsController::class, 'updateCourseEnglishArticlesCard'])->name('card');
        Route::patch('sidebar', [CmsController::class, 'updateCourseEnglishArticlesSidebar'])->name('sidebar');
    });

    // CourseEnglish: Contact Us page
    Route::prefix('course-english/contact-us')->name('course-english.contact.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishContact'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateCourseEnglishContact'])->name('update');
    });

    // CourseEnglish: Travel & Tourism page
    Route::prefix('course-english/travel-and-tourism')->name('course-english.travel.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishTravel'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishTravelHero'])->name('hero');
        Route::patch('cta', [CmsController::class, 'updateCourseEnglishTravelCta'])->name('cta');
        Route::patch('features', [CmsController::class, 'updateCourseEnglishTravelFeatures'])->name('features');
        Route::patch('destinations', [CmsController::class, 'updateCourseEnglishTravelDestinations'])->name('destinations');
        Route::patch('services', [CmsController::class, 'updateCourseEnglishTravelServices'])->name('services');
        Route::patch('inquiry', [CmsController::class, 'updateCourseEnglishTravelInquiry'])->name('inquiry');
    });

    // CourseEnglish: University Admissions page
    Route::prefix('course-english/university-admissions')->name('course-english.university-admissions.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishUniversityAdmissions'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishUniversityAdmissionsHero'])->name('hero');
        Route::patch('cards', [CmsController::class, 'updateCourseEnglishUniversityAdmissionsCards'])->name('cards');
        Route::patch('stats', [CmsController::class, 'updateCourseEnglishUniversityAdmissionsStats'])->name('stats');
    });

    // CourseEnglish: Compare page
    Route::prefix('course-english/compare')->name('course-english.compare.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishCompare'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishCompareHero'])->name('hero');
        Route::patch('table', [CmsController::class, 'updateCourseEnglishCompareTable'])->name('table');
    });

    // CourseEnglish: Wishlist page
    Route::prefix('course-english/wishlist')->name('course-english.wishlist.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishWishlist'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishWishlistHero'])->name('hero');
        Route::patch('card', [CmsController::class, 'updateCourseEnglishWishlistCard'])->name('card');
        Route::patch('empty', [CmsController::class, 'updateCourseEnglishWishlistEmpty'])->name('empty');
    });

    // CourseEnglish: Online Courses page
    Route::prefix('course-english/online-courses')->name('course-english.online-courses.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishOnlineCourses'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishOnlineCoursesHero'])->name('hero');
        Route::patch('results', [CmsController::class, 'updateCourseEnglishOnlineCoursesResults'])->name('results');
        Route::patch('load-more', [CmsController::class, 'updateCourseEnglishOnlineCoursesLoadMore'])->name('load-more');
    });

    // CourseEnglish: Summer Programs page
    Route::prefix('course-english/summer-programs')->name('course-english.summer-programs.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishSummerPrograms'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishSummerProgramsHero'])->name('hero');
        Route::patch('results', [CmsController::class, 'updateCourseEnglishSummerProgramsResults'])->name('results');
        Route::patch('load-more', [CmsController::class, 'updateCourseEnglishSummerProgramsLoadMore'])->name('load-more');
    });

    // CourseEnglish: Training & Professional Courses page
    Route::prefix('course-english/training-and-professional-courses')->name('course-english.training.')->group(function () {
        Route::get('/', [CmsController::class, 'editCourseEnglishTrainingCourses'])->name('edit');
        Route::patch('hero', [CmsController::class, 'updateCourseEnglishTrainingCoursesHero'])->name('hero');
        Route::patch('results', [CmsController::class, 'updateCourseEnglishTrainingCoursesResults'])->name('results');
        Route::patch('load-more', [CmsController::class, 'updateCourseEnglishTrainingCoursesLoadMore'])->name('load-more');
    });

    // University: About page
    Route::prefix('university/home')->name('university.home.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityHome'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityHome'])->name('update');
    });

    // University: About page
    Route::prefix('university/about')->name('university.about.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityAbout'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityAbout'])->name('update');
    });

    // University: Contact page
    Route::prefix('university/contact')->name('university.contact.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityContact'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityContact'])->name('update');
    });

    // University: Services landing
    Route::prefix('university/services')->name('university.services.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityServices'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityServices'])->name('update');
    });

    // University: Visa Support
    Route::prefix('university/visa-support')->name('university.visa.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityVisa'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityVisa'])->name('update');
    });

    // University: Agents
    Route::prefix('university/agents')->name('university.agents.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityAgents'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityAgents'])->name('update');
    });

    // University: Accommodation
    Route::prefix('university/accommodation')->name('university.accommodation.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityAccommodation'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityAccommodation'])->name('update');
    });

    // University: Applications (overview)
    Route::prefix('university/applications')->name('university.applications.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityApplications'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityApplications'])->name('update');
    });

    // University: Application (detail)
    Route::prefix('university/application')->name('university.application.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityApplication'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityApplication'])->name('update');
    });

    // University: Student Guide
    Route::prefix('university/student-guide')->name('university.student-guide.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityStudentGuide'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityStudentGuide'])->name('update');
    });

    // University: Destinations
    Route::prefix('university/destinations')->name('university.destinations.')->group(function () {
        Route::get('/', [CmsController::class, 'editUniversityDestinations'])->name('edit');
        Route::patch('/', [CmsController::class, 'updateUniversityDestinations'])->name('update');
    });

    // Branding: CourseEnglish
    Route::prefix('course-english/branding')->name('course-english.branding.')->group(function () {
        Route::get('/', [BrandingController::class, 'editCourseEnglish'])->name('edit');
        Route::patch('header', [BrandingController::class, 'updateCourseEnglishHeader'])->name('header');
        Route::patch('footer', [BrandingController::class, 'updateCourseEnglishFooter'])->name('footer');
        Route::patch('mobile', [BrandingController::class, 'updateCourseEnglishMobile'])->name('mobile');
    });

    // Branding: University
    Route::prefix('university/branding')->name('university.branding.')->group(function () {
        Route::get('/', [BrandingController::class, 'editUniversity'])->name('edit');
        Route::patch('header', [BrandingController::class, 'updateUniversityHeader'])->name('header');
        Route::patch('footer', [BrandingController::class, 'updateUniversityFooter'])->name('footer');
        Route::patch('mobile', [BrandingController::class, 'updateUniversityMobile'])->name('mobile');
    });

    // Legacy branding routes (keep backward compatibility)
    Route::get('branding', [BrandingController::class, 'edit'])->name('branding.edit');
    Route::patch('branding/header', [BrandingController::class, 'updateHeader'])->name('branding.header');
    Route::patch('branding/footer', [BrandingController::class, 'updateFooter'])->name('branding.footer');
    Route::patch('branding/mobile', [BrandingController::class, 'updateMobile'])->name('branding.mobile');
});
