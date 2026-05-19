<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LanguageSchoolBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_school_id',
        'city_id',
        'slug',
        'description',
        'ar_description',
        'gallery_urls',
        'video_url',
    ];

    protected $casts = [
        'gallery_urls' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug) && $model->id) {
                $model->slug = Str::slug($model->id);
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(LanguageSchool::class, 'language_school_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function courses()
    {
        return $this->hasMany(LanguageSchoolCourse::class, 'branch_id');
    }

    // Alias for nested whereHas chains
    public function languageSchoolCourses()
    {
        return $this->hasMany(LanguageSchoolCourse::class, 'branch_id');
    }

    public function registrationFees()
    {
        return $this->hasMany(LanguageSchoolBranchRegistrationFee::class, 'branch_id');
    }

    public function highSeasonFees()
    {
        return $this->hasMany(LanguageSchoolBranchHighSeasonFee::class, 'branch_id');
    }

    public function insuranceFees()
    {
        return $this->hasMany(LanguageSchoolInsuranceFee::class, 'branch_id');
    }

    public function supplements()
    {
        return $this->hasMany(LanguageSchoolSupplement::class, 'branch_id');
    }

    public function pickups()
    {
        return $this->hasMany(LanguageSchoolPickup::class, 'branch_id');
    }

    public function accommodations()
    {
        return $this->hasMany(LanguageSchoolAccommodation::class, 'branch_id');
    }

    public function summerCamps()
    {
        return $this->hasMany(LanguageCourseSummerCamp::class, 'branch_id');
    }
}
