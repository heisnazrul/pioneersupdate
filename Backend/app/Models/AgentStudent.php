<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'student_user_id',
        'name',
        'email',
        'phone',
        'country',
        'onboarding_token',
        'onboarding_token_expires_at',
        'onboarded_at',
    ];

    protected $casts = [
        'onboarding_token_expires_at' => 'datetime',
        'onboarded_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}
