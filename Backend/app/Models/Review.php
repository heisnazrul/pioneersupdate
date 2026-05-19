<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'ar_name',
        'photo',
        'institute_name',
        'ar_institute_name',
        'title',
        'ar_title',
        'review_text',
        'ar_review_text',
        'rating',
        'is_approved',
        'university_name',
        'course_name',
        'country_name',
        'video_url',
        'video_iframe',
        'thumbnail',
        'is_active',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true)->orWhere('is_approved', true);
    }
}
