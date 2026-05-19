<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniversityCampus extends Model
{
    protected $fillable = [
        'university_id',
        'city_id',
        'name',
        'ar_name',
        'slug',
        'address',
        'ar_address',
        'lat',
        'lng',
        'is_online',
        'is_active',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'is_online' => 'boolean',
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

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline(Builder $query)
    {
        return $query->where('is_online', true);
    }
}
