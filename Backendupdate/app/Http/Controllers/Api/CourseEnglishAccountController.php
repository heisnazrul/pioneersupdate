<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\LanguageCourseCompare;
use App\Models\LanguageCourseWishlist;
use App\Models\LanguageSchoolCourse;
use App\Models\Role;
use App\Models\User;
use App\Services\Referral\ReferralService;
use App\Services\Profile\ProfileService;
use App\Services\Payout\PayoutService;
use App\Support\CourseEnglishApiSupport;
use App\Support\LanguageCourseBookingPresenter;
use App\Support\OnlineCourseBookingPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseEnglishAccountController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
        private readonly LanguageCourseBookingPresenter $bookingPresenter,
        private readonly OnlineCourseBookingPresenter $onlineBookingPresenter,
        private readonly ReferralService $referralService,
        private readonly ProfileService $profileService,
        private readonly PayoutService $payoutService,
    ) {
    }

    public function wishlist(Request $request): JsonResponse
    {
        $items = LanguageCourseWishlist::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get()
            ->map(fn (LanguageCourseWishlist $item) => $this->mapInteractionItem($item->course_type, (int) $item->course_id))
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'keys' => $items->map(fn ($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
            'items' => $items,
        ]);
    }

    public function wishlistAdd(Request $request): JsonResponse
    {
        $data = $this->validateInteractionPayload($request);

        LanguageCourseWishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'course_type' => $data['course_type'],
            'course_id' => $data['course_id'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function wishlistRemove(Request $request): JsonResponse
    {
        $data = $this->validateInteractionPayload($request);

        LanguageCourseWishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('course_type', $data['course_type'])
            ->where('course_id', $data['course_id'])
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function compare(Request $request): JsonResponse
    {
        $records = LanguageCourseCompare::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        $items = $records
            ->map(function (LanguageCourseCompare $item) {
                $mapped = $this->mapInteractionItem($item->course_type, (int) $item->course_id);
                if (!$mapped) {
                    return null;
                }

                return array_merge($mapped, [
                    'weeks' => (int) ($item->weeks ?: 12),
                ]);
            })
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'keys' => $items->map(fn ($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
            'items' => $items,
        ]);
    }

    public function compareAdd(Request $request): JsonResponse
    {
        $data = $this->validateInteractionPayload($request, true);

        LanguageCourseCompare::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_type' => $data['course_type'],
                'course_id' => $data['course_id'],
            ],
            [
                'weeks' => (int) ($data['weeks'] ?? 12),
            ],
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function compareRemove(Request $request): JsonResponse
    {
        $data = $this->validateInteractionPayload($request);

        LanguageCourseCompare::query()
            ->where('user_id', $request->user()->id)
            ->where('course_type', $data['course_type'])
            ->where('course_id', $data['course_id'])
            ->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function studentMe(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing([
            'profile.nationalityCountry',
            'profile.currentCountry',
            'profile.currentCity',
            'roles',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->profileService->payload($user),
        ]);
    }

    public function studentUpdateProfile(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('profile');
        $data = $request->validate($this->profileService->profileRules($user));
        $user = $this->profileService->update($user, $data, $request);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $this->profileService->payload($user),
        ]);
    }

    public function studentPayouts(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->payoutService->listForUser($request->user()),
        ]);
    }

    public function studentCreatePayout(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payout = $this->payoutService->createForStudent(
            $request->user()->fresh(['profile']),
            isset($data['amount']) ? (float) $data['amount'] : null,
            $data['notes'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Payout request submitted successfully.',
            'data' => $this->payoutService->serialize($payout),
        ], 201);
    }

    public function studentBookings(Request $request): JsonResponse
    {
        $user = $request->user();
        $email = strtolower((string) $user->email);

        $tableBookings = LanguageCourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['course.branch.school', 'course.branch.city.country', 'school'])
            ->latest('id')
            ->get()
            ->map(fn (LanguageCourseBooking $booking) => $this->bookingPresenter->summary($booking))
            ->filter()
            ->values();

        $onlineBookings = OnlineCourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['course.courseType', 'school'])
            ->latest('id')
            ->get()
            ->map(fn (OnlineCourseBooking $booking) => $this->onlineBookingPresenter->summary($booking))
            ->filter()
            ->values();

        $existingReferences = $tableBookings
            ->concat($onlineBookings)
            ->pluck('reference_no')
            ->filter()
            ->values()
            ->all();

        $leads = ContactSubmission::query()
            ->where(function ($query) use ($user, $email) {
                $query->where('email', $email)
                    ->orWhere('message', 'like', '%"user_id":' . $user->id . '%')
                    ->orWhere('message', 'like', '%"user_id": ' . $user->id . '%');
            })
            ->where('subject', 'like', '%booking:%')
            ->latest('id')
            ->get();

        $legacy = $leads
            ->map(fn (ContactSubmission $lead) => $this->mapBookingLead($lead))
            ->filter(function (?array $item) use ($existingReferences) {
                if (! $item) {
                    return false;
                }

                $reference = $item['reference_no'] ?? null;

                return ! $reference || ! in_array($reference, $existingReferences, true);
            })
            ->values();

        $data = $tableBookings->concat($onlineBookings)->concat($legacy)->sortByDesc('created_at')->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function studentBookingShow(Request $request, string $referenceNo): JsonResponse
    {
        $booking = LanguageCourseBooking::query()
            ->where('reference_no', $referenceNo)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($booking) {
            $detail = $this->bookingPresenter->detail($booking);

            if ($detail) {
                return response()->json([
                    'success' => true,
                    'data' => $detail,
                ]);
            }
        }

        $onlineBooking = OnlineCourseBooking::query()
            ->where('reference_no', $referenceNo)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($onlineBooking) {
            $detail = $this->onlineBookingPresenter->detail($onlineBooking);

            if ($detail) {
                return response()->json([
                    'success' => true,
                    'data' => $detail,
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Booking not found.',
        ], 404);
    }

    public function studentReferrals(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $this->referralService->studentReferralStats($user),
        ]);
    }

    public function courseEnglishStudentBookings(Request $request): JsonResponse
    {
        return $this->studentBookings($request);
    }

    private function validateInteractionPayload(Request $request, bool $allowWeeks = false): array
    {
        $rules = [
            'course_type' => ['required', Rule::in(['language_courses', 'online_courses', 'summer_camps', 'training_courses'])],
            'course_id' => ['required', 'integer', 'min:1'],
        ];

        if ($allowWeeks) {
            $rules['weeks'] = ['nullable', 'integer', 'min:1', 'max:52'];
        }

        return $request->validate($rules);
    }

    private function mapInteractionItem(string $courseType, int $courseId): ?array
    {
        $modelClass = $this->support->courseTypeModel($courseType);

        if (!$modelClass) {
            return null;
        }

        $with = match ($courseType) {
            'language_courses' => ['category', 'promotions' => fn ($q) => $q->active(), 'branch.school', 'branch.city.country', 'branch.pickups', 'branch.accommodations', 'branch.insurance'],
            'online_courses' => ['school.branches.city.country', 'courseType'],
            'summer_camps' => ['branch.school', 'branch.city.country', 'courseType', 'detail'],
            'training_courses' => ['school', 'branch.city.country', 'courseType'],
            default => [],
        };

        $course = $modelClass::query()->with($with)->find($courseId);
        if (!$course) {
            return null;
        }

        $payload = $this->support->coursePayload($courseType, $course);
        if (!$payload) {
            return null;
        }

        return [
            'course_type' => $courseType,
            'course_id' => $courseId,
            'course' => $payload,
        ];
    }

    private function studentReferralCode(User $user): string
    {
        return Str::upper(substr(hash('crc32b', $user->id . '|' . $user->email), 0, 8));
    }

    private function mapLanguageCourseBooking(LanguageCourseBooking $booking): ?array
    {
        $coursePayload = $this->mapInteractionItem('language_courses', (int) $booking->course_id)['course'] ?? null;
        $school = $booking->school;
        $course = $booking->course;

        return [
            'id' => $booking->id,
            'booking_id' => $booking->reference_no,
            'reference_no' => $booking->reference_no,
            'status' => $booking->status,
            'school_name' => $coursePayload['school_name'] ?? $school?->name_en,
            'school_ar_name' => $coursePayload['school_ar_name'] ?? $school?->name_ar,
            'course_name' => $coursePayload['name'] ?? $course?->course_name_from_school,
            'course_ar_name' => $coursePayload['ar_name'] ?? $course?->course_name_from_school_ar,
            'course_type' => 'language_courses',
            'country_name' => $coursePayload['country_name'] ?? null,
            'country_ar_name' => $coursePayload['country_ar_name'] ?? null,
            'country_flag' => $coursePayload['flag'] ?? null,
            'city_name' => $coursePayload['city_name'] ?? null,
            'city_ar_name' => $coursePayload['city_ar_name'] ?? null,
            'start_date' => optional($booking->start_date)->format('Y-m-d'),
            'weeks' => $booking->weeks,
            'final_price' => (float) $booking->total_amount,
            'original_price' => (float) $booking->subtotal,
            'total' => (float) $booking->total_amount,
            'currency' => strtoupper((string) $booking->display_currency),
            'rating' => $coursePayload['rating'] ?? 4,
            'course_image' => $coursePayload['image'] ?? ($coursePayload['logo'] ?? null),
            'school_logo' => $coursePayload['logo'] ?? null,
            'created_at' => optional($booking->created_at)->toIso8601String(),
            'student_name' => $booking->contact_name,
            'student_email' => $booking->contact_email,
        ];
    }

    private function mapBookingLead(ContactSubmission $lead): ?array
    {
        $payload = json_decode((string) $lead->message, true);
        $source = $payload['source'] ?? null;
        if (! is_array($payload) || ! in_array($source, ['courseenglish_booking', 'coursesat_booking'], true)) {
            return null;
        }

        $bookingData = is_array($payload['booking_data'] ?? null) ? $payload['booking_data'] : [];
        $userData = is_array($payload['user_data'] ?? null) ? $payload['user_data'] : [];
        $courseType = $this->normalizeBookingCourseType((string) ($payload['booking_type'] ?? 'language_course'));
        $courseId = (int) ($bookingData['course_id'] ?? 0);
        $coursePayload = $courseId > 0 ? ($this->mapInteractionItem($courseType, $courseId)['course'] ?? null) : null;

        $status = match ($lead->status) {
            'resolved' => 'confirmed',
            'contacted' => 'pending',
            default => 'pending',
        };

        $finalPrice = $bookingData['final_price'] ?? null;
        $currency = strtoupper((string) ($bookingData['currency'] ?? 'SAR'));

        return [
            'id' => $lead->id,
            'booking_id' => $payload['reference_no'] ?? ('CE-' . str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT)),
            'reference_no' => $payload['reference_no'] ?? null,
            'status' => $status,
            'school_name' => $coursePayload['school_name'] ?? ($coursePayload['name'] ?? null),
            'school_ar_name' => $coursePayload['school_ar_name'] ?? null,
            'course_name' => $coursePayload['name'] ?? ($coursePayload['course_name'] ?? null),
            'course_ar_name' => $coursePayload['ar_name'] ?? null,
            'course_type' => $coursePayload['course_type'] ?? $courseType,
            'country_name' => $coursePayload['country_name'] ?? null,
            'country_ar_name' => $coursePayload['country_ar_name'] ?? null,
            'country_flag' => $coursePayload['flag'] ?? null,
            'city_name' => $coursePayload['city_name'] ?? null,
            'city_ar_name' => $coursePayload['city_ar_name'] ?? null,
            'start_date' => $bookingData['start_date'] ?? null,
            'weeks' => $bookingData['weeks'] ?? null,
            'final_price' => $finalPrice,
            'original_price' => $coursePayload['old_price_sar'] ?? ($finalPrice ? round((float) $finalPrice * 1.2, 2) : null),
            'total' => $finalPrice,
            'currency' => $currency,
            'rating' => $coursePayload['rating'] ?? 4,
            'course_image' => $coursePayload['image'] ?? ($coursePayload['logo'] ?? null),
            'school_logo' => $coursePayload['logo'] ?? null,
            'created_at' => optional($lead->created_at)->toIso8601String(),
            'student_name' => $userData['name'] ?? $lead->name,
            'student_email' => $userData['email'] ?? $lead->email,
        ];
    }

    private function normalizeBookingCourseType(string $bookingType): string
    {
        return match ($bookingType) {
            'online_course' => 'online_courses',
            'summer_camp' => 'summer_camps',
            'training_course' => 'training_courses',
            default => 'language_courses',
        };
    }
}
