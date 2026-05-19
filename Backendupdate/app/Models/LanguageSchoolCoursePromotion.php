<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageSchoolCoursePromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'promotion_percentage',
        'promo_from',
        'promo_to',
        'is_active'
    ];

    protected $casts = [
        'promotion_percentage' => 'decimal:2',
        'promo_from'           => 'date',
        'promo_to'             => 'date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(LanguageSchoolCourse::class, 'course_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }
}
