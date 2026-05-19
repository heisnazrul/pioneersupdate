<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Gallery;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTag;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolDiscount;
use App\Models\LanguageSchoolPioneersDiscount;
use App\Support\CurrencyConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CourseEnglishListingController extends Controller
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

    private function normalizeGalleryValue($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value), fn($v) => $v !== ''));
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded), fn($v) => $v !== ''));
            }

            return collect(preg_split('/[\r\n,]+/', $value) ?: [])
                ->map(fn($v) => trim((string) $v))
                ->filter(fn($v) => $v !== '')
                ->values()
                ->all();
        }

        return [];
    }

    private function galleryEntryToUrl($entry): ?string
    {
        if (is_null($entry) || $entry === '') {
            return null;
        }

        // Numeric id (or numeric string) -> galleries.id lookup
        if (is_numeric($entry)) {
            $path = Gallery::query()->whereKey((int) $entry)->value('image_path');
            return $this->toPublicUrl($path);
        }

        $entry = trim((string) $entry);

        // Value like "Some title (ID: 12)"
        if (preg_match('/\bID:\s*(\d+)\b/i', $entry, $m)) {
            $path = Gallery::query()->whereKey((int) $m[1])->value('image_path');
            if ($path) {
                return $this->toPublicUrl($path);
            }
        }

        // Ignore broken tokens like "b" that produce /storage/b
        if (!Str::startsWith($entry, ['http://', 'https://']) && !str_contains($entry, '/') && !str_contains($entry, '.')) {
            return null;
        }

        return $this->toPublicUrl($entry);
    }

    private function getBranchCoverImage($branch): ?string
    {
        if (!$branch) {
            return null;
        }

        // Preferred: explicit gallery image ids if present in branch payload/schema
        $galleryIdEntries = $this->normalizeGalleryValue($branch->gallery_image_ids ?? null);
        foreach ($galleryIdEntries as $entry) {
            $url = $this->galleryEntryToUrl($entry);
            if ($url) {
                return $url;
            }
        }

        // Single gallery image id fallback
        if (!empty($branch->gallery_image_id)) {
            $url = $this->galleryEntryToUrl($branch->gallery_image_id);
            if ($url) {
                return $url;
            }
        }

        // Legacy/current: gallery_urls array/string
        $galleryEntries = $this->normalizeGalleryValue($branch->gallery_urls ?? null);
        foreach ($galleryEntries as $entry) {
            $url = $this->galleryEntryToUrl($entry);
            if ($url) {
                return $url;
            }
        }

        return null;
    }

    private function cmsPayloadForSlug(string $slug): array
    {
        $page = CmsPage::query()
            ->forApp('courseenglish')
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            return ['en' => [], 'ar' => [], 'meta' => []];
        }

        $en = json_decode($page->content, true) ?: [];
        $ar = json_decode($page->ar_content, true) ?: [];

        // Normalize asset paths
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

    // ----------------------------------------------------------------
    // GET /api/courseenglish/language-institutes
    // ----------------------------------------------------------------
    public function languageInstitutes(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_language_institutes_' . $lang . '_' . md5(json_encode($request->all()));

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request, $isArabic) {
            $perPage = (int) $request->input('per_page', 12);
            $converter = new CurrencyConverter();

            // 1) All tags for the filter/sort dropdown
            $tags = LanguageCourseTag::select('id', 'name', 'ar_name')->get();

            // 2) Paginated courses – one card per course
            $query = LanguageSchoolCourse::query()
                ->with([
                    'branch:id,language_school_id,city_id,gallery_urls,slug',
                    'branch.school:id,name,ar_name,slug,logo,rating,is_preferred',
                    'branch.city:id,name,ar_name,slug',
                    'branch.city.country:id,name,ar_name,flag,currency_code,slug',
                    'type:id,name,ar_name',
                    'tag:id,name,ar_name',
                    'fees' => fn($q) => $q->select('id', 'language_school_course_id', 'week_number', 'fee'),
                ])
                ->whereHas('branch.school');

            // Search Filters
            if ($slug = $request->input('school_slug')) {
                $query->whereHas('branch.school', fn($q) => $q->where('slug', $slug));
            }
            if ($slug = $request->input('city_slug')) {
                $query->whereHas('branch.city', fn($q) => $q->where('slug', $slug));
            }
            if ($slug = $request->input('country_slug')) {
                $query->whereHas('branch.city.country', fn($q) => $q->where('slug', $slug));
            }
            if ($type = $request->input('course_type')) {
                // Match by ID or Name
                $query->whereHas('type', function ($q) use ($type) {
                    if (is_numeric($type)) {
                        $q->where('id', $type);
                    } else {
                        $q->where('name', 'like', "%{$type}%")
                            ->orWhere('ar_name', 'like', "%{$type}%");
                    }
                });
            }
            // weeks and start_date are passed for booking link construction, not necessarily filtering here unless availability check needed. 
            // For now, listing shows all courses.

            $paginator = $query->orderBy('id')->paginate($perPage);

            $items = $paginator->getCollection()->map(function (LanguageSchoolCourse $course) use ($converter, $request) {
                $branch = $course->branch;
                $school = $branch?->school;
                $city = $branch?->city;
                $country = $city?->country;
                $baseCurrency = $country?->currency_code ?: 'GBP';

                // Price from week-1 fee (or selected week if filtering implemented later)
                $weekOneFee = $course->fees->where('week_number', 1)->first();
                $price = $weekOneFee ? (float) $weekOneFee->fee : null;
                $prices = $converter->toGbpSar($price, $baseCurrency);

                // Branch image: first gallery image id/url -> branch image -> school logo
                $image = $this->getBranchCoverImage($branch) ?: $this->toPublicUrl($school?->logo);

                // Location
                $location = collect([$city?->name, $country?->name])->filter()->implode(', ');

                // Booleans
                $hasAccommodation = $branch ? $branch->accommodations()->exists() : false;
                $hasPickup = $branch ? $branch->pickups()->exists() : false;
                $hasInsurance = $branch ? $branch->insuranceFees()->exists() : false;

                // Slug Logic: Prefer branch slug, fallback to school-city construction
                $slug = null;
                if ($branch && $branch->slug && !is_numeric($branch->slug)) {
                    $slug = $branch->slug;
                } elseif ($school && $school->slug) {
                    $slug = $school->slug . ($city ? '-' . $city->slug : '');
                }

                // Append query params for detail link construction
                $weeks = $request->input('weeks');
                $startDate = $request->input('start_date');

                return [
                    'id' => $course->id,
                    'image' => $image,
                    'name' => $school?->name . ($city ? ' - ' . $city->name : ''),
                    'ar_name' => $school?->ar_name,
                    'slug' => $slug,
                    'location' => $location ?: null,
                    'city' => $city?->name,
                    'city_ar' => $city?->ar_name,
                    'country' => $country?->name,
                    'country_ar' => $country?->ar_name,
                    'flag' => $this->toPublicUrl($country?->flag),
                    'logo' => $this->toPublicUrl($school?->logo),
                    'rating' => (float) ($school?->rating ?? 0),
                    'is_preferred' => (bool) ($school?->is_preferred ?? false),
                    'course_name' => $course->name,
                    'course_ar_name' => $course->ar_name,
                    'course_type' => $course->type?->name,
                    'course_type_ar' => $course->type?->ar_name,
                    'tag' => $course->tag?->name,
                    'tag_ar' => $course->tag?->ar_name,
                    'lessons' => $course->lessons_per_week,
                    'hours' => $course->study_time,
                    'min_age' => $course->min_age,
                    'level' => $course->required_level,
                    'price_gbp' => $prices['gbp'],
                    'price_sar' => $prices['sar'],
                    'has_accommodation' => $hasAccommodation,
                    'has_pickup' => $hasPickup,
                    'has_insurance' => $hasInsurance,
                    'weeks_param' => $weeks,
                    'start_date_param' => $startDate,
                ];
            })->values();

            return [
                'tags' => $tags,
                'courses' => $items,
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ];
        });

        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/language-institutes/{slug}
    // ----------------------------------------------------------------
    public function show(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_show_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug, $isArabic) {
            $converter = new CurrencyConverter();

            // Strategy to find the correct Branch:
            // 1. Check if the slug matches a LanguageSchoolBranch directly 
            // 2. Check if the slug matches "school-city" pattern
            // 3. Check if the slug matches a LanguageSchool directly (fallback to first branch)

            $branch = null;
            $school = null;

            // 1. Direct Branch Slug Match
            $branch = \App\Models\LanguageSchoolBranch::where('slug', $slug)
                ->with([
                    'school',
                    'city.country:id,name,ar_name,flag,currency_code',
                    'courses' => function ($q) {
                        $q->with(['type:id,name,ar_name', 'tag:id,name,ar_name', 'fees']);
                    },
                    'accommodations' => function ($q) {
                        $q->with(['tag:id,name,ar_name', 'bedroomType:id,name,ar_name', 'bathroomType:id,name,ar_name', 'mealPlan:id,name,ar_name']);
                    },
                    'pickups',
                    'insuranceFees',
                    'supplements',
                    'registrationFees',
                ])
                ->first();

            // 2. School-City Match
            if (!$branch) {
                // Find a school whose slug is a prefix of the requested slug
                // e.g. requested "lsi-education-london", school slug "lsi-education" -> match
                // We order by length descending to catch the longest matching school slug first (in case of "school-name-city" vs "school-name")
                $schoolCandidate = \App\Models\LanguageSchool::whereRaw("? LIKE CONCAT(slug, '-%')", [$slug])
                    ->orderByRaw('LENGTH(slug) DESC')
                    ->first();

                if ($schoolCandidate) {
                    // Extract city slug part
                    $citySlug = Str::after($slug, $schoolCandidate->slug . '-');

                    // Find branch for this school and city
                    $branch = \App\Models\LanguageSchoolBranch::where('language_school_id', $schoolCandidate->id)
                        ->whereHas('city', function ($q) use ($citySlug) {
                            $q->where('slug', $citySlug);
                        })
                        ->with([
                            'school',
                            'city.country:id,name,ar_name,flag,currency_code',
                            'courses' => function ($q) {
                                $q->with(['type:id,name,ar_name', 'tag:id,name,ar_name', 'fees']);
                            },
                            'accommodations' => function ($q) {
                                $q->with(['tag:id,name,ar_name', 'bedroomType:id,name,ar_name', 'bathroomType:id,name,ar_name', 'mealPlan:id,name,ar_name']);
                            },
                            'pickups',
                            'insuranceFees',
                            'supplements',
                            'registrationFees',
                        ])
                        ->first();
                }
            }

            // 3. Fallback: School Slug Direct Match (Old behavior)
            if (!$branch) {
                $school = \App\Models\LanguageSchool::where('slug', $slug)
                    ->with([
                        'branches' => function ($q) {
                            $q->with(['city', 'school']); // eager load basic info to filter
                        }
                    ])
                    ->first();

                if ($school && $school->branches->isNotEmpty()) {
                    // Determine which branch to show. Default to first.
                    // Re-query to get full relations for the selected branch
                    $firstBranchId = $school->branches->first()->id;
                    $branch = \App\Models\LanguageSchoolBranch::where('id', $firstBranchId)
                        ->with([
                            'school',
                            'city.country:id,name,ar_name,flag,currency_code',
                            'courses' => function ($q) {
                                $q->with(['type:id,name,ar_name', 'tag:id,name,ar_name', 'fees']);
                            },
                            'accommodations' => function ($q) {
                                $q->with(['tag:id,name,ar_name', 'bedroomType:id,name,ar_name', 'bathroomType:id,name,ar_name', 'mealPlan:id,name,ar_name']);
                            },
                            'pickups',
                            'insuranceFees',
                            'supplements',
                            'registrationFees',
                        ])
                        ->first();
                }
            }

            if (!$branch) {
                return null;
            }

            $school = $branch->school;
            $city = $branch->city;
            $country = $city?->country;
            $baseCurrency = $country?->currency_code ?: 'GBP';

            // ----------------------------------------------------------------
            // School Info
            // ----------------------------------------------------------------
            $schoolInfo = [
                'id' => $school->id,
                'name' => $school->name,
                'ar_name' => $school->ar_name,
                'slug' => $school->slug,
                'description' => $school->description,
                'ar_description' => $school->ar_description,
                'logo' => $this->toPublicUrl($school->logo),
                'rating' => (float) ($school->rating ?? 0),
                'is_preferred' => (bool) $school->is_preferred,
                'location' => collect([$city?->name, $country?->name])->filter()->implode(', '),
                'city' => $city?->name,
                'city_ar' => $city?->ar_name,
                'country' => $country?->name,
                'country_ar' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
                'image' => $this->getBranchCoverImage($branch) ?: $this->toPublicUrl($school->logo),
                'gallery' => collect($this->normalizeGalleryValue($branch->gallery_urls ?? []))
                    ->map(fn($entry) => $this->galleryEntryToUrl($entry))
                    ->filter()
                    ->values()
                    ->toArray(),
                'video_url' => $branch->video_url,
                // Accreditations
                'accreditations' => \App\Models\Accreditation::whereIn('id', $school->accreditation_ids ?? [])
                    ->get()
                    ->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->name,
                        'ar_name' => $a->ar_name,
                        'logo' => $this->toPublicUrl($a->picture),
                    ]),
            ];

            // ----------------------------------------------------------------
            // Courses
            // ----------------------------------------------------------------
            $courses = $branch->courses->map(function ($course) use ($converter, $baseCurrency) {
                // Price from week-1
                $weekOneFee = $course->fees->where('week_number', 1)->first();
                $price = $weekOneFee ? (float) $weekOneFee->fee : 0;
                $prices = $converter->toGbpSar($price, $baseCurrency);

                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'ar_name' => $course->ar_name,
                    'description' => $course->description,
                    'ar_description' => $course->ar_description,
                    'hours' => $course->study_time,
                    'lessons' => $course->lessons_per_week,
                    'min_age' => $course->min_age,
                    'level' => $course->required_level,
                    'tag' => $course->tag?->name,
                    'tag_ar' => $course->tag?->ar_name,
                    'type' => $course->type?->name,
                    'type_ar' => $course->type?->ar_name,
                    'start_day' => $course->start_day,
                    'price_gbp' => $prices['gbp'],
                    'price_sar' => $prices['sar'],
                    'fees' => $course->fees->map(fn($f) => [
                        'week_number' => $f->week_number,
                        'fee' => (float) $f->fee,
                    ])->values(),
                ];
            })->values();

            // ----------------------------------------------------------------
            // Accommodations
            // ----------------------------------------------------------------
            $accommodations = $branch->accommodations->map(function ($acc) use ($converter, $baseCurrency) {
                $priceVal = (float) ($acc->fee_per_week ?: 0);
                $prices = $converter->toGbpSar($priceVal, $baseCurrency);

                // Construct features list for frontend bullets
                $features = [];
                if ($acc->bedroomType)
                    $features[] = $acc->bedroomType->name;
                if ($acc->bathroomType)
                    $features[] = $acc->bathroomType->name;
                if ($acc->mealPlan)
                    $features[] = $acc->mealPlan->name;
                if ($acc->required_age)
                    $features[] = "Age {$acc->required_age}+";

                return [
                    'id' => $acc->id,
                    'title' => $acc->title,
                    'ar_title' => $acc->ar_title,
                    'description' => null,
                    'ar_description' => null,
                    'features' => $features, // Now a proper array of strings
                    'fee_per_week_gbp' => $prices['gbp'],
                    'fee_per_week_sar' => $prices['sar'],
                    'required_age' => $acc->required_age,
                    'tag' => $acc->tag?->name,
                    'tag_ar' => $acc->tag?->ar_name,
                    'bedroom_type' => $acc->bedroomType?->name,
                    'bedroom_type_ar' => $acc->bedroomType?->ar_name,
                    'bathroom_type' => $acc->bathroomType?->name,
                    'bathroom_type_ar' => $acc->bathroomType?->ar_name,
                    'meal_plan' => $acc->mealPlan?->name,
                    'meal_plan_ar' => $acc->mealPlan?->ar_name,
                    'image' => null,
                ];
            })->values();

            // ----------------------------------------------------------------
            // Pickups (Extra Option 1)
            // ----------------------------------------------------------------
            $pickups = $branch->pickups->map(function ($p) use ($converter, $baseCurrency) {
                $prices = $converter->toGbpSar((float) $p->price, $baseCurrency);

                return [
                    'id' => $p->id,
                    'route' => $p->route, // "Heathrow Airport -> School"
                    'notes' => $p->notes,
                    'price_gbp' => $prices['gbp'],
                    'price_sar' => $prices['sar'],
                ];
            })->values();

            // ----------------------------------------------------------------
            // Insurances (Extra Option 2)
            // ----------------------------------------------------------------
            $insurances = $branch->insuranceFees->map(function ($ins) use ($converter, $baseCurrency) {
                $prices = $converter->toGbpSar((float) $ins->amount, $baseCurrency);

                return [
                    'id' => $ins->id,
                    'name' => $ins->name ?? 'Insurance',
                    'ar_name' => $ins->ar_name ?? 'تأمين',
                    'description' => "{$ins->billing_count} {$ins->billing_unit}",
                    'amount_gbp' => $prices['gbp'],
                    'amount_sar' => $prices['sar'],
                    'billing_unit' => $ins->billing_unit,
                ];
            })->values();

            // ----------------------------------------------------------------
            // Supplements (Extra Option 3)
            // ----------------------------------------------------------------
            $supplements = $branch->supplements->map(function ($s) use ($converter, $baseCurrency) {
                $prices = $converter->toGbpSar((float) $s->amount, $baseCurrency);

                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'ar_name' => $s->ar_name,
                    'amount_gbp' => $prices['gbp'],
                    'amount_sar' => $prices['sar'],
                    'start_date' => optional($s->start_date)->format('Y-m-d'),
                    'end_date' => optional($s->end_date)->format('Y-m-d'),
                ];
            })->values();

            // ----------------------------------------------------------------
            // Registration Fees
            // ----------------------------------------------------------------
            $regFees = $branch->registrationFees;
            $registrationFee = null;
            if ($regFees->isNotEmpty()) {
                $fee = $regFees->first();
                $regPrices = $converter->toGbpSar((float) $fee->amount, $baseCurrency);
                $registrationFee = [
                    'amount_gbp' => $regPrices['gbp'],
                    'amount_sar' => $regPrices['sar'],
                ];
            }

            // ----------------------------------------------------------------
            // Discounts
            // ----------------------------------------------------------------
            $now = now();
            $branchId = $branch->id;

            $schoolDiscounts = LanguageSchoolDiscount::active()
                ->where(function ($q) use ($now) {
                    $q->where(function ($sub) use ($now) {
                        $sub->whereNull('start_date')->whereNull('end_date');
                    })->orWhere(function ($sub) use ($now) {
                        $sub->where('start_date', '<=', $now)->where('end_date', '>=', $now);
                    });
                })
                ->where(function ($q) use ($branchId) {
                    $q->where('applies_to_all_branches', true)
                        ->orWhereJsonContains('school_branch_ids', $branchId);
                })
                ->get()
                ->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'ar_name' => $d->ar_name,
                    'discount_percentage' => (float) $d->discount_percentage,
                ]);

            $pioneersDiscounts = LanguageSchoolPioneersDiscount::where('is_active', true)
                ->get()
                ->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'ar_name' => $d->ar_name,
                    'weeks' => $d->weeks,
                    'discount_amount' => (float) $d->discount_amount,
                    'discount_full_for' => $d->discount_full_for,
                ]);

            return [
                'school' => $schoolInfo,
                'courses' => $courses,
                'accommodations' => $accommodations,
                'pickups' => $pickups,
                'insurances' => $insurances,
                'supplements' => $supplements,
                'registration_fee' => $registrationFee,
                'discounts' => $schoolDiscounts->values(),
                'pioneers_discounts' => $pioneersDiscounts->values(),
            ];
        });

        if (!$response) {
            return response()->json(['message' => 'Institute branch not found'], 404);
        }

        return response()->json($response);
    }
    // ----------------------------------------------------------------
    // GET /api/courseenglish/language-institutes/cms
    // ----------------------------------------------------------------
    public function cms(): JsonResponse
    {
        $response = Cache::remember('ce_listing_cms_language_institutes', now()->addWeek(), function () {
            return $this->cmsPayloadForSlug('language-institutes');
        });
        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/online-courses/cms
    // ----------------------------------------------------------------
    public function onlineCms(): JsonResponse
    {
        $response = Cache::remember('ce_listing_cms_online_courses', now()->addWeek(), function () {
            return $this->cmsPayloadForSlug('online-courses');
        });
        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/summer-programs/cms
    // ----------------------------------------------------------------
    public function summerCms(): JsonResponse
    {
        $response = Cache::remember('ce_listing_cms_summer_programs', now()->addWeek(), function () {
            return $this->cmsPayloadForSlug('summer-programs');
        });
        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/training-and-professional-courses/cms
    // ----------------------------------------------------------------
    public function trainingCms(): JsonResponse
    {
        $response = Cache::remember('ce_listing_cms_training_courses', now()->addWeek(), function () {
            return $this->cmsPayloadForSlug('training-and-professional-courses');
        });
        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/online-courses/{slug}
    // ----------------------------------------------------------------
    public function onlineCourseDetails(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_onlineCourseDetails_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $converter = new CurrencyConverter();

            $school = \App\Models\LanguageSchool::where('slug', $slug)
                ->with([
                    'branches' => function ($q) {
                        $q->with(['city.country:id,name,ar_name,flag,currency_code']);
                    },
                ])
                ->first();

            if (!$school) {
                return null;
            }

            $branch = $school->branches->first();
            $city = $branch?->city;
            $country = $city?->country;
            $baseCurrency = $country?->currency_code ?: 'GBP';

            $coverImage = null;
            foreach ($school->branches as $candidate) {
                $coverImage = $this->getBranchCoverImage($candidate);
                if ($coverImage) {
                    break;
                }
            }

            $schoolInfo = [
                'id' => $school->id,
                'name' => $school->name,
                'ar_name' => $school->ar_name,
                'slug' => $school->slug,
                'description' => $school->description,
                'ar_description' => $school->ar_description,
                'logo' => $this->toPublicUrl($school->logo),
                'rating' => (float) ($school->rating ?? 0),
                'location' => collect([$city?->name, $country?->name])->filter()->implode(', '),
                'city' => $city?->name,
                'city_ar' => $city?->ar_name,
                'country' => $country?->name,
                'country_ar' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
                'image' => $coverImage ?: $this->toPublicUrl($school->logo),
                'gallery' => collect($this->normalizeGalleryValue($branch?->gallery_urls ?? []))
                    ->map(fn($entry) => $this->galleryEntryToUrl($entry))
                    ->filter()
                    ->values()
                    ->toArray(),
            ];

            $courses = LanguageCourseOnlineCourse::query()
                ->where('language_school_id', $school->id)
                ->where('visible', true)
                ->where('status', 'published')
                ->with(['courseType:id,name,ar_name', 'tag:id,name,ar_name'])
                ->orderBy('id')
                ->get()
                ->map(function (LanguageCourseOnlineCourse $course) use ($converter, $baseCurrency) {
                    $prices = $converter->toGbpSar($course->fee_amount, $course->currency_code ?: $baseCurrency);

                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'ar_name' => $course->ar_name,
                        'description' => $course->description,
                        'ar_description' => $course->ar_description,
                        'required_level' => $course->required_level,
                        'study_time' => $course->study_time,
                        'lessons_per_week' => $course->lessons_per_week,
                        'min_age' => $course->min_age,
                        'start_date' => $course->start_date,
                        'fee_type' => $course->fee_type,
                        'price_gbp' => $prices['gbp'],
                        'price_sar' => $prices['sar'],
                        'price' => $course->fee_amount,
                        'currency_code' => $course->currency_code,
                        'tag' => $course->tag?->name,
                        'tag_ar_name' => $course->tag?->ar_name,
                        'course_type' => $course->courseType?->name,
                        'course_type_ar_name' => $course->courseType?->ar_name,
                    ];
                })
                ->values();

            return [
                'school' => $schoolInfo,
                'courses' => $courses,
            ];
        });

        if (!$response) {
            return response()->json(['message' => 'School not found'], 404);
        }

        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/online-courses
    // ----------------------------------------------------------------
    public function onlineCourses(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_onlineCourses_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request) {
            $perPage = (int) $request->input('per_page', 12);
            $converter = new CurrencyConverter();

            $paginator = LanguageCourseOnlineCourse::query()
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
                ->paginate($perPage);

            $items = $paginator->getCollection()->map(function (LanguageCourseOnlineCourse $course) use ($converter) {
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
                    'slug' => $course->school?->slug,
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
                    'lessons_per_week' => $course->lessons_per_week,
                    'study_time' => $course->study_time,
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
                    'rating' => null,
                ];
            })->values();

            return [
                'online_courses' => $items,
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ];
        });

        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/summer-programs
    // ----------------------------------------------------------------
    public function summerPrograms(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_summerPrograms_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request) {
            $perPage = (int) $request->input('per_page', 12);
            $converter = new CurrencyConverter();

            $paginator = LanguageCourseSummerCamp::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'branch.city.country:id,name,ar_name,flag,currency_code',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->paginate($perPage);

            $items = $paginator->getCollection()->map(function (LanguageCourseSummerCamp $camp) use ($converter) {
                $city = $camp->branch?->city;
                $country = $city?->country;
                $school = $camp->branch?->school;
                $baseCurrency = $country?->currency_code ?: 'GBP';
                $prices = $converter->toGbpSar($camp->fee_amount, $baseCurrency);

                // Slug Logic: Prefer branch slug as per user request
                $slug = $camp->branch?->slug;
                if (!$slug && $school?->slug) {
                    // Fallback to school slug if branch has no slug
                    $slug = $school->slug;
                }

                return [
                    'id' => $camp->id,
                    'name' => $camp->name,
                    'ar_name' => $camp->ar_name,
                    'title' => $camp->name,
                    'ar_title' => $camp->ar_name,
                    'slug' => $slug,
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
            })->values();

            return [
                'summer_camps' => $items,
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ];
        });

        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/summer-programs/{slug}
    // ----------------------------------------------------------------
    public function summerProgramDetails(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_summerProgramDetails_' . $slug . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $converter = new CurrencyConverter();

            // 1. Find Branch by Slug (User requested Branch Slug)
            $branch = \App\Models\LanguageSchoolBranch::where('slug', $slug)
                ->with(['school', 'city.country'])
                ->first();

            $school = null;

            if ($branch) {
                $school = $branch->school;
            } else {
                // Fallback: Check if it's a School slug (Backward compatibility / fallthrough)
                $school = \App\Models\LanguageSchool::where('slug', $slug)->first();
            }

            if (!$school) {
                return null;
            }

            // Re-query school with all branches and camps to get the full list
            // We want ALL camps of this school, regardless of which branch slug was used to enter.
            $school = \App\Models\LanguageSchool::where('id', $school->id)
                ->with([
                    'branches.city.country:id,name,ar_name,flag,currency_code',
                    'branches.summerCamps' => function ($q) {
                        $q->where('visible', true)
                            ->where('status', 'published')
                            ->with(['courseType', 'tag', 'details']);
                    }
                ])
                ->first();

            // 2. Aggregate all camps from all branches of this school
            $allCamps = collect();

            // We need to determine the "Main" location/city to display for the school info.
            // If we entered via a specific branch, use that branch's city.
            // Otherwise use the first branch's city.
            $displayBranch = $branch ?? $school->branches->first();
            $schoolCity = $displayBranch?->city;
            $schoolCountry = $displayBranch?->city?->country;
            $schoolBaseCurrency = $schoolCountry?->currency_code ?: 'GBP';

            foreach ($school->branches as $b) {
                if ($b->summerCamps) {
                    $allCamps = $allCamps->merge($b->summerCamps);
                }
            }

            // Transform Camps
            $campsData = $allCamps->map(function ($camp) use ($converter, $schoolBaseCurrency) {
                $itemsCurrency = $camp->currency_code ?: $schoolBaseCurrency;
                $prices = $converter->toGbpSar($camp->fee_amount, $itemsCurrency);

                return [
                    'id' => $camp->id,
                    'name' => $camp->name,
                    'ar_name' => $camp->ar_name,
                    'description' => $camp->description,
                    'ar_description' => $camp->ar_description,
                    'start_date' => $camp->start_date,
                    'end_date' => $camp->end_date,
                    'age_range' => $camp->age_range,
                    'fee_type' => $camp->fee_type,
                    'price' => $camp->fee_amount,
                    'price_gbp' => $prices['gbp'],
                    'price_sar' => $prices['sar'],
                    'currency' => $camp->currency_code,
                    'course_type' => $camp->courseType?->name,
                    'course_type_ar' => $camp->courseType?->ar_name,
                    'tag' => $camp->tag?->name,
                    'tag_ar' => $camp->tag?->ar_name,
                    'image' => $this->toPublicUrl($camp->thumbnail),
                    // 'gallery' => ... (camp specific gallery? or branch gallery? Use Branch gallery logic if needed)
                    'video_url' => $camp->branch?->video_url, // access branch relation if loaded, but we merged camps. Use lazy load if needed or eager load above.

                    // Map details from LanguageCourseSummerCampDetail
                    'included_items' => $camp->details->first()?->academics ? explode("\n", $camp->details->first()->academics) : [], // Fallback/Tentative mapping
                    'accommodation_details' => $camp->details->first()?->accommodation,

                    'camp_details' => $camp->details->first() ? [
                        'overview' => $camp->details->first()->overview,
                        'ar_overview' => $camp->details->first()->ar_overview,
                        'academics' => $camp->details->first()->academics,
                        'ar_academics' => $camp->details->first()->ar_academics,
                        'activities' => $camp->details->first()->activities,
                        'ar_activities' => $camp->details->first()->ar_activities,
                        'accommodation' => $camp->details->first()->accommodation,
                        'ar_accommodation' => $camp->details->first()->ar_accommodation,
                        'safeguarding' => $camp->details->first()->safeguarding,
                        'ar_safeguarding' => $camp->details->first()->ar_safeguarding,
                        'images' => collect($camp->details->first()->images ?? [])->map(fn($img) => $this->toPublicUrl($img))->toArray(),
                    ] : null,

                    'city' => $camp->branch?->city?->name,
                    'country' => $camp->branch?->city?->country?->name,
                ];
            })->values();

            // School/Location Info
            $schoolInfo = [
                'id' => $school->id,
                'name' => $school->name,
                'ar_name' => $school->ar_name,
                'slug' => $school->slug,
                'logo' => $this->toPublicUrl($school->logo),
                'rating' => (float) ($school->rating ?? 0),
                'location' => collect([$schoolCity?->name, $schoolCountry?->name])->filter()->implode(', '),
                'city' => $schoolCity?->name,
                'city_ar' => $schoolCity?->ar_name,
                'country' => $schoolCountry?->name,
                'country_ar' => $schoolCountry?->ar_name,
                'flag' => $this->toPublicUrl($schoolCountry?->flag),
                'image' => $this->getBranchCoverImage($displayBranch),
            ];

            return [
                'school' => $schoolInfo,
                'camps' => $campsData,
            ];
        });

        if (!$response) {
            return response()->json(['message' => 'Institute/Program not found'], 404);
        }

        return response()->json($response);
    }

    // ----------------------------------------------------------------
    // GET /api/courseenglish/training-and-professional-courses/{slug}
    // ----------------------------------------------------------------
    public function trainingCourseDetails(Request $request, string $slug): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';

        $courseId = $request->integer('course_id');
        if (!$courseId) {
            if (is_numeric($slug)) {
                $courseId = (int) $slug;
            } elseif (preg_match('/(\d+)$/', $slug, $matches)) {
                $courseId = (int) $matches[1];
            }
        }

        $cacheKey = 'ce_listing_trainingCourseDetails_' . ($courseId ?: $slug) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug, $courseId) {
            $converter = new CurrencyConverter();

            $query = LanguageCourseTrainingCourse::query()
                ->with([
                    'branch.school',
                    'branch.city.country:id,name,ar_name,flag,currency_code',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ]);

            if ($courseId > 0) {
                $query->whereKey($courseId);
            } else {
                return null;
            }

            $course = $query->first();

            if (!$course) {
                return null;
            }

            $branch = $course->branch;
            $school = $branch?->school;
            $city = $branch?->city;
            $country = $city?->country;
            $baseCurrency = $country?->currency_code ?: 'GBP';
            $prices = $converter->toGbpSar($course->fee_amount, $baseCurrency);

            $schoolInfo = [
                'id' => $school?->id,
                'name' => $school?->name,
                'ar_name' => $school?->ar_name,
                'slug' => $school?->slug,
                'logo' => $this->toPublicUrl($school?->logo),
                'rating' => (float) ($school?->rating ?? 0),
                'location' => collect([$city?->name, $country?->name])->filter()->implode(', '),
                'city' => $city?->name,
                'city_ar' => $city?->ar_name,
                'country' => $country?->name,
                'country_ar' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
            ];

            $courseDetails = [
                'id' => $course->id,
                'name' => $course->name,
                'ar_name' => $course->ar_name,
                'description' => $course->description,
                'ar_description' => $course->ar_description,
                'start_date' => $course->start_date,
                'duration' => $course->duration . ' ' . $course->duration_unit,
                'price' => $course->fee_amount,
                'price_gbp' => $prices['gbp'],
                'price_sar' => $prices['sar'],
                'currency' => $baseCurrency,
                'course_type' => $course->courseType?->name,
                'course_type_ar' => $course->courseType?->ar_name,
                'tag' => $course->tag?->name,
                'tag_ar' => $course->tag?->ar_name,
                'image' => $this->toPublicUrl($course->thumbnail),
                'gallery' => collect($branch?->gallery_urls ?? [])->map(fn($url) => $this->toPublicUrl($url))->toArray(),
                'slug' => 'training-course-' . $course->id,
            ];

            return [
                'school' => $schoolInfo,
                'course' => $courseDetails,
            ];
        });

        if (!$response) {
            return response()->json(['message' => 'Training Course not found'], 404);
        }

        return response()->json($response);
    }
    // ----------------------------------------------------------------
    public function trainingCourses(Request $request): JsonResponse
    {
        $isArabic = $this->isArabic($request);
        $lang = $isArabic ? 'ar' : 'en';
        $cacheKey = 'ce_listing_trainingCourses_' . md5(json_encode($request->all())) . '_' . $lang;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($request) {
            $perPage = (int) $request->input('per_page', 12);
            $converter = new CurrencyConverter();

            $paginator = LanguageCourseTrainingCourse::query()
                ->where('visible', true)
                ->where('status', 'published')
                ->with([
                    'school:id,name,ar_name',
                    'branch.city.country:id,name,ar_name,flag',
                    'courseType:id,name,ar_name',
                    'tag:id,name,ar_name',
                ])
                ->orderBy('id')
                ->paginate($perPage);

            $items = $paginator->getCollection()->map(function (LanguageCourseTrainingCourse $course) use ($converter) {
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
            })->values();

            return [
                'training_courses' => $items,
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ];
        });

        return response()->json($response);
    }
}
