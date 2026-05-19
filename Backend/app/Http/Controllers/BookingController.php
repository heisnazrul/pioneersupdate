<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Send OTP to WhatsApp number
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = $request->phone;

        // Generate 6-digit OTP
        $otpCode = (string) rand(100000, 999999);

        // Expiry 10 minutes
        $expiresAt = Carbon::now()->addMinutes(10);

        // Ideally, we associate OTP with a user if they exist, or just store it by phone if not.
        // Since UserOtp table requires user_id, we need a way to store temp OTPs or CREATE a temp user.
        // However, the requirement is to verify BEFORE account creation.
        // For simplicity, let's check if user exists. 
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            // Option: Create a temporary user or store in cache. 
            // Given the schema, let's use Cache or a temporary text-based storage if we don't want to pollute users table.
            // EXPECTATION: The frontend sends phone, we verifying it.
            // Let's use Cache for simpler non-user-bound OTPs, OR find/create a 'guest' user? 
            // Better: We will use the `user_otps` table but we need a user_id. 
            // Let's Look for user by phone. If not found, we can't use user_otps easily without creating the user first.
            // ALTERNATIVE: Create the User immediately as "inactive" or "guest"? 

            // DECISION: To keep it clean, we will use Cache for the OTP since the user might not exist yet.
            // key: booking_otp_{phone}
        }

        // Using Cache for simplicity and to support non-existing users
        $cacheKey = 'booking_otp_' . preg_replace('/[^0-9]/', '', $phone);
        cache([$cacheKey => $otpCode], $expiresAt);

        // TODO: Integrate actual WhatsApp SMS API here.
        // For now, Log it for local testing.
        Log::info("WhatsApp OTP for {$phone}: {$otpCode}");

        return response()->json([
            'message' => 'OTP sent successfully',
            'debug_otp' => $otpCode, // REMOVE IN PRODUCTION
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        $phone = $request->phone;
        $otp = $request->otp;
        $cacheKey = 'booking_otp_' . preg_replace('/[^0-9]/', '', $phone);
        $cachedOtp = cache($cacheKey);

        if ($cachedOtp && $cachedOtp === $otp) {
            // OTP Valid
            cache()->forget($cacheKey);

            // Generate a specialized token or just return success signal
            $verificationToken = Str::random(40);
            $tokenKey = 'verified_phone_' . $verificationToken;
            cache([$tokenKey => $phone], Carbon::now()->addMinutes(20)); // Verified status lasts 20 mins

            return response()->json([
                'success' => true,
                'message' => 'Phone verified successfully',
                'verification_token' => $verificationToken,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP',
        ], 400);
    }

    /**
     * Store Booking
     */
    /**
     * Resolve User (Find or Create)
     */
    private function resolveUser(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {
            return $user;
        }

        $verificationToken = $request->verification_token;
        if (!$verificationToken) {
            return null; // Should be handled by validation before calling this
        }

        $tokenKey = 'verified_phone_' . $verificationToken;
        $verifiedPhone = cache($tokenKey);

        if (!$verifiedPhone) {
            abort(401, 'Verification token expired or invalid');
        }

        // Find or Create User from Verified Phone
        $user = User::where('phone', $verifiedPhone)->orWhere('username', $verifiedPhone)->first();

        if (!$user) {
            $userData = $request->user_data ?? [];
            $name = $userData['name'] ?? 'User ' . substr($verifiedPhone, -4);
            $email = $userData['email'] ?? $verifiedPhone . '@placeholder.com';

            $user = User::create([
                'name' => $name,
                'username' => $verifiedPhone,
                'phone' => $verifiedPhone,
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
                'status' => 'active',
                'role' => 'lg_student',
            ]);
        }

        // Clear verification token only if we used it successfully
        cache()->forget($tokenKey);

        return $user;
    }

    /**
     * Store Language Course Booking
     */
    public function storeLanguageCourse(Request $request)
    {
        // 1. Validation
        $isLoggedIn = auth('api')->check();
        $request->validate([
            'verification_token' => $isLoggedIn ? 'nullable' : 'required|string',
            'booking_data' => 'required|array',
            'booking_data.course_id' => 'required',
            'booking_data.start_date' => 'required|date',
            'booking_data.weeks' => 'required|integer',
            'user_data' => 'sometimes|array',
        ]);

        // 2. Resolve User
        $user = $this->resolveUser($request);
        $verifiedPhone = $user->phone ?? $user->username;
        $bookingData = $request->booking_data;

        // 3. Create Booking
        DB::beginTransaction();
        try {
            // Ensure optional fields exist to avoid undefined index errors
            $accommodationId = $bookingData['accommodation_id'] ?? null;
            $pickupId = $bookingData['pickup_id'] ?? null;
            $insuranceId = $bookingData['insurance_id'] ?? null;
            $userAge = $bookingData['user_age'] ?? null;
            $accommodationWeeks = $bookingData['accommodation_weeks'] ?? null;
            $supplementsIds = isset($bookingData['supplements_ids']) ? json_encode($bookingData['supplements_ids']) : null;
            $currency = $bookingData['currency'] ?? 'GBP';

            $bookingId = DB::table('language_course_bookings')->insertGetId([
                'user_id' => $user->id,
                'course_id' => $bookingData['course_id'],
                'accommodation_id' => $accommodationId,
                'pickup_id' => $pickupId,
                'insurance_id' => $insuranceId,
                'whatsapp' => $verifiedPhone,
                'user_age' => $userAge,
                'weeks' => $bookingData['weeks'],
                'start_date' => $bookingData['start_date'],
                'accommodation_weeks' => $accommodationWeeks,
                'supplements_ids' => $supplementsIds,
                'final_price' => $bookingData['final_price'],
                'currency' => $currency,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $this->bookingResponse($user, $bookingId, 'Language Course booked successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Language Course Booking Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create booking', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store Online Course Booking
     */
    public function storeOnlineCourse(Request $request)
    {
        // 1. Validation
        $isLoggedIn = auth('api')->check();
        $request->validate([
            'verification_token' => $isLoggedIn ? 'nullable' : 'required|string',
            'booking_data' => 'required|array',
            'booking_data.course_id' => 'required',
            'booking_data.start_date' => 'required|date',
            'booking_data.weeks' => 'required|integer',
            'user_data' => 'sometimes|array',
        ]);

        // 2. Resolve User
        $user = $this->resolveUser($request);
        $verifiedPhone = $user->phone ?? $user->username;
        $bookingData = $request->booking_data;

        // 3. Create Booking
        DB::beginTransaction();
        try {
            $bookingId = DB::table('online_course_bookings')->insertGetId([
                'user_id' => $user->id,
                'course_id' => $bookingData['course_id'],
                'whatsapp' => $verifiedPhone,
                'weeks' => $bookingData['weeks'],
                'start_date' => $bookingData['start_date'],
                'final_price' => $bookingData['final_price'] ?? 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $this->bookingResponse($user, $bookingId, 'Online Course booked successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Online Course Booking Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create booking', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store Summer Camp Booking
     */
    /**
     * Store Summer Camp Booking
     */
    public function storeSummerCamp(Request $request)
    {
        // 1. Validation
        $isLoggedIn = auth('api')->check();
        $request->validate([
            'verification_token' => $isLoggedIn ? 'nullable' : 'required|string',
            'booking_data' => 'required|array',
            'booking_data.course_id' => 'required', // This is actually the summer_camp_id
            'booking_data.start_date' => 'required|date',
            'booking_data.weeks' => 'required|integer',
            'user_data' => 'sometimes|array',
        ]);

        // 2. Resolve User
        $user = $this->resolveUser($request);
        $verifiedPhone = $user->phone ?? $user->username;
        $bookingData = $request->booking_data;

        // 3. Create Booking
        DB::beginTransaction();
        try {
            $supplements = isset($bookingData['special_supplements']) ? json_encode($bookingData['special_supplements']) : null;
            $campId = $bookingData['camp_id'] ?? $bookingData['course_id'];

            $bookingId = DB::table('summer_camps_bookings')->insertGetId([
                'user_id' => $user->id,
                'camp_id' => $campId,
                'whatsapp' => $verifiedPhone,
                'weeks' => $bookingData['weeks'],
                'start_date' => $bookingData['start_date'],
                'special_supplements' => $supplements,
                'final_price' => $bookingData['final_price'] ?? 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $this->bookingResponse($user, $bookingId, 'Summer Camp booked successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Summer Camp Booking Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create booking', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store Training Course Booking
     */
    public function storeTrainingCourse(Request $request)
    {
        // 1. Validation
        $isLoggedIn = auth('api')->check();
        $request->validate([
            'verification_token' => $isLoggedIn ? 'nullable' : 'required|string',
            'booking_data' => 'required|array',
            'booking_data.course_id' => 'required',
            'booking_data.start_date' => 'required|date',
            // Training courses might be fixed duration, so 'weeks' might be optional or fixed 1
            'user_data' => 'sometimes|array',
        ]);

        // 2. Resolve User
        $user = $this->resolveUser($request);
        $verifiedPhone = $user->phone ?? $user->username;
        $bookingData = $request->booking_data;

        // 3. Create Booking
        DB::beginTransaction();
        try {
            // Training courses usually have a specific duration, not per week, but table might expect 'weeks' or just storing it for consistency
            $weeks = $bookingData['weeks'] ?? 1;

            // Check if table exists (it should via migration)
            // 'training_course_bookings'

            $bookingId = DB::table('training_course_bookings')->insertGetId([
                'user_id' => $user->id,
                'course_id' => $bookingData['course_id'],
                'whatsapp' => $verifiedPhone,
                'start_date' => $bookingData['start_date'],
                'final_price' => $bookingData['final_price'] ?? 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return $this->bookingResponse($user, $bookingId, 'Training Course booked successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Training Course Booking Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create booking', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper to format response
     */
    private function bookingResponse($user, $bookingId, $message)
    {
        // Generate Token for Auto-Login if needed
        $token = $user->createToken('BookingAuth')->accessToken;

        return response()->json([
            'success' => true,
            'message' => $message,
            'booking_id' => $bookingId,
            'user_id' => $user->id,
            'is_new_user' => $user->wasRecentlyCreated,
            'token' => $token,
        ]);
    }

    /**
     * Set Password (for new users)
     */
    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user(); // Get authenticated user
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password set successfully',
            'user' => $user
        ]);
    }
}
