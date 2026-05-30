<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseCategory;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolPickup;
use App\Models\LanguageSchoolPioneersDiscount;
use App\Models\User;
use App\Services\LanguageCourse\LanguageCourseBookingService;
use App\Services\LanguageCourse\LanguageCoursePricingService;
use App\Services\OnlineCourse\OnlineCourseBookingService;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StaffBookingCatalogService
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
        private readonly LanguageCoursePricingService $pricing,
    ) {
    }

    public function categories(): Collection
    {
        return LanguageCourseCategory::query()
            ->active()
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar', 'slug']);
    }

    public function schools(?int $categoryId, string $bookingType = 'language_course'): Collection
    {
        if ($bookingType === 'online_course') {
            return LanguageSchool::query()
                ->active()
                ->whereHas('onlineCourses', function (Builder $query) use ($categoryId) {
                    $query->where('visible', true)->where('status', 'published');
                    if ($categoryId) {
                        $query->where('course_type_id', $categoryId);
                    }
                })
                ->orderBy('name_en')
                ->get(['id', 'name_en', 'name_ar', 'slug', 'logo_url']);
        }

        return LanguageSchool::query()
            ->active()
            ->whereHas('branches.courses', function (Builder $query) use ($categoryId) {
                $query->active();
                if ($categoryId) {
                    $query->where('course_category_id', $categoryId);
                }
            })
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar', 'slug', 'logo_url']);
    }

    public function branches(int $schoolId, ?int $categoryId = null): Collection
    {
        return LanguageSchoolBranch::query()
            ->where('school_id', $schoolId)
            ->where('is_active', 'yes')
            ->whereHas('courses', function (Builder $query) use ($categoryId) {
                $query->active();
                if ($categoryId) {
                    $query->where('course_category_id', $categoryId);
                }
            })
            ->with(['city.country'])
            ->orderBy('id')
            ->get()
            ->map(fn (LanguageSchoolBranch $branch) => [
                'id' => $branch->id,
                'slug' => $branch->slug,
                'city_name' => $branch->city?->name,
                'country_name' => $branch->city?->country?->name,
            ]);
    }

    public function branchCatalog(int $branchId, ?int $categoryId = null): array
    {
        $branch = LanguageSchoolBranch::query()
            ->with(['school', 'city.country'])
            ->findOrFail($branchId);

        $coursesQuery = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $branchId)
            ->with(['promotions' => fn ($q) => $q->active(), 'category']);

        if ($categoryId) {
            $coursesQuery->where('course_category_id', $categoryId);
        }

        $rawCourses = $coursesQuery->get();

        $accommodations = LanguageSchoolAccommodation::query()
            ->where('branch_id', $branchId)
            ->where('is_active', 'yes')
            ->with(['type', 'bedroomType', 'bathroomType', 'mealPlan'])
            ->get()
            ->map(fn ($item) => $this->support->accommodationCard($item))
            ->values();

        $pickups = LanguageSchoolPickup::query()
            ->where('branch_id', $branchId)
            ->get()
            ->map(fn ($item) => $this->support->pickupCard($item))
            ->values();

        $insurances = LanguageSchoolInsurance::query()
            ->where('branch_id', $branchId)
            ->get()
            ->map(fn ($item) => $this->support->insuranceCard($item))
            ->values();

        $discounts = $rawCourses
            ->flatMap(fn (LanguageSchoolCourse $course) => $course->promotions->map(fn ($promotion) => [
                'course_id' => $course->id,
                'discount_percentage' => (float) $promotion->promotion_percentage,
            ]))
            ->values();

        $pioneersDiscounts = LanguageSchoolPioneersDiscount::query()
            ->where('is_active', true)
            ->orderBy('weeks')
            ->get()
            ->map(fn (LanguageSchoolPioneersDiscount $discount) => [
                'id' => $discount->id,
                'name' => $discount->name,
                'weeks' => $discount->weeks,
                'discount_amount' => (float) $discount->discount_amount,
                'discount_amount_prices' => $this->support->buildPriceMap($discount->discount_amount, 'GBP'),
                'discount_full_for' => $discount->discount_full_for,
            ])
            ->values();

        $selectedCourse = $rawCourses->first();
        $registrationAmount = (float) ($selectedCourse?->registration_admin_fee ?? 0);
        $registrationBaseCurrency = strtoupper((string) ($branch->city?->country?->currency_code ?: 'GBP'));

        return [
            'branch' => [
                'id' => $branch->id,
                'slug' => $branch->slug,
                'school_id' => $branch->school_id,
                'school_name' => $branch->school?->name_en,
                'city_name' => $branch->city?->name,
            ],
            'courses' => $rawCourses
                ->map(fn (LanguageSchoolCourse $course) => $this->support->courseSatLanguageInstituteCard($course))
                ->values(),
            'accommodations' => $accommodations,
            'pickups' => $pickups,
            'insurances' => $insurances,
            'discounts' => $discounts,
            'pioneers_discounts' => $pioneersDiscounts,
            'registration_fee' => [
                'amount' => $registrationAmount,
                'prices' => $this->support->buildPriceMap($registrationAmount, $registrationBaseCurrency),
            ],
            'currencies' => $this->support->courseSatCurrenciesPayload(),
        ];
    }

    public function onlineCourses(int $schoolId, ?int $categoryId = null): Collection
    {
        return LanguageOnlineCourse::query()
            ->where('language_school_id', $schoolId)
            ->where('visible', true)
            ->where('status', 'published')
            ->when($categoryId, fn ($q) => $q->where('course_type_id', $categoryId))
            ->with(['courseType', 'school'])
            ->orderBy('name_en')
            ->get()
            ->map(fn ($course) => $this->support->onlineCourseCard($course));
    }

    /**
     * @param  array<string, mixed>  $selection
     * @param  array<string, mixed>  $catalog
     */
    public function previewLanguagePricing(array $selection, array $catalog, string $currency = 'SAR'): array
    {
        $course = collect($catalog['courses'] ?? [])->firstWhere('id', (int) ($selection['course_id'] ?? 0));
        $accommodation = null;
        $accId = $selection['accommodation_id'] ?? null;
        if ($accId && $accId !== 'no-acc') {
            $accommodation = collect($catalog['accommodations'] ?? [])->firstWhere('id', (int) $accId);
        }

        $pickup = ! empty($selection['pickup_id'])
            ? collect($catalog['pickups'] ?? [])->firstWhere('id', (int) $selection['pickup_id'])
            : null;

        $insuranceIds = array_map('intval', $selection['insurance_ids'] ?? []);
        $insurances = collect($catalog['insurances'] ?? [])
            ->filter(fn ($item) => in_array((int) ($item['id'] ?? 0), $insuranceIds, true))
            ->values()
            ->all();

        $pricing = $this->pricing->compute([
            'selectedCourse' => $course,
            'selectedCourseId' => $selection['course_id'] ?? null,
            'selectedAccommodation' => $accommodation,
            'selectedPickup' => $pickup,
            'selectedInsurances' => $insurances,
            'selectedSupplements' => [],
            'weeks' => $selection['weeks'] ?? 1,
            'startDate' => $selection['start_date'] ?? null,
            'accAge' => $selection['acc_age'] ?? null,
            'currency' => $currency,
            'registrationFeeObj' => $catalog['registration_fee'] ?? null,
            'pioneersDiscounts' => collect($catalog['pioneers_discounts'] ?? [])->values()->all(),
            'discounts' => collect($catalog['discounts'] ?? [])->values()->all(),
        ]);

        return $pricing;
    }

    public function studentsForCounsellor(int $counsellorId): Collection
    {
        return User::query()
            ->where('role', 'lg_student')
            ->where(function ($query) use ($counsellorId) {
                $query->whereIn('id', function ($sub) use ($counsellorId) {
                    $sub->select('student_user_id')
                        ->from('staff_student_assignments')
                        ->where('staff_user_id', $counsellorId);
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);
    }
}
