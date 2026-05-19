<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseEnglishBookingController extends Controller
{
    private const OTP_PREFIX = 'ce-booking-otp:';
    private const VERIFY_PREFIX = 'ce-booking-verify:';

    public function sendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $phone = trim($data['phone']);
        $otp = (string) random_int(100000, 999999);

        Cache::put(self::OTP_PREFIX . $phone, $otp, now()->addMinutes(5));

        $payload = [
            'success' => true,
            'message' => 'Verification code sent successfully.',
        ];

        if (config('app.debug')) {
            $payload['debug_otp'] = $otp;
        }

        return response()->json($payload);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = trim($data['phone']);
        $cached = Cache::get(self::OTP_PREFIX . $phone);

        if (!$cached || !hash_equals((string) $cached, (string) $data['otp'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], 422);
        }

        Cache::forget(self::OTP_PREFIX . $phone);

        $token = Str::random(64);
        Cache::put(self::VERIFY_PREFIX . $token, $phone, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'verification_token' => $token,
        ]);
    }

    public function bookLanguageCourse(Request $request): JsonResponse
    {
        return $this->createLead($request, 'language_course');
    }

    public function bookOnlineCourse(Request $request): JsonResponse
    {
        return $this->createLead($request, 'online_course');
    }

    public function bookSummerCamp(Request $request): JsonResponse
    {
        return $this->createLead($request, 'summer_camp');
    }

    public function bookTrainingCourse(Request $request): JsonResponse
    {
        return $this->createLead($request, 'training_course');
    }

    public function setPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        if (!$user instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    private function createLead(Request $request, string $type): JsonResponse
    {
        $data = $request->validate([
            'course_type' => ['nullable', 'string', 'max:50'],
            'verification_token' => ['nullable', 'string', 'size:64'],
            'user_data.name' => ['required', 'string', 'max:255'],
            'user_data.email' => ['required', 'email', 'max:255'],
            'booking_data' => ['required', 'array'],
            'booking_data.course_id' => ['required'],
            'booking_data.start_date' => ['nullable', 'date'],
            'booking_data.weeks' => ['nullable', 'integer', 'min:1'],
            'booking_data.final_price' => ['nullable', 'numeric', 'min:0'],
            'booking_data.currency' => ['nullable', 'string', 'max:10'],
            'booking_data.accommodation_id' => ['nullable'],
            'booking_data.pickup_id' => ['nullable'],
            'booking_data.insurance_id' => ['nullable'],
            'booking_data.supplements_ids' => ['nullable', 'array'],
            'booking_data.user_age' => ['nullable', 'integer', 'min:1'],
            'booking_data.accommodation_weeks' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = Auth::guard('sanctum')->user();
        $createdToken = null;
        $phone = null;

        if (!$user instanceof User) {
            $verificationToken = $data['verification_token'] ?? null;
            $phone = $verificationToken ? Cache::pull(self::VERIFY_PREFIX . $verificationToken) : null;

            if (!$phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone verification is required.',
                ], 422);
            }

            $email = strtolower($data['user_data']['email']);
            $user = User::with('roles')
                ->where('email', $email)
                ->orWhere('phone', $phone)
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $data['user_data']['name'],
                    'email' => $email,
                    'phone' => $phone,
                    'status' => 'active',
                    'role' => 'lg_student',
                    'password' => Hash::make(Str::random(24)),
                ]);
            } else {
                $user->fill([
                    'name' => $user->name ?: $data['user_data']['name'],
                    'phone' => $user->phone ?: $phone,
                ])->save();
            }

            $roleId = Role::query()->where('slug', 'lg_student')->value('id');
            if ($roleId) {
                $user->roles()->syncWithoutDetaching([$roleId]);
            }

            $createdToken = $user->createToken('courseenglish-booking', ['app:courseenglish', 'role:lg_student'])->plainTextToken;
        }

        $lead = ContactSubmission::create([
            'name' => $data['user_data']['name'],
            'email' => strtolower($data['user_data']['email']),
            'phone' => $user?->phone ?: $phone,
            'subject' => 'CourseEnglish booking: ' . $type,
            'message' => json_encode([
                'source' => 'courseenglish_booking',
                'booking_type' => $type,
                'user_id' => $user?->id,
                'user_data' => $data['user_data'],
                'booking_data' => $data['booking_data'],
            ], JSON_UNESCAPED_UNICODE),
            'status' => 'pending',
        ]);

        $payload = [
            'success' => true,
            'booking_id' => $lead->id,
            'message' => 'Booking request received successfully.',
        ];

        if ($createdToken) {
            $payload['token'] = $createdToken;
        }

        return response()->json($payload, 201);
    }
}
