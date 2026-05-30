<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralAttribution extends Model
{
    protected $fillable = [
        'referred_user_id',
        'referrer_type',
        'referrer_user_id',
        'referrer_agent_id',
        'referral_code',
        'source',
        'status',
        'qualified_at',
        'first_booking_type',
        'first_booking_id',
    ];

    protected function casts(): array
    {
        return [
            'qualified_at' => 'datetime',
        ];
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function referrerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function referrerAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'referrer_agent_id');
    }
}
