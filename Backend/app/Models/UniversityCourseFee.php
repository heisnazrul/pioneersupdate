<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityCourseFee extends Model
{
    protected $fillable = [
        'course_id',
        'campus_id',
        'first_year_fee',
        'currency',
        'note',
        'ar_note',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'first_year_fee' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(UniversityCourse::class, 'course_id');
    }

    public function campus()
    {
        return $this->belongsTo(UniversityCampus::class, 'campus_id');
    }
}
