<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentStudent;
use App\Models\ContactSubmission;
use App\Models\Role;
use App\Models\User;
use App\Services\Referral\ReferralService;
use App\Services\Profile\ProfileService;
use App\Services\Payout\PayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CourseEnglishAgentController extends Controller
{
    public function __construct(
        private readonly ReferralService $referralService,
        private readonly ProfileService $profileService,
        private readonly PayoutService $payoutService,
    ) {
    }

    public function me(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $user->loadMissing([
            'profile.nationalityCountry',
            'profile.currentCountry',
            'profile.currentCity',
        ]);

        return response()->json([
            'success' => true,
            'data' => array_merge(
                $this->profileService->payload($user, $agent),
                ['referral_link' => $this->referralLink($agent)],
            ),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $data = $request->validate($this->profileService->profileRules($user));
        $user = $this->profileService->update($user, $data, $request);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => array_merge(
                $this->profileService->payload($user, $agent),
                ['referral_link' => $this->referralLink($agent)],
            ),
        ]);
    }

    public function payouts(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->payoutService->listForUser($user),
        ]);
    }

    public function createPayout(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $data = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $agent = $this->agentRecord($user);
        $payout = $this->payoutService->createForAgent(
            $user->fresh(['profile']),
            $agent,
            isset($data['amount']) ? (float) $data['amount'] : null,
            $data['notes'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Payout request submitted successfully.',
            'data' => $this->payoutService->serialize($payout),
        ], 201);
    }

    public function overview(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $studentsTotal = AgentStudent::query()->where('agent_id', $agent->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'students_total' => $studentsTotal,
                'referrals_active' => $agent->referral_code ? 1 : 0,
                'commission_percent' => $agent->commission_percent,
                'status' => $agent->status,
                'referral_code' => $agent->referral_code,
                'referral_link' => $this->referralLink($agent),
                'signups' => $studentsTotal,
            ],
        ]);
    }

    public function referrals(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $stats = $this->referralService->agentReferralStats($agent);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function updateReferralCode(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $data = $request->validate([
            'referral_code' => ['required', 'string', 'max:20'],
        ]);

        $agent = $this->agentRecord($user);
        $agent = $this->referralService->updateAgentReferralCode($agent, $data['referral_code']);

        return response()->json([
            'success' => true,
            'data' => $this->referralService->agentReferralStats($agent),
        ]);
    }

    public function refreshReferral(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $agent->update([
            'referral_code' => Str::upper(Str::random(8)),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'referral_code' => $agent->referral_code,
                'referral_link' => $this->referralService->referralLinkForCode($agent->referral_code),
            ],
        ]);
    }

    public function students(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agent = $this->agentRecord($user);
        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));
        $search = trim((string) $request->input('search', ''));

        $query = AgentStudent::query()
            ->where('agent_id', $agent->id)
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $students->getCollection()->map(fn (AgentStudent $student) => [
                'id' => $student->id,
                'student_user_id' => $student->student_user_id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'country' => $student->country,
                'onboarded_at' => optional($student->onboarded_at)->format('Y-m-d'),
            ])->values(),
            'meta' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
            ],
        ]);
    }

    public function newStudent(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $agent = $this->agentRecord($user);

        $payload = DB::transaction(function () use ($agent, $data) {
            $student = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'status' => 'active',
                'role' => 'lg_student',
                'password' => Hash::make(Str::random(24)),
            ]);

            $roleId = Role::query()->where('slug', 'lg_student')->value('id');
            if ($roleId) {
                $student->roles()->syncWithoutDetaching([$roleId]);
            }

            $record = AgentStudent::create([
                'agent_id' => $agent->id,
                'student_user_id' => $student->id,
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'country' => $data['country'] ?? null,
                'onboarding_token' => Str::random(48),
                'onboarding_token_expires_at' => now()->addDays(7),
            ]);

            return [
                'id' => $record->id,
                'student_user_id' => $record->student_user_id,
                'name' => $record->name,
                'email' => $record->email,
                'phone' => $record->phone,
                'country' => $record->country,
                'onboarded_at' => optional($record->onboarded_at)->format('Y-m-d'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payload,
        ], 201);
    }

    public function bookings(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:agent_students,id'],
            'course_type' => ['required', 'string', 'max:50'],
            'course_id' => ['required'],
            'course_title' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'weeks' => ['required', 'integer', 'min:1'],
            'final_price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'accommodation_id' => ['nullable'],
            'pickup_id' => ['nullable'],
            'insurance_id' => ['nullable'],
            'supplements_ids' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $agent = $this->agentRecord($user);
        $student = AgentStudent::query()->where('agent_id', $agent->id)->find($data['student_id']);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student does not belong to this agent.'], 403);
        }

        $lead = ContactSubmission::create([
            'name' => $student->name,
            'email' => $student->email,
            'phone' => $student->phone,
            'subject' => 'CourseEnglish agent booking',
            'message' => json_encode([
                'source' => 'agent_booking',
                'agent_user_id' => $user->id,
                'agent_id' => $agent->id,
                'student_id' => $student->id,
                'payload' => $data,
            ], JSON_UNESCAPED_UNICODE),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'booking_id' => $lead->id,
                'status' => 'pending',
            ],
        ], 201);
    }

    private function resolveAgentUser(Request $request): ?User
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return null;
        }

        return $user->hasRole('lg_agent') ? $user : null;
    }

    private function agentRecord(User $user): Agent
    {
        return Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );
    }

    private function referralLink(Agent $agent): string
    {
        return $this->referralService->referralLinkForCode($agent->referral_code);
    }

    private function agentPayload(Agent $agent, User $user): array
    {
        return [
            'id' => $agent->id,
            'user_id' => $agent->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $agent->phone ?: $user->phone,
            'company_name' => $agent->company_name,
            'status' => $agent->status,
            'referral_code' => $agent->referral_code,
            'referral_discount' => $agent->referral_discount,
            'commission_percent' => $agent->commission_percent,
            'referral_link' => $this->referralLink($agent),
            'verified_at' => $agent->verified_at,
            'role' => 'lg_agent',
        ];
    }
}
