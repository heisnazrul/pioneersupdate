<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'status',
        'referral_code',
        'referral_discount',
        'commission_percent',
        'referral_joined_at',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'referral_joined_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(AgentStudent::class);
    }

    public function referralLink(): ?string
    {
        return $this->referral_code ? url('/?ref=' . $this->referral_code) : null;
    }
}
