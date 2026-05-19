<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'slug',
        'city',
        'ar_city',
        'country',
        'ar_country',
        'address',
        'ar_address',
        'phone',
        'email',
        'type',
        'image',
        'map_url',
        'description',
        'ar_description',
        'hours',
        'ar_hours',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
