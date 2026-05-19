<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::creating(function (UniversityAccommodationRoom $room) {
            if (empty($room->slug)) {
                $room->slug = Str::slug($room->title);
            }
        });

        static::updating(function (UniversityAccommodationRoom $room) {
            if (empty($room->slug)) {
                $room->slug = Str::slug($room->title);
            }
        });
    }
}
