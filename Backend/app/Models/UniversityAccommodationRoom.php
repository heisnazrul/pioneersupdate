<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityAccommodationRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'ar_title',
        'slug',
        'description',
        'ar_description',
        'price',
        'features',
        'image',
        'details',
        'ar_details',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
