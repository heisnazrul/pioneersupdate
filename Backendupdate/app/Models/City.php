<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ar_name', 'slug', 'description', 'ar_description',
        'country_id', 'latitude', 'longitude', 'display_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
