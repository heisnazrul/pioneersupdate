<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'ar_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function courses()
    {
        return $this->hasMany(UniversityCourse::class);
    }

    public function courseCatalogs()
    {
        return $this->belongsToMany(UniversityCourseCatalog::class, 'university_course_levels', 'level_id', 'course_catalog_id')
            ->withTimestamps();
    }
}
