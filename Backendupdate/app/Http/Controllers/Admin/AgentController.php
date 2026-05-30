<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use App\Services\Referral\ReferralService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function __construct(
        private readonly ReferralService $referralService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $agents = Agent::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('referral_code', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.agents.index', [
            'agents' => $agents,
            'statuses' => ['pending', 'active', 'inactive', 'suspended'],
            'activeStatus' => $status,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.agents.create', [
            'statuses' => ['pending', 'active', 'inactive', 'suspended'],
            'userStatuses' => User::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAgentData($request);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'lg_agent',
                'status' => $data['user_status'],
            ]);
            $user->assignPrimaryRole('lg_agent');

            $code = $this->resolveReferralCode($data['referral_code'] ?? null);

            Agent::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'] ?? null,
                'phone' => $data['agent_phone'] ?? $data['phone'] ?? null,
                'status' => $data['status'],
                'referral_code' => $code,
                'referral_slug' => $data['referral_slug'] ?? null,
                'is_code_custom' => ! empty($data['referral_code']),
                'referral_discount' => $data['referral_discount'] ?? 0,
                'commission_percent' => $data['commission_percent'] ?? 0,
                'commission_balance' => $data['commission_balance'] ?? 0,
                'total_commission_earned' => $data['total_commission_earned'] ?? 0,
                'verified_at' => $data['status'] === 'active' ? now() : null,
            ]);
        });

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully.');
    }

    public function show(Agent $agent): View
    {
        $agent->load(['user', 'students']);

        return view('admin.agents.show', compact('agent'));
    }

    public function edit(Agent $agent): View
    {
        $agent->load('user');

        return view('admin.agents.edit', [
            'agent' => $agent,
            'statuses' => ['pending', 'active', 'inactive', 'suspended'],
            'userStatuses' => User::STATUSES,
        ]);
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $data = $this->validateAgentData($request, $agent);
        $agent->load('user');

        DB::transaction(function () use ($data, $agent) {
            $user = $agent->user;
            if ($user) {
                $user->update([
                    'name' => $data['name'],
                    'email' => strtolower($data['email']),
                    'phone' => $data['phone'] ?? null,
                    'status' => $data['user_status'],
                ]);

                if (! empty($data['password'])) {
                    $user->update(['password' => Hash::make($data['password'])]);
                }
            }

            $code = $agent->referral_code;
            if (! empty($data['referral_code']) && strtoupper($data['referral_code']) !== $agent->referral_code) {
                if (! $this->referralService->isCodeAvailable($data['referral_code'], $agent->id, null)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'referral_code' => ['This referral code is already taken.'],
                    ]);
                }
                $code = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $data['referral_code']) ?? '');
            }

            $agent->update([
                'company_name' => $data['company_name'] ?? null,
                'phone' => $data['agent_phone'] ?? $data['phone'] ?? null,
                'status' => $data['status'],
                'referral_code' => $code,
                'referral_slug' => $data['referral_slug'] ?? null,
                'is_code_custom' => ! empty($data['referral_code']) ? true : $agent->is_code_custom,
                'referral_discount' => $data['referral_discount'] ?? 0,
                'commission_percent' => $data['commission_percent'] ?? 0,
                'commission_balance' => $data['commission_balance'] ?? 0,
                'total_commission_earned' => $data['total_commission_earned'] ?? 0,
                'verified_at' => $data['status'] === 'active' ? ($agent->verified_at ?? now()) : $agent->verified_at,
            ]);
        });

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        DB::transaction(function () use ($agent) {
            $userId = $agent->user_id;
            $agent->delete();

            if ($userId) {
                $user = User::find($userId);
                if ($user && $user->role === 'lg_agent') {
                    $user->delete();
                }
            }
        });

        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted successfully.');
    }

    private function validateAgentData(Request $request, ?Agent $agent = null): array
    {
        $userId = $agent?->user_id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'agent_phone' => ['nullable', 'string', 'max:50'],
            'password' => [$agent ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'user_status' => ['required', Rule::in(User::STATUSES)],
            'company_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'active', 'inactive', 'suspended'])],
            'referral_code' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9]+$/'],
            'referral_slug' => ['nullable', 'string', 'max:50', Rule::unique('agents', 'referral_slug')->ignore($agent?->id)],
            'referral_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_balance' => ['nullable', 'numeric', 'min:0'],
            'total_commission_earned' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    private function resolveReferralCode(?string $code): string
    {
        if ($code) {
            $normalized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $code) ?? '');
            if (! $this->referralService->isCodeAvailable($normalized, null, null)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'referral_code' => ['This referral code is already taken.'],
                ]);
            }

            return $normalized;
        }

        do {
            $generated = Str::upper(Str::random(8));
        } while (! $this->referralService->isCodeAvailable($generated, null, null));

        return $generated;
    }
}
