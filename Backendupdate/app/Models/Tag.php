<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
    ];

    public function languageSchoolCourses(): BelongsToMany
    {
        return $this->belongsToMany(
            LanguageSchoolCourse::class,
            'language_school_course_tag',
            'tag_id',
            'language_school_course_id'
        )->withTimestamps();
    }
}
