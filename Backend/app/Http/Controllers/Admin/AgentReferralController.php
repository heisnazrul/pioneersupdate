<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AgentReferralController extends Controller
{
    public function index(): View
    {
        $agents = Agent::query()
            ->with('user')
            ->orderBy('company_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.referrals.index', compact('agents'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('admin.referrals.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', Rule::unique('agents', 'user_id')],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending','approved','rejected'])],
            'referral_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $data['referral_code'] = $this->generateReferralCode();
        $data['referral_discount'] = $data['referral_discount'] ?? 0;
        $data['commission_percent'] = $data['commission_percent'] ?? 0;

        Agent::create($data);

        return redirect()->route('admin.referrals.index')->with('success', 'Agent assigned successfully.');
    }

    public function edit(Agent $agent): View
    {
        $agent->load('user');

        return view('admin.referrals.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent): RedirectResponse
    {
        $data = $request->validate([
            'referral_code' => ['nullable', 'string', 'max:50', Rule::unique('agents', 'referral_code')->ignore($agent->id)],
            'referral_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['pending','approved','rejected'])],
        ]);

        $agent->update($data);

        return redirect()->route('admin.referrals.index')->with('success', 'Referral settings updated successfully.');
    }

    public function students(): View
    {
        $referrals = AgentStudent::query()
            ->with(['agent.user'])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.referrals.students', compact('referrals'));
    }

    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Agent::where('referral_code', $code)->exists());

        return $code;
    }
}
