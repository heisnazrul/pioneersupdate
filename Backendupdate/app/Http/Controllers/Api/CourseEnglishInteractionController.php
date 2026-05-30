<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseCompare;
use App\Models\LanguageCourseWishlist;
use App\Support\CourseEnglishApiSupport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CourseEnglishInteractionController extends Controller
{
    public function __construct(
        private readonly CourseEnglishApiSupport $support,
    ) {
    }

    public function resolve(Request $request): JsonResponse
    {
        $data = $request->validate([
            'wishlist' => ['nullable', 'array'],
            'wishlist.*.course_type' => ['required', Rule::in($this->courseTypes())],
            'wishlist.*.course_id' => ['required', 'integer', 'min:1'],
            'compare' => ['nullable', 'array'],
            'compare.*.course_type' => ['required', Rule::in($this->courseTypes())],
            'compare.*.course_id' => ['required', 'integer', 'min:1'],
            'compare.*.weeks' => ['nullable', 'integer', 'min:1', 'max:52'],
        ]);

        $wishlistItems = collect($data['wishlist'] ?? [])
            ->unique(fn ($item) => $item['course_type'] . ':' . $item['course_id'])
            ->map(fn ($item) => $this->mapInteractionItem($item['course_type'], (int) $item['course_id']))
            ->filter()
            ->values();

        $compareItems = collect($data['compare'] ?? [])
            ->unique(fn ($item) => $item['course_type'] . ':' . $item['course_id'])
            ->map(function ($item) {
                $mapped = $this->mapInteractionItem($item['course_type'], (int) $item['course_id']);
                if (! $mapped) {
                    return null;
                }

                return array_merge($mapped, [
                    'weeks' => (int) ($item['weeks'] ?? 12),
                ]);
            })
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'wishlist' => [
                'keys' => $wishlistItems->map(fn ($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
                'items' => $wishlistItems,
            ],
            'compare' => [
                'keys' => $compareItems->map(fn ($item) => $item['course_type'] . ':' . $item['course_id'])->values(),
                'items' => $compareItems,
            ],
        ]);
    }

    public function merge(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'wishlist' => ['nullable', 'array'],
            'wishlist.*.course_type' => ['required', Rule::in($this->courseTypes())],
            'wishlist.*.course_id' => ['required', 'integer', 'min:1'],
            'compare' => ['nullable', 'array'],
            'compare.*.course_type' => ['required', Rule::in($this->courseTypes())],
            'compare.*.course_id' => ['required', 'integer', 'min:1'],
            'compare.*.weeks' => ['nullable', 'integer', 'min:1', 'max:52'],
        ]);

        DB::transaction(function () use ($user, $data) {
            foreach ($data['wishlist'] ?? [] as $item) {
                LanguageCourseWishlist::firstOrCreate([
                    'user_id' => $user->id,
                    'course_type' => $item['course_type'],
                    'course_id' => (int) $item['course_id'],
                ]);
            }

            foreach ($data['compare'] ?? [] as $item) {
                LanguageCourseCompare::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_type' => $item['course_type'],
                        'course_id' => (int) $item['course_id'],
                    ],
                    [
                        'weeks' => (int) ($item['weeks'] ?? 12),
                    ],
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Guest interactions merged successfully.',
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function courseTypes(): array
    {
        return ['language_courses', 'online_courses', 'summer_camps', 'training_courses'];
    }

    private function mapInteractionItem(string $courseType, int $courseId): ?array
    {
        $modelClass = $this->support->courseTypeModel($courseType);
        if (! $modelClass) {
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
        if (! $course) {
            return null;
        }

        $payload = $this->support->coursePayload($courseType, $course);
        if (! $payload) {
            return null;
        }

        return [
            'course_type' => $courseType,
            'course_id' => $courseId,
            'course' => $payload,
        ];
    }
}
