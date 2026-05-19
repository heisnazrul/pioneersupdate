<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniversityCourseCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_area_id',
        'name',
        'ar_name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (UniversityCourseCatalog $catalog) {
            if (empty($catalog->slug)) {
                $catalog->slug = Str::slug($catalog->name);
            }
        });

        static::updating(function (UniversityCourseCatalog $catalog) {
            if (empty($catalog->slug)) {
                $catalog->slug = Str::slug($catalog->name);
            }
        });
    }

    public function subjectArea()
    {
        return $this->belongsTo(SubjectArea::class);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'university_course_levels', 'course_catalog_id', 'level_id')
            ->withTimestamps();
    }

    public function universityCourses()
    {
        return $this->hasMany(UniversityCourse::class, 'course_catalog_id');
    }
}
