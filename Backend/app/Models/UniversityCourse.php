<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UniversityCourse extends Model
{
    protected $fillable = [
        'university_id',
        'course_catalog_id',
        'level_id',
        'duration_value',
        'duration_unit',
        'overview',
        'ar_overview',
        'awarding_body',
        'ar_awarding_body',
        'first_year_fee',
        'currency',
        'degree_requirement',
        'language_requirement',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_value' => 'integer',
        'first_year_fee' => 'decimal:2',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function courseCatalog()
    {
        return $this->belongsTo(UniversityCourseCatalog::class, 'course_catalog_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function getNameAttribute(): ?string
    {
        return $this->courseCatalog?->name;
    }

    public function getArNameAttribute(): ?string
    {
        return $this->courseCatalog?->ar_name;
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function wishlists()
    {
        return $this->hasMany(UniversityWishlist::class, 'course_id');
    }

    public function intakeTerms()
    {
        return $this->belongsToMany(IntakeTerm::class, 'university_course_intakes')
            ->using(UniversityCourseIntake::class)
            ->withPivot(['deadline_date', 'start_date', 'is_active'])
            ->withTimestamps();
    }
}
