<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageSchoolCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'course_category_id',
        'course_name_from_school',
        'course_name_from_school_ar',
        'slug',
        'hours_per_week',
        'lessons_per_week',
        'min_level',
        'min_age',
        'material_books_fee',
        'registration_admin_fee',
        'mandatory_additional_fee_name',
        'mandatory_additional_fee',
        'week_category_1', 'weekly_fee_1',
        'week_category_2', 'weekly_fee_2',
        'week_category_3', 'weekly_fee_3',
        'week_category_4', 'weekly_fee_4',
        'week_category_5', 'weekly_fee_5',
        'week_category_6', 'weekly_fee_6',
        'week_category_7', 'weekly_fee_7',
        'is_active'
    ];

    protected $casts = [
        'hours_per_week'            => 'decimal:2',
        'material_books_fee'        => 'decimal:2',
        'registration_admin_fee'    => 'decimal:2',
        'mandatory_additional_fee'  => 'decimal:2',
        'weekly_fee_1'              => 'decimal:2',
        'weekly_fee_2'              => 'decimal:2',
        'weekly_fee_3'              => 'decimal:2',
        'weekly_fee_4'              => 'decimal:2',
        'weekly_fee_5'              => 'decimal:2',
        'weekly_fee_6'              => 'decimal:2',
        'weekly_fee_7'              => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolBranch::class, 'branch_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LanguageCourseCategory::class, 'course_category_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(LanguageSchoolCoursePromotion::class, 'course_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }
}
