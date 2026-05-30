<?php

namespace App\Services\OnlineCourse;

use App\Models\ContactSubmission;
use App\Models\LanguageOnlineCourse;
use App\Models\OnlineCourseBooking;
use App\Models\Role;
use App\Models\User;
use App\Support\CourseEnglishApiSupport;
use App\Support\CurrencyConverter;
use App\Support\OnlineCourseBookingPresenter;
use App\Services\Referral\ReferralService;
use App\Services\Agent\AgentBookingResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class OnlineCourseBookingService
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
        private readonly CurrencyConverter $currencyConverter,
        private readonly OnlineCourseBookingPresenter $bookingPresenter,
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

            $course = LanguageOnlineCourse::query()
                ->with(['school.branches.city.country', 'courseType'])
                ->where('id', (int) $selection['course_id'])
                ->where('visible', true)
                ->where('status', 'published')
                ->firstOrFail();

            $weeks = isset($selection['weeks']) ? max(1, (int) $selection['weeks']) : null;
            if ($course->fee_type === 'weekly' && ! $weeks) {
                throw ValidationException::withMessages([
                    'selection.weeks' => ['Weeks are required for weekly online courses.'],
                ]);
            }

            $baseCurrency = 'GBP';
            $courseFee = (float) $course->fee_amount;
            if ($course->fee_type === 'weekly') {
                $courseFee = $courseFee * (int) $weeks;
            }
            $registrationFee = (float) ($course->registration_fee ?? 0);
            $subtotal = $courseFee + $registrationFee;

            $referral = $this->referralService->resolveBookingReferral(
                $user,
                $subtotal,
                'online_courses',
                $payload['referral_code'] ?? null,
            );
            $total = max(0, $subtotal - (float) $referral['discount_amount']);

            $currencySnapshot = $this->currencyConverter->bookingCurrencySnapshot(
                $baseCurrency,
                $displayCurrency,
                $total,
            );
            $total = (float) ($currencySnapshot['display_total'] ?? $total);

            $pricing = [
                'courseFee' => $courseFee,
                'registrationFee' => $registrationFee,
                'subtotal' => $subtotal,
                'total' => $total,
                'fee_type' => $course->fee_type,
                'weeks' => $weeks,
            ];

            $booking = OnlineCourseBooking::create([
                'reference_no' => $this->generateReferenceNo(),
                'user_id' => $user->id,
                'language_school_id' => $course->language_school_id,
                'online_course_id' => $course->id,
                'status' => 'pending',
                'source' => 'coursesat',
                'contact_name' => $user->name,
                'contact_email' => strtolower($user->email),
                'contact_phone' => (string) ($user->phone ?? $userData['phone'] ?? ''),
                'contact_whatsapp' => (string) ($userData['phone'] ?? $user->phone ?? ''),
                'weeks' => $weeks,
                'start_date' => $selection['start_date'],
                'course_fee' => $courseFee,
                'registration_fee' => $registrationFee,
                'subtotal' => $subtotal,
                'total_amount' => $total,
                'base_currency' => $baseCurrency,
                'display_currency' => $displayCurrency,
                'exchange_rate' => $currencySnapshot['exchange_rate'] ?? null,
                'conversion_fee_percent' => $currencySnapshot['conversion_fee_percent'] ?? 0,
                'conversion_fee_amount' => $currencySnapshot['conversion_fee_amount'] ?? 0,
                'currency_snapshot' => $currencySnapshot,
                'pricing_snapshot' => $pricing,
                'selection_snapshot' => $selection,
                'referral_code' => $referral['referral_code'] ?? ($payload['referral_code'] ?? null),
                'referral_discount_amount' => $referral['discount_amount'],
                'referrer_type' => $referral['referrer_type'],
                'referrer_user_id' => $referral['referrer_user_id'],
                'referrer_agent_id' => $referral['referrer_agent_id'],
                'referral_commission_amount' => $referral['commission_amount'],
                'booked_by_agent_id' => $bookedByAgentId,
                'booked_by_agent_user_id' => $bookedByAgentUserId,
            ]);

            $this->createCrmLead($booking, $user, $pricing, $displayCurrency);

            $this->referralService->recordBookingCommission($booking, 'online_course', $referral, $user);

            $booking->refresh();
            $booking->load(['school', 'course.courseType']);

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

    private function generateReferenceNo(): string
    {
        do {
            $reference = 'OC' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (OnlineCourseBooking::query()->where('reference_no', $reference)->exists());

        return $reference;
    }

    /**
     * @param  array<string, mixed>  $pricing
     */
    private function createCrmLead(OnlineCourseBooking $booking, User $user, array $pricing, string $displayCurrency): void
    {
        $course = $booking->course;
        if (! $course) {
            return;
        }

        ContactSubmission::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'subject' => 'CourseSat booking: online_course',
            'message' => json_encode([
                'source' => 'coursesat_booking',
                'booking_type' => 'online_course',
                'booking_id' => $booking->id,
                'reference_no' => $booking->reference_no,
                'user_id' => $user->id,
                'booking_data' => [
                    'online_course_id' => $course->id,
                    'weeks' => $booking->weeks,
                    'start_date' => optional($booking->start_date)->format('Y-m-d'),
                    'final_price' => $booking->total_amount,
                    'currency' => $displayCurrency,
                ],
                'pricing' => $pricing,
            ], JSON_UNESCAPED_UNICODE),
            'status' => 'pending',
        ]);
    }
}
