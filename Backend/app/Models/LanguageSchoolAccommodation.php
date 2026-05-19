<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSchoolAccommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'language_course_tag_id',
        'bedroom_type_id',
        'bathroom_type_id',
        'meal_plan_id',
        'title',
        'ar_title',
        'required_age',
        'fee_per_week',
        'admin_charge',
        'under18_supplement_per_week',
        'notes',
    ];

    protected $casts = [
        'fee_per_week' => 'decimal:2',
        'admin_charge' => 'decimal:2',
        'under18_supplement_per_week' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function tag()
    {
        return $this->belongsTo(\App\Models\LanguageCourseTag::class, 'language_course_tag_id');
    }

    public function bedroomType()
    {
        return $this->belongsTo(\App\Models\BedroomType::class, 'bedroom_type_id');
    }

    public function bathroomType()
    {
        return $this->belongsTo(\App\Models\BathroomType::class, 'bathroom_type_id');
    }

    public function mealPlan()
    {
        return $this->belongsTo(\App\Models\MealPlan::class, 'meal_plan_id');
    }
}
