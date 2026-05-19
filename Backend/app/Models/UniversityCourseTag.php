<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityCourseTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'ar_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
