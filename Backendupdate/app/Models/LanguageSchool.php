<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en', 'name_ar', 'slug',
        'logo_url', 'has_online', 'status'
    ];

    public function branches()
    {
        return $this->hasMany(LanguageSchoolBranch::class, 'school_id');
    }

    public function onlineCourses(): HasMany
    {
        return $this->hasMany(LanguageOnlineCourse::class, 'language_school_id');
    }

    public function trainingCourses(): HasMany
    {
        return $this->hasMany(LanguageCourseTrainingCourse::class, 'language_school_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
