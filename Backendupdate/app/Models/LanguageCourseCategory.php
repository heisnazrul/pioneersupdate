<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageCourseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'is_active'
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(LanguageSchoolCourse::class, 'course_category_id');
    }

    public function onlineCourses(): HasMany
    {
        return $this->hasMany(LanguageOnlineCourse::class, 'course_type_id');
    }

    public function summerCamps(): HasMany
    {
        return $this->hasMany(LanguageCourseSummerCamp::class, 'course_type_id');
    }

    public function trainingCourses(): HasMany
    {
        return $this->hasMany(LanguageCourseTrainingCourse::class, 'course_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }
}
