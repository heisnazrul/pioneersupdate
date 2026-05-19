<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Support\SystemSettings;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private const OTP_TTL_SECONDS = 300;
    private const OTP_LENGTH = 6;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'role' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'uni_student',
                'status' => 'active'
            ]);

            // Link existing guest applications
            $apps = \App\Models\Application::where('email', $user->email)->whereNull('user_id')->get();
            foreach ($apps as $app) {
                $app->update(['user_id' => $user->id]);

                // Sync profile data if missing on user
                // Assuming fields map: phone->phone, etc.
                if (!$user->phone && $app->phone) {
                    $user->phone = $app->phone;
                    $user->save();
                }
            }

            $tokenResult = $user->createToken('api');

            return response()->json([
                'success' => true,
                'token_type' => 'Bearer',
                'access_token' => $tokenResult->accessToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($user->status === 'banned') {
            return response()->json([
                'success' => false,
                'message' => 'This account has been banned.',
            ], 403);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This account is inactive.',
            ], 403);
        }

        if (SystemSettings::requiresEmailVerification($user->role) && !$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification required.',
            ], 403);
        }

        $tokenResult = $user->createToken('api');
        $user->forceFill(['last_login_at' => now()])->save();

        return response()->json([
            'success' => true,
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $token = $user->token();
        if ($token) {
            $token->revoke();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ]);
    }

    public function sendWhatsappOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number',
            ], 422);
        }

        $phone = trim((string) $request->input('phone'));
        $digitsOnly = preg_replace('/\D+/', '', $phone) ?? '';
        $variants = array_filter([
            $phone,
            str_replace(' ', '', $phone),
            $digitsOnly ? ('+' . $digitsOnly) : null,
            $digitsOnly,
            $digitsOnly ? ('00' . $digitsOnly) : null,
        ]);

        if ($digitsOnly !== '') {
            if (str_starts_with($digitsOnly, '880')) {
                $local = substr($digitsOnly, 3);
                if ($local !== '') {
                    $variants[] = '0' . $local;
                }
            }

            if (str_starts_with($digitsOnly, '0')) {
                $variants[] = ltrim($digitsOnly, '0');
            }
        }

        $variants = array_values(array_unique(array_filter($variants)));

        $user = User::query()
            ->whereIn('role', ['lg_student', 'uni_student'])
            ->where(function ($query) use ($variants, $digitsOnly) {
                $query->whereIn('phone', $variants)
                    ->orWhereHas('profile', function ($q) use ($variants, $digitsOnly) {
                        $q->whereIn('alt_phone_e164', $variants);

                        if ($digitsOnly !== '') {
                            $q->orWhereRaw(
                                "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(alt_phone_e164, '+', ''), ' ', ''), '-', ''), '(', ''), ')', '') = ?",
                                [$digitsOnly]
                            );
                        }
                    });

                if ($digitsOnly !== '') {
                    $query->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone, '+', ''), ' ', ''), '-', ''), '(', ''), ')', '') = ?",
                        [$digitsOnly]
                    );
                }
            })
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number',
            ], 404);
        }

        if ($user->status === 'banned') {
            return response()->json([
                'success' => false,
                'message' => 'This account has been banned.',
            ], 403);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This account is inactive.',
            ], 403);
        }

        $otp = $this->issueWhatsappOtp($user);

        if (!$this->sendWhatsappMessage($phone, $otp->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to send OTP',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent',
            'expires_in' => self::OTP_TTL_SECONDS,
        ]);
    }

    public function verifyWhatsappOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'max:30'],
            'otp' => ['required', 'digits:' . self::OTP_LENGTH],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 422);
        }

        $phone = trim((string) $request->input('phone'));
        $digitsOnly = preg_replace('/\D+/', '', $phone) ?? '';
        $variants = array_filter([
            $phone,
            str_replace(' ', '', $phone),
            $digitsOnly ? ('+' . $digitsOnly) : null,
            $digitsOnly,
            $digitsOnly ? ('00' . $digitsOnly) : null,
        ]);

        if ($digitsOnly !== '') {
            if (str_starts_with($digitsOnly, '880')) {
                $local = substr($digitsOnly, 3);
                if ($local !== '') {
                    $variants[] = '0' . $local;
                }
            }

            if (str_starts_with($digitsOnly, '0')) {
                $variants[] = ltrim($digitsOnly, '0');
            }
        }

        $variants = array_values(array_unique(array_filter($variants)));

        $user = User::query()
            ->whereIn('role', ['lg_student', 'uni_student'])
            ->where(function ($query) use ($variants, $digitsOnly) {
                $query->whereIn('phone', $variants)
                    ->orWhereHas('profile', function ($q) use ($variants, $digitsOnly) {
                        $q->whereIn('alt_phone_e164', $variants);

                        if ($digitsOnly !== '') {
                            $q->orWhereRaw(
                                "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(alt_phone_e164, '+', ''), ' ', ''), '-', ''), '(', ''), ')', '') = ?",
                                [$digitsOnly]
                            );
                        }
                    });

                if ($digitsOnly !== '') {
                    $query->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone, '+', ''), ' ', ''), '-', ''), '(', ''), ')', '') = ?",
                        [$digitsOnly]
                    );
                }
            })
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number',
            ], 404);
        }

        $otp = UserOtp::query()
            ->where('user_id', $user->id)
            ->where('purpose', 'whatsapp_login')
            ->where('code', (string) $request->input('otp'))
            ->whereNull('used_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('id')
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 422);
        }

        $otp->markAsUsed();

        $tokenResult = $user->createToken('api');
        $user->forceFill(['last_login_at' => now()])->save();

        return response()->json([
            'success' => true,
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    private function issueWhatsappOtp(User $user): UserOtp
    {
        $user->otps()
            ->where('purpose', 'whatsapp_login')
            ->whereNull('used_at')
            ->delete();

        $code = str_pad((string) random_int(0, (10 ** self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        return $user->otps()->create([
            'purpose' => 'whatsapp_login',
            'code' => $code,
            'channel' => 'whatsapp',
            'expires_at' => CarbonImmutable::now()->addSeconds(self::OTP_TTL_SECONDS),
            'meta' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);
    }

    private function sendWhatsappMessage(string $phone, string $code): bool
    {
        $settings = SystemSettings::twilio();
        $sid = $settings['account_sid'] ?? null;
        $token = $settings['auth_token'] ?? null;
        $from = $settings['from_whatsapp'] ?? null;

        if (!$sid || !$token || !$from) {
            return false;
        }

        $to = str_starts_with($phone, 'whatsapp:') ? $phone : 'whatsapp:' . $phone;

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => $to,
                'Body' => "Your login code is {$code}",
            ]);

        return $response->successful();
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|string',
            'nationality' => 'nullable|string',
            'dob' => 'nullable|date',
            'studyLevel' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Update User phone
            if ($request->has('phone')) {
                $user->phone = $request->phone;
                $user->save();
            }

            // Update/Create Profile
            $profileData = [
                'nationality' => $request->nationality,
                'study_level' => $request->studyLevel,
            ];

            if ($request->has('dob')) {
                $profileData['date_of_birth'] = $request->dob;
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'user' => $user->load('profile')
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }
}
