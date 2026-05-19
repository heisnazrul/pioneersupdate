<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BathroomType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bathroom_code',
        'name',
        'ar_name',
        'description',
        'ar_description',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
