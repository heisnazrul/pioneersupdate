<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageCourseOnlineCourse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'language_course_online_courses';

    protected $fillable = [
        'slug',
        'language_school_id',
        'course_type_id',
        'tag_id',
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
        'visible' => 'bool',
    ];

    public function school()
    {
        return $this->belongsTo(LanguageSchool::class, 'language_school_id');
    }

    public function courseType()
    {
        return $this->belongsTo(LanguageCourseType::class, 'course_type_id');
    }

    public function tag()
    {
        return $this->belongsTo(LanguageCourseTag::class, 'tag_id');
    }
}
