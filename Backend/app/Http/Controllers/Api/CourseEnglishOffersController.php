<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseTag;
use App\Models\LanguageCourseType;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\CmsPage;
use App\Support\CurrencyConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CourseEnglishOffersController extends Controller
{
    private function normalizeGallery($value): array
    {
        if (empty($value)) {
            return [];
        }

        // If stored as JSON string
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_string($decoded) && !empty($decoded)) {
                    $value = [$decoded];
                } else {
                    $value = $decoded;
                }
            } else {
                // Fallback: split by comma if not valid JSON
                $value = array_map('trim', explode(',', $value));
            }
        }

        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->flatten()
            ->filter()
            ->map(fn($path) => $this->toPublicUrl($path))
            ->filter()
            ->values()
            ->all();
    }

    private function pickBranchImage($branch): ?string
    {
        if (!$branch)
            return null;

        $gallery = $this->normalizeGallery($branch->gallery_urls ?? []);
        if (count($gallery) > 0) {
            return $gallery[0];
        }

        if ($branch->school?->logo) {
            return $this->toPublicUrl($branch->school->logo);
        }

        return null;
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

    protected function isArabic(Request $request): bool
    {
        return $request->header('X-Lang') === 'ar' || $request->input('lang') === 'ar';
    }

    private function cmsPayload(): array
    {
        $page = CmsPage::query()
            ->forApp('courseenglish')
            ->where('slug', 'offers')
            ->first();

        if (!$page) {
            return ['en' => [], 'ar' => [], 'meta' => []];
        }

        $en = json_decode($page->content, true) ?: [];
        $ar = json_decode($page->ar_content, true) ?: [];

        $normalize = function ($value) use (&$normalize) {
            if (is_array($value)) {
                return array_map($normalize, $value);
            }
            if (is_string($value) && !str_starts_with($value, 'http') && str_contains($value, '/')) {
                return $this->toPublicUrl($value);
            }
            return $value;
        };

        return [
            'en' => $normalize($en),
            'ar' => $normalize($ar),
            'meta' => [
                'title' => $page->title,
                'ar_title' => $page->ar_title,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
            ],
        ];
    }

    public function cms(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_offers_cms_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            return $this->cmsPayload();
        });

        return response()->json($response);
    }

    public function index(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_offers_index_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $generalTypeId = LanguageCourseType::query()
                ->where('name', 'General English Course')
                ->value('id');

            if (!$generalTypeId) {
                return [
                    'language_course_tags' => [],
                    'language_courses' => [],
                ];
            }

            $tagIds = LanguageSchoolCourse::query()
                ->where('language_course_type_id', $generalTypeId)
                ->whereNotNull('language_course_tag_id')
                ->distinct()
                ->pluck('language_course_tag_id')
                ->values();

            $tags = LanguageCourseTag::query()
                ->select('id', 'name', 'ar_name')
                ->whereIn('id', $tagIds)
                ->orderBy('id')
                ->get()
                ->map(function (LanguageCourseTag $tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'ar_name' => $tag->ar_name,
                        'slug' => Str::slug($tag->name),
                    ];
                })
                ->values();

            $courses = [];
            $converter = new CurrencyConverter();
            $baseQuery = LanguageSchoolCourse::query()
                ->where('language_course_type_id', $generalTypeId)
                ->with([
                    'tag:id,name,ar_name',
                    'type:id,name,ar_name',
                    'branch:id,language_school_id,city_id,gallery_urls,slug',
                    'branch.school:id,name,ar_name,logo,rating,slug',
                    'branch.city:id,country_id,name,ar_name,slug',
                    'branch.city.country:id,name,ar_name,flag',
                ])
                ->withMin('fees', 'fee')
                ->withMax('fees', 'fee')
                ->orderBy('id');

            foreach ($tags as $tag) {
                $tagCourses = (clone $baseQuery)
                    ->where('language_course_tag_id', $tag['id'])
                    ->limit(8)
                    ->get();

                foreach ($tagCourses as $course) {
                    $gallery = $this->normalizeGallery($course->branch?->gallery_urls ?? []);
                    $image = $this->pickBranchImage($course->branch);
                    $minFee = $course->fees_min_fee;
                    $maxFee = $course->fees_max_fee;
                    $newPrices = $converter->toGbpSar($minFee, 'GBP');
                    $oldPrices = $maxFee !== null ? $converter->toGbpSar($maxFee, 'GBP') : ['gbp' => null, 'sar' => null];
                    $courses[] = [
                        'id' => $course->id,
                        'name' => $course->name,
                        'ar_name' => $course->ar_name,
                        'slug' => $course->branch?->slug ?: ($course->branch?->school?->slug && $course->branch?->city?->slug ? $course->branch->school->slug . '-' . $course->branch->city->slug : null),
                        'tag_id' => $course->language_course_tag_id,
                        'tag' => $course->tag?->name,
                        'tag_ar_name' => $course->tag?->ar_name,
                        'tag_slug' => $course->tag?->name ? Str::slug($course->tag->name) : null,
                        'school_name' => $course->branch?->school?->name,
                        'school_ar_name' => $course->branch?->school?->ar_name,
                        'city' => $course->branch?->city?->name,
                        'city_ar_name' => $course->branch?->city?->ar_name,
                        'school_logo' => $this->toPublicUrl($course->branch?->school?->logo),
                        'country' => $course->branch?->city?->country?->name,
                        'country_ar_name' => $course->branch?->city?->country?->ar_name,
                        'flag' => $this->toPublicUrl($course->branch?->city?->country?->flag),
                        'course_type' => $course->type?->name,
                        'course_type_ar_name' => $course->type?->ar_name,
                        'price_new' => $minFee,
                        'price_old' => $maxFee !== null && $maxFee != $minFee ? $maxFee : null,
                        'price_new_gbp' => $newPrices['gbp'],
                        'price_new_sar' => $newPrices['sar'],
                        'price_old_gbp' => $oldPrices['gbp'],
                        'price_old_sar' => $oldPrices['sar'],
                        'currency_gbp' => 'GBP',
                        'currency_sar' => 'SAR',
                        'rating' => $course->branch?->school?->rating,
                        'image' => $image,
                        'gallery_urls' => $gallery,
                    ];
                }
            }

            return [
                'language_course_tags' => $tags,
                'language_courses' => $courses,
            ];
        });

        return response()->json($response);
    }

    public function page(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_offers_page_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () {
            $converter = new CurrencyConverter();

            $generalTypeId = LanguageCourseType::query()
                ->where('name', 'General English Course')
                ->value('id');

            $languageCourses = [];
            if ($generalTypeId) {
                $courses = LanguageSchoolCourse::query()
                    ->where('language_course_type_id', $generalTypeId)
                    ->with([
                        'tag:id,name,ar_name',
                        'type:id,name,ar_name',
                        'branch:id,language_school_id,city_id,gallery_urls,slug',
                        'branch.school:id,name,ar_name,logo,rating,is_preferred,slug',
                        'branch.city:id,country_id,name,ar_name,slug',
                        'branch.city.country:id,name,ar_name,flag,currency_code,slug',
                        'fees' => fn($q) => $q->select('id', 'language_school_course_id', 'week_number', 'fee'),
                    ])
                    ->withMin('fees', 'fee')
                    ->withMax('fees', 'fee')
                    ->orderBy('id')
                    ->limit(24)
                    ->get();

                $languageCourses = $courses->map(function (LanguageSchoolCourse $course) use ($converter) {
                    $gallery = $this->normalizeGallery($course->branch?->gallery_urls ?? []);
                    $image = $this->pickBranchImage($course->branch);
                    $minFee = $course->fees_min_fee;
                    $maxFee = $course->fees_max_fee;
                    $newPrices = $converter->toGbpSar($minFee, 'GBP');
                    $oldPrices = $maxFee !== null ? $converter->toGbpSar($maxFee, 'GBP') : ['gbp' => null, 'sar' => null];

                    $city = $course->branch?->city;
                    $country = $city?->country;
                    $location = collect([$city?->name, $country?->name])->filter()->implode(', ');

                    return [
                        'id' => $course->id,
                        'name' => $course->branch?->school?->name,
                        'ar_name' => $course->branch?->school?->ar_name,
                        'slug' => $course->branch?->slug ?: ($course->branch?->school?->slug && $city?->slug ? $course->branch->school->slug . '-' . $city->slug : null),
                        'image' => $image,
                        'gallery_urls' => $gallery,
                        'flag' => $this->toPublicUrl($country?->flag),
                        'rating' => $course->branch?->school?->rating,
                        'is_preferred' => (bool) ($course->branch?->school?->is_preferred ?? false),
                        'location' => $location ?: null,
                        'city' => $city?->name,
                        'city_ar' => $city?->ar_name,
                        'country' => $country?->name,
                        'country_ar' => $country?->ar_name,
                        'course_name' => $course->name,
                        'course_ar_name' => $course->ar_name,
                        'course_type' => $course->type?->name,
                        'course_type_ar' => $course->type?->ar_name,
                        'tag' => $course->tag?->name,
                        'tag_ar' => $course->tag?->ar_name,
                        'lessons' => $course->lessons_per_week,
                        'hours' => $course->study_time,
                        'level' => $course->required_level,
                        'price_gbp' => $newPrices['gbp'],
                        'price_sar' => $newPrices['sar'],
                        'old_price_gbp' => $oldPrices['gbp'],
                        'old_price_sar' => $oldPrices['sar'],
                    ];
                })->values();
            }

            $onlineCourses = LanguageCourseOnlineCourse::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'school:id,name,ar_name,logo',
                    'school.branches.city.country:id,name,ar_name,flag',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(16)
                ->get()
                ->map(function (LanguageCourseOnlineCourse $course) use ($converter) {
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
                        'country' => $country?->name,
                        'country_ar_name' => $country?->ar_name,
                        'flag' => $this->toPublicUrl($country?->flag),
                        'mode' => 'Online',
                        'tag' => $course->tag?->name,
                        'tag_ar_name' => $course->tag?->ar_name,
                        'course_type' => $course->courseType?->name,
                        'course_type_ar_name' => $course->courseType?->ar_name,
                        'lessons_per_week' => $course->lessons_per_week,
                        'study_time' => $course->study_time,
                        'price_new' => $course->fee_amount,
                        'price_new_gbp' => $prices['gbp'],
                        'price_new_sar' => $prices['sar'],
                        'price_unit' => $course->fee_type,
                        'image' => $this->toPublicUrl($course->thumbnail) ?: $this->pickBranchImage($branch),
                    ];
                })
                ->values();

            $summerCamps = LanguageCourseSummerCamp::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'branch.city.country:id,name,ar_name,flag,currency_code',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(16)
                ->get()
                ->map(function (LanguageCourseSummerCamp $camp) use ($converter) {
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
                        'start_date' => $camp->start_date,
                        'price_from' => $camp->fee_amount,
                        'price_from_gbp' => $prices['gbp'],
                        'price_from_sar' => $prices['sar'],
                        'fee_type' => $camp->fee_type,
                        'tag' => $camp->tag?->name,
                        'tag_ar_name' => $camp->tag?->ar_name,
                        'image' => $this->toPublicUrl($camp->thumbnail) ?: $this->pickBranchImage($camp->branch),
                    ];
                })
                ->values();

            $trainingCourses = LanguageCourseTrainingCourse::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'school:id,name,ar_name',
                    'branch.city.country:id,name,ar_name,flag',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->limit(16)
                ->get()
                ->map(function (LanguageCourseTrainingCourse $course) use ($converter) {
                    $city = $course->branch?->city;
                    $country = $city?->country;
                    $baseCurrency = $country?->currency_code ?: 'GBP';
                    $prices = $converter->toGbpSar($course->fee_amount, $baseCurrency);

                    $location = null;
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
                        'price_gbp' => $prices['gbp'],
                        'price_sar' => $prices['sar'],
                        'duration' => $duration,
                        'image' => $this->toPublicUrl($course->thumbnail) ?: $this->pickBranchImage($course->branch),
                        'currency_code' => $baseCurrency,
                        'slug' => 'training-course-' . $course->id,
                    ];
                })
                ->values();

            return [
                'language_courses' => $languageCourses,
                'summer_camps' => $summerCamps,
                'online_courses' => $onlineCourses,
                'training_courses' => $trainingCourses,
            ];
        });

        return response()->json($response);
    }
}
