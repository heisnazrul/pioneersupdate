<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Certification;
use App\Models\Faq;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolPickup;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CourseEnglishApiSupport
{
    public function __construct(
        private readonly CurrencyConverter $currencyConverter
    ) {
    }

    public function isArabic(Request $request): bool
    {
        $lang = strtolower((string) ($request->query('lang') ?: $request->header('X-Lang') ?: $request->cookie('ce_language')));

        if ($lang === 'ar') {
            return true;
        }

        return Str::startsWith(strtolower((string) $request->header('Accept-Language', '')), 'ar');
    }

    public function toPublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/assets/', 'assets/'])) {
            return Str::startsWith($path, '/') ? $path : '/' . $path;
        }

        $base = rtrim(config('app.url'), '/');
        $clean = ltrim($path, '/');

        if (Str::startsWith($clean, 'storage/')) {
            return $base . '/' . $clean;
        }

        return $base . '/storage/' . $clean;
    }

    public function normalizeGallery(mixed $value): array
    {
        if (!$value) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = array_map('trim', explode(',', $value));
            }
        }

        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->flatten()
            ->filter()
            ->map(fn ($path) => $this->toPublicUrl((string) $path))
            ->filter()
            ->values()
            ->all();
    }

    public function toGbpSar(float|int|string|null $amount, ?string $baseCurrency = 'GBP'): array
    {
        return $this->currencyConverter->toGbpSar($amount, $baseCurrency);
    }

    /**
     * @return array<string, float>
     */
    public function buildPriceMap(float|int|string|null $amount, string $baseCurrency): array
    {
        return $this->currencyConverter->buildPriceMap($amount, $baseCurrency);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function courseSatCurrenciesPayload(): array
    {
        $codes = $this->currencyConverter->availableCurrencyCodes();
        usort($codes, function (string $a, string $b): int {
            $order = ['SAR' => 0, 'GBP' => 1];
            $rankA = $order[$a] ?? 99;
            $rankB = $order[$b] ?? 99;

            return $rankA <=> $rankB ?: strcmp($a, $b);
        });

        return array_map(fn (string $code) => $this->courseSatCurrencyMeta($code), $codes);
    }

    public function courseSatOfferCard(LanguageSchoolCourse $course, ?Tag $primaryTag = null): array
    {
        $branch = $course->branch;
        $school = $branch?->school;
        $city = $branch?->city;
        $country = $city?->country;
        $category = $course->category;
        $gallery = $this->normalizeGallery($branch?->branch_images ?? []);
        $baseCurrency = strtoupper((string) ($country?->currency_code ?: 'GBP'));

        $basePrice = $this->languageCourseBaseFee($course);
        $oldPrice = $this->languageCourseOldFee($course);
        $promo = $course->relationLoaded('promotions')
            ? $course->promotions->first()
            : null;

        if ($promo && $basePrice !== null) {
            $discounted = round($basePrice * (1 - (((float) $promo->promotion_percentage) / 100)), 2);
            $oldPrice = $basePrice;
            $basePrice = $discounted;
        }

        $pricesNew = $this->buildPriceMap($basePrice, $baseCurrency);
        $pricesOld = $this->buildPriceMap($oldPrice, $baseCurrency);

        $tag = $primaryTag ?? ($course->relationLoaded('tags') ? $course->tags->first() : null);

        $discountPercent = null;
        if ($oldPrice && $basePrice && $oldPrice > $basePrice) {
            $discountPercent = (int) round((1 - ($basePrice / $oldPrice)) * 100);
        } elseif ($promo) {
            $discountPercent = (int) round((float) $promo->promotion_percentage);
        }

        return [
            'id' => $course->id,
            'name' => $course->course_name_from_school,
            'ar_name' => $course->course_name_from_school_ar,
            'slug' => $branch?->slug ?: $course->slug,
            'branch_slug' => $branch?->slug,
            'course_slug' => $course->slug,
            'school_slug' => $school?->slug,
            'school_name' => $school?->name_en,
            'school_ar_name' => $school?->name_ar,
            'city' => $city?->name,
            'city_name' => $city?->name,
            'city_ar' => $city?->ar_name,
            'city_ar_name' => $city?->ar_name,
            'country' => $country?->name,
            'country_name' => $country?->name,
            'country_ar_name' => $country?->ar_name,
            'country_flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'logo' => $this->toPublicUrl($school?->logo_url),
            'school_logo' => $this->toPublicUrl($school?->logo_url),
            'image' => $gallery[0] ?? $this->toPublicUrl($school?->logo_url),
            'gallery_urls' => $gallery,
            'course_type' => $category?->name_en,
            'course_type_ar_name' => $category?->name_ar,
            'tag_id' => $tag?->id,
            'tag_ids' => $course->relationLoaded('tags') ? $course->tags->pluck('id')->values()->all() : [],
            'tag_slug' => $tag?->slug,
            'tag' => $tag?->name,
            'tag_ar_name' => $tag?->ar_name,
            'level' => $course->min_level,
            'hours' => $course->hours_per_week,
            'lessons' => $course->lessons_per_week,
            'min_age' => $course->min_age,
            'price' => $basePrice,
            'old_price' => $oldPrice,
            'base_currency' => $baseCurrency,
            'prices' => [
                'new' => $pricesNew,
                'old' => $pricesOld,
            ],
            'price_new' => $basePrice,
            'price_new_gbp' => $pricesNew['GBP'] ?? null,
            'price_new_sar' => $pricesNew['SAR'] ?? null,
            'price_old_gbp' => $pricesOld['GBP'] ?? null,
            'price_old_sar' => $pricesOld['SAR'] ?? null,
            'discount_percent' => $discountPercent,
            'currency' => $baseCurrency,
            'currency_code' => $baseCurrency,
            'rating' => 5,
            'promotion_percentage' => $promo?->promotion_percentage,
            'url' => $school?->slug ? "/language-institutes/{$school->slug}?course_id={$course->id}" : null,
        ];
    }

    public function courseSatLanguageInstituteCard(LanguageSchoolCourse $course): array
    {
        $primaryTag = $course->relationLoaded('tags') ? $course->tags->first() : null;
        $card = $this->courseSatOfferCard($course, $primaryTag);
        $branch = $course->branch;
        $category = $course->category;

        $tagNames = $course->relationLoaded('tags')
            ? $course->tags->pluck('name')->filter()->values()->all()
            : [];

        if ($category?->name_en && ! in_array($category->name_en, $tagNames, true)) {
            $tagNames[] = $category->name_en;
        }

        $pricesNew = $card['prices']['new'] ?? [];
        $pricesOld = $card['prices']['old'] ?? [];

        $baseCurrency = strtoupper((string) ($card['base_currency'] ?? 'GBP'));
        $weekTiers = $this->languageCourseWeekTiers($course, $baseCurrency);
        $materialFee = (float) ($course->material_books_fee ?? 0);
        $registrationFee = (float) ($course->registration_admin_fee ?? 0);
        $mandatoryFee = (float) ($course->mandatory_additional_fee ?? 0);

        return array_merge($card, [
            'course_name' => $card['name'],
            'course_ar_name' => $card['ar_name'],
            'school_name_en' => $card['school_name'],
            'school_name_ar' => $card['school_ar_name'],
            'country_en' => $card['country_name'],
            'country_ar' => $card['country_ar_name'],
            'tag_ar' => $card['tag_ar_name'],
            'course_type_ar' => $card['course_type_ar_name'],
            'tags' => $tagNames,
            'has_accommodation' => $branch && $branch->relationLoaded('accommodations')
                ? $branch->accommodations->isNotEmpty()
                : false,
            'has_pickup' => $branch && $branch->relationLoaded('pickups')
                ? $branch->pickups->isNotEmpty()
                : false,
            'has_insurance' => $branch && $branch->relationLoaded('insurance')
                ? (bool) $branch->insurance
                : false,
            'week_tiers' => $weekTiers,
            'material_books_fee' => $materialFee,
            'material_books_prices' => $this->buildPriceMap($materialFee, $baseCurrency),
            'registration_admin_fee' => $registrationFee,
            'registration_admin_prices' => $this->buildPriceMap($registrationFee, $baseCurrency),
            'mandatory_additional_fee' => $mandatoryFee,
            'mandatory_additional_fee_name' => $course->mandatory_additional_fee_name,
            'mandatory_additional_prices' => $this->buildPriceMap($mandatoryFee, $baseCurrency),
            'price_gbp' => $pricesNew['GBP'] ?? null,
            'price_sar' => $pricesNew['SAR'] ?? null,
            'price_per_week' => $card['price'],
            'price_per_week_gbp' => $pricesNew['GBP'] ?? null,
            'price_per_week_sar' => $pricesNew['SAR'] ?? null,
            'price_per_week_prices' => $pricesNew,
            'old_price_gbp' => $pricesOld['GBP'] ?? null,
            'old_price_sar' => $pricesOld['SAR'] ?? null,
        ]);
    }

    public function schoolSummary(?LanguageSchool $school, mixed $branch = null): array
    {
        $branch?->loadMissing('accreditations');
        $city = $branch?->city;
        $country = $city?->country;
        $gallery = $this->normalizeGallery($branch?->branch_images ?? []);

        return [
            'id' => $school?->id,
            'name' => $school?->name_en,
            'ar_name' => $school?->name_ar,
            'slug' => $school?->slug,
            'description' => $school?->about_en,
            'ar_description' => $school?->about_ar,
            'image' => $gallery[0] ?? $this->toPublicUrl($school?->logo_url),
            'logo' => $this->toPublicUrl($school?->logo_url),
            'gallery_urls' => $gallery,
            'city' => $city?->name,
            'city_ar' => $city?->ar_name,
            'city_name' => $city?->name,
            'city_ar_name' => $city?->ar_name,
            'country' => $country?->name,
            'country_ar' => $country?->ar_name,
            'country_name' => $country?->name,
            'country_ar_name' => $country?->ar_name,
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'location' => collect([$city?->name, $country?->name])->filter()->join(', '),
            'location_ar' => collect([$city?->ar_name, $country?->ar_name])->filter()->join('، '),
            'rating' => 5,
            'accreditations' => $branch?->accreditations?->map(fn ($accreditation) => [
                'id' => $accreditation->id,
                'name' => $accreditation->name,
                'ar_name' => $accreditation->ar_name,
                'logo' => $this->toPublicUrl($accreditation->logo),
            ])->values()->all() ?? [],
        ];
    }

    public function languageCourseCard(LanguageSchoolCourse $course): array
    {
        $branch = $course->branch;
        $school = $branch?->school;
        $city = $branch?->city;
        $country = $city?->country;
        $category = $course->category;
        $gallery = $this->normalizeGallery($branch?->branch_images ?? []);

        $basePrice = $this->languageCourseBaseFee($course);
        $oldPrice = $this->languageCourseOldFee($course);
        $promo = $course->relationLoaded('promotions')
            ? $course->promotions->first()
            : null;

        if ($promo && $basePrice !== null) {
            $discounted = round($basePrice * (1 - (((float) $promo->promotion_percentage) / 100)), 2);
            $oldPrice = $basePrice;
            $basePrice = $discounted;
        }

        $priceNew = $this->toGbpSar($basePrice, 'GBP');
        $priceOld = $this->toGbpSar($oldPrice, 'GBP');

        return [
            'id' => $course->id,
            'name' => $course->course_name_from_school,
            'ar_name' => $course->course_name_from_school_ar,
            'slug' => $branch?->slug ?: $course->slug,
            'branch_slug' => $branch?->slug,
            'course_slug' => $course->slug,
            'school_slug' => $school?->slug,
            'school_name' => $school?->name_en,
            'school_ar_name' => $school?->name_ar,
            'city' => $city?->name,
            'city_name' => $city?->name,
            'city_ar' => $city?->ar_name,
            'city_ar_name' => $city?->ar_name,
            'country' => $country?->name,
            'country_name' => $country?->name,
            'country_ar_name' => $country?->ar_name,
            'country_flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'logo' => $this->toPublicUrl($school?->logo_url),
            'school_logo' => $this->toPublicUrl($school?->logo_url),
            'image' => $gallery[0] ?? $this->toPublicUrl($school?->logo_url),
            'gallery_urls' => $gallery,
            'course_type' => $category?->name_en,
            'course_type_ar' => $category?->name_ar,
            'tag' => $category?->name_en,
            'tag_ar_name' => $category?->name_ar,
            'level' => $course->min_level,
            'required_level' => $course->min_level,
            'hours' => $course->hours_per_week,
            'hours_per_week' => $course->hours_per_week,
            'study_time' => $course->hours_per_week ? rtrim(rtrim((string) $course->hours_per_week, '0'), '.') . ' hours/week' : null,
            'lessons' => $course->lessons_per_week,
            'lessons_per_week' => $course->lessons_per_week,
            'min_age' => $course->min_age,
            'required_age' => $course->min_age,
            'price' => $basePrice,
            'price_gbp' => $priceNew['gbp'],
            'price_sar' => $priceNew['sar'],
            'price_new' => $basePrice,
            'price_new_gbp' => $priceNew['gbp'],
            'price_new_sar' => $priceNew['sar'],
            'old_price' => $oldPrice,
            'old_price_gbp' => $priceOld['gbp'],
            'old_price_sar' => $priceOld['sar'],
            'price_per_week' => $basePrice,
            'price_per_week_gbp' => $priceNew['gbp'],
            'price_per_week_sar' => $priceNew['sar'],
            'material_books_fee' => (float) $course->material_books_fee,
            'registration_admin_fee' => (float) $course->registration_admin_fee,
            'mandatory_additional_fee_name' => $course->mandatory_additional_fee_name,
            'mandatory_additional_fee' => (float) $course->mandatory_additional_fee,
            'currency' => 'GBP',
            'currency_code' => 'GBP',
            'rating' => 5,
            'has_accommodation' => $branch && $branch->relationLoaded('accommodations') ? $branch->accommodations->isNotEmpty() : false,
            'has_pickup' => $branch && $branch->relationLoaded('pickups') ? $branch->pickups->isNotEmpty() : false,
            'has_insurance' => $branch && $branch->relationLoaded('insurance') ? (bool) $branch->insurance : false,
            'promotion_percentage' => $promo?->promotion_percentage,
            'url' => $school?->slug ? "/language-institutes/{$school->slug}?course_id={$course->id}" : null,
        ];
    }

    public function onlineCourseCard(LanguageOnlineCourse $course): array
    {
        $school = $course->school;
        $branch = $school?->relationLoaded('branches') ? $school->branches->first() : null;
        $city = $branch?->city;
        $country = $city?->country;
        $prices = $this->toGbpSar($course->fee_amount, 'GBP');

        return [
            'id' => $course->id,
            'slug' => $course->slug,
            'school_slug' => $school?->slug,
            'name' => $course->name,
            'ar_name' => $course->ar_name,
            'title' => $course->name,
            'ar_title' => $course->ar_name,
            'provider' => $school?->name_en,
            'provider_ar_name' => $school?->name_ar,
            'school_name' => $school?->name_en,
            'school_ar_name' => $school?->name_ar,
            'description' => $course->description,
            'ar_description' => $course->ar_description,
            'study_time' => $course->study_time,
            'lessons_per_week' => $course->lessons_per_week,
            'required_level' => $course->required_level,
            'min_age' => $course->min_age,
            'start_date' => $course->start_date,
            'course_type' => $course->courseType?->name_en,
            'course_type_ar_name' => $course->courseType?->name_ar,
            'mode' => 'Online',
            'city' => $city?->name,
            'city_name' => $city?->name,
            'city_ar_name' => $city?->ar_name,
            'country' => $country?->name,
            'country_name' => $country?->name,
            'country_ar_name' => $country?->ar_name,
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'price' => (float) $course->fee_amount,
            'price_new' => (float) $course->fee_amount,
            'price_new_gbp' => $prices['gbp'],
            'price_new_sar' => $prices['sar'],
            'price_gbp' => $prices['gbp'],
            'price_sar' => $prices['sar'],
            'currency' => 'GBP',
            'currency_code' => 'GBP',
            'lessons' => $course->lessons_per_week,
            'hours' => $course->study_time,
            'level' => $course->required_level,
            'ar_level' => $course->required_level,
            'registration_fee' => (float) ($course->registration_fee ?? 0),
            'fee_type' => $course->fee_type,
            'price_unit' => $course->fee_type === 'weekly' ? 'per week' : 'per course',
            'image' => $this->toPublicUrl($course->thumbnail) ?: $this->toPublicUrl($school?->logo_url),
            'thumbnail' => $this->toPublicUrl($course->thumbnail),
            'rating' => 5,
            'url' => $school?->slug ? "/online-courses/{$school->slug}?course_id={$course->id}" : null,
        ];
    }

    public function courseSatOnlineCard(LanguageOnlineCourse $course): array
    {
        $school = $course->school;
        $branch = $school?->relationLoaded('branches') ? $school->branches->first() : null;
        $country = $branch?->city?->country;
        $baseCurrency = strtoupper((string) ($country?->currency_code ?: 'GBP'));
        $priceMap = $this->buildPriceMap($course->fee_amount, $baseCurrency);

        return array_merge($this->onlineCourseCard($course), [
            'base_currency' => $baseCurrency,
            'prices' => [
                'new' => $priceMap,
            ],
            'price_new' => (float) $course->fee_amount,
            'price_new_gbp' => $priceMap['GBP'] ?? null,
            'price_new_sar' => $priceMap['SAR'] ?? null,
            'currency' => $baseCurrency,
            'currency_code' => $baseCurrency,
        ]);
    }

    public function courseSatSummerCampCard(LanguageCourseSummerCamp $camp): array
    {
        $branch = $camp->branch;
        $country = $branch?->city?->country;
        $baseCurrency = strtoupper((string) ($country?->currency_code ?: 'GBP'));
        $priceMap = $this->buildPriceMap($camp->fee_amount, $baseCurrency);

        return array_merge($this->summerCampCard($camp), [
            'base_currency' => $baseCurrency,
            'prices' => [
                'from' => $priceMap,
            ],
            'price_from' => (float) $camp->fee_amount,
            'price_from_gbp' => $priceMap['GBP'] ?? null,
            'price_from_sar' => $priceMap['SAR'] ?? null,
            'currency' => $baseCurrency,
            'currency_code' => $baseCurrency,
        ]);
    }

    public function summerCampCard(LanguageCourseSummerCamp $camp): array
    {
        $branch = $camp->branch;
        $school = $branch?->school;
        $city = $branch?->city;
        $country = $city?->country;
        $detail = $camp->detail;
        $prices = $this->toGbpSar($camp->fee_amount, 'GBP');

        return [
            'id' => $camp->id,
            'slug' => $branch?->slug ?: $camp->slug,
            'camp_slug' => $camp->slug,
            'name' => $camp->name,
            'ar_name' => $camp->ar_name,
            'title' => $camp->name,
            'ar_title' => $camp->ar_name,
            'description' => $camp->description,
            'ar_description' => $camp->ar_description,
            'age_range' => $camp->age_range,
            'study_time' => $camp->study_time,
            'lessons_per_week' => $camp->lessons_per_week,
            'required_level' => $camp->required_level,
            'start_date' => $camp->start_date,
            'payment_deadline' => optional($camp->payment_deadline)->format('Y-m-d'),
            'course_type' => $camp->courseType?->name_en,
            'course_type_ar_name' => $camp->courseType?->name_ar,
            'school_name' => $school?->name_en,
            'school_ar_name' => $school?->name_ar,
            'city' => $city?->name,
            'city_ar_name' => $city?->ar_name,
            'country' => $country?->name,
            'country_ar_name' => $country?->ar_name,
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'price_from' => (float) $camp->fee_amount,
            'price_from_gbp' => $prices['gbp'],
            'price_from_sar' => $prices['sar'],
            'price_gbp' => $prices['gbp'],
            'price_sar' => $prices['sar'],
            'currency' => 'GBP',
            'currency_code' => 'GBP',
            'image' => $this->toPublicUrl($camp->thumbnail) ?: $this->toPublicUrl($school?->logo_url),
            'thumbnail' => $this->toPublicUrl($camp->thumbnail),
            'images' => $this->normalizeGallery($detail?->images),
            'overview' => $detail?->overview,
            'ar_overview' => $detail?->ar_overview,
            'academics' => $detail?->academics,
            'ar_academics' => $detail?->ar_academics,
            'activities' => $detail?->activities,
            'ar_activities' => $detail?->ar_activities,
            'accommodation' => $detail?->accommodation,
            'ar_accommodation' => $detail?->ar_accommodation,
            'safeguarding' => $detail?->safeguarding,
            'ar_safeguarding' => $detail?->ar_safeguarding,
            'url' => $branch?->slug ? "/summer-programs/{$branch->slug}?camp={$camp->id}" : null,
        ];
    }

    public function trainingCourseCard(LanguageCourseTrainingCourse $course): array
    {
        $school = $course->school;
        $branch = $course->branch;
        $city = $branch?->city;
        $country = $city?->country;
        $prices = $this->toGbpSar($course->fee_amount, 'GBP');

        return [
            'id' => $course->id,
            'slug' => (string) $course->id,
            'school_slug' => $school?->slug,
            'name' => $course->name,
            'ar_name' => $course->ar_name,
            'title' => $course->name,
            'ar_title' => $course->ar_name,
            'provider' => $school?->name_en,
            'provider_ar_name' => $school?->name_ar,
            'category' => $course->courseType?->name_en,
            'category_ar_name' => $course->courseType?->name_ar,
            'description' => $course->description,
            'ar_description' => $course->ar_description,
            'required_level' => $course->required_level,
            'study_time' => $course->study_time,
            'lessons_per_week' => $course->lessons_per_week,
            'min_age' => $course->min_age,
            'start_date' => $course->start_date,
            'duration' => $course->study_time ?: $course->fee_type,
            'location' => collect([$city?->name, $country?->name])->filter()->join(', '),
            'price' => (float) $course->fee_amount,
            'price_gbp' => $prices['gbp'],
            'price_sar' => $prices['sar'],
            'currency' => 'GBP',
            'currency_code' => 'GBP',
            'image' => $this->toPublicUrl($course->thumbnail) ?: $this->toPublicUrl($school?->logo_url),
            'thumbnail' => $this->toPublicUrl($course->thumbnail),
            'flag' => $this->toPublicUrl($country?->resolveFlagPath()),
            'url' => "/training-and-professional-courses/{$course->id}",
        ];
    }

    public function accommodationCard(LanguageSchoolAccommodation $accommodation): array
    {
        $accommodation->loadMissing(['branch.city.country']);
        $baseCurrency = strtoupper((string) ($accommodation->branch?->city?->country?->currency_code ?: 'GBP'));

        $weeklyPrices = $this->buildPriceMap($accommodation->weekly_fee, $baseCurrency);
        $adminPrices = $this->buildPriceMap($accommodation->admin_fee, $baseCurrency);

        return [
            'id' => $accommodation->id,
            'name' => $accommodation->name,
            'title' => $accommodation->name,
            'ar_name' => $accommodation->name_ar,
            'ar_title' => $accommodation->name_ar,
            'type' => $accommodation->type?->name_en,
            'type_ar' => $accommodation->type?->name_ar,
            'bedroom_type' => $accommodation->bedroomType?->name_en,
            'bedroom_type_ar' => $accommodation->bedroomType?->name_ar,
            'bathroom_type' => $accommodation->bathroomType?->name_en,
            'bathroom_type_ar' => $accommodation->bathroomType?->name_ar,
            'meal_plan' => $accommodation->mealPlan?->name_en,
            'meal_plan_ar' => $accommodation->mealPlan?->name_ar,
            'base_currency' => $baseCurrency,
            'fee_per_week' => $accommodation->weekly_fee,
            'fee_per_week_prices' => $weeklyPrices,
            'fee_per_week_gbp' => $weeklyPrices['GBP'] ?? null,
            'fee_per_week_sar' => $weeklyPrices['SAR'] ?? null,
            'admin_fee' => $accommodation->admin_fee,
            'admin_fee_prices' => $adminPrices,
            'admin_fee_gbp' => $adminPrices['GBP'] ?? null,
            'admin_fee_sar' => $adminPrices['SAR'] ?? null,
            'security_deposit' => $accommodation->security_deposit,
            'min_age' => $accommodation->min_age,
            'supplements' => [
                'under_18' => [
                    'fee' => (float) $accommodation->under_18_supplement_fee,
                    'prices' => $this->buildPriceMap($accommodation->under_18_supplement_fee, $baseCurrency),
                    'per_week' => true,
                ],
                'summer' => [
                    'fee' => (float) $accommodation->summer_supplement_fee,
                    'prices' => $this->buildPriceMap($accommodation->summer_supplement_fee, $baseCurrency),
                    'start_date' => optional($accommodation->summer_start_date)->format('Y-m-d'),
                    'end_date' => optional($accommodation->summer_end_date)->format('Y-m-d'),
                    'per_week' => true,
                ],
                'winter' => [
                    'fee' => (float) $accommodation->winter_supplement_fee,
                    'prices' => $this->buildPriceMap($accommodation->winter_supplement_fee, $baseCurrency),
                    'start_date' => optional($accommodation->winter_start_date)->format('Y-m-d'),
                    'end_date' => optional($accommodation->winter_end_date)->format('Y-m-d'),
                    'per_week' => true,
                ],
                'other' => [
                    'name' => $accommodation->other_supplement_name,
                    'fee' => (float) $accommodation->other_supplement_fee,
                    'prices' => $this->buildPriceMap($accommodation->other_supplement_fee, $baseCurrency),
                    'start_date' => optional($accommodation->other_start_date)->format('Y-m-d'),
                    'end_date' => optional($accommodation->other_end_date)->format('Y-m-d'),
                    'per_week' => true,
                ],
            ],
            'features' => array_values(array_filter([
                $accommodation->bedroomType?->name_en,
                $accommodation->bathroomType?->name_en,
                $accommodation->mealPlan?->name_en,
                $accommodation->min_age ? 'Age ' . $accommodation->min_age . '+' : null,
            ])),
            'features_ar' => array_values(array_filter([
                $accommodation->bedroomType?->name_ar,
                $accommodation->bathroomType?->name_ar,
                $accommodation->mealPlan?->name_ar,
                $accommodation->min_age ? 'العمر ' . $accommodation->min_age . '+' : null,
            ])),
        ];
    }

    public function pickupCard(LanguageSchoolPickup $pickup): array
    {
        $pickup->loadMissing(['branch.city.country']);
        $baseCurrency = strtoupper((string) ($pickup->branch?->city?->country?->currency_code ?: 'GBP'));
        $fees = $this->buildPriceMap($pickup->fee, $baseCurrency);

        return [
            'id' => $pickup->id,
            'name' => $pickup->pickup_location,
            'ar_name' => $pickup->pickup_location_ar,
            'route' => $pickup->pickup_location,
            'ar_route' => $pickup->pickup_location_ar,
            'base_currency' => $baseCurrency,
            'price' => $pickup->fee,
            'prices' => $fees,
            'price_gbp' => $fees['GBP'] ?? null,
            'price_sar' => $fees['SAR'] ?? null,
        ];
    }

    public function insuranceCard(LanguageSchoolInsurance $insurance): array
    {
        $insurance->loadMissing(['branch.city.country']);
        $baseCurrency = strtoupper((string) ($insurance->branch?->city?->country?->currency_code ?: 'GBP'));
        $weeklyPrices = $this->buildPriceMap($insurance->weekly_fee, $baseCurrency);
        $adminPrices = $this->buildPriceMap($insurance->admin_fee, $baseCurrency);

        return [
            'id' => $insurance->id,
            'name' => 'Insurance',
            'base_currency' => $baseCurrency,
            'amount' => $insurance->weekly_fee,
            'prices' => $weeklyPrices,
            'amount_gbp' => $weeklyPrices['GBP'] ?? null,
            'amount_sar' => $weeklyPrices['SAR'] ?? null,
            'price' => $insurance->weekly_fee,
            'price_gbp' => $weeklyPrices['GBP'] ?? null,
            'price_sar' => $weeklyPrices['SAR'] ?? null,
            'admin_fee' => $insurance->admin_fee,
            'admin_fee_prices' => $adminPrices,
            'admin_fee_gbp' => $adminPrices['GBP'] ?? null,
            'admin_fee_sar' => $adminPrices['SAR'] ?? null,
            'is_mandatory' => $insurance->is_mandatory === 'yes',
        ];
    }

    public function blogCard(Blog $blog): array
    {
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'ar_title' => $blog->ar_title,
            'slug' => $blog->slug,
            'summary' => $blog->summary,
            'ar_summary' => $blog->ar_summary,
            'content' => $blog->content,
            'ar_content' => $blog->ar_content,
            'image' => $this->toPublicUrl($blog->featured_image),
            'published_at' => optional($blog->published_at)->toIso8601String(),
            'date' => optional($blog->published_at)->format('d M Y'),
            'category' => $blog->category?->name,
            'category_ar_name' => $blog->category?->ar_name,
            'category_slug' => $blog->category?->slug,
            'author' => $blog->publisher?->name ?: 'Pioneers Edu',
        ];
    }

    public function faqCard(Faq $faq): array
    {
        return [
            'id' => $faq->id,
            'category' => $faq->category,
            'ar_category' => $faq->ar_category,
            'question' => $faq->question,
            'ar_question' => $faq->ar_question,
            'answer' => $faq->answer,
            'ar_answer' => $faq->ar_answer,
            'display_order' => $faq->display_order,
        ];
    }

    public function reviewCard(Review $review): array
    {
        $screenshots = collect($review->screenshots ?? [])
            ->filter()
            ->map(fn ($path) => $this->toPublicUrl($path))
            ->values()
            ->all();

        return [
            'id' => $review->id,
            'name' => $review->name,
            'ar_name' => $review->ar_name,
            'title' => $review->title,
            'ar_title' => $review->ar_title,
            'review' => $review->review_text,
            'ar_review' => $review->ar_review_text,
            'review_text' => $review->review_text,
            'ar_review_text' => $review->ar_review_text,
            'rating' => $review->rating,
            'photo' => $this->toPublicUrl($review->photo ?: $review->thumbnail),
            'thumbnail' => $this->toPublicUrl($review->thumbnail ?: $review->photo),
            'screenshot' => $screenshots[0] ?? $this->toPublicUrl($review->thumbnail),
            'screenshots' => $screenshots,
            'institute_name' => $review->institute_name,
            'ar_institute_name' => $review->ar_institute_name,
            'university_name' => $review->university_name,
            'course_name' => $review->course_name,
            'country_name' => $review->country_name,
        ];
    }

    public function certificationCard(Certification $certification): array
    {
        return [
            'id' => $certification->id,
            'title' => $certification->title_en,
            'ar_title' => $certification->title_ar,
            'subtitle' => $certification->subtitle_en,
            'ar_subtitle' => $certification->subtitle_ar,
            'image' => $this->toPublicUrl($certification->image),
            'link' => $certification->link,
        ];
    }

    public function courseTypeModel(string $courseType): ?string
    {
        return match ($courseType) {
            'language_courses' => LanguageSchoolCourse::class,
            'online_courses' => LanguageOnlineCourse::class,
            'summer_camps' => LanguageCourseSummerCamp::class,
            'training_courses' => LanguageCourseTrainingCourse::class,
            default => null,
        };
    }

    public function coursePayload(string $courseType, mixed $course): ?array
    {
        return match ($courseType) {
            'language_courses' => $course instanceof LanguageSchoolCourse ? $this->languageCourseCard($course) : null,
            'online_courses' => $course instanceof LanguageOnlineCourse ? $this->onlineCourseCard($course) : null,
            'summer_camps' => $course instanceof LanguageCourseSummerCamp ? $this->summerCampCard($course) : null,
            'training_courses' => $course instanceof LanguageCourseTrainingCourse ? $this->trainingCourseCard($course) : null,
            default => null,
        };
    }

    public function brandingPayload(): array
    {
        $branding = Setting::get('branding', []);
        $schools = LanguageSchool::query()
            ->active()
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (LanguageSchool $school) => [
                'id' => $school->id,
                'name' => $school->name_en,
                'ar_name' => $school->name_ar,
                'slug' => $school->slug,
                'logo' => $this->toPublicUrl($school->logo_url),
            ])
            ->values()
            ->all();

        $defaults = [
            'header' => [
                'logo' => [
                    'main' => $this->toPublicUrl(Arr::get($branding, 'header.logo.main')),
                ],
            ],
            'mobile' => [
                'logo' => $this->toPublicUrl(Arr::get($branding, 'mobile.logo')),
                'promo' => Arr::get($branding, 'mobile.promo', []),
                'drawer_social' => Arr::get($branding, 'mobile.drawer_social', []),
            ],
            'schools' => $schools,
        ];

        return array_replace_recursive($defaults, $this->normalizeValue($branding));
    }

    public function staticPage(string $slug): array
    {
        $pages = [
            'about' => [
                'content' => [
                    'breadcrumb' => ['home' => 'Home', 'current' => 'About Us'],
                    'page_title' => 'About Course English',
                    'intro' => [
                        'title' => 'We help students choose the right language institute with clarity.',
                        'paragraph_1' => 'CourseEnglish connects students with trusted language institutes in leading destinations.',
                        'paragraph_2' => 'We simplify comparing schools, programs, cities, and pricing before you book.',
                        'image' => '/img.png',
                    ],
                    'vision' => [
                        'title' => 'Our Vision',
                        'body' => 'To make international language education simpler, clearer, and more transparent for every student.',
                    ],
                    'mission' => [
                        'title' => 'Our Mission',
                        'body' => 'To present reliable language study options and help families compare them with confidence.',
                    ],
                    'why' => [
                        'title' => 'Why Us?',
                        'subtitle' => 'Because informed decisions start with trusted information.',
                        'items' => [
                            ['title' => 'Trusted Options', 'body' => 'We focus on reliable schools and practical student support.', 'icon' => '/like.gif'],
                            ['title' => 'Clear Comparison', 'body' => 'Compare programs, locations, and pricing in one place.', 'icon' => '/team.gif'],
                            ['title' => 'Better Value', 'body' => 'Find strong educational options with competitive pricing.', 'icon' => '/tag.gif'],
                        ],
                    ],
                ],
                'ar_content' => [
                    'breadcrumb' => ['home' => 'الرئيسية', 'current' => 'من نحن'],
                    'page_title' => 'عن كورس إنجلش',
                    'intro' => [
                        'title' => 'نساعد الطلاب على اختيار معهد اللغة المناسب بوضوح وثقة.',
                        'paragraph_1' => 'تربط كورس إنجلش الطلاب بمعاهد لغة موثوقة في أبرز الوجهات التعليمية.',
                        'paragraph_2' => 'نُسهّل مقارنة المعاهد والبرامج والمدن والتكاليف قبل اتخاذ قرار الحجز.',
                        'image' => '/img.png',
                    ],
                    'vision' => [
                        'title' => 'رؤيتنا',
                        'body' => 'أن نجعل تجربة اختيار دراسة اللغة في الخارج أبسط وأكثر وضوحاً وشفافية لكل طالب.',
                    ],
                    'mission' => [
                        'title' => 'رسالتنا',
                        'body' => 'عرض خيارات موثوقة لدراسة اللغة ومساعدة الأسر والطلاب على المقارنة بثقة.',
                    ],
                    'why' => [
                        'title' => 'لماذا نحن؟',
                        'subtitle' => 'لأن القرار الصحيح يبدأ بمعلومة موثوقة.',
                        'items' => [
                            ['title' => 'خيارات موثوقة', 'body' => 'نركّز على المعاهد الموثوقة والدعم العملي للطالب.', 'icon' => '/like.gif'],
                            ['title' => 'مقارنة واضحة', 'body' => 'قارن البرامج والمواقع والأسعار في مكان واحد.', 'icon' => '/team.gif'],
                            ['title' => 'قيمة أفضل', 'body' => 'اعثر على خيارات تعليمية قوية بأسعار تنافسية.', 'icon' => '/tag.gif'],
                        ],
                    ],
                ],
            ],
            'contact' => [
                'content' => [
                    'breadcrumb' => ['home' => 'Home', 'current' => 'Contact Us'],
                    'title' => 'Contact Us',
                    'cards' => [
                        ['icon' => 'phone', 'label' => 'Customer Service', 'value' => '+966 55 487 9888', 'href' => 'tel:+966554879888'],
                        ['icon' => 'whatsapp', 'label' => 'WhatsApp', 'value' => '+966 55 487 9888', 'href' => 'https://wa.me/966554879888'],
                        ['icon' => 'email', 'label' => 'Email Us', 'value' => 'courseenglish@gmail.com', 'href' => 'mailto:courseenglish@gmail.com'],
                    ],
                    'form' => [
                        'title' => 'Or send us a message and we will reply quickly.',
                        'full_name_label' => 'Full Name',
                        'full_name_placeholder' => 'Enter full name',
                        'email_label' => 'Email',
                        'email_placeholder' => 'Enter email',
                        'message_label' => 'Message',
                        'message_placeholder' => 'Write your message',
                        'submit_text' => 'Send',
                        'sending_text' => 'Sending...',
                        'success_text' => 'Message sent successfully.',
                        'fail_text' => 'Failed to send message. Please try again.',
                    ],
                ],
                'ar_content' => [
                    'breadcrumb' => ['home' => 'الرئيسية', 'current' => 'اتصل بنا'],
                    'title' => 'اتصل بنا',
                    'cards' => [
                        ['icon' => 'phone', 'label' => 'خدمة العملاء', 'value' => '+966 55 487 9888', 'href' => 'tel:+966554879888'],
                        ['icon' => 'whatsapp', 'label' => 'واتساب', 'value' => '+966 55 487 9888', 'href' => 'https://wa.me/966554879888'],
                        ['icon' => 'email', 'label' => 'البريد الإلكتروني', 'value' => 'courseenglish@gmail.com', 'href' => 'mailto:courseenglish@gmail.com'],
                    ],
                    'form' => [
                        'title' => 'أو يمكنك إرسال رسالة وسنعاود التواصل سريعاً.',
                        'full_name_label' => 'الاسم الكامل',
                        'full_name_placeholder' => 'أدخل الاسم الكامل',
                        'email_label' => 'البريد الإلكتروني',
                        'email_placeholder' => 'أدخل البريد الإلكتروني',
                        'message_label' => 'الرسالة',
                        'message_placeholder' => 'اكتب رسالتك',
                        'submit_text' => 'إرسال',
                        'sending_text' => 'جارٍ الإرسال...',
                        'success_text' => 'تم إرسال الرسالة بنجاح.',
                        'fail_text' => 'تعذر إرسال الرسالة. حاول مرة أخرى.',
                    ],
                ],
            ],
            'travel' => [
                'content' => [
                    'hero' => ['title' => 'Travel & Tourism', 'subtitle' => 'Travel planning that complements your study experience.', 'image' => '/assets/hero.png'],
                    'cta_card' => ['title' => 'Plan your trip with confidence', 'description' => 'From arrival planning to local experiences, we help students stay organized.'],
                    'features' => ['items' => [['title' => 'Flight planning'], ['title' => 'Arrival guidance'], ['title' => 'Trip coordination']]],
                    'destinations' => ['title' => 'Popular destinations', 'items' => []],
                    'services' => ['title' => 'Services', 'items' => [['title' => 'Travel coordination'], ['title' => 'Pre-arrival guidance']]],
                    'inquiry' => ['title' => 'Need travel help?', 'subtitle' => 'Send us your details and our team will get back to you.'],
                ],
                'ar_content' => [
                    'hero' => ['title' => 'السفر والسياحة', 'subtitle' => 'خدمات سفر تساعدك على تنظيم رحلتك التعليمية.', 'image' => '/assets/hero.png'],
                    'cta_card' => ['title' => 'خطط لرحلتك بثقة', 'description' => 'من التخطيط للوصول وحتى التجربة المحلية، نساعد الطالب على التنظيم بشكل أفضل.'],
                    'features' => ['items' => [['title' => 'تخطيط الرحلات'], ['title' => 'إرشادات الوصول'], ['title' => 'تنسيق الرحلة']]],
                    'destinations' => ['title' => 'وجهات شائعة', 'items' => []],
                    'services' => ['title' => 'الخدمات', 'items' => [['title' => 'تنسيق السفر'], ['title' => 'إرشادات ما قبل الوصول']]],
                    'inquiry' => ['title' => 'تحتاج مساعدة في السفر؟', 'subtitle' => 'أرسل بياناتك وسيتواصل معك فريقنا.'],
                ],
            ],
            'university_admissions' => [
                'content' => [
                    'hero' => ['badge' => 'Academic Pathways', 'title' => 'University Admissions Support', 'description' => 'Explore academic progression options after language study.'],
                    'cards' => [
                        ['title' => 'University Search', 'description' => 'Review suitable academic destinations and institutions.', 'icon' => 'faSearch', 'button_text' => 'Explore', 'url' => '/university-admissions'],
                        ['title' => 'Application Guidance', 'description' => 'Understand entry routes, required documents, and next steps.', 'icon' => 'faGraduationCap', 'button_text' => 'Learn More', 'url' => '/contact-us'],
                    ],
                    'stats' => [
                        ['value' => '50+', 'label' => 'Partners'],
                        ['value' => '10+', 'label' => 'Destinations'],
                        ['value' => '1:1', 'label' => 'Support'],
                        ['value' => '24/7', 'label' => 'Guidance'],
                    ],
                ],
                'ar_content' => [
                    'hero' => ['badge' => 'المسارات الأكاديمية', 'title' => 'دعم القبول الجامعي', 'description' => 'اكتشف خيارات التقدم الأكاديمي بعد دراسة اللغة.'],
                    'cards' => [
                        ['title' => 'البحث عن جامعة', 'description' => 'استعرض الوجهات والمؤسسات الأكاديمية المناسبة.', 'icon' => 'faSearch', 'button_text' => 'استكشف', 'url' => '/university-admissions'],
                        ['title' => 'إرشاد التقديم', 'description' => 'تعرّف على متطلبات القبول والوثائق والخطوات القادمة.', 'icon' => 'faGraduationCap', 'button_text' => 'اعرف المزيد', 'url' => '/contact-us'],
                    ],
                    'stats' => [
                        ['value' => '50+', 'label' => 'شريك'],
                        ['value' => '10+', 'label' => 'وجهات'],
                        ['value' => '1:1', 'label' => 'دعم'],
                        ['value' => '24/7', 'label' => 'متابعة'],
                    ],
                ],
            ],
        ];

        return $pages[$slug] ?? ['content' => [], 'ar_content' => []];
    }

    private function courseSatCurrencyMeta(string $code): array
    {
        $code = strtoupper(trim($code));

        $labels = [
            'SAR' => ['name' => 'Saudi Riyal', 'ar_name' => 'ريال سعودي'],
            'GBP' => ['name' => 'British Pound', 'ar_name' => 'جنيه إسترليني'],
            'USD' => ['name' => 'US Dollar', 'ar_name' => 'دولار أمريكي'],
            'EUR' => ['name' => 'Euro', 'ar_name' => 'يورو'],
            'CAD' => ['name' => 'Canadian Dollar', 'ar_name' => 'دولار كندي'],
            'AUD' => ['name' => 'Australian Dollar', 'ar_name' => 'دولار أسترالي'],
            'MYR' => ['name' => 'Malaysian Ringgit', 'ar_name' => 'رينغيت ماليزي'],
        ];

        $label = $labels[$code] ?? ['name' => $code, 'ar_name' => $code];

        $icons = [
            'SAR' => ['icon_light' => '/assets/sar.svg', 'icon_dark' => '/assets/sar-black.svg'],
            'GBP' => ['icon_light' => '/assets/gbp.svg', 'icon_dark' => '/assets/gbp-black.svg'],
        ];

        $symbols = [
            'GBP' => '£',
            'EUR' => '€',
            'USD' => '$',
            'CAD' => 'CA$',
            'AUD' => 'A$',
        ];

        return [
            'code' => $code,
            'name' => $label['name'],
            'ar_name' => $label['ar_name'],
            'symbol' => $symbols[$code] ?? null,
            'icon_light' => $icons[$code]['icon_light'] ?? null,
            'icon_dark' => $icons[$code]['icon_dark'] ?? null,
        ];
    }

    /**
     * @return list<array{from_weeks: int, weekly_fee: float, prices: array<string, float>}>
     */
    public function languageCourseWeekTiers(LanguageSchoolCourse $course, ?string $baseCurrency = null): array
    {
        $course->loadMissing(['branch.city.country']);
        $base = strtoupper((string) ($baseCurrency ?: $course->branch?->city?->country?->currency_code ?: 'GBP'));

        $tiers = [];

        foreach (range(1, 7) as $i) {
            $weeklyFee = $course->{"weekly_fee_{$i}"};
            if ($weeklyFee === null) {
                continue;
            }

            $weekCategory = $course->{"week_category_{$i}"};

            $tiers[] = [
                'from_weeks' => $weekCategory !== null ? (int) $weekCategory : 1,
                'weekly_fee' => (float) $weeklyFee,
                'prices' => $this->buildPriceMap($weeklyFee, $base),
            ];
        }

        usort($tiers, fn (array $a, array $b) => $a['from_weeks'] <=> $b['from_weeks']);

        return $tiers;
    }

    private function languageCourseBaseFee(LanguageSchoolCourse $course): ?float
    {
        foreach (range(1, 7) as $i) {
            $value = $course->{"weekly_fee_{$i}"};
            if ($value !== null) {
                return (float) $value;
            }
        }

        return null;
    }

    private function languageCourseOldFee(LanguageSchoolCourse $course): ?float
    {
        $fees = collect(range(1, 7))
            ->map(fn ($i) => $course->{"weekly_fee_{$i}"})
            ->filter(fn ($value) => $value !== null)
            ->map(fn ($value) => (float) $value)
            ->values();

        if ($fees->isEmpty()) {
            return null;
        }

        $max = $fees->max();
        $min = $fees->min();

        return $max > $min ? (float) $max : null;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => $this->normalizeValue($item), $value);
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            $looksLikeAsset =
                Str::startsWith($trimmed, ['http://', 'https://', '/assets/', 'assets/', '/storage/', 'storage/'])
                || (Str::contains($trimmed, '/') && preg_match('/\.[a-z0-9]{2,5}$/i', $trimmed));

            if ($looksLikeAsset) {
                return $this->toPublicUrl($trimmed) ?? $trimmed;
            }

            return $value;
        }

        return $value;
    }
}
