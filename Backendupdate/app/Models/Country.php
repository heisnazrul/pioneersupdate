<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ar_name', 'auxiliary_name', 'ar_auxiliary_name', 'slug', 'flag', 'country_code',
        'is_popular', 'currency_code', 'phone_code',
        'description', 'ar_description', 'capital', 'continent',
        'display_order', 'is_active',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }
}
