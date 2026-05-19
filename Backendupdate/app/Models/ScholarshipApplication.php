<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'scholarship_id',
        'scholarship_title',
        'scholarship_slug',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'city',
        'education_level',
        'grade_average',
        'english_proficiency',
        'status',
        'assignee_id',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
