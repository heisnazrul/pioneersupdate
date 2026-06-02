<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\ContactSubmission;
use App\Models\Faq;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolPioneersDiscount;
use App\Models\LanguageSchoolPickup;
use App\Models\Review;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseEnglishController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support
    ) {
    }

    public function utilities(Request $request): JsonResponse
    {
        $schools = LanguageSchool::query()
            ->active()
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar', 'slug', 'logo_url'])
            ->map(fn (LanguageSchool $school) => [
                'id' => $school->id,
                'name' => $school->name_en,
                'ar_name' => $school->name_ar,
                'slug' => $school->slug,
                'logo' => $this->support->toPublicUrl($school->logo_url),
            ])
            ->values();

        $countries = \App\Models\Country::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'ar_name', 'slug', 'flag', 'country_code'])
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'ar_name' => $country->ar_name,
                'slug' => $country->slug,
                'country_code' => $country->country_code,
                'flag' => $this->support->toPublicUrl($country->resolveFlagPath()),
            ])
            ->values();

        $cities = \App\Models\City::query()
            ->active()
            ->with('country:id,name,ar_name,flag,country_code')
            ->orderBy('name')
            ->get(['id', 'country_id', 'name', 'ar_name', 'slug'])
            ->map(fn ($city) => [
                'id' => $city->id,
                'name' => $city->name,
                'ar_name' => $city->ar_name,
                'slug' => $city->slug,
                'country_name' => $city->country?->name,
                'country_ar_name' => $city->country?->ar_name,
                'country_code' => $city->country?->country_code,
                'flag' => $this->support->toPublicUrl($city->country?->resolveFlagPath()),
            ])
            ->values();

        $languageCourseTypes = LanguageCourseCategory::query()
            ->active()
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar', 'slug'])
            ->map(fn (LanguageCourseCategory $category) => [
                'id' => $category->id,
                'name' => $category->name_en,
                'ar_name' => $category->name_ar,
                'slug' => $category->slug,
            ])
            ->values();

        return response()->json([
            'schools' => $schools,
            'countries' => $countries,
            'cities' => $cities,
            'language_course_types' => $languageCourseTypes,
        ]);
    }

    public function branding(): JsonResponse
    {
        return response()->json([
            'branding' => $this->support->brandingPayload(),
        ]);
    }

    public function homeOnline(): JsonResponse
    {
        $courses = LanguageOnlineCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with([
                'school.branches.city.country',
                'courseType',
            ])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageOnlineCourse $course) => $this->support->onlineCourseCard($course))
            ->values();

        return response()->json([
            'online_courses' => $courses,
        ]);
    }

    public function homeSummer(): JsonResponse
    {
        $camps = LanguageCourseSummerCamp::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with([
                'branch.school',
                'branch.city.country',
                'courseType',
                'detail',
            ])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageCourseSummerCamp $camp) => $this->support->summerCampCard($camp))
            ->values();

        return response()->json([
            'summer_camps' => $camps,
        ]);
    }

    public function homeTraining(): JsonResponse
    {
        $courses = LanguageCourseTrainingCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with([
                'school',
                'branch.city.country',
                'courseType',
            ])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageCourseTrainingCourse $course) => $this->support->trainingCourseCard($course))
            ->values();

        return response()->json([
            'training_courses' => $courses,
        ]);
    }

    public function homeBlogs(): JsonResponse
    {
        $blogs = Blog::query()
            ->whereNotNull('published_at')
            ->whereIn('audience_scope', ['school', 'all'])
            ->with(['category', 'publisher'])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->map(fn (Blog $blog) => $this->support->blogCard($blog))
            ->values();

        return response()->json([
            'blogs' => $blogs,
        ]);
    }

    public function certificates(): JsonResponse
    {
        $certificates = Certification::query()
            ->latest('id')
            ->get()
            ->map(fn (Certification $certification) => $this->support->certificationCard($certification))
            ->values();

        return response()->json([
            'certificates' => $certificates,
        ]);
    }

    public function reviews(): JsonResponse
    {
        $reviews = Review::query()
            ->where('is_active', true)
            ->where('is_approved', true)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn (Review $review) => $this->support->reviewCard($review))
            ->values();

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    public function faqs(): JsonResponse
    {
        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        $items = $faqs
            ->map(fn (Faq $faq) => $this->support->faqCard($faq))
            ->values();

        $categories = $faqs
            ->unique(fn (Faq $faq) => $faq->category)
            ->map(fn (Faq $faq) => [
                'category' => $faq->category,
                'ar_category' => $faq->ar_category,
            ])
            ->values();

        return response()->json([
            'faqs' => $items,
            'categories' => $categories,
        ]);
    }

    public function offers(): JsonResponse
    {
        $categories = LanguageCourseCategory::query()
            ->active()
            ->orderBy('name_en')
            ->get()
            ->map(fn (LanguageCourseCategory $category) => [
                'id' => $category->id,
                'name' => $category->name_en,
                'ar_name' => $category->name_ar,
                'slug' => $category->slug,
            ])
            ->values();

        $courses = LanguageSchoolCourse::query()
            ->active()
            ->with([
                'category',
                'promotions' => fn ($query) => $query->active(),
                'branch.school',
                'branch.city.country',
                'branch.pickups',
                'branch.accommodations',
                'branch.insurance',
            ])
            ->latest('id')
            ->limit(24)
            ->get()
            ->map(fn (LanguageSchoolCourse $course) => $this->support->languageCourseCard($course))
            ->values();

        return response()->json([
            'language_course_tags' => $categories,
            'language_courses' => $courses,
        ]);
    }

    public function offerPage(): JsonResponse
    {
        $languageCourses = LanguageSchoolCourse::query()
            ->active()
            ->with([
                'category',
                'promotions' => fn ($query) => $query->active(),
                'branch.school',
                'branch.city.country',
                'branch.pickups',
                'branch.accommodations',
                'branch.insurance',
            ])
            ->latest('id')
            ->limit(16)
            ->get()
            ->map(fn (LanguageSchoolCourse $course) => $this->support->languageCourseCard($course))
            ->values();

        $summerCamps = LanguageCourseSummerCamp::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['branch.school', 'branch.city.country', 'courseType', 'detail'])
            ->latest('id')
            ->limit(12)
            ->get()
            ->map(fn (LanguageCourseSummerCamp $camp) => $this->support->summerCampCard($camp))
            ->values();

        $onlineCourses = LanguageOnlineCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school.branches.city.country', 'courseType'])
            ->latest('id')
            ->limit(12)
            ->get()
            ->map(fn (LanguageOnlineCourse $course) => $this->support->onlineCourseCard($course))
            ->values();

        $trainingCourses = LanguageCourseTrainingCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school', 'branch.city.country', 'courseType'])
            ->latest('id')
            ->limit(12)
            ->get()
            ->map(fn (LanguageCourseTrainingCourse $course) => $this->support->trainingCourseCard($course))
            ->values();

        return response()->json([
            'language_courses' => $languageCourses,
            'summer_camps' => $summerCamps,
            'online_courses' => $onlineCourses,
            'training_courses' => $trainingCourses,
        ]);
    }

    public function languageInstitutes(Request $request): JsonResponse
    {
        $query = LanguageSchoolCourse::query()
            ->active()
            ->with([
                'category',
                'promotions' => fn ($q) => $q->active(),
                'branch.school',
                'branch.city.country',
                'branch.pickups',
                'branch.accommodations',
                'branch.insurance',
            ]);

        if ($request->filled('school_slug')) {
            $schoolSlug = (string) $request->input('school_slug');
            $query->whereHas('branch.school', fn ($q) => $q->where('slug', $schoolSlug));
        }

        if ($request->filled('city_slug')) {
            $citySlug = (string) $request->input('city_slug');
            $query->whereHas('branch.city', fn ($q) => $q->where('slug', $citySlug));
        }

        if ($request->filled('country_slug')) {
            $countrySlug = (string) $request->input('country_slug');
            $query->whereHas('branch.city.country', fn ($q) => $q->where('slug', $countrySlug));
        }

        if ($request->filled('course_type')) {
            $courseType = (string) $request->input('course_type');
            $query->whereHas('category', function ($q) use ($courseType) {
                $q->where('name_en', $courseType)
                    ->orWhere('name_ar', $courseType)
                    ->orWhere('slug', Str::slug($courseType));
            });
        }

        $courses = $query
            ->latest('id')
            ->limit((int) $request->input('per_page', 200))
            ->get()
            ->map(fn (LanguageSchoolCourse $course) => $this->support->languageCourseCard($course))
            ->values();

        $tags = LanguageCourseCategory::query()
            ->active()
            ->orderBy('name_en')
            ->get()
            ->map(fn (LanguageCourseCategory $category) => [
                'id' => $category->id,
                'name' => $category->name_en,
                'ar_name' => $category->name_ar,
                'slug' => $category->slug,
            ])
            ->values();

        return response()->json([
            'courses' => $courses,
            'tags' => $tags,
            'total' => $courses->count(),
        ]);
    }

    public function languageInstituteDetail(string $slug): JsonResponse
    {
        $branch = \App\Models\LanguageSchoolBranch::query()
            ->with(['school', 'city.country'])
            ->where('slug', $slug)
            ->first();

        $school = $branch?->school;

        if (!$school) {
            return response()->json([
                'message' => 'Institute not found.',
            ], 404);
        }

        $rawCourses = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $branch->id)
            ->with(['category', 'promotions' => fn ($q) => $q->active(), 'branch.school', 'branch.city.country', 'branch.pickups', 'branch.accommodations', 'branch.insurance'])
            ->get();

        $courses = $rawCourses
            ->map(fn (LanguageSchoolCourse $course) => $this->support->languageCourseCard($course))
            ->values();

        $accommodations = LanguageSchoolAccommodation::query()
            ->where('branch_id', $branch->id)
            ->where('is_active', 'yes')
            ->with(['type', 'bedroomType', 'bathroomType', 'mealPlan'])
            ->get()
            ->map(fn (LanguageSchoolAccommodation $accommodation) => $this->support->accommodationCard($accommodation))
            ->values();

        $pickups = LanguageSchoolPickup::query()
            ->where('branch_id', $branch->id)
            ->get()
            ->map(fn (LanguageSchoolPickup $pickup) => $this->support->pickupCard($pickup))
            ->values();

        $insurance = LanguageSchoolInsurance::query()
            ->where('branch_id', $branch->id)
            ->first();

        $registrationAmount = (float) ($rawCourses->min('registration_admin_fee') ?? 0);
        $registration = $this->support->toGbpSar($registrationAmount, 'GBP');

        $discounts = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $branch->id)
            ->with(['promotions' => fn ($q) => $q->active()])
            ->get()
            ->flatMap(fn (LanguageSchoolCourse $course) => $course->promotions->map(fn ($promotion) => [
                'course_id' => $course->id,
                'discount_percentage' => (float) $promotion->promotion_percentage,
                'valid_from' => optional($promotion->promo_from)->format('Y-m-d'),
                'valid_to' => optional($promotion->promo_to)->format('Y-m-d'),
            ]))
            ->values();

        $pioneersDiscounts = LanguageSchoolPioneersDiscount::query()
            ->where('is_active', true)
            ->orderBy('weeks')
            ->get()
            ->map(function (LanguageSchoolPioneersDiscount $discount) {
                $amountPrices = $this->support->buildPriceMap($discount->discount_amount, 'GBP');

                return [
                    'id' => $discount->id,
                    'name' => $discount->name,
                    'ar_name' => $discount->ar_name,
                    'weeks' => $discount->weeks,
                    'discount_amount' => (float) $discount->discount_amount,
                    'discount_amount_prices' => $amountPrices,
                    'discount_full_for' => $discount->discount_full_for,
                ];
            })
            ->values();

        return response()->json([
            'school' => $this->support->schoolSummary($school, $branch),
            'courses' => $courses,
            'accommodations' => $accommodations,
            'pickups' => $pickups,
            'insurances' => $insurance ? collect([$this->support->insuranceCard($insurance)]) : collect(),
            'supplements' => [],
            'registration_fee' => [
                'amount' => $registrationAmount,
                'amount_gbp' => $registration['gbp'],
                'amount_sar' => $registration['sar'],
            ],
            'discounts' => $discounts,
            'pioneers_discounts' => $pioneersDiscounts,
        ]);
    }

    public function onlineCourses(): JsonResponse
    {
        $courses = LanguageOnlineCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school.branches.city.country', 'courseType'])
            ->latest('id')
            ->get()
            ->map(fn (LanguageOnlineCourse $course) => $this->support->onlineCourseCard($course))
            ->values();

        return response()->json([
            'online_courses' => $courses,
            'total' => $courses->count(),
        ]);
    }

    public function onlineCourseDetail(string $slug): JsonResponse
    {
        $school = LanguageSchool::query()
            ->with(['branches.city.country'])
            ->where('slug', $slug)
            ->first();

        $courseBySlug = null;
        if (!$school) {
            $courseBySlug = LanguageOnlineCourse::query()
                ->with(['school.branches.city.country'])
                ->where('slug', $slug)
                ->first();
            $school = $courseBySlug?->school;
        }

        if (!$school) {
            return response()->json(['message' => 'Online course provider not found.'], 404);
        }

        $courses = LanguageOnlineCourse::query()
            ->where('language_school_id', $school->id)
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school.branches.city.country', 'courseType'])
            ->get()
            ->map(fn (LanguageOnlineCourse $course) => $this->support->onlineCourseCard($course))
            ->values();

        return response()->json([
            'school' => $this->support->schoolSummary($school, $school->branches->first()),
            'courses' => $courses,
            'selected_course_id' => $courseBySlug?->id,
            'registration_fee' => (float) ($courses->min('registration_fee') ?? 0),
        ]);
    }

    public function summerPrograms(): JsonResponse
    {
        $camps = LanguageCourseSummerCamp::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['branch.school', 'branch.city.country', 'courseType', 'detail'])
            ->latest('id')
            ->get()
            ->map(fn (LanguageCourseSummerCamp $camp) => $this->support->summerCampCard($camp))
            ->values();

        return response()->json([
            'summer_camps' => $camps,
            'total' => $camps->count(),
        ]);
    }

    public function summerProgramDetail(string $slug): JsonResponse
    {
        $branch = \App\Models\LanguageSchoolBranch::query()
            ->with(['school', 'city.country'])
            ->where('slug', $slug)
            ->first();

        $campBySlug = null;
        if (!$branch) {
            $campBySlug = LanguageCourseSummerCamp::query()
                ->with(['branch.school', 'branch.city.country'])
                ->where('slug', $slug)
                ->first();
            $branch = $campBySlug?->branch;
        }

        if (!$branch || !$branch->school) {
            return response()->json(['message' => 'Summer camp provider not found.'], 404);
        }

        $camps = LanguageCourseSummerCamp::query()
            ->where('branch_id', $branch->id)
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['branch.school', 'branch.city.country', 'courseType', 'detail'])
            ->get()
            ->map(fn (LanguageCourseSummerCamp $camp) => $this->support->summerCampCard($camp))
            ->values();

        return response()->json([
            'school' => $this->support->schoolSummary($branch->school, $branch),
            'camps' => $camps,
            'selected_camp_id' => $campBySlug?->id,
        ]);
    }

    public function trainingCourses(): JsonResponse
    {
        $courses = LanguageCourseTrainingCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school', 'branch.city.country', 'courseType'])
            ->latest('id')
            ->get()
            ->map(fn (LanguageCourseTrainingCourse $course) => $this->support->trainingCourseCard($course))
            ->values();

        return response()->json([
            'training_courses' => $courses,
            'total' => $courses->count(),
        ]);
    }

    public function trainingCourseDetail(string $slug): JsonResponse
    {
        $course = LanguageCourseTrainingCourse::query()
            ->where('id', is_numeric($slug) ? (int) $slug : 0)
            ->where('visible', true)
            ->where('status', 'published')
            ->with(['school', 'branch.city.country', 'courseType'])
            ->first();

        if (!$course) {
            $school = LanguageSchool::query()->where('slug', $slug)->first();
            if ($school) {
                $course = LanguageCourseTrainingCourse::query()
                    ->where('language_school_id', $school->id)
                    ->where('visible', true)
                    ->where('status', 'published')
                    ->with(['school', 'branch.city.country', 'courseType'])
                    ->first();
            }
        }

        if (!$course) {
            return response()->json(['message' => 'Training course not found.'], 404);
        }

        return response()->json([
            'school' => $this->support->schoolSummary($course->school, $course->branch),
            'course' => $this->support->trainingCourseCard($course),
        ]);
    }

    public function about(): JsonResponse
    {
        return response()->json($this->support->staticPage('about'));
    }

    public function universityAdmissions(): JsonResponse
    {
        return response()->json($this->support->staticPage('university_admissions'));
    }

    public function travelAndTourism(): JsonResponse
    {
        return response()->json($this->support->staticPage('travel'));
    }

    public function contactPage(): JsonResponse
    {
        return response()->json($this->support->staticPage('contact'));
    }

    public function contactSubmit(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'subject' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        ContactSubmission::create([
            'name' => $data['first_name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? 'CourseEnglish Contact',
            'message' => $data['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
        ], 201);
    }
}
