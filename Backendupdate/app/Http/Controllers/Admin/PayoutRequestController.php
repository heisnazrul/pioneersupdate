<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\Payout\PayoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PayoutRequestController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService,
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $referrerType = $request->query('referrer_type');
        $search = trim((string) $request->query('search', ''));

        $payouts = PayoutRequest::query()
            ->with(['user', 'agent.user', 'processor'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($referrerType, fn ($q) => $q->where('referrer_type', $referrerType))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_no', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('email', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.payout-requests.index', [
            'payouts' => $payouts,
            'statuses' => PayoutRequest::STATUSES,
            'activeStatus' => $status,
            'activeReferrerType' => $referrerType,
            'search' => $search,
        ]);
    }

    public function show(PayoutRequest $payoutRequest): View
    {
        $payoutRequest->load(['user.profile', 'agent.user', 'processor']);

        return view('admin.payout-requests.show', [
            'payout' => $payoutRequest,
            'statuses' => PayoutRequest::STATUSES,
        ]);
    }

    public function edit(PayoutRequest $payoutRequest): View
    {
        $payoutRequest->load(['user.profile', 'agent.user']);

        return view('admin.payout-requests.edit', [
            'payout' => $payoutRequest,
            'statuses' => PayoutRequest::STATUSES,
        ]);
    }

    public function update(Request $request, PayoutRequest $payoutRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(PayoutRequest::STATUSES)],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $this->payoutService->updateStatus(
            $payoutRequest,
            $data['status'],
            $data['admin_notes'] ?? null,
            $request->user(),
        );

        return redirect()
            ->route('admin.payout-requests.show', $payoutRequest)
            ->with('success', 'Payout request updated successfully.');
    }
}
