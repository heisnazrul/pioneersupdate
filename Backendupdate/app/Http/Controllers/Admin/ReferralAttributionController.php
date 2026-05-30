<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralAttribution;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralAttributionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $referrerType = $request->query('referrer_type');
        $search = trim((string) $request->query('search', ''));

        $attributions = ReferralAttribution::query()
            ->with(['referredUser', 'referrerUser', 'referrerAgent.user'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($referrerType, fn ($q) => $q->where('referrer_type', $referrerType))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('referral_code', 'like', "%{$search}%")
                        ->orWhereHas('referredUser', fn ($uq) => $uq->where('email', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.referral-attributions.index', [
            'attributions' => $attributions,
            'statuses' => ['pending', 'qualified', 'expired', 'cancelled'],
            'activeStatus' => $status,
            'activeReferrerType' => $referrerType,
            'search' => $search,
        ]);
    }

    public function show(ReferralAttribution $referralAttribution): View
    {
        $referralAttribution->load(['referredUser', 'referrerUser', 'referrerAgent.user']);

        return view('admin.referral-attributions.show', [
            'attribution' => $referralAttribution,
        ]);
    }
}
