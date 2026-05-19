<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class LanguageSchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
        'description',
        'ar_description',
        'logo',
        'accreditation_ids',
        'rating',
        'is_preferred',
    ];

    protected $casts = [
        'accreditation_ids' => 'array',
        'rating' => 'decimal:2',
        'is_preferred' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug) && $model->name) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function branches()
    {
        return $this->hasMany(LanguageSchoolBranch::class);
    }
}
