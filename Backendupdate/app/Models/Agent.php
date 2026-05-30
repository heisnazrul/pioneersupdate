<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'phone', 'status',
        'referral_code', 'referral_slug', 'is_code_custom',
        'referral_discount', 'commission_percent', 'commission_balance', 'total_commission_earned',
        'referral_joined_at', 'verified_at',
    ];

    protected $casts = [
        'referral_joined_at' => 'datetime',
        'verified_at'        => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(AgentStudent::class);
    }
}
