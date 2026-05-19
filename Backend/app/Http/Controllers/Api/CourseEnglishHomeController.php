<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Blog;
use App\Models\Certification;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\Review;
use App\Models\Setting;
use App\Support\CurrencyConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseEnglishHomeController extends Controller
{
    private function isArabic(Request $request): bool
    {
        $lang = strtolower((string) ($request->query('lang') ?: $request->header('X-Lang') ?: $request->cookie('uni_language')));
        if ($lang === 'ar')
            return true;
        $accept = strtolower((string) $request->header('Accept-Language', ''));
        return Str::startsWith($accept, 'ar');
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $base = rtrim(config('app.url'), '/');
        $clean = ltrim($path, '/');

        if (Str::startsWith($clean, 'storage/')) {
            return $base . '/' . $clean;
        }

        return $base . '/storage/' . $clean;
    }

    private function decodeCmsContent(?string $payload): array
    {
        if (!$payload) {
            return [];
        }

        $decoded = json_decode($payload, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function resolveCmsAsset(string $value): string
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return $value;
        }

        if (Str::startsWith($trimmed, ['http://', 'https://'])) {
            return $trimmed;
        }

        if (Str::startsWith($trimmed, ['/assets/', 'assets/'])) {
            return $trimmed;
        }

        $clean = ltrim($trimmed, '/');

        if (!Str::contains($clean, '/') && !Str::contains($clean, '.')) {
            return $trimmed;
        }

        if (Str::startsWith($clean, 'storage/')) {
            return rtrim(config('app.url'), '/') . '/' . $clean;
        }

        if (Storage::disk('public')->exists($clean)) {
            return rtrim(config('app.url'), '/') . '/storage/' . $clean;
        }

        return $trimmed;
    }

    private function normalizeCmsAssets($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normalizeCmsAssets($item);
            }

            return $value;
        }

        if (is_string($value)) {
            return $this->resolveCmsAsset($value);
        }

        return $value;
    }

    public function branding(): JsonResponse
    {
        $branding = Setting::get('branding', []);

        return response()->json([
            'branding' => $this->normalizeCmsAssets($branding),
        ]);
    }

    public function cms(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_cms_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $homePage = CmsPage::query()
                ->forApp('courseenglish')
                ->where('slug', 'home')
                ->first();

            $offersPage = CmsPage::query()
                ->forApp('courseenglish')
                ->where('slug', 'offers')
                ->first();

            $home = $this->decodeCmsContent($homePage?->content);
            $homeAr = $this->decodeCmsContent($homePage?->ar_content);
            $offers = $this->decodeCmsContent($offersPage?->content);
            $offersAr = $this->decodeCmsContent($offersPage?->ar_content);

            $hero = [
                'en' => $this->normalizeCmsAssets($home['hero'] ?? []),
                'ar' => $this->normalizeCmsAssets($homeAr['hero'] ?? []),
            ];

            $homeSections = $home;
            unset($homeSections['hero']);
            $homeSectionsAr = $homeAr;
            unset($homeSectionsAr['hero']);

            return [
                'hero' => $hero,
                'offer' => [
                    'en' => $this->normalizeCmsAssets($offers),
                    'ar' => $this->normalizeCmsAssets($offersAr),
                ],
                'home' => [
                    'en' => $this->normalizeCmsAssets($homeSections),
                    'ar' => $this->normalizeCmsAssets($homeSectionsAr),
                ],
            ];
        });

        return response()->json($response);
    }

    public function online(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_online_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $converter = new CurrencyConverter();
            $courses = LanguageCourseOnlineCourse::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'school:id,name,ar_name,logo,slug',
                    'school.branches:id,language_school_id,city_id,slug',
                    'school.branches.city:id,country_id,name,ar_name,slug',
                    'school.branches.city.country:id,name,ar_name,flag',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(8)
                ->get()
                ->map(function (LanguageCourseOnlineCourse $course) use ($converter, $isArabic) {
                    $branch = $course->school?->branches?->first();
                    $city = $branch?->city;
                    $country = $city?->country;
                    $prices = $converter->toGbpSar($course->fee_amount, $course->currency_code ?: 'GBP');

                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'ar_name' => $course->ar_name,
                        'title' => $course->name,
                        'ar_title' => $course->ar_name,
                        'provider' => $course->school?->name,
                        'provider_ar_name' => $course->school?->ar_name,
                        'slug' => $course->school->slug,
                        'city' => $city?->name,
                        'city_ar_name' => $city?->ar_name,
                        'country' => $country?->name,
                        'country_ar_name' => $country?->ar_name,
                        'flag' => $this->toPublicUrl($country?->flag),
                        'mode' => 'Online',
                        'tag' => $course->tag?->name,
                        'tag_ar_name' => $course->tag?->ar_name,
                        'course_type' => $course->courseType?->name,
                        'course_type_ar_name' => $course->courseType?->ar_name,
                        'price_new' => $course->fee_amount,
                        'price_old' => null,
                        'currency_code' => $course->currency_code,
                        'price_new_gbp' => $prices['gbp'],
                        'price_new_sar' => $prices['sar'],
                        'currency_gbp' => 'GBP',
                        'currency_sar' => 'SAR',
                        'price_unit' => $course->fee_type,
                        'image' => $this->toPublicUrl($course->thumbnail),
                        'thumbnail' => $this->toPublicUrl($course->thumbnail),
                    ];
                })
                ->values();

            return [
                'online_courses' => $courses,
            ];
        });

        return response()->json($response);
    }

    public function summer(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_summer_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $converter = new CurrencyConverter();
            $camps = LanguageCourseSummerCamp::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'branch.city.country:id,name,ar_name,flag,currency_code',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(8)
                ->get()
                ->map(function (LanguageCourseSummerCamp $camp) use ($converter, $isArabic) {
                    $city = $camp->branch?->city;
                    $country = $city?->country;
                    $baseCurrency = $country?->currency_code ?: 'GBP';
                    $prices = $converter->toGbpSar($camp->fee_amount, $baseCurrency);

                    return [
                        'id' => $camp->id,
                        'name' => $camp->name,
                        'ar_name' => $camp->ar_name,
                        'title' => $camp->name,
                        'ar_title' => $camp->ar_name,
                        'city' => $city?->name,
                        'city_ar_name' => $city?->ar_name,
                        'country' => $country?->name,
                        'country_ar_name' => $country?->ar_name,
                        'flag' => $this->toPublicUrl($country?->flag),
                        'age_range' => $camp->age_range,
                        'description' => $camp->description,
                        'ar_description' => $camp->ar_description,
                        'price_from' => $camp->fee_amount,
                        'price_from_gbp' => $prices['gbp'],
                        'price_from_sar' => $prices['sar'],
                        'currency_gbp' => 'GBP',
                        'currency_sar' => 'SAR',
                        'fee_type' => $camp->fee_type,
                        'course_type' => $camp->courseType?->name,
                        'course_type_ar_name' => $camp->courseType?->ar_name,
                        'tag' => $camp->tag?->name,
                        'tag_ar_name' => $camp->tag?->ar_name,
                        'image' => $this->toPublicUrl($camp->thumbnail),
                        'thumbnail' => $this->toPublicUrl($camp->thumbnail),
                    ];
                })
                ->values();

            return [
                'summer_camps' => $camps,
            ];
        });

        return response()->json($response);
    }

    public function trainingCourses(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_training_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($isArabic) {
            $converter = new CurrencyConverter();
            $courses = LanguageCourseTrainingCourse::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'school:id,name,ar_name',
                    'branch.city.country:id,name,ar_name,flag',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(8)
                ->get()
                ->map(function (LanguageCourseTrainingCourse $course) use ($converter, $isArabic) {
                    $city = $course->branch?->city;
                    $country = $city?->country;
                    $location = null;
                    $baseCurrency = $country?->currency_code ?: 'GBP';
                    $prices = $converter->toGbpSar($course->fee_amount, $baseCurrency);

                    if ($city && $country) {
                        $location = $city->name . ', ' . $country->name;
                    } elseif ($city) {
                        $location = $city->name;
                    } elseif ($country) {
                        $location = $country->name;
                    }

                    $duration = null;
                    if ($course->study_time) {
                        $duration = $course->study_time . ' hours/week';
                    } elseif ($course->lessons_per_week) {
                        $duration = $course->lessons_per_week . ' lessons/week';
                    }

                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'ar_name' => $course->ar_name,
                        'title' => $course->name,
                        'ar_title' => $course->ar_name,
                        'category' => $course->courseType?->name,
                        'category_ar_name' => $course->courseType?->ar_name,
                        'provider' => $course->school?->name,
                        'provider_ar_name' => $course->school?->ar_name,
                        'location' => $location ?: 'Online / Hybrid',
                        'tag' => $course->tag?->name,
                        'tag_ar_name' => $course->tag?->ar_name,
                        'price' => $course->fee_amount,
                        'currency_code' => $baseCurrency,
                        'price_gbp' => $prices['gbp'],
                        'price_sar' => $prices['sar'],
                        'currency_gbp' => 'GBP',
                        'currency_sar' => 'SAR',
                        'duration' => $duration,
                        'image' => $this->toPublicUrl($course->thumbnail),
                        'thumbnail' => $this->toPublicUrl($course->thumbnail),
                        'slug' => 'training-course-' . $course->id,
                    ];
                })
                ->values();

            return [
                'training_courses' => $courses,
            ];
        });

        return response()->json($response);
    }

    public function blogs(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_blogs_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $blogs = Blog::query()
                ->with(['category:id,name,ar_name'])
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->limit(8)
                ->get()
                ->map(function (Blog $blog) {
                    $image = $this->toPublicUrl($blog->featured_image);
                    return [
                        'id' => $blog->id,
                        'title' => $blog->title,
                        'ar_title' => $blog->ar_title,
                        'slug' => $blog->slug,
                        'summary' => $blog->summary,
                        'ar_summary' => $blog->ar_summary,
                        'category' => $blog->category?->name ?? 'Blog',
                        'category_ar_name' => $blog->category?->ar_name,
                        'featured_image' => $image,
                        'image' => $image,
                        'localImage' => $image,
                        'published_at' => $blog->published_at?->format('d M Y'),
                    ];
                })
                ->values();

            return [
                'blogs' => $blogs,
            ];
        });

        return response()->json($response);
    }

    public function certificates(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_certificates_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $certificates = Certification::query()
                ->orderBy('id')
                ->get()
                ->map(function (Certification $certification) {
                    return [
                        'id' => $certification->id,
                        'title' => $certification->title,
                        'ar_title' => $certification->ar_title,
                        'subtitle' => $certification->subtitle,
                        'ar_subtitle' => $certification->ar_subtitle,
                        'image' => $this->toPublicUrl($certification->certificate_image),
                        'certificate_image' => $this->toPublicUrl($certification->certificate_image),
                        'link' => $certification->certification_link,
                        'certification_link' => $certification->certification_link,
                    ];
                })
                ->values();

            return [
                'certificates' => $certificates,
            ];
        });

        return response()->json($response);
    }

    public function reviews(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_reviews_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $reviews = Review::query()
                ->active()
                ->orderByDesc('id')
                ->limit(12)
                ->get()
                ->map(function (Review $review) {
                    $role = $review->course_name
                        ?: $review->institute_name
                        ?: $review->university_name
                        ?: $review->country_name;

                    return [
                        'id' => $review->id,
                        'name' => $review->name,
                        'ar_name' => $review->ar_name,
                        'title' => $review->title,
                        'ar_title' => $review->ar_title,
                        'review_text' => $review->review_text,
                        'ar_review_text' => $review->ar_review_text,
                        'rating' => $review->rating,
                        'role' => $role,
                        'institute_name' => $review->institute_name,
                        'ar_institute_name' => $review->ar_institute_name,
                        'photo' => $this->toPublicUrl($review->photo),
                        'thumbnail' => $this->toPublicUrl($review->thumbnail),
                        'video_url' => $review->video_url,
                        'video_iframe' => $review->video_iframe,
                    ];
                })
                ->values();

            return [
                'reviews' => $reviews,
            ];
        });

        return response()->json($response);
    }

    public function faqs(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_home_faqs_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $faqs = Faq::query()
                ->orderBy('category')
                ->orderBy('id')
                ->get()
                ->map(function (Faq $faq) {
                    return [
                        'id' => $faq->id,
                        'category' => $faq->category,
                        'ar_category' => $faq->ar_category,
                        'question' => $faq->question,
                        'ar_question' => $faq->ar_question,
                        'answer' => $faq->answer,
                        'ar_answer' => $faq->ar_answer,
                    ];
                })
                ->values();

            return [
                'faqs' => $faqs,
            ];
        });

        return response()->json($response);
    }
}
