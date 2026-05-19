<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public const STATUSES = ['draft', 'new', 'reviewing', 'approved', 'rejected'];

    protected $fillable = [
        'application_id', 'first_name', 'last_name', 'email', 'phone',
        'citizenship', 'nationality', 'nationality_other',
        'highest_education', 'grade_average',
        'has_english_test', 'english_test_type', 'english_test_score',
        'destination_interest', 'destinations_other', 'preferred_intake', 'budget_range',
        'user_id', 'status', 'assigned_to', 'assigned_role', 'status_notes',
    ];

    protected $casts = [
        'destination_interest' => 'array',
        'has_english_test'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
