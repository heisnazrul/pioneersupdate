<?php

namespace App\Services\LanguageCourse;

use App\Models\ContactSubmission;
use App\Models\LanguageCourseBooking;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolPickup;
use App\Models\LanguageSchoolPioneersDiscount;
use App\Models\Role;
use App\Models\User;
use App\Support\CourseEnglishApiSupport;
use App\Support\CurrencyConverter;
use App\Support\LanguageCourseBookingPresenter;
use App\Services\Referral\ReferralService;
use App\Services\Agent\AgentBookingResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class LanguageCourseBookingService
{
    public function __construct(
        private readonly LanguageCoursePricingService $pricing,
        private readonly CourseEnglishApiSupport $support,
        private readonly CurrencyConverter $currencyConverter,
        private readonly LanguageCourseBookingPresenter $bookingPresenter,
        private readonly ReferralService $referralService,
        private readonly AgentBookingResolver $agentBookingResolver,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function create(array $payload, ?User $authenticatedUser = null): array
    {
        return DB::transaction(function () use ($payload, $authenticatedUser) {
            $selection = $payload['selection'];
            $displayCurrency = strtoupper((string) ($payload['display_currency'] ?? 'SAR'));
            $userData = is_array($payload['user_data'] ?? null) ? $payload['user_data'] : [];

            [$user, $createdToken, $bookedByAgentId, $bookedByAgentUserId] = $this->resolveBookingUser(
                $authenticatedUser,
                $userData,
                $payload['referral_code'] ?? null,
                $payload,
            );

            $catalog = $this->loadCatalog($selection);
            $pricing = $this->buildPricing($catalog, $selection, $displayCurrency);

            $referral = $this->referralService->resolveBookingReferral(
                $user,
                (float) $pricing['total'],
                'language_courses',
                $payload['referral_code'] ?? null,
            );
            $finalTotal = max(0, (float) $pricing['total'] - (float) $referral['discount_amount']);

            $courseModel = $catalog['courseModel'];
            $baseCurrency = strtoupper((string) ($catalog['course']['base_currency'] ?? 'GBP'));
            $currencySnapshot = $this->currencyConverter->bookingCurrencySnapshot(
                $baseCurrency,
                $displayCurrency,
                $finalTotal,
            );

            $booking = LanguageCourseBooking::create([
                'reference_no' => $this->generateReferenceNo(),
                'user_id' => $user->id,
                'language_school_id' => $courseModel->branch->school_id,
                'course_id' => (int) $selection['course_id'],
                'status' => 'pending',
                'source' => 'coursesat',
                'contact_name' => $user->name,
                'contact_email' => strtolower($user->email),
                'contact_phone' => (string) ($user->phone ?? $userData['phone'] ?? ''),
                'contact_whatsapp' => (string) ($userData['phone'] ?? $user->phone ?? ''),
                'weeks' => (int) $selection['weeks'],
                'start_date' => $selection['start_date'],
                'user_age' => isset($selection['acc_age']) ? (int) $selection['acc_age'] : null,
                'accommodation_id' => $this->nullableId($selection['accommodation_id'] ?? null),
                'pickup_id' => $this->nullableId($selection['pickup_id'] ?? null),
                'accommodation_weeks' => (int) $selection['weeks'],
                'insurance_ids' => array_values(array_map('intval', $catalog['insuranceIds'])),
                'supplement_ids' => array_values(array_map('intval', $catalog['supplementIds'])),
                'selection_snapshot' => $catalog['selectionSnapshot'],
                'course_weekly_fee' => $pricing['weeklyCourseFee'],
                'course_total' => $pricing['courseTotal'],
                'accommodation_weekly_fee' => $pricing['weeklyAccFee'],
                'accommodation_total' => $pricing['accTotal'],
                'accommodation_waived' => $pricing['accWaived'],
                'accommodation_original' => $pricing['accWaived'] ? $pricing['accOriginalTotal'] : null,
                'registration_fee' => $this->feeTotal($pricing['oneTimeFees'], 'registration'),
                'material_fee' => $this->feeTotal($pricing['oneTimeFees'], 'material_books'),
                'mandatory_fee' => $this->feeTotal($pricing['oneTimeFees'], 'mandatory'),
                'pickup_fee' => $pricing['pickupTotal'],
                'pickup_waived' => $pricing['pickupWaived'],
                'pickup_original' => $pricing['pickupWaived'] ? $pricing['pickupOriginalTotal'] : null,
                'insurance_total' => $this->insuranceWeeklyTotal($pricing['insuranceLines']),
                'insurance_admin_fee' => $this->insuranceAdminTotal($pricing['insuranceLines']),
                'acc_supplements_total' => array_sum(array_column($pricing['accSupplements'], 'total')),
                'other_supplements_total' => array_sum(array_column($pricing['supplementLines'], 'total')),
                'course_discount_percent' => $pricing['courseDiscountPercent'],
                'course_discount_amount' => $pricing['courseDiscountAmount'],
                'pioneers_discount_total' => $pricing['pioneersCashTotal'],
                'coupon_code' => $payload['coupon_code'] ?? null,
                'coupon_discount_amount' => 0,
                'referral_code' => $referral['referral_code'] ?? ($payload['referral_code'] ?? null),
                'referral_discount_amount' => $referral['discount_amount'],
                'referrer_type' => $referral['referrer_type'],
                'referrer_user_id' => $referral['referrer_user_id'],
                'referrer_agent_id' => $referral['referrer_agent_id'],
                'referral_commission_amount' => $referral['commission_amount'],
                'booked_by_agent_id' => $bookedByAgentId,
                'booked_by_agent_user_id' => $bookedByAgentUserId,
                'subtotal' => $pricing['subtotal'],
                'total_amount' => $finalTotal,
                'base_currency' => $baseCurrency,
                'display_currency' => $displayCurrency,
                'exchange_rate' => $currencySnapshot['exchange_rate'],
                'conversion_fee_percent' => $currencySnapshot['conversion_fee_percent'],
                'conversion_fee_amount' => $currencySnapshot['conversion_fee_amount'],
                'currency_snapshot' => $currencySnapshot,
                'pricing_snapshot' => $pricing,
            ]);

            $this->createCrmLead($booking, $user, $pricing, $displayCurrency);

            $this->referralService->recordBookingCommission($booking, 'language_course', $referral, $user);

            $booking->refresh();
            $booking->load(['school', 'course.branch.city.country', 'accommodation', 'pickup']);

            $response = [
                'success' => true,
                'booking_id' => $booking->id,
                'reference_no' => $booking->reference_no,
                'total_amount' => (float) $booking->total_amount,
                'display_currency' => $booking->display_currency,
                'message' => 'Booking request received successfully.',
                'booking' => $this->bookingPresenter->detail($booking),
            ];

            if ($createdToken) {
                $response['token'] = $createdToken;
                $response['token_type'] = 'Bearer';
                $response['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ];
            }

            return $response;
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function createForStaffStudent(User $student, User $staff, array $payload): array
    {
        $payload['referral_code'] = null;

        return DB::transaction(function () use ($student, $staff, $payload) {
            $selection = $payload['selection'];
            $displayCurrency = strtoupper((string) ($payload['display_currency'] ?? 'SAR'));

            $catalog = $this->loadCatalog($selection);
            $pricing = $this->buildPricing($catalog, $selection, $displayCurrency);
            $finalTotal = (float) $pricing['total'];

            $courseModel = $catalog['courseModel'];
            $baseCurrency = strtoupper((string) ($catalog['course']['base_currency'] ?? 'GBP'));
            $currencySnapshot = $this->currencyConverter->bookingCurrencySnapshot(
                $baseCurrency,
                $displayCurrency,
                $finalTotal,
            );

            $booking = LanguageCourseBooking::create([
                'reference_no' => $this->generateReferenceNo(),
                'user_id' => $student->id,
                'language_school_id' => $courseModel->branch->school_id,
                'course_id' => (int) $selection['course_id'],
                'status' => 'pending',
                'source' => 'staff_panel',
                'assigned_to' => $staff->id,
                'contact_name' => $student->name,
                'contact_email' => strtolower($student->email),
                'contact_phone' => (string) ($student->phone ?? ''),
                'contact_whatsapp' => (string) ($student->phone ?? ''),
                'weeks' => (int) $selection['weeks'],
                'start_date' => $selection['start_date'],
                'user_age' => isset($selection['acc_age']) ? (int) $selection['acc_age'] : null,
                'accommodation_id' => $this->nullableId($selection['accommodation_id'] ?? null),
                'pickup_id' => $this->nullableId($selection['pickup_id'] ?? null),
                'accommodation_weeks' => (int) $selection['weeks'],
                'insurance_ids' => array_values(array_map('intval', $catalog['insuranceIds'])),
                'supplement_ids' => array_values(array_map('intval', $catalog['supplementIds'])),
                'selection_snapshot' => $catalog['selectionSnapshot'],
                'course_weekly_fee' => $pricing['weeklyCourseFee'],
                'course_total' => $pricing['courseTotal'],
                'accommodation_weekly_fee' => $pricing['weeklyAccFee'],
                'accommodation_total' => $pricing['accTotal'],
                'accommodation_waived' => $pricing['accWaived'],
                'accommodation_original' => $pricing['accWaived'] ? $pricing['accOriginalTotal'] : null,
                'registration_fee' => $this->feeTotal($pricing['oneTimeFees'], 'registration'),
                'material_fee' => $this->feeTotal($pricing['oneTimeFees'], 'material_books'),
                'mandatory_fee' => $this->feeTotal($pricing['oneTimeFees'], 'mandatory'),
                'pickup_fee' => $pricing['pickupTotal'],
                'pickup_waived' => $pricing['pickupWaived'],
                'pickup_original' => $pricing['pickupWaived'] ? $pricing['pickupOriginalTotal'] : null,
                'insurance_total' => $this->insuranceWeeklyTotal($pricing['insuranceLines']),
                'insurance_admin_fee' => $this->insuranceAdminTotal($pricing['insuranceLines']),
                'acc_supplements_total' => array_sum(array_column($pricing['accSupplements'], 'total')),
                'other_supplements_total' => array_sum(array_column($pricing['supplementLines'], 'total')),
                'course_discount_percent' => $pricing['courseDiscountPercent'],
                'course_discount_amount' => $pricing['courseDiscountAmount'],
                'pioneers_discount_total' => $pricing['pioneersCashTotal'],
                'coupon_code' => null,
                'coupon_discount_amount' => 0,
                'referral_code' => null,
                'referral_discount_amount' => 0,
                'referrer_type' => null,
                'referrer_user_id' => null,
                'referrer_agent_id' => null,
                'referral_commission_amount' => 0,
                'booked_by_agent_id' => null,
                'booked_by_agent_user_id' => null,
                'subtotal' => $pricing['subtotal'],
                'total_amount' => $finalTotal,
                'base_currency' => $baseCurrency,
                'display_currency' => $displayCurrency,
                'exchange_rate' => $currencySnapshot['exchange_rate'],
                'conversion_fee_percent' => $currencySnapshot['conversion_fee_percent'],
                'conversion_fee_amount' => $currencySnapshot['conversion_fee_amount'],
                'currency_snapshot' => $currencySnapshot,
                'pricing_snapshot' => $pricing,
                'notes' => $payload['notes'] ?? null,
            ]);

            $this->createCrmLead($booking, $student, $pricing, $displayCurrency);

            $booking->refresh();
            $booking->load(['school', 'course.branch.city.country', 'accommodation', 'pickup']);

            return [
                'success' => true,
                'booking_id' => $booking->id,
                'reference_no' => $booking->reference_no,
                'total_amount' => (float) $booking->total_amount,
                'display_currency' => $booking->display_currency,
                'booking' => $this->bookingPresenter->detail($booking),
            ];
        });
    }

    public function resolveAuthenticatedUser(?string $bearerToken): ?User
    {
        if (! $bearerToken) {
            return null;
        }

        $accessToken = PersonalAccessToken::findToken($bearerToken);

        return $accessToken?->tokenable instanceof User ? $accessToken->tokenable : null;
    }

    /**
     * @param  array<string, mixed>  $userData
     * @param  array<string, mixed>  $payload
     * @return array{0: User, 1: ?string, 2: ?int, 3: ?int}
     */
    private function resolveBookingUser(
        ?User $authenticatedUser,
        array $userData,
        ?string $referralCode,
        array $payload,
    ): array {
        if ($authenticatedUser && $this->agentBookingResolver->isAgent($authenticatedUser)) {
            $agentStudentId = (int) ($payload['agent_student_id'] ?? 0);
            if ($agentStudentId <= 0) {
                throw ValidationException::withMessages([
                    'agent_student_id' => ['Please select a student for this booking.'],
                ]);
            }

            [$studentUser, $agentId, $agentUserId] = $this->agentBookingResolver->resolveStudentForAgent(
                $authenticatedUser,
                $agentStudentId,
            );

            return [$studentUser, null, $agentId, $agentUserId];
        }

        [$user, $token] = $this->resolveUser($authenticatedUser, $userData, $referralCode);

        return [$user, $token, null, null];
    }

    /**
     * @param  array<string, mixed>  $userData
     * @return array{0: User, 1: ?string}
     */
    private function resolveUser(?User $authenticatedUser, array $userData, ?string $referralCode = null): array
    {
        if ($authenticatedUser instanceof User) {
            return [$authenticatedUser->fresh(), null];
        }

        $name = trim((string) ($userData['name'] ?? ''));
        $email = strtolower(trim((string) ($userData['email'] ?? '')));
        $phone = trim((string) ($userData['phone'] ?? ''));
        $password = (string) ($userData['password'] ?? '');

        if ($name === '' || $email === '' || $phone === '' || $password === '') {
            throw ValidationException::withMessages([
                'user_data' => ['Name, email, phone, and password are required for guest booking.'],
            ]);
        }

        if (strlen($password) < 8) {
            throw ValidationException::withMessages([
                'user_data.password' => ['Password must be at least 8 characters.'],
            ]);
        }

        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'user_data.email' => ['This email is already registered. Please log in to continue.'],
            ]);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => 'active',
            'role' => 'lg_student',
            'password' => Hash::make($password),
        ]);

        $roleId = Role::query()->where('slug', 'lg_student')->value('id');
        if ($roleId) {
            $user->roles()->syncWithoutDetaching([$roleId]);
        }

        $this->referralService->ensureStudentReferralCode($user);

        $code = $referralCode ?: ($userData['referral_code'] ?? null);
        if ($code) {
            $this->referralService->attributeUser($user, (string) $code, 'code');
        }

        $token = $user->createToken('coursesat-booking', ['app:courseenglish', 'role:lg_student'])->plainTextToken;

        return [$user->fresh(), $token];
    }

    /**
     * @param  array<string, mixed>  $selection
     * @return array<string, mixed>
     */
    private function loadCatalog(array $selection): array
    {
        $courseModel = LanguageSchoolCourse::query()
            ->active()
            ->with(['branch.school', 'branch.city.country', 'promotions' => fn ($q) => $q->active(), 'tags', 'category'])
            ->findOrFail((int) $selection['course_id']);

        $course = $this->support->courseSatLanguageInstituteCard($courseModel);

        $accommodation = null;
        $accommodationModel = null;
        $accommodationId = $selection['accommodation_id'] ?? null;
        if ($accommodationId && $accommodationId !== 'no-acc') {
            $accommodationModel = LanguageSchoolAccommodation::query()
                ->where('branch_id', $courseModel->branch_id)
                ->where('is_active', 'yes')
                ->with(['type', 'bedroomType', 'bathroomType', 'mealPlan'])
                ->findOrFail((int) $accommodationId);
            $accommodation = $this->support->accommodationCard($accommodationModel);
        }

        $pickup = null;
        if (! empty($selection['pickup_id'])) {
            $pickupModel = LanguageSchoolPickup::query()
                ->where('branch_id', $courseModel->branch_id)
                ->findOrFail((int) $selection['pickup_id']);
            $pickup = $this->support->pickupCard($pickupModel);
        }

        $insuranceIds = array_values(array_unique(array_map('intval', $selection['insurance_ids'] ?? [])));
        $extras = array_values(array_unique(array_map('intval', $selection['extras'] ?? [])));
        $insurances = [];

        $branchInsurances = LanguageSchoolInsurance::query()
            ->where('branch_id', $courseModel->branch_id)
            ->get();

        foreach ($branchInsurances as $branchInsurance) {
            $shouldInclude = in_array($branchInsurance->id, $insuranceIds, true)
                || in_array($branchInsurance->id, $extras, true)
                || $branchInsurance->is_mandatory === 'yes';

            if ($shouldInclude) {
                $insurances[] = $this->support->insuranceCard($branchInsurance);
                $insuranceIds[] = $branchInsurance->id;
            }
        }

        $insuranceIds = array_values(array_unique($insuranceIds));

        $supplementIds = array_values(array_unique(array_map('intval', $selection['supplement_ids'] ?? [])));

        $discounts = LanguageSchoolCourse::query()
            ->active()
            ->where('branch_id', $courseModel->branch_id)
            ->with(['promotions' => fn ($q) => $q->active()])
            ->get()
            ->flatMap(fn (LanguageSchoolCourse $item) => $item->promotions->map(fn ($promotion) => [
                'course_id' => $item->id,
                'discount_percentage' => (float) $promotion->promotion_percentage,
            ]))
            ->values()
            ->all();

        $pioneersDiscounts = LanguageSchoolPioneersDiscount::query()
            ->where('is_active', true)
            ->orderBy('weeks')
            ->get()
            ->map(function (LanguageSchoolPioneersDiscount $discount) {
                return [
                    'id' => $discount->id,
                    'name' => $discount->name,
                    'ar_name' => $discount->ar_name,
                    'weeks' => $discount->weeks,
                    'discount_amount' => (float) $discount->discount_amount,
                    'discount_amount_prices' => $this->support->buildPriceMap($discount->discount_amount, 'GBP'),
                    'discount_full_for' => $discount->discount_full_for,
                ];
            })
            ->values()
            ->all();

        $registrationAmount = (float) ($courseModel->registration_admin_fee ?? 0);
        $registrationBaseCurrency = strtoupper((string) ($courseModel->branch?->city?->country?->currency_code ?: 'GBP'));
        $registrationFeeObj = [
            'amount' => $registrationAmount,
            'prices' => $this->support->buildPriceMap($registrationAmount, $registrationBaseCurrency),
        ];

        return [
            'courseModel' => $courseModel,
            'course' => $course,
            'accommodation' => $accommodation,
            'pickup' => $pickup,
            'insurances' => $insurances,
            'supplements' => [],
            'insuranceIds' => $insuranceIds,
            'supplementIds' => $supplementIds,
            'discounts' => $discounts,
            'pioneersDiscounts' => $pioneersDiscounts,
            'registrationFeeObj' => $registrationFeeObj,
            'selectionSnapshot' => [
                'course' => [
                    'id' => $course['id'] ?? null,
                    'name' => $course['name'] ?? null,
                    'ar_name' => $course['ar_name'] ?? null,
                ],
                'accommodation' => $accommodation ? [
                    'id' => $accommodation['id'] ?? null,
                    'name' => $accommodation['name'] ?? null,
                ] : null,
                'pickup' => $pickup ? [
                    'id' => $pickup['id'] ?? null,
                    'name' => $pickup['name'] ?? null,
                ] : null,
                'insurance_ids' => $insuranceIds,
                'supplement_ids' => $supplementIds,
                'weeks' => (int) $selection['weeks'],
                'start_date' => $selection['start_date'],
                'acc_age' => $selection['acc_age'] ?? null,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @param  array<string, mixed>  $selection
     * @return array<string, mixed>
     */
    private function buildPricing(array $catalog, array $selection, string $displayCurrency): array
    {
        return $this->pricing->compute([
            'selectedCourse' => $catalog['course'],
            'selectedCourseId' => $catalog['course']['id'] ?? null,
            'selectedAccommodation' => $catalog['accommodation'],
            'selectedPickup' => $catalog['pickup'],
            'selectedInsurances' => $catalog['insurances'],
            'selectedSupplements' => $catalog['supplements'],
            'weeks' => (int) $selection['weeks'],
            'startDate' => $selection['start_date'],
            'accAge' => $selection['acc_age'] ?? null,
            'currency' => $displayCurrency,
            'registrationFeeObj' => $catalog['registrationFeeObj'],
            'pioneersDiscounts' => $catalog['pioneersDiscounts'],
            'discounts' => $catalog['discounts'],
            'supplementLabels' => [],
        ]);
    }

    private function generateReferenceNo(): string
    {
        $prefix = 'LCB-' . now()->format('Ymd') . '-';
        $last = LanguageCourseBooking::withTrashed()
            ->where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('reference_no');

        $sequence = $last ? ((int) substr($last, -6)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
    }

    private function nullableId(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === 'no-acc') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @param  list<array<string, mixed>>  $fees
     */
    private function feeTotal(array $fees, string $key): float
    {
        foreach ($fees as $fee) {
            if (($fee['key'] ?? '') === $key) {
                return (float) ($fee['total'] ?? 0);
            }
        }

        return 0.0;
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     */
    private function insuranceWeeklyTotal(array $lines): float
    {
        return (float) array_sum(array_map(
            fn (array $line) => ($line['key'] ?? '') === 'insurance_weekly' ? (float) ($line['total'] ?? 0) : 0.0,
            $lines,
        ));
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     */
    private function insuranceAdminTotal(array $lines): float
    {
        return (float) array_sum(array_map(
            fn (array $line) => ($line['key'] ?? '') === 'insurance_admin' ? (float) ($line['total'] ?? 0) : 0.0,
            $lines,
        ));
    }

    private function createCrmLead(LanguageCourseBooking $booking, User $user, array $pricing, string $displayCurrency): void
    {
        ContactSubmission::create([
            'name' => $booking->contact_name,
            'email' => $booking->contact_email,
            'phone' => $booking->contact_phone,
            'subject' => 'CourseSat booking: language_course',
            'message' => json_encode([
                'source' => 'coursesat_booking',
                'booking_type' => 'language_course',
                'booking_id' => $booking->id,
                'reference_no' => $booking->reference_no,
                'user_id' => $user->id,
                'booking_data' => [
                    'course_id' => $booking->course_id,
                    'weeks' => $booking->weeks,
                    'start_date' => optional($booking->start_date)->format('Y-m-d'),
                    'final_price' => $booking->total_amount,
                    'currency' => $displayCurrency,
                    'accommodation_id' => $booking->accommodation_id,
                    'pickup_id' => $booking->pickup_id,
                    'insurance_ids' => $booking->insurance_ids,
                    'supplement_ids' => $booking->supplement_ids,
                ],
                'pricing' => $pricing,
            ], JSON_UNESCAPED_UNICODE),
            'status' => 'pending',
        ]);
    }
}
