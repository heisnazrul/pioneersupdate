<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReferralCommissionController extends Controller
{
    private const STATUSES = ['pending', 'approved', 'paid', 'cancelled'];

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $referrerType = $request->query('referrer_type');
        $search = trim((string) $request->query('search', ''));

        $commissions = ReferralCommission::query()
            ->with(['referredUser', 'referrerUser', 'referrerAgent.user'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($referrerType, fn ($q) => $q->where('referrer_type', $referrerType))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_reference', 'like', "%{$search}%")
                        ->orWhereHas('referredUser', fn ($uq) => $uq->where('email', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.referral-commissions.index', [
            'commissions' => $commissions,
            'statuses' => self::STATUSES,
            'activeStatus' => $status,
            'activeReferrerType' => $referrerType,
            'search' => $search,
        ]);
    }

    public function show(ReferralCommission $referralCommission): View
    {
        $referralCommission->load(['referredUser', 'referrerUser', 'referrerAgent.user']);

        return view('admin.referral-commissions.show', [
            'commission' => $referralCommission,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(ReferralCommission $referralCommission): View
    {
        $referralCommission->load(['referredUser', 'referrerUser', 'referrerAgent.user']);

        return view('admin.referral-commissions.edit', [
            'commission' => $referralCommission,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, ReferralCommission $referralCommission): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'commission_amount' => ['required', 'numeric', 'min:0'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_given' => ['nullable', 'numeric', 'min:0'],
            'payable_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
        ]);

        if ($data['status'] === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        if ($data['status'] !== 'paid') {
            $data['paid_at'] = $request->input('paid_at') ?: null;
        }

        $referralCommission->update($data);

        return redirect()->route('admin.referral-commissions.show', $referralCommission)
            ->with('success', 'Commission updated successfully.');
    }
}
