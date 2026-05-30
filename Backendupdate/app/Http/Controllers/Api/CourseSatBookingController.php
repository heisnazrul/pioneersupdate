<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LanguageCourse\LanguageCourseBookingService;
use App\Services\OnlineCourse\OnlineCourseBookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseSatBookingController extends Controller
{
    public function __construct(
        private readonly LanguageCourseBookingService $bookingService,
        private readonly OnlineCourseBookingService $onlineBookingService,
    ) {
    }

    public function storeLanguageCourse(Request $request): JsonResponse
    {
        $data = $request->validate([
            'selection' => ['required', 'array'],
            'selection.course_id' => ['required', 'integer', 'min:1'],
            'selection.weeks' => ['required', 'integer', 'min:1'],
            'selection.start_date' => ['required', 'date'],
            'selection.accommodation_id' => ['nullable'],
            'selection.pickup_id' => ['nullable', 'integer'],
            'selection.insurance_ids' => ['nullable', 'array'],
            'selection.insurance_ids.*' => ['integer', 'min:1'],
            'selection.supplement_ids' => ['nullable', 'array'],
            'selection.supplement_ids.*' => ['integer', 'min:1'],
            'selection.extras' => ['nullable', 'array'],
            'selection.extras.*' => ['integer', 'min:1'],
            'selection.acc_age' => ['nullable', 'integer', 'min:1'],
            'display_currency' => ['required', 'string', 'max:10'],
            'user_data' => ['nullable', 'array'],
            'user_data.name' => ['nullable', 'string', 'max:255'],
            'user_data.email' => ['nullable', 'email', 'max:255'],
            'user_data.phone' => ['nullable', 'string', 'max:50'],
            'user_data.password' => ['nullable', 'string', 'min:8'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'agent_student_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = $this->bookingService->resolveAuthenticatedUser($request->bearerToken());

        if (! $user && empty($data['user_data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication or guest contact details are required.',
            ], 422);
        }

        $result = $this->bookingService->create($data, $user);

        return response()->json($result, 201);
    }

    public function storeOnlineCourse(Request $request): JsonResponse
    {
        $data = $request->validate([
            'selection' => ['required', 'array'],
            'selection.course_id' => ['required', 'integer', 'min:1'],
            'selection.weeks' => ['nullable', 'integer', 'min:1'],
            'selection.start_date' => ['required', 'date'],
            'display_currency' => ['required', 'string', 'max:10'],
            'user_data' => ['nullable', 'array'],
            'user_data.name' => ['nullable', 'string', 'max:255'],
            'user_data.email' => ['nullable', 'email', 'max:255'],
            'user_data.phone' => ['nullable', 'string', 'max:50'],
            'user_data.password' => ['nullable', 'string', 'min:8'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'agent_student_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = $this->onlineBookingService->resolveAuthenticatedUser($request->bearerToken());

        if (! $user && empty($data['user_data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication or guest contact details are required.',
            ], 422);
        }

        $result = $this->onlineBookingService->create($data, $user);

        return response()->json($result, 201);
    }
}
