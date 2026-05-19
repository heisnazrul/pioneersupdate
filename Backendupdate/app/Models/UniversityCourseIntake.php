<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UniversityCourseIntake extends Pivot
{
    protected $table = 'university_course_intakes';

    protected $fillable = [
        'university_course_id',
        'intake_term_id',
        'deadline_date',
        'start_date',
        'is_active',
    ];

    protected $casts = [
        'deadline_date' => 'date',
        'start_date' => 'date',
        'is_active' => 'boolean',
    ];
}
