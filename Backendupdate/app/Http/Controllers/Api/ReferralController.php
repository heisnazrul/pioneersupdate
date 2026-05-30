<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Referral\ReferralService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function __construct(
        private readonly ReferralService $referralService,
    ) {
    }

    public function resolve(Request $request): JsonResponse
    {
        $code = trim((string) $request->query('code', ''));
        if ($code === '') {
            return response()->json(['success' => false, 'message' => 'Code is required.'], 422);
        }

        $resolved = $this->referralService->resolveCode($code);
        if (! $resolved) {
            return response()->json(['success' => false, 'message' => 'Invalid referral code.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($resolved, [
                'referral_link' => $this->referralService->referralLinkForCode($resolved['referral_code']),
            ]),
        ]);
    }

    public function trackClick(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'landing_path' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->referralService->trackClick(
            $data['code'],
            $request,
            $data['landing_path'] ?? null,
        );

        if (! $result) {
            return response()->json(['success' => false, 'message' => 'Invalid referral code.'], 404);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }
}
