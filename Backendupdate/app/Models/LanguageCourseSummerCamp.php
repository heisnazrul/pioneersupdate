<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageCourseSummerCamp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'branch_id',
        'course_type_id',
        'name',
        'ar_name',
        'description',
        'ar_description',
        'required_level',
        'study_time',
        'lessons_per_week',
        'age_range',
        'start_date',
        'payment_deadline',
        'fee_type',
        'fee_amount',
        'registration_fee',
        'thumbnail',
        'visible',
        'status',
    ];

    protected $casts = [
        'payment_deadline' => 'date',
        'fee_amount' => 'decimal:2',
        'registration_fee' => 'decimal:2',
        'visible' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function courseType(): BelongsTo
    {
        return $this->belongsTo(LanguageCourseCategory::class, 'course_type_id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(LanguageCourseSummerCampDetail::class, 'camp_id');
    }
}
