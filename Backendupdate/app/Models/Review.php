<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ar_name', 'photo', 'institute_name', 'ar_institute_name',
        'title', 'ar_title', 'review_text', 'ar_review_text', 'gender', 'rating',
        'facebook_link', 'twitter_link', 'instagram_link', 'linkedin_link',
        'screenshots', 'video', 'video_url', 'video_iframe', 'thumbnail',
        'university_name', 'course_name', 'country_name',
        'is_approved', 'is_active',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'is_approved' => 'boolean',
        'is_active'   => 'boolean',
    ];
}
