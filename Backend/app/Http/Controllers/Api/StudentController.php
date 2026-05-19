<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentStudent;
use App\Models\LanguageCourseCompare;
use App\Models\LanguageCourseBooking;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\OnlineCourseBooking;
use App\Models\LanguageCourseSummerCamp;
use App\Models\SummerCampBooking;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageCourseWishlist;
use App\Models\LanguageSchoolSupplement;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private array $studentRoles = ['lg_student', 'student', 'uni_student'];

    public function me(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $authUser = $request->user();
        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $targetId = (int) ($data['user_id'] ?? $authUser->id);
        if ($authUser->id !== $targetId && $authUser->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
            ], 403);
        }

        $student = User::find($targetId);
        if (!$student || !in_array($student->role, $this->studentRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a student.',
            ], 403);
        }

        $wishlistCount = LanguageCourseWishlist::query()
            ->where('user_id', $student->id)
            ->count();

        $compareCount = LanguageCourseCompare::query()
            ->where('user_id', $student->id)
            ->count();

        $invitedByAgent = AgentStudent::query()
            ->where('student_user_id', $student->id)
            ->latest('id')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'role' => $student->role,
                'status' => $student->status,
                'wishlist_count' => $wishlistCount,
                'compare_count' => $compareCount,
                'invited_by_agent' => (bool) $invitedByAgent,
                'onboarded_at' => $invitedByAgent?->onboarded_at,
            ],
        ]);
    }

    /**
     * Invoice data for a single language-course booking.
     */
    public function invoiceData(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $lang = $request->get('lang', 'en') === 'ar' ? 'ar' : 'en';

        $booking = LanguageCourseBooking::query()
            ->where('user_id', $user->id)
            ->with([
                'course.fees',
                'course.materialFees',
                'course.branch.school',
                'course.branch.registrationFees',
                'course.branch.highSeasonFees',
                'course.branch.insuranceFees',
                'course.branch.supplements',
                'accommodation',
                'pickup',
                'insurance',
            ])
            ->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        $payload = $this->buildLanguageInvoice($booking, $lang);

        return response()->json([
            'success' => true,
            'data' => $payload,
        ]);
    }
    public function bookings(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // 1. Language Course Bookings
        $languageBookings = LanguageCourseBooking::query()
            ->where('user_id', $user->id)
            ->with([
                'course.type',
                'course.branch.school',
                'course.branch.city.country',
            ])
            ->get()
            ->map(function ($booking) {
                $course = $booking->course;
                $branch = $course?->branch;
                $school = $branch?->school;
                $city = $branch?->city;
                $country = $city?->country;
                $gallery = $branch?->gallery_urls;
                $image = $this->buildMediaUrl(is_array($gallery) ? ($gallery[0] ?? null) : null)
                    ?: $this->buildMediaUrl($school?->logo)
                    ?: '/assets/hero.png';

                return [
                    'id' => $booking->id,
                    'type' => 'language_course',
                    'booking_id' => 'LG-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'course_name' => $course?->name ?? 'Unknown Course',
                    'course_ar_name' => $course?->ar_name,
                    'course_type' => $course?->type?->name,
                    'course_type_ar' => $course?->type?->ar_name,
                    'school_name' => $school?->name ?? 'Unknown School',
                    'school_ar_name' => $school?->ar_name,
                    'city_name' => $city?->name,
                    'city_ar_name' => $city?->ar_name,
                    'country_name' => $country?->name,
                    'country_ar_name' => $country?->ar_name,
                    'country_flag' => $this->buildMediaUrl($country?->flag),
                    'image' => $image,
                    'start_date' => $booking->start_date?->format('Y-m-d'),
                    'weeks' => $booking->weeks,
                    'status' => $booking->status,
                    'final_price' => $booking->final_price,
                    'original_price' => null,
                    'currency' => $booking->currency,
                    'created_at' => $booking->created_at?->toIso8601String(),
                ];
            });

        // 2. Online Course Bookings
        $onlineBookings = OnlineCourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['course.school', 'course.courseType'])
            ->get()
            ->map(function ($booking) {
                $course = $booking->course;
                $school = $course?->school;
                $image = $this->buildMediaUrl($course?->thumbnail)
                    ?: $this->buildMediaUrl($school?->logo)
                    ?: '/assets/hero.png';

                return [
                    'id' => $booking->id,
                    'type' => 'online_course',
                    'booking_id' => 'ON-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'course_name' => $course?->name ?? $course?->title ?? 'Unknown Online Course',
                    'course_ar_name' => $course?->ar_name,
                    'course_type' => $course?->courseType?->name,
                    'course_type_ar' => $course?->courseType?->ar_name,
                    'school_name' => $school?->name ?? 'Online',
                    'school_ar_name' => $school?->ar_name,
                    'city_name' => null,
                    'city_ar_name' => null,
                    'country_name' => null,
                    'country_ar_name' => null,
                    'country_flag' => null,
                    'image' => $image,
                    'start_date' => $booking->start_date?->format('Y-m-d'),
                    'weeks' => $booking->weeks,
                    'status' => $booking->status,
                    'final_price' => $booking->final_price,
                    'original_price' => null,
                    'currency' => $booking->currency ?? 'GBP',
                    'created_at' => $booking->created_at?->toIso8601String(),
                ];
            });

        // 3. Summer Camp Bookings
        $campBookings = SummerCampBooking::query()
            ->where('user_id', $user->id)
            ->with(['camp.branch.school', 'camp.branch.city.country', 'camp.courseType'])
            ->get()
            ->map(function ($booking) {
                $camp = $booking->camp;
                $branch = $camp?->branch;
                $school = $branch?->school;
                $city = $branch?->city;
                $country = $city?->country;
                $image = $this->buildMediaUrl($camp?->thumbnail)
                    ?: $this->buildMediaUrl($school?->logo)
                    ?: '/assets/hero.png';

                return [
                    'id' => $booking->id,
                    'type' => 'summer_camp',
                    'booking_id' => 'SC-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                    'course_name' => $camp?->name ?? $camp?->title ?? 'Unknown Camp',
                    'course_ar_name' => $camp?->ar_name,
                    'course_type' => $camp?->courseType?->name,
                    'course_type_ar' => $camp?->courseType?->ar_name,
                    'school_name' => $school?->name ?? 'Summer Camp',
                    'school_ar_name' => $school?->ar_name,
                    'city_name' => $city?->name,
                    'city_ar_name' => $city?->ar_name,
                    'country_name' => $country?->name,
                    'country_ar_name' => $country?->ar_name,
                    'country_flag' => $this->buildMediaUrl($country?->flag),
                    'image' => $image,
                    'start_date' => $booking->start_date?->format('Y-m-d'),
                    'weeks' => $booking->weeks,
                    'status' => $booking->status,
                    'final_price' => $booking->final_price,
                    'original_price' => null,
                    'currency' => $booking->currency ?? 'GBP',
                    'created_at' => $booking->created_at?->toIso8601String(),
                ];
            });

        $allBookings = $languageBookings
            ->concat($onlineBookings)
            ->concat($campBookings)
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $allBookings,
        ]);
    }

    private function buildLanguageInvoice(LanguageCourseBooking $booking, string $lang): array
    {
        $course = $booking->course;
        $branch = $course?->branch;
        $school = $branch?->school;
        $currency = $booking->currency ?? 'SAR';

        $lineItems = [];
        $add = function (string $code, string $en, string $ar, $amount, $qty = 1) use (&$lineItems, $currency, $lang) {
            if ($amount === null) {
                return;
            }
            $num = (float) $amount;
            $total = $num * ($qty ?: 1);
            $lineItems[] = [
                'code' => $code,
                'name' => $lang === 'ar' ? $ar : $en,
                'qty' => $qty,
                'amount' => round($num, 2),
                'total' => round($total, 2),
                'currency' => $currency,
            ];
        };

        // Tuition: choose fee for week_number <= weeks
        $tuitionFee = $course?->fees()
            ->where('week_number', '<=', $booking->weeks ?? 0)
            ->orderByDesc('week_number')
            ->first();
        if ($tuitionFee) {
            $add('tuition', 'Tuition', 'رسوم الدراسة', $tuitionFee->fee, $booking->weeks ?? 1);
        }

        // Registration fee (branch)
        $regFee = $branch?->registrationFees()->first();
        if ($regFee) {
            $add('registration', 'Registration fee', 'رسوم التسجيل', $regFee->amount);
        }

        // Material fee
        $mat = $course?->materialFees()->first();
        if ($mat) {
            $qty = $mat->billing_unit === 'week' ? ($booking->weeks ?? 1) * ($mat->billing_count ?? 1) : ($mat->billing_count ?? 1);
            $add('materials', 'Materials', 'مواد دراسية', $mat->amount, $qty);
        }

        // Accommodation
        $acc = $booking->accommodation;
        if ($acc) {
            $weeks = $booking->accommodation_weeks ?? $booking->weeks ?? 1;
            $base = $acc->price ?? $acc->fee_per_week ?? $acc->admin_charge ?? 0;
            $add('accommodation', 'Accommodation', 'السكن', $base, $weeks);
        }

        // Airport pickup
        $pickup = $booking->pickup;
        if ($pickup) {
            $add('pickup', 'Airport pickup', 'استقبال المطار', $pickup->price);
        }

        // Insurance
        $ins = $booking->insurance;
        if ($ins) {
            $qty = $ins->billing_unit === 'week' ? ($booking->weeks ?? 1) * ($ins->billing_count ?? 1) : ($ins->billing_count ?? 1);
            $add('insurance', 'Insurance', 'التأمين', $ins->amount, $qty);
        }

        // Supplements
        $suppIds = $booking->supplements_ids ?? [];
        if (!empty($suppIds)) {
            $supplements = LanguageSchoolSupplement::query()->whereIn('id', (array) $suppIds)->get();
            foreach ($supplements as $supp) {
                $qty = ($supp->billing_unit ?? null) === 'week' ? ($booking->weeks ?? 1) * ($supp->billing_count ?? 1) : ($supp->billing_count ?? 1);
                $add('supplement', $supp->name ?? 'Supplement', $supp->ar_name ?? 'إضافة', $supp->amount, $qty);
            }
        }

        // High season fee
        $highSeason = $branch?->highSeasonFees()
            ->where('week_start', '<=', $booking->weeks ?? 0)
            ->orderByDesc('week_start')
            ->first();
        if ($highSeason) {
            $add('high_season', 'High season fee', 'رسوم الموسم', $highSeason->fee, $booking->weeks ?? 1);
        }

        $subtotal = array_reduce($lineItems, fn($c, $i) => $c + ($i['total'] ?? 0), 0.0);
        $remaining = $booking->final_price ? (float) $booking->final_price - $subtotal : 0;
        if (abs($remaining) > 0.01) {
            $add('adjustment', 'Adjustment', 'تسوية', $remaining);
        }

        return [
            'booking_id' => $booking->booking_id ?? ('LG-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT)),
            'course' => [
                'name' => $lang === 'ar' ? ($course?->ar_name ?? $course?->name) : ($course?->name ?? $course?->ar_name),
                'weeks' => $booking->weeks,
                'start_date' => $booking->start_date?->format('Y-m-d'),
            ],
            'school' => [
                'name' => $lang === 'ar' ? ($school?->ar_name ?? $school?->name) : ($school?->name ?? $school?->ar_name),
                'logo' => $this->buildMediaUrl($school?->logo),
            ],
            'currency' => $currency,
            'line_items' => $lineItems,
            'totals' => [
                'amount' => $booking->final_price ?? $subtotal,
                'currency' => $currency,
            ],
        ];
    }


    private function buildMediaUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $trimmed = trim($path);
        if ($trimmed === '') {
            return null;
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $normalized = $trimmed;
        if (str_starts_with($trimmed, 'storage/')) {
            $normalized = "/{$trimmed}";
        }

        if (!str_starts_with($normalized, '/')) {
            $normalized = "/storage/{$normalized}";
        }

        return url($normalized);
    }
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
            ]
        ]);
    }
}
