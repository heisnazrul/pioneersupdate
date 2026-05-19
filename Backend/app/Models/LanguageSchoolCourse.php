<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LanguageSchoolCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'language_course_type_id',
        'language_course_tag_id',
        'slug',
        'name',
        'ar_name',
        'description',
        'ar_description',
        'start_day',
        'required_level',
        'study_time',
        'lessons_per_week',
        'min_age',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug) && $model->name) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function type()
    {
        return $this->belongsTo(LanguageCourseType::class, 'language_course_type_id');
    }

    public function tag()
    {
        return $this->belongsTo(LanguageCourseTag::class, 'language_course_tag_id');
    }

    public function fees()
    {
        return $this->hasMany(LanguageSchoolCourseFee::class, 'language_school_course_id');
    }

    public function materialFees()
    {
        return $this->hasMany(LanguageSchoolCourseMaterialFee::class, 'language_school_course_id');
    }
}
