<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageCourseTrainingCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'language_school_id',
        'branch_id',
        'course_type_id',
        'name',
        'ar_name',
        'description',
        'ar_description',
        'required_level',
        'study_time',
        'lessons_per_week',
        'min_age',
        'start_date',
        'fee_type',
        'fee_amount',
        'registration_fee',
        'thumbnail',
        'visible',
        'status',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'visible' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(LanguageSchool::class, 'language_school_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function courseType(): BelongsTo
    {
        return $this->belongsTo(LanguageCourseCategory::class, 'course_type_id');
    }
}
