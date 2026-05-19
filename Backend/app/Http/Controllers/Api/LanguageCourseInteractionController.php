<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseCompare;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageCourseWishlist;
use App\Models\LanguageSchoolCourse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LanguageCourseInteractionController extends Controller
{
    private function toPublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $base = rtrim(config('app.url'), '/');
        $clean = ltrim($path, '/');

        if (Str::startsWith($clean, 'storage/')) {
            return $base . '/' . $clean;
        }

        return $base . '/storage/' . $clean;
    }

    private function normalizeCourseType(?string $type): ?string
    {
        $raw = Str::of((string) $type)->trim()->lower()->replace('-', '_')->value();

        return match ($raw) {
            'language_school_course', 'language_school_courses', 'language_course', 'language_courses' => 'language_courses',
            'summer_camp', 'summer_camps', 'language_course_summer_camp', 'language_course_summer_camps' => 'summer_camps',
            'online_course', 'online_courses', 'language_course_online_course', 'language_course_online_courses' => 'online_courses',
            'training_course', 'training_courses', 'language_course_training_course', 'language_course_training_courses' => 'training_courses',
            default => null,
        };
    }

    private function resolveCourseModel(string $normalizedType, int $courseId): array
    {
        return match ($normalizedType) {
            'language_courses' => [
                'model' => LanguageSchoolCourse::query()
                    ->with([
                        'branch:id,language_school_id,city_id,slug,gallery_urls',
                        'branch.school:id,name,ar_name,slug,logo,rating',
                        'branch.city:id,name,ar_name,slug,country_id',
                        'branch.city.country:id,name,ar_name,flag',
                        'fees:id,language_school_course_id,fee,week_number',
                    ])
                    ->find($courseId),
                'id_column' => 'id',
            ],
            'summer_camps' => [
                'model' => LanguageCourseSummerCamp::query()
                    ->with([
                        'branch:id,language_school_id,city_id,slug,gallery_urls',
                        'branch.school:id,name,ar_name,slug,logo,rating',
                        'branch.city:id,name,ar_name,slug',
                        'branch.city.country:id,name,ar_name,flag',
                    ])
                    ->find($courseId),
                'id_column' => 'id',
            ],
            'online_courses' => [
                'model' => LanguageCourseOnlineCourse::query()
                    ->with([
                        'school:id,name,ar_name,slug,logo,rating',
                    ])
                    ->find($courseId),
                'id_column' => 'id',
            ],
            'training_courses' => [
                'model' => LanguageCourseTrainingCourse::query()
                    ->with([
                        'school:id,name,ar_name,slug,logo,rating',
                        'branch:id,language_school_id,city_id,slug,gallery_urls',
                        'branch.city:id,name,ar_name,slug',
                        'branch.city.country:id,name,ar_name,flag',
                    ])
                    ->find($courseId),
                'id_column' => 'id',
            ],
            default => ['model' => null, 'id_column' => 'id'],
        };
    }

    private function languageCourseSlug($branch, $school, $city): ?string
    {
        if ($branch && $branch->slug && !is_numeric($branch->slug)) {
            return $branch->slug;
        }

        if ($school && $school->slug) {
            return $school->slug . ($city ? '-' . $city->slug : '');
        }

        return null;
    }

    private function mapCoursePayload(string $normalizedType, $course): ?array
    {
        if (!$course) {
            return null;
        }

        if ($normalizedType === 'language_courses') {
            $branch = $course->branch;
            $school = $branch?->school;
            $city = $branch?->city;
            $country = $city?->country;
            $gallery = $branch?->gallery_urls;
            if (is_string($gallery)) {
                $decoded = json_decode($gallery, true);
                $gallery = is_array($decoded) ? $decoded : [$gallery];
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            $firstGallery = null;
            foreach ($gallery as $g) {
                $url = $this->toPublicUrl($g);
                if ($url) {
                    $firstGallery = $url;
                    break;
                }
            }
            $image = $firstGallery ?: $this->toPublicUrl($school?->logo);
            if (!$image && $course->image) {
                $image = $this->toPublicUrl($course->image);
            }

            $fees = collect($course->fees ?? []);
            $minFee = $fees->min('fee');
            $currency = $school?->currency_code ?? 'GBP';

            return [
                'course_type' => $normalizedType,
                'id' => $course->id,
                'course_id' => $course->id,
                'name' => $course->name,
                'ar_name' => $course->ar_name,
                'school_name' => $school?->name,
                'school_ar_name' => $school?->ar_name,
                'city_name' => $city?->name,
                'city_ar_name' => $city?->ar_name,
                'country_name' => $country?->name,
                'country_ar_name' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
                'slug' => $this->languageCourseSlug($branch, $school, $city),
                'url' => '/language-institutes/' . $this->languageCourseSlug($branch, $school, $city) . '?course_id=' . $course->id,
                'image' => $image,
                'gallery_urls' => $gallery,
                'lessons_per_week' => $course->lessons_per_week,
                'study_time' => $course->study_time,
                'required_level' => $course->required_level,
                'start_date' => $course->start_day,
                'currency_code' => $currency,
                'price' => $minFee ? (float) $minFee : null,
            ];
        }

        if ($normalizedType === 'online_courses') {
            $school = $course->school;
            // online courses don't have city/country; fallback to first city in DB
            static $firstCity = null;
            if ($firstCity === null) {
                $firstCity = \App\Models\City::query()->with('country')->orderBy('id')->first();
            }
            $city = $firstCity;
            $country = $firstCity?->country;
            $image = $this->toPublicUrl($course->thumbnail) ?: $this->toPublicUrl($school?->logo);

            return [
                'course_type' => $normalizedType,
                'id' => $course->id,
                'course_id' => $course->id,
                'name' => $course->name,
                'ar_name' => $course->ar_name,
                'school_name' => $school?->name,
                'school_ar_name' => $school?->ar_name,
                'slug' => $school?->slug,
                'url' => $school?->slug ? '/online-course/' . $school->slug . '?course_id=' . $course->id : null,
                'image' => $image,
                'lessons_per_week' => $course->lessons_per_week,
                'study_time' => $course->study_time,
                'required_level' => $course->required_level,
                'start_date' => $course->start_date,
                'currency_code' => $course->currency_code,
                'price' => $course->fee_amount ? (float) $course->fee_amount : null,
                'city_name' => $city?->name,
                'city_ar_name' => $city?->ar_name,
                'country_name' => $country?->name,
                'country_ar_name' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
            ];
        }

        if ($normalizedType === 'summer_camps') {
            $branch = $course->branch;
            $school = $branch?->school;
            $city = $branch?->city;
            $country = $city?->country;
            $gallery = $branch?->gallery_urls;
            if (is_string($gallery)) {
                $decoded = json_decode($gallery, true);
                $gallery = is_array($decoded) ? $decoded : [$gallery];
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            $firstGallery = null;
            foreach ($gallery as $g) {
                $url = $this->toPublicUrl($g);
                if ($url) {
                    $firstGallery = $url;
                    break;
                }
            }

            $image = $this->toPublicUrl($course->thumbnail) ?: $firstGallery ?: $this->toPublicUrl($school?->logo);

            return [
                'course_type' => $normalizedType,
                'id' => $course->id,
                'course_id' => $course->id,
                'name' => $course->name,
                'ar_name' => $course->ar_name,
                'school_name' => $school?->name,
                'school_ar_name' => $school?->ar_name,
                'city_name' => $city?->name,
                'city_ar_name' => $city?->ar_name,
                'country_name' => $country?->name,
                'country_ar_name' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
                'slug' => $course->slug,
                'url' => '/summer-programs',
                'image' => $image,
                'gallery_urls' => $gallery,
                'lessons_per_week' => $course->lessons_per_week,
                'study_time' => $course->study_time,
                'required_level' => $course->required_level,
                'start_date' => $course->start_date,
                'currency_code' => $course->currency_code,
                'price' => $course->fee_amount ? (float) $course->fee_amount : null,
            ];
        }

        if ($normalizedType === 'training_courses') {
            $branch = $course->branch;
            $city = $branch?->city;
            $country = $city?->country;
            $school = $course->school ?: $branch?->school;
            $image = $this->toPublicUrl($course->thumbnail);
            if (!$image && !empty($branch?->gallery_urls)) {
                $image = $this->toPublicUrl($branch->gallery_urls[0]);
            }

            return [
                'course_type' => $normalizedType,
                'id' => $course->id,
                'course_id' => $course->id,
                'name' => $course->name,
                'ar_name' => $course->ar_name,
                'school_name' => $school?->name,
                'school_ar_name' => $school?->ar_name,
                'city_name' => $city?->name,
                'city_ar_name' => $city?->ar_name,
                'country_name' => $country?->name,
                'country_ar_name' => $country?->ar_name,
                'flag' => $this->toPublicUrl($country?->flag),
                'slug' => 'training-course-' . $course->id,
                'url' => '/training-and-professional-courses',
                'image' => $image,
                'lessons_per_week' => $course->lessons_per_week,
                'study_time' => $course->study_time,
                'required_level' => $course->required_level,
                'start_date' => $course->start_date,
                'currency_code' => $country?->currency_code ?: 'GBP',
                'price' => $course->fee_amount ? (float) $course->fee_amount : null,
            ];
        }

        return null;
    }

    private function validatePayload(Request $request): array
    {
        $data = $request->validate([
            'course_type' => ['required', 'string'],
            'course_id' => ['required', 'integer', 'min:1'],
        ]);

        $normalizedType = $this->normalizeCourseType($data['course_type']);
        if (!$normalizedType) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid course_type. Use language_courses, summer_camps, online_courses, or training_courses.',
            ], 422));
        }

        return [
            'course_type' => $normalizedType,
            'course_id' => (int) $data['course_id'],
        ];
    }

    public function wishlistList(Request $request): JsonResponse
    {
        $items = LanguageCourseWishlist::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        $payload = $items->map(function (LanguageCourseWishlist $row) {
            $resolved = $this->resolveCourseModel($row->course_type, (int) $row->course_id);
            $mapped = $this->mapCoursePayload($row->course_type, $resolved['model']);

            return [
                'id' => $row->id,
                'course_type' => $row->course_type,
                'course_id' => (int) $row->course_id,
                'course' => $mapped,
                'created_at' => $row->created_at,
            ];
        })->filter(fn($item) => !is_null($item['course']))->values();

        return response()->json([
            'total' => $payload->count(),
            'items' => $payload,
            'keys' => $payload->map(fn($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
        ]);
    }

    public function wishlistAdd(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);
        $resolved = $this->resolveCourseModel($data['course_type'], $data['course_id']);
        if (!$resolved['model']) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $row = LanguageCourseWishlist::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'course_type' => $data['course_type'],
            'course_id' => $data['course_id'],
        ]);

        return response()->json([
            'message' => 'Added to wishlist.',
            'item' => [
                'id' => $row->id,
                'course_type' => $row->course_type,
                'course_id' => (int) $row->course_id,
            ],
        ]);
    }

    public function wishlistRemove(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);

        LanguageCourseWishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('course_type', $data['course_type'])
            ->where('course_id', $data['course_id'])
            ->delete();

        return response()->json(['message' => 'Removed from wishlist.']);
    }

    public function compareList(Request $request): JsonResponse
    {
        $items = LanguageCourseCompare::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        $payload = $items->map(function (LanguageCourseCompare $row) {
            $resolved = $this->resolveCourseModel($row->course_type, (int) $row->course_id);
            $mapped = $this->mapCoursePayload($row->course_type, $resolved['model']);

            return [
                'id' => $row->id,
                'course_type' => $row->course_type,
                'course_id' => (int) $row->course_id,
                'course' => $mapped,
                'created_at' => $row->created_at,
            ];
        })->filter(fn($item) => !is_null($item['course']))->values();

        return response()->json([
            'total' => $payload->count(),
            'items' => $payload,
            'keys' => $payload->map(fn($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
        ]);
    }

    public function compareAdd(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);
        $resolved = $this->resolveCourseModel($data['course_type'], $data['course_id']);
        if (!$resolved['model']) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        $row = LanguageCourseCompare::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'course_type' => $data['course_type'],
            'course_id' => $data['course_id'],
        ]);

        return response()->json([
            'message' => 'Added to compare.',
            'item' => [
                'id' => $row->id,
                'course_type' => $row->course_type,
                'course_id' => (int) $row->course_id,
            ],
        ]);
    }

    public function compareRemove(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);

        LanguageCourseCompare::query()
            ->where('user_id', $request->user()->id)
            ->where('course_type', $data['course_type'])
            ->where('course_id', $data['course_id'])
            ->delete();

        return response()->json(['message' => 'Removed from compare.']);
    }
}
