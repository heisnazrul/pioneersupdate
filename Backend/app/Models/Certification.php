<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'title',
        'ar_title',
        'subtitle',
        'ar_subtitle',
        'certificate_image',
        'certification_link',
    ];

    // No is_active in Passport schema
}
