<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\LanguageCourseCompare;
use App\Models\LanguageCourseWishlist;
use App\Models\LanguageSchoolCourse;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseEnglishAccountController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support
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
        $items = LanguageCourseCompare::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get()
            ->map(fn (LanguageCourseCompare $item) => $this->mapInteractionItem($item->course_type, (int) $item->course_id))
            ->filter()
            ->values();

        return response()->json([
            'keys' => $items->map(fn ($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
            'items' => $items,
        ]);
    }

    public function compareAdd(Request $request): JsonResponse
    {
        $data = $this->validateInteractionPayload($request);

        LanguageCourseCompare::firstOrCreate([
            'user_id' => $request->user()->id,
            'course_type' => $data['course_type'],
            'course_id' => $data['course_id'],
        ]);

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
            'data' => $this->studentPayload($user),
        ]);
    }

    public function studentUpdateProfile(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('profile');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:4096'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'postal_code' => ['nullable', 'string', 'max:100'],
            'alt_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        $profile = $user->profile ?: new UserProfile(['user_id' => $user->id]);
        $country = !empty($data['country']) ? $this->findCountry($data['country']) : null;
        $city = !empty($data['city']) ? $this->findCity($data['city']) : null;

        $profile->fill([
            'date_of_birth' => $data['birth_date'] ?? $profile->date_of_birth,
            'gender' => $data['gender'] ?? $profile->gender,
            'current_country_id' => $country?->id ?? $profile->current_country_id,
            'current_city_id' => $city?->id ?? $profile->current_city_id,
            'address_line' => $data['address'] ?? $profile->address_line,
            'postal_code' => $data['postal_code'] ?? $profile->postal_code,
            'alt_phone_e164' => $data['alt_phone'] ?? $profile->alt_phone_e164,
        ]);
        $profile->save();

        $user->unsetRelation('profile');
        $user->loadMissing([
            'profile.nationalityCountry',
            'profile.currentCountry',
            'profile.currentCity',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $this->studentPayload($user),
        ]);
    }

    public function studentBookings(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    public function courseEnglishStudentBookings(): JsonResponse
    {
        return $this->studentBookings();
    }

    private function validateInteractionPayload(Request $request): array
    {
        return $request->validate([
            'course_type' => ['required', Rule::in(['language_courses', 'online_courses', 'summer_camps', 'training_courses'])],
            'course_id' => ['required', 'integer', 'min:1'],
        ]);
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

    private function studentPayload(User $user): array
    {
        $profile = $user->profile;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->primaryFrontendRoleForApp(User::APP_COURSEENGLISH) ?: $user->role,
            'avatar' => $this->support->toPublicUrl($user->avatar),
            'status' => $user->status,
            'birth_date' => optional($profile?->date_of_birth)->format('Y-m-d'),
            'gender' => $profile?->gender,
            'country' => $profile?->currentCountry?->name,
            'city' => $profile?->currentCity?->name,
            'address' => $profile?->address_line,
            'postal_code' => $profile?->postal_code,
            'national_id' => null,
            'alt_phone' => $profile?->alt_phone_e164,
        ];
    }

    private function findCountry(string $value): ?Country
    {
        return Country::query()
            ->where('name', $value)
            ->orWhere('ar_name', $value)
            ->orWhere('slug', \Illuminate\Support\Str::slug($value))
            ->first();
    }

    private function findCity(string $value): ?City
    {
        return City::query()
            ->where('name', $value)
            ->orWhere('ar_name', $value)
            ->orWhere('slug', \Illuminate\Support\Str::slug($value))
            ->first();
    }
}
