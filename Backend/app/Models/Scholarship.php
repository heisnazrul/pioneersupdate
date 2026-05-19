<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Scholarship extends Model
{
    protected $fillable = [
        'university_id',
        'provider_name',
        'ar_provider_name',
        'name',
        'ar_name',
        'slug',
        'summary',
        'ar_summary',
        'description',
        'ar_description',
        'amount_type',
        'amount_value',
        'currency',
        'min_amount',
        'max_amount',
        'deadline_date',
        'eligible_nationalities',
        'eligibility_text',
        'ar_eligibility_text',
        'apply_link',
        'is_active',
        'tags',
    ];

    protected $casts = [
        'amount_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'deadline_date' => 'date',
        'eligible_nationalities' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }
}
