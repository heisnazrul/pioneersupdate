<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentStudent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    private array $agentRoles = ['agent', 'uni_agent', 'lg_agent'];

    public function me(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = $this->resolveAgentUser($request, isset($data['user_id']) ? (int) $data['user_id'] : null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $agent->id,
                'user_id' => $agent->user_id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $agent->phone ?? $user->phone,
                'company_name' => $agent->company_name,
                'status' => $agent->status,
                'referral_code' => $agent->referral_code,
                'referral_discount' => $agent->referral_discount,
                'commission_percent' => $agent->commission_percent,
                'referral_link' => $agent->referralLink(),
                'verified_at' => $agent->verified_at,
            ],
        ]);
    }

    public function overview(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request, null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        $studentsTotal = AgentStudent::where('agent_id', $agent->id)->count();
        $signups = $studentsTotal;

        return response()->json([
            'success' => true,
            'data' => [
                'students_total' => $studentsTotal,
                'referrals_active' => $agent->referral_code ? 1 : 0,
                'commission_percent' => $agent->commission_percent,
                'status' => $agent->status,
                'referral_code' => $agent->referral_code,
                'referral_link' => $agent->referralLink(),
                'signups' => $signups,
            ],
        ]);
    }

    public function referrals(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request, null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        $signups = AgentStudent::where('agent_id', $agent->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'referral_code' => $agent->referral_code,
                'referral_link' => $agent->referralLink(),
                'commission_percent' => $agent->commission_percent,
                'referral_discount' => $agent->referral_discount,
                'signups' => $signups,
                'total_clicks' => 0,
                'conversion' => null,
            ],
        ]);
    }

    public function refreshReferral(Request $request): JsonResponse
    {
        $user = $this->resolveAgentUser($request, null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        $agent->update([
            'referral_code' => Str::upper(Str::random(8)),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'referral_code' => $agent->referral_code,
                'referral_link' => $agent->referralLink(),
            ],
        ]);
    }

    public function bookCourse(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:agent_students,id'],
            'course_type' => ['required', 'in:language,online'],
            'course_id' => ['required'],
            'start_date' => ['required', 'date'],
            'weeks' => ['required', 'integer', 'min:1'],
            'final_price' => ['required', 'numeric'],
            'currency' => ['nullable', 'string', 'max:3'],
            'accommodation_id' => ['nullable', 'integer'],
            'pickup_id' => ['nullable', 'integer'],
            'insurance_id' => ['nullable', 'integer'],
            'supplements_ids' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = $this->resolveAgentUser($request, null);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User is not an agent.'], 403);
        }

        $agentStudent = AgentStudent::where('id', $data['student_id'])->first();
        if (!$agentStudent || $agentStudent->agent?->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Student does not belong to this agent.'], 403);
        }

        $studentUser = $agentStudent->user;
        if (!$studentUser) {
            return response()->json(['success' => false, 'message' => 'Student user not found.'], 404);
        }

        if ($data['course_type'] === 'language') {
            $bookingId = DB::table('language_course_bookings')->insertGetId([
                'user_id' => $studentUser->id,
                'course_id' => $data['course_id'],
                'accommodation_id' => $data['accommodation_id'] ?? null,
                'pickup_id' => $data['pickup_id'] ?? null,
                'insurance_id' => $data['insurance_id'] ?? null,
                'whatsapp' => $studentUser->phone ?? $studentUser->username,
                'weeks' => $data['weeks'],
                'start_date' => $data['start_date'],
                'accommodation_weeks' => $data['weeks'],
                'supplements_ids' => isset($data['supplements_ids']) ? json_encode($data['supplements_ids']) : null,
                'final_price' => $data['final_price'],
                'currency' => $data['currency'] ?? 'GBP',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'data' => ['booking_id' => $bookingId, 'status' => 'pending']]);
        }

        if ($data['course_type'] === 'online') {
            $bookingId = DB::table('online_course_bookings')->insertGetId([
                'user_id' => $studentUser->id,
                'course_id' => $data['course_id'],
                'whatsapp' => $studentUser->phone ?? $studentUser->username,
                'weeks' => $data['weeks'],
                'start_date' => $data['start_date'],
                'final_price' => $data['final_price'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'data' => ['booking_id' => $bookingId, 'status' => 'pending']]);
        }

        return response()->json(['success' => false, 'message' => 'Course type not supported'], 422);
    }

    public function students(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = $this->resolveAgentUser($request, isset($data['user_id']) ? (int) $data['user_id'] : null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        $students = AgentStudent::query()
            ->where('agent_id', $agent->id)
            ->orderByDesc('created_at')
            ->get(['id', 'student_user_id', 'name', 'email', 'phone', 'country', 'onboarded_at']);

        return response()->json([
            'success' => true,
            'data' => $students,
        ]);
    }

    public function newStudent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $this->resolveAgentUser($request, isset($data['user_id']) ? (int) $data['user_id'] : null);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an agent.',
            ], 403);
        }

        $agent = Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'pending',
            ]
        );

        $studentData = DB::transaction(function () use ($agent, $data) {
            $studentUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'role' => 'lg_student',
                'status' => 'active',
                'password' => Hash::make(Str::random(20)),
            ]);

            $agentStudent = AgentStudent::create([
                'agent_id' => $agent->id,
                'student_user_id' => $studentUser->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'country' => $data['country'] ?? null,
                'onboarding_token' => Str::random(48),
                'onboarding_token_expires_at' => now()->addDays(7),
            ]);

            return [
                'id' => $agentStudent->id,
                'student_user_id' => $agentStudent->student_user_id,
                'name' => $agentStudent->name,
                'email' => $agentStudent->email,
                'phone' => $agentStudent->phone,
                'country' => $agentStudent->country,
                'onboarded_at' => $agentStudent->onboarded_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $studentData,
        ], 201);
    }

    private function resolveAgentUser(Request $request, ?int $userId): ?User
    {
        $authUser = $request->user();
        if (!$authUser) {
            return null;
        }

        $resolvedUserId = $userId ?? $authUser->id;

        if ($authUser->id !== $resolvedUserId && $authUser->role !== 'admin') {
            return null;
        }

        $target = User::find($resolvedUserId);
        if (!$target || !in_array($target->role, $this->agentRoles, true)) {
            return null;
        }

        return $target;
    }
}
