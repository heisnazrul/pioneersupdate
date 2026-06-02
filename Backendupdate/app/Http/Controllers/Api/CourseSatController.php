<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Review;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolPickup;
use App\Models\LanguageSchoolPioneersDiscount;
use App\Models\Tag;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseSatController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support
    ) {
    }

    public function home(): JsonResponse
    {
        return response()->json([
            'hero' => [
                'search_data' => $this->buildHeroSearchData(),
            ],
        ]);
    }

    public function languageInstitutes(Request $request): JsonResponse
    {
        $query = LanguageSchoolCourse::query()
            ->tap(fn (Builder $builder) => $this->applyActiveCourseConstraints($builder))
            ->with($this->languageInstituteCourseRelations());

        $this->applyInstituteSearchFilters($query, $request);

        if ($request->filled('course_type')) {
            $courseType = (string) $request->input('course_type');
            $query->whereHas('category', function (Builder $builder) use ($courseType) {
                $builder->where('name_en', $courseType)
                    ->orWhere('name_ar', $courseType)
                    ->orWhere('slug', Str::slug($courseType));
            });
        }

        $courses = $query
            ->latest('id')
            ->limit((int) $request->input('per_page', 200))
            ->get()
            ->map(fn (LanguageSchoolCourse $course) => $this->support->courseSatLanguageInstituteCard($course))
            ->values();

        $tags = Tag::query()
            ->whereHas('languageSchoolCourses', fn (Builder $builder) => $this->applyActiveCourseConstraints($builder))
            ->orderBy('id')
            ->get()
            ->map(fn (Tag $tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'ar_name' => $tag->ar_name,
                'slug' => $tag->slug,
            ])
            ->values();

        return response()->json([
            'courses' => $courses,
            'tags' => $tags,
            'currencies' => $this->support->courseSatCurrenciesPayload(),
            'search_data' => $this->buildHeroSearchData(),
            'total' => $courses->count(),
        ]);
    }

    public function languageInstituteDetail(Request $request, string $slug): JsonResponse
    {
        $school = LanguageSchool::query()
            ->active()
            ->where('slug', $slug)
            ->first();

        $branch = null;

        if (! $school) {
            $branch = LanguageSchoolBranch::query()
                ->where('slug', $slug)
                ->where('is_active', 'yes')
                ->with(['school', 'city.country'])
                ->first();

            $school = $branch?->school;
        }

        if (! $school) {
            return response()->json([
                'message' => 'Institute not found.',
            ], 404);
        }

        $courseId = $request->query('course_id');

        if ($courseId) {
            $anchorCourse = LanguageSchoolCourse::query()
                ->active()
                ->with('branch.city.country')
                ->where('id', $courseId)
                ->whereHas('branch', fn (Builder $query) => $query
                    ->where('school_id', $school->id)
                    ->where('is_active', 'yes'))
                ->first();

            $branch = $anchorCourse?->branch;
        }

        if (! $branch) {
            $branch = LanguageSchoolBranch::query()
                ->where('school_id', $school->id)
                ->where('is_active', 'yes')
                ->whereHas('courses', fn (Builder $query) => $query->active())
                ->with(['school', 'city.country'])
                ->orderBy('id')
                ->first();
        }

        if (! $branch) {
            return response()->json([
                'message' => 'Institute not found.',
            ], 404);
        }

        $branch->loadMissing(['school', 'city.country']);

        $rawCourses = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $branch->id)
            ->with($this->languageInstituteCourseRelations())
            ->get();

        $courses = $rawCourses
            ->map(fn (LanguageSchoolCourse $course) => $this->support->courseSatLanguageInstituteCard($course))
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

        $selectedCourseModel = $courseId
            ? $rawCourses->firstWhere('id', (int) $courseId)
            : $rawCourses->first();
        $registrationAmount = (float) ($selectedCourseModel?->registration_admin_fee ?? 0);
        $registrationBaseCurrency = strtoupper((string) ($branch->city?->country?->currency_code ?: 'GBP'));
        $registration = $this->support->buildPriceMap($registrationAmount, $registrationBaseCurrency);

        $discounts = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $branch->id)
            ->with(['promotions' => fn ($query) => $query->active()])
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
            'branch_slug' => $branch->slug,
            'courses' => $courses,
            'accommodations' => $accommodations,
            'pickups' => $pickups,
            'insurances' => $insurance ? collect([$this->support->insuranceCard($insurance)]) : collect(),
            'supplements' => [],
            'registration_fee' => [
                'amount' => $registrationAmount,
                'amount_gbp' => $registration['GBP'] ?? null,
                'amount_sar' => $registration['SAR'] ?? null,
                'prices' => $registration,
            ],
            'discounts' => $discounts,
            'pioneers_discounts' => $pioneersDiscounts,
            'currencies' => $this->support->courseSatCurrenciesPayload(),
        ]);
    }

    public function homeOffers(): JsonResponse
    {
        $tags = Tag::query()
            ->whereHas('languageSchoolCourses', fn (Builder $query) => $this->applyActiveCourseConstraints($query))
            ->orderBy('id')
            ->get()
            ->map(fn (Tag $tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'ar_name' => $tag->ar_name,
                'slug' => $tag->slug,
            ])
            ->values();

        $courses = collect();

        foreach ($tags as $tagPayload) {
            $tag = Tag::query()->find($tagPayload['id']);
            if (!$tag) {
                continue;
            }

            LanguageSchoolCourse::query()
                ->active()
                ->whereHas('tags', fn (Builder $query) => $query->where('tags.id', $tag->id))
                ->tap(fn (Builder $query) => $this->applyActiveCourseConstraints($query))
                ->with($this->offerCourseRelations())
                ->orderByDesc('id')
                ->limit(6)
                ->get()
                ->each(function (LanguageSchoolCourse $course) use ($courses, $tag) {
                    $courses->push($this->support->courseSatOfferCard($course, $tag));
                });
        }

        return response()->json([
            'language_course_tags' => $tags,
            'language_courses' => $courses->values()->all(),
            'currencies' => $this->support->courseSatCurrenciesPayload(),
        ]);
    }

    public function homeCurrencies(): JsonResponse
    {
        return response()->json([
            'currencies' => $this->support->courseSatCurrenciesPayload(),
        ]);
    }

    public function homeOnline(): JsonResponse
    {
        $courses = LanguageOnlineCourse::query()
            ->where('visible', true)
            ->where('status', 'published')
            ->whereHas('school', fn (Builder $query) => $query->active())
            ->with([
                'school.branches.city.country',
                'courseType',
            ])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageOnlineCourse $course) => $this->support->courseSatOnlineCard($course))
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
            ->whereHas('branch', fn (Builder $query) => $query
                ->where('is_active', 'yes')
                ->whereHas('school', fn (Builder $schoolQuery) => $schoolQuery->active())
                ->whereHas('city', fn (Builder $cityQuery) => $cityQuery
                    ->active()
                    ->whereHas('country', fn (Builder $countryQuery) => $countryQuery->active())))
            ->with([
                'branch.school',
                'branch.city.country',
                'courseType',
                'detail',
            ])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageCourseSummerCamp $camp) => $this->support->courseSatSummerCampCard($camp))
            ->values();

        return response()->json([
            'summer_camps' => $camps,
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

    public function homeReviews(): JsonResponse
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

    private function applyActiveCourseConstraints(Builder $query): Builder
    {
        return $query
            ->active()
            ->whereHas('branch', fn (Builder $branchQuery) => $branchQuery
                ->where('is_active', 'yes')
                ->whereHas('school', fn (Builder $schoolQuery) => $schoolQuery->active())
                ->whereHas('city', fn (Builder $cityQuery) => $cityQuery
                    ->active()
                    ->whereHas('country', fn (Builder $countryQuery) => $countryQuery->active())))
            ->whereHas('category', fn (Builder $categoryQuery) => $categoryQuery->active());
    }

    private function offerCourseRelations(): array
    {
        return [
            'category:id,name_en,name_ar,slug',
            'tags:id,name,ar_name,slug',
            'promotions' => fn ($query) => $query->active(),
            'branch.school:id,name_en,name_ar,slug,logo_url',
            'branch.city:id,country_id,name,ar_name,slug',
            'branch.city.country:id,name,ar_name,slug,flag,currency_code',
        ];
    }

    private function languageInstituteCourseRelations(): array
    {
        return array_merge($this->offerCourseRelations(), [
            'branch.pickups',
            'branch.accommodations',
            'branch.insurance',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildHeroSearchData(): array
    {
        $courses = LanguageSchoolCourse::query()
            ->tap(fn (Builder $builder) => $this->applyActiveCourseConstraints($builder))
            ->with([
                'branch.school:id,name_en,name_ar,slug,logo_url',
                'branch.city:id,country_id,name,ar_name,slug',
                'branch.city.country:id,name,ar_name,slug,flag',
                'category:id,name_en,name_ar,slug',
            ])
            ->get();

        $branches = $courses
            ->pluck('branch')
            ->filter()
            ->unique('id')
            ->values();

        $schools = $branches
            ->groupBy('school_id')
            ->map(function ($schoolBranches) {
                $school = $schoolBranches->first()?->school;

                if (!$school) {
                    return null;
                }

                return [
                    'id' => $school->id,
                    'name' => $school->name_en,
                    'ar_name' => $school->name_ar,
                    'slug' => $school->slug,
                    'logo' => $this->support->toPublicUrl($school->logo_url),
                    'city_ids' => $schoolBranches->pluck('city_id')->unique()->values()->all(),
                    'country_ids' => $schoolBranches
                        ->pluck('city.country_id')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all(),
                ];
            })
            ->filter()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        $cities = $branches
            ->groupBy('city_id')
            ->map(function ($cityBranches) {
                $city = $cityBranches->first()?->city;
                $country = $city?->country;

                if (!$city || !$country) {
                    return null;
                }

                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'ar_name' => $city->ar_name,
                    'slug' => $city->slug,
                    'country_id' => $country->id,
                    'country_name' => $country->name,
                    'country_ar_name' => $country->ar_name,
                    'country_code' => $country->country_code,
                    'flag' => $this->support->toPublicUrl($country->resolveFlagPath()),
                ];
            })
            ->filter()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        $countryCourseCounts = $courses
            ->filter(fn ($course) => $course->branch?->city?->country_id)
            ->countBy(fn ($course) => $course->branch->city->country_id);

        $countryBranchCounts = $branches
            ->filter(fn ($branch) => $branch->city?->country_id)
            ->countBy(fn ($branch) => $branch->city->country_id);

        $countries = $branches
            ->map(fn ($branch) => $branch->city?->country)
            ->filter()
            ->unique('id')
            ->values()
            ->sort(function ($a, $b) use ($countryCourseCounts, $countryBranchCounts) {
                $courseDiff = ($countryCourseCounts[$b->id] ?? 0) <=> ($countryCourseCounts[$a->id] ?? 0);
                if ($courseDiff !== 0) {
                    return $courseDiff;
                }

                $branchDiff = ($countryBranchCounts[$b->id] ?? 0) <=> ($countryBranchCounts[$a->id] ?? 0);
                if ($branchDiff !== 0) {
                    return $branchDiff;
                }

                return strnatcasecmp($a->name, $b->name);
            })
            ->values()
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'ar_name' => $country->ar_name,
                'slug' => $country->slug,
                'country_code' => $country->country_code,
                'flag' => $this->support->toPublicUrl($country->resolveFlagPath()),
                'course_count' => $countryCourseCounts[$country->id] ?? 0,
                'branch_count' => $countryBranchCounts[$country->id] ?? 0,
            ])
            ->all();

        $courseTypes = $courses
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name_en', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name_en,
                'ar_name' => $category->name_ar,
                'slug' => $category->slug,
            ])
            ->all();

        $branchItems = $branches
            ->map(function ($branch) {
                $school = $branch->school;
                $city = $branch->city;
                $country = $city?->country;

                if (! $school || ! $city || ! $country) {
                    return null;
                }

                $labelEn = trim("{$school->name_en} - {$city->name}");
                $labelAr = trim(collect([$school->name_ar, $city->ar_name])->filter()->join(' - '));

                return [
                    'id' => $branch->id,
                    'name' => $labelEn,
                    'ar_name' => $labelAr !== '' ? $labelAr : $labelEn,
                    'slug' => $branch->slug,
                    'school_slug' => $school->slug,
                    'school_name' => $school->name_en,
                    'school_ar_name' => $school->name_ar,
                    'city_slug' => $city->slug,
                    'city_name' => $city->name,
                    'city_ar_name' => $city->ar_name,
                    'country_slug' => $country->slug,
                    'country_name' => $country->name,
                    'country_ar_name' => $country->ar_name,
                    'country_code' => $country->country_code,
                    'flag' => $this->support->toPublicUrl($country->resolveFlagPath()),
                    'logo' => $this->support->toPublicUrl($school->logo_url),
                    'search_text' => collect([
                        $school->name_en,
                        $school->name_ar,
                        $city->name,
                        $city->ar_name,
                        $country->name,
                        $country->ar_name,
                        $branch->slug,
                        $school->slug,
                        $city->slug,
                        $country->slug,
                    ])->filter()->join(' '),
                ];
            })
            ->filter()
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        return [
            'schools' => $schools,
            'countries' => $countries,
            'cities' => $cities,
            'branches' => $branchItems,
            'course_types' => $courseTypes,
        ];
    }

    private function applyInstituteSearchFilters(Builder $query, Request $request): void
    {
        $targets = $this->parseSearchTargets($request);

        if ($targets !== []) {
            $query->where(function (Builder $builder) use ($targets) {
                foreach ($targets as $target) {
                    $builder->orWhere(function (Builder $scoped) use ($target) {
                        match ($target['type']) {
                            'branch' => $scoped->whereHas(
                                'branch',
                                fn (Builder $branchQuery) => $branchQuery->where('slug', $target['slug'])
                            ),
                            'school' => $scoped->whereHas(
                                'branch.school',
                                fn (Builder $schoolQuery) => $schoolQuery->where('slug', $target['slug'])
                            ),
                            'city' => $scoped->whereHas(
                                'branch.city',
                                fn (Builder $cityQuery) => $cityQuery->where('slug', $target['slug'])
                            ),
                            'country' => $scoped->whereHas(
                                'branch.city.country',
                                fn (Builder $countryQuery) => $countryQuery->where('slug', $target['slug'])
                            ),
                            'school_country' => $scoped->whereHas(
                                'branch',
                                function (Builder $branchQuery) use ($target) {
                                    $branchQuery
                                        ->whereHas('school', fn (Builder $schoolQuery) => $schoolQuery->where('slug', $target['school_slug']))
                                        ->whereHas('city.country', fn (Builder $countryQuery) => $countryQuery->where('slug', $target['country_slug']));
                                }
                            ),
                            default => $scoped->whereRaw("0 = 1"),
                        };
                    });
                }
            });

            return;
        }

        if ($request->filled('branch_slug')) {
            $slugs = $this->parseSlugList((string) $request->input('branch_slug'));
            $query->whereHas('branch', fn (Builder $builder) => $builder->whereIn('slug', $slugs));
        }

        if ($request->filled('school_slug')) {
            $slugs = $this->parseSlugList((string) $request->input('school_slug'));
            $query->whereHas('branch.school', fn (Builder $builder) => $builder->whereIn('slug', $slugs));
        }

        if ($request->filled('city_slug')) {
            $slugs = $this->parseSlugList((string) $request->input('city_slug'));
            $query->whereHas('branch.city', fn (Builder $builder) => $builder->whereIn('slug', $slugs));
        }

        if ($request->filled('country_slug')) {
            $slugs = $this->parseSlugList((string) $request->input('country_slug'));
            $query->whereHas('branch.city.country', fn (Builder $builder) => $builder->whereIn('slug', $slugs));
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseSearchTargets(Request $request): array
    {
        if (! $request->filled('search_targets')) {
            return [];
        }

        $raw = (string) $request->input('search_targets');
        $targets = [];

        foreach (explode(',', $raw) as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }

            if (str_contains($segment, ':')) {
                [$type, $slug] = array_pad(explode(':', $segment, 2), 2, '');
                $type = trim($type);
                $slug = trim($slug);

                if ($type === 'school_country' && str_contains($slug, '|')) {
                    [$schoolSlug, $countrySlug] = array_pad(explode('|', $slug, 2), 2, '');
                    if ($schoolSlug !== '' && $countrySlug !== '') {
                        $targets[] = [
                            'type' => 'school_country',
                            'school_slug' => $schoolSlug,
                            'country_slug' => $countrySlug,
                        ];
                    }
                    continue;
                }

                if ($type !== '' && $slug !== '') {
                    $targets[] = ['type' => $type, 'slug' => $slug];
                }
            }
        }

        return $targets;
    }

    /**
     * @return array<int, string>
     */
    private function parseSlugList(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
