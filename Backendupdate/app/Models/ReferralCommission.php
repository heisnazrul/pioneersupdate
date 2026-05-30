<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'referrer_type',
        'referrer_user_id',
        'referrer_agent_id',
        'referred_user_id',
        'booking_type',
        'booking_id',
        'booking_reference',
        'booking_total',
        'discount_given',
        'commission_percent',
        'commission_amount',
        'currency',
        'status',
        'payable_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'payable_at' => 'datetime',
            'paid_at' => 'datetime',
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
