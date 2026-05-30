<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'referral_code',
        'referrer_type',
        'referrer_user_id',
        'referrer_agent_id',
        'ip_address',
        'landing_path',
        'converted',
        'converted_user_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'converted' => 'boolean',
            'created_at' => 'datetime',
        ];
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
