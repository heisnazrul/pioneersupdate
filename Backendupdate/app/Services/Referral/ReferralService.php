<?php

namespace App\Services\Referral;

use App\Models\Agent;
use App\Models\ReferralAttribution;
use App\Models\ReferralClick;
use App\Models\ReferralCommission;
use App\Models\ReferralProgramSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReferralService
{
    public function referralLinkForCode(string $code): string
    {
        $frontend = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');

        return $frontend . '/?ref=' . urlencode($code);
    }

    public function ensureStudentReferralCode(User $user): string
    {
        if ($user->referral_code) {
            return $user->referral_code;
        }

        do {
            $code = Str::upper(Str::random(8));
        } while (! $this->isCodeAvailable($code));

        $user->forceFill(['referral_code' => $code])->save();

        return $code;
    }

    public function isCodeAvailable(string $code, ?int $excludeAgentId = null, ?int $excludeUserId = null): bool
    {
        $normalized = Str::upper(trim($code));
        if (strlen($normalized) < 4) {
            return false;
        }

        $userExists = User::query()
            ->where('referral_code', $normalized)
            ->when($excludeUserId, fn ($q) => $q->where('id', '!=', $excludeUserId))
            ->exists();

        if ($userExists) {
            return false;
        }

        $agentQuery = Agent::query()->where(function ($q) use ($normalized) {
            $q->where('referral_code', $normalized)
                ->orWhere('referral_slug', Str::lower($normalized));
        });

        if ($excludeAgentId) {
            $agentQuery->where('id', '!=', $excludeAgentId);
        }

        return ! $agentQuery->exists();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function resolveCode(string $code): ?array
    {
        $normalized = Str::upper(trim($code));
        $slug = Str::lower(trim($code));

        if ($normalized === '') {
            return null;
        }

        $student = User::query()
            ->where('referral_code', $normalized)
            ->where('status', 'active')
            ->first();

        if ($student) {
            $settings = $this->settingsFor('student');

            return [
                'referrer_type' => 'student',
                'referrer_user_id' => $student->id,
                'referrer_agent_id' => null,
                'referral_code' => $student->referral_code,
                'referrer_name' => $student->name,
                'discount_percent' => (float) $settings->discount_value,
                'commission_percent' => (float) $settings->commission_value,
            ];
        }

        $agent = Agent::query()
            ->where(function ($q) use ($normalized, $slug) {
                $q->where('referral_code', $normalized)
                    ->orWhere('referral_slug', $slug);
            })
            ->whereIn('status', ['active', 'approved'])
            ->with('user')
            ->first();

        if ($agent) {
            $global = $this->settingsFor('agent');
            $discount = $agent->referral_discount > 0 ? (float) $agent->referral_discount : (float) $global->discount_value;
            $commission = $agent->commission_percent > 0 ? (float) $agent->commission_percent : (float) $global->commission_value;

            return [
                'referrer_type' => 'agent',
                'referrer_user_id' => null,
                'referrer_agent_id' => $agent->id,
                'referral_code' => $agent->referral_code,
                'referrer_name' => $agent->company_name ?: $agent->user?->name,
                'discount_percent' => $discount,
                'commission_percent' => $commission,
            ];
        }

        return null;
    }

    public function trackClick(string $code, ?Request $request = null, ?string $landingPath = null): ?array
    {
        $resolved = $this->resolveCode($code);
        if (! $resolved) {
            return null;
        }

        ReferralClick::create([
            'referral_code' => $resolved['referral_code'],
            'referrer_type' => $resolved['referrer_type'],
            'referrer_user_id' => $resolved['referrer_user_id'],
            'referrer_agent_id' => $resolved['referrer_agent_id'],
            'ip_address' => $request?->ip(),
            'landing_path' => $landingPath,
            'created_at' => now(),
        ]);

        $settings = $this->settingsFor($resolved['referrer_type'] === 'agent' ? 'agent' : 'student');

        return array_merge($resolved, [
            'cookie_ttl_days' => (int) $settings->cookie_ttl_days,
            'referral_link' => $this->referralLinkForCode($resolved['referral_code']),
        ]);
    }

    public function attributeUser(User $user, string $code, string $source = 'link'): ?ReferralAttribution
    {
        if ($user->referred_by_type || ReferralAttribution::query()->where('referred_user_id', $user->id)->exists()) {
            return ReferralAttribution::query()->where('referred_user_id', $user->id)->first();
        }

        $resolved = $this->resolveCode($code);
        if (! $resolved) {
            return null;
        }

        if ($resolved['referrer_type'] === 'student' && (int) $resolved['referrer_user_id'] === (int) $user->id) {
            return null;
        }

        if ($resolved['referrer_type'] === 'agent') {
            $agent = Agent::find($resolved['referrer_agent_id']);
            if ($agent && (int) $agent->user_id === (int) $user->id) {
                return null;
            }
        }

        return DB::transaction(function () use ($user, $resolved, $source) {
            $attribution = ReferralAttribution::create([
                'referred_user_id' => $user->id,
                'referrer_type' => $resolved['referrer_type'],
                'referrer_user_id' => $resolved['referrer_user_id'],
                'referrer_agent_id' => $resolved['referrer_agent_id'],
                'referral_code' => $resolved['referral_code'],
                'source' => $source,
                'status' => 'pending',
            ]);

            $user->forceFill([
                'referred_by_type' => $resolved['referrer_type'],
                'referred_by_user_id' => $resolved['referrer_user_id'],
                'referred_by_agent_id' => $resolved['referrer_agent_id'],
                'referral_code_used' => $resolved['referral_code'],
                'referred_at' => now(),
            ])->save();

            ReferralClick::query()
                ->where('referral_code', $resolved['referral_code'])
                ->where('converted', false)
                ->latest('id')
                ->limit(1)
                ->update(['converted' => true, 'converted_user_id' => $user->id]);

            return $attribution;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function resolveBookingReferral(User $user, float $subtotal, string $bookingType, ?string $explicitCode = null): array
    {
        $empty = [
            'discount_amount' => 0.0,
            'referral_code' => null,
            'referrer_type' => null,
            'referrer_user_id' => null,
            'referrer_agent_id' => null,
            'commission_percent' => 0.0,
            'commission_amount' => 0.0,
        ];

        if ($subtotal <= 0) {
            return $empty;
        }

        $attribution = ReferralAttribution::query()->where('referred_user_id', $user->id)->first();

        if (! $attribution && $explicitCode) {
            $this->attributeUser($user, $explicitCode, 'code');
            $attribution = ReferralAttribution::query()->where('referred_user_id', $user->id)->first();
        }

        if (! $attribution) {
            return $empty;
        }

        $scope = $attribution->referrer_type === 'agent' ? 'agent' : 'student';
        $settings = $this->settingsFor($scope);

        if (! $settings->is_active) {
            return $empty;
        }

        $appliesTo = $settings->applies_to ?? ['language_courses', 'online_courses'];
        if (! in_array($bookingType, $appliesTo, true)) {
            return $empty;
        }

        if ($user->referred_at && $settings->attribution_window_days) {
            $referredAt = $user->referred_at instanceof Carbon
                ? $user->referred_at
                : Carbon::parse($user->referred_at);
            $expires = $referredAt->copy()->addDays((int) $settings->attribution_window_days);
            if (now()->gt($expires)) {
                return $empty;
            }
        }

        if ($subtotal < (float) $settings->min_booking_amount) {
            return $empty;
        }

        $discountPercent = (float) $settings->discount_value;
        $commissionPercent = (float) $settings->commission_value;

        if ($attribution->referrer_type === 'agent' && $attribution->referrer_agent_id) {
            $agent = Agent::find($attribution->referrer_agent_id);
            if ($agent) {
                if ($agent->referral_discount > 0) {
                    $discountPercent = (float) $agent->referral_discount;
                }
                if ($agent->commission_percent > 0) {
                    $commissionPercent = (float) $agent->commission_percent;
                }
            }
        }

        $discountAmount = round($subtotal * $discountPercent / 100, 2);
        if ($settings->max_discount_amount) {
            $discountAmount = min($discountAmount, (float) $settings->max_discount_amount);
        }

        $commissionBase = max(0, $subtotal - $discountAmount);
        $commissionAmount = round($commissionBase * $commissionPercent / 100, 2);
        if ($settings->max_commission_amount) {
            $commissionAmount = min($commissionAmount, (float) $settings->max_commission_amount);
        }

        return [
            'discount_amount' => $discountAmount,
            'referral_code' => $attribution->referral_code,
            'referrer_type' => $attribution->referrer_type,
            'referrer_user_id' => $attribution->referrer_user_id,
            'referrer_agent_id' => $attribution->referrer_agent_id,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
        ];
    }

    /**
     * @param  object  $booking  LanguageCourseBooking|OnlineCourseBooking
     */
    public function recordBookingCommission(object $booking, string $bookingType, array $referralData, User $referredUser): void
    {
        if (($referralData['commission_amount'] ?? 0) <= 0) {
            return;
        }

        if (ReferralCommission::query()
            ->where('booking_type', $bookingType)
            ->where('booking_id', $booking->id)
            ->exists()) {
            return;
        }

        DB::transaction(function () use ($booking, $bookingType, $referralData, $referredUser) {
            ReferralCommission::create([
                'referrer_type' => $referralData['referrer_type'],
                'referrer_user_id' => $referralData['referrer_user_id'],
                'referrer_agent_id' => $referralData['referrer_agent_id'],
                'referred_user_id' => $referredUser->id,
                'booking_type' => $bookingType,
                'booking_id' => $booking->id,
                'booking_reference' => $booking->reference_no,
                'booking_total' => (float) $booking->total_amount,
                'discount_given' => (float) ($referralData['discount_amount'] ?? 0),
                'commission_percent' => (float) $referralData['commission_percent'],
                'commission_amount' => (float) $referralData['commission_amount'],
                'currency' => $booking->display_currency ?? 'SAR',
                'status' => 'approved',
                'payable_at' => now(),
            ]);

            if ($referralData['referrer_type'] === 'student' && $referralData['referrer_user_id']) {
                User::query()->where('id', $referralData['referrer_user_id'])->update([
                    'referral_commission_balance' => DB::raw('referral_commission_balance + ' . (float) $referralData['commission_amount']),
                    'referral_commission_total' => DB::raw('referral_commission_total + ' . (float) $referralData['commission_amount']),
                ]);
            }

            if ($referralData['referrer_type'] === 'agent' && $referralData['referrer_agent_id']) {
                Agent::query()->where('id', $referralData['referrer_agent_id'])->update([
                    'commission_balance' => DB::raw('commission_balance + ' . (float) $referralData['commission_amount']),
                    'total_commission_earned' => DB::raw('total_commission_earned + ' . (float) $referralData['commission_amount']),
                ]);
            }

            ReferralAttribution::query()
                ->where('referred_user_id', $referredUser->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'qualified',
                    'qualified_at' => now(),
                    'first_booking_type' => $bookingType,
                    'first_booking_id' => $booking->id,
                ]);
        });
    }

    public function updateAgentReferralCode(Agent $agent, string $code): Agent
    {
        $normalized = Str::upper(preg_replace('/[^A-Za-z0-9]/', '', $code) ?? '');

        if (strlen($normalized) < 4 || strlen($normalized) > 20) {
            throw ValidationException::withMessages([
                'referral_code' => ['Referral code must be 4-20 alphanumeric characters.'],
            ]);
        }

        if (! $this->isCodeAvailable($normalized, $agent->id, null)) {
            throw ValidationException::withMessages([
                'referral_code' => ['This referral code is already taken.'],
            ]);
        }

        $agent->update([
            'referral_code' => $normalized,
            'is_code_custom' => true,
        ]);

        return $agent->fresh();
    }

    public function studentReferralStats(User $user): array
    {
        $code = $this->ensureStudentReferralCode($user);
        $settings = $this->settingsFor('student');

        $signups = ReferralAttribution::query()
            ->where('referrer_type', 'student')
            ->where('referrer_user_id', $user->id)
            ->count();

        $clicks = ReferralClick::query()
            ->where('referrer_type', 'student')
            ->where('referrer_user_id', $user->id)
            ->count();

        $conversion = $clicks > 0 ? round(($signups / $clicks) * 100, 1) : null;

        return [
            'referral_code' => $code,
            'referral_link' => $this->referralLinkForCode($code),
            'discount_percent' => (float) $settings->discount_value,
            'commission_percent' => (float) $settings->commission_value,
            'total_clicks' => $clicks,
            'signups' => $signups,
            'conversion' => $conversion,
            'reward_balance_sar' => (float) ($user->referral_commission_balance ?? 0),
            'reward_total_sar' => (float) ($user->referral_commission_total ?? 0),
        ];
    }

    public function agentReferralStats(Agent $agent): array
    {
        $global = $this->settingsFor('agent');
        $discount = $agent->referral_discount > 0 ? (float) $agent->referral_discount : (float) $global->discount_value;
        $commission = $agent->commission_percent > 0 ? (float) $agent->commission_percent : (float) $global->commission_value;

        $signups = ReferralAttribution::query()
            ->where('referrer_type', 'agent')
            ->where('referrer_agent_id', $agent->id)
            ->count();

        $clicks = ReferralClick::query()
            ->where('referrer_type', 'agent')
            ->where('referrer_agent_id', $agent->id)
            ->count();

        $conversion = $clicks > 0 ? round(($signups / $clicks) * 100, 1) : null;

        return [
            'referral_code' => $agent->referral_code,
            'referral_slug' => $agent->referral_slug,
            'referral_link' => $this->referralLinkForCode($agent->referral_code),
            'is_code_custom' => (bool) $agent->is_code_custom,
            'commission_percent' => $commission,
            'referral_discount' => $discount,
            'signups' => $signups,
            'total_clicks' => $clicks,
            'conversion' => $conversion,
            'reward_balance' => (float) ($agent->commission_balance ?? 0),
            'reward_total' => (float) ($agent->total_commission_earned ?? 0),
        ];
    }

    private function settingsFor(string $scope): ReferralProgramSetting
    {
        return ReferralProgramSetting::query()->firstOrCreate(
            ['scope' => $scope],
            [
                'discount_type' => 'percent',
                'discount_value' => 5,
                'commission_type' => 'percent',
                'commission_value' => $scope === 'agent' ? 5 : 3,
                'applies_to' => ['language_courses', 'online_courses'],
            ]
        );
    }
}
