<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubjectArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'ar_name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (SubjectArea $subjectArea) {
            if (empty($subjectArea->slug)) {
                $subjectArea->slug = Str::slug($subjectArea->name);
            }
        });

        static::updating(function (SubjectArea $subjectArea) {
            if (empty($subjectArea->slug)) {
                $subjectArea->slug = Str::slug($subjectArea->name);
            }
        });
    }

    public function courseCatalogs()
    {
        return $this->hasMany(UniversityCourseCatalog::class);
    }
}
