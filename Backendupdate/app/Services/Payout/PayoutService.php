<?php

namespace App\Services\Payout;

use App\Models\Agent;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Services\Profile\ProfileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PayoutService
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listForUser(User $user): array
    {
        return PayoutRequest::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->limit(50)
            ->get()
            ->map(fn (PayoutRequest $request) => $this->serialize($request))
            ->all();
    }

    public function createForStudent(User $user, ?float $amount = null, ?string $notes = null): PayoutRequest
    {
        if (! $user->hasRole('lg_student')) {
            throw ValidationException::withMessages([
                'payout' => ['Only students can request student payouts.'],
            ]);
        }

        $balance = (float) ($user->referral_commission_balance ?? 0);

        return $this->createRequest($user, null, 'student', $balance, $amount, $notes);
    }

    public function createForAgent(User $user, Agent $agent, ?float $amount = null, ?string $notes = null): PayoutRequest
    {
        if (! $user->hasRole('lg_agent')) {
            throw ValidationException::withMessages([
                'payout' => ['Only agents can request agent payouts.'],
            ]);
        }

        $balance = (float) ($agent->commission_balance ?? 0);

        return $this->createRequest($user, $agent, 'agent', $balance, $amount, $notes);
    }

    public function updateStatus(PayoutRequest $payoutRequest, string $status, ?string $adminNotes = null, ?User $admin = null): PayoutRequest
    {
        if (! in_array($status, PayoutRequest::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => ['Invalid payout status.'],
            ]);
        }

        return DB::transaction(function () use ($payoutRequest, $status, $adminNotes, $admin) {
            $previousStatus = $payoutRequest->status;

            if ($status === 'paid' && $previousStatus !== 'paid') {
                $this->deductBalance($payoutRequest);
            }

            if (in_array($status, ['rejected', 'cancelled'], true) && $previousStatus === 'paid') {
                $this->restoreBalance($payoutRequest);
            }

            $payoutRequest->update([
                'status' => $status,
                'admin_notes' => $adminNotes ?? $payoutRequest->admin_notes,
                'processed_by' => $admin?->id ?? $payoutRequest->processed_by,
                'processed_at' => in_array($status, ['paid', 'rejected', 'cancelled', 'approved'], true)
                    ? now()
                    : $payoutRequest->processed_at,
            ]);

            return $payoutRequest->fresh(['user', 'agent.user', 'processor']);
        });
    }

    public function serialize(PayoutRequest $request): array
    {
        return [
            'id' => $request->id,
            'reference_no' => $request->reference_no,
            'referrer_type' => $request->referrer_type,
            'amount' => (float) $request->amount,
            'currency' => $request->currency,
            'status' => $request->status,
            'user_notes' => $request->user_notes,
            'admin_notes' => $request->admin_notes,
            'created_at' => optional($request->created_at)->toIso8601String(),
            'processed_at' => optional($request->processed_at)->toIso8601String(),
        ];
    }

    private function createRequest(
        User $user,
        ?Agent $agent,
        string $referrerType,
        float $balance,
        ?float $amount,
        ?string $notes,
    ): PayoutRequest {
        if ($balance <= 0) {
            throw ValidationException::withMessages([
                'amount' => ['You do not have any commission balance available for payout.'],
            ]);
        }

        if (! $this->profileService->hasBankDetails($user)) {
            throw ValidationException::withMessages([
                'bank' => ['Please add your bank details before requesting a payout.'],
            ]);
        }

        $pendingExists = PayoutRequest::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($pendingExists) {
            throw ValidationException::withMessages([
                'payout' => ['You already have a pending payout request.'],
            ]);
        }

        $requestedAmount = $amount ?? $balance;
        if ($requestedAmount <= 0 || $requestedAmount > $balance) {
            throw ValidationException::withMessages([
                'amount' => ['Invalid payout amount.'],
            ]);
        }

        $profile = $user->profile;

        return PayoutRequest::create([
            'reference_no' => $this->generateReference(),
            'user_id' => $user->id,
            'agent_id' => $agent?->id,
            'referrer_type' => $referrerType,
            'amount' => round($requestedAmount, 2),
            'currency' => 'SAR',
            'status' => 'pending',
            'bank_account_name' => $profile?->bank_account_name,
            'bank_name' => $profile?->bank_name,
            'bank_account_number' => $profile?->bank_account_number,
            'bank_iban' => $profile?->bank_iban,
            'bank_swift_code' => $profile?->bank_swift_code,
            'user_notes' => $notes,
        ]);
    }

    private function deductBalance(PayoutRequest $payoutRequest): void
    {
        $amount = (float) $payoutRequest->amount;

        if ($payoutRequest->referrer_type === 'agent' && $payoutRequest->agent_id) {
            $agent = Agent::query()->lockForUpdate()->find($payoutRequest->agent_id);
            if (! $agent || (float) $agent->commission_balance < $amount) {
                throw ValidationException::withMessages([
                    'amount' => ['Insufficient agent commission balance.'],
                ]);
            }
            $agent->decrement('commission_balance', $amount);

            return;
        }

        $user = User::query()->lockForUpdate()->find($payoutRequest->user_id);
        if (! $user || (float) $user->referral_commission_balance < $amount) {
            throw ValidationException::withMessages([
                'amount' => ['Insufficient commission balance.'],
            ]);
        }
        $user->decrement('referral_commission_balance', $amount);
    }

    private function restoreBalance(PayoutRequest $payoutRequest): void
    {
        $amount = (float) $payoutRequest->amount;

        if ($payoutRequest->referrer_type === 'agent' && $payoutRequest->agent_id) {
            Agent::query()->whereKey($payoutRequest->agent_id)->increment('commission_balance', $amount);

            return;
        }

        User::query()->whereKey($payoutRequest->user_id)->increment('referral_commission_balance', $amount);
    }

    private function generateReference(): string
    {
        do {
            $reference = 'PO-' . strtoupper(Str::random(8));
        } while (PayoutRequest::query()->where('reference_no', $reference)->exists());

        return $reference;
    }
}
