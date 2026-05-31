<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LanguageSchoolBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'city_id', 'slug',
        'about_en', 'about_ar',
        'new_year_close_from', 'new_year_close_to',
        'branch_images', 'is_active'
    ];

    protected $casts = [
        'branch_images' => 'array',
        'new_year_close_from' => 'date',
        'new_year_close_to' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(LanguageSchool::class, 'school_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function accreditations(): BelongsToMany
    {
        return $this->belongsToMany(
            Accreditation::class,
            'accreditation_language_school_branch',
            'language_school_branch_id',
            'accreditation_id'
        )->withTimestamps();
    }

    public function courses()
    {
        return $this->hasMany(LanguageSchoolCourse::class, 'branch_id');
    }

    public function pickups()
    {
        return $this->hasMany(LanguageSchoolPickup::class, 'branch_id');
    }

    public function accommodations()
    {
        return $this->hasMany(LanguageSchoolAccommodation::class, 'branch_id');
    }

    public function insurance()
    {
        return $this->hasOne(LanguageSchoolInsurance::class, 'branch_id');
    }

    public function summerCamps()
    {
        return $this->hasMany(LanguageCourseSummerCamp::class, 'branch_id');
    }

    public function trainingCourses()
    {
        return $this->hasMany(LanguageCourseTrainingCourse::class, 'branch_id');
    }
}
