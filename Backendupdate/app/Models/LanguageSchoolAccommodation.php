<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageSchoolAccommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'accommodation_type_id',
        'name',
        'name_ar',
        'bedroom_type_id',
        'bathroom_type_id',
        'meal_plan_id',
        'weekly_fee',
        'admin_fee',
        'security_deposit',
        'min_age',
        'under_18_supplement_fee',
        'summer_supplement_fee',
        'summer_start_date',
        'summer_end_date',
        'winter_supplement_fee',
        'winter_start_date',
        'winter_end_date',
        'other_supplement_name',
        'other_supplement_fee',
        'other_start_date',
        'other_end_date',
        'is_active',
    ];

    protected $casts = [
        'weekly_fee'              => 'decimal:2',
        'admin_fee'               => 'decimal:2',
        'security_deposit'        => 'decimal:2',
        'under_18_supplement_fee' => 'decimal:2',
        'summer_supplement_fee'   => 'decimal:2',
        'winter_supplement_fee'   => 'decimal:2',
        'other_supplement_fee'    => 'decimal:2',
        'summer_start_date'       => 'date',
        'summer_end_date'         => 'date',
        'winter_start_date'       => 'date',
        'winter_end_date'         => 'date',
        'other_start_date'        => 'date',
        'other_end_date'          => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccommodationType::class, 'accommodation_type_id');
    }

    public function bedroomType(): BelongsTo
    {
        return $this->belongsTo(BedroomType::class, 'bedroom_type_id');
    }

    public function bathroomType(): BelongsTo
    {
        return $this->belongsTo(BathroomType::class, 'bathroom_type_id');
    }

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan_id');
    }
}
