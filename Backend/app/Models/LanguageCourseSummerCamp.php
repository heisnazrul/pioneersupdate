<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageCourseSummerCamp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'language_course_summer_camps';

    protected $fillable = [
        'slug',
        'branch_id',
        'course_type_id',
        'tag_id',
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
        'visible' => 'bool',
    ];

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function courseType()
    {
        return $this->belongsTo(LanguageCourseType::class, 'course_type_id');
    }

    public function tag()
    {
        return $this->belongsTo(LanguageCourseTag::class, 'tag_id');
    }

    public function details()
    {
        return $this->hasMany(LanguageCourseSummerCampDetail::class, 'camp_id');
    }
}
