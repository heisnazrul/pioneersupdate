<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedList extends Model
{
    protected $fillable = [
        'key',
        'name',
        'ar_name',
        'is_active',
    ];
}
