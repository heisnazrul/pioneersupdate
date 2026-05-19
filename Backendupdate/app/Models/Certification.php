<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'title_en', 
        'title_ar', 
        'subtitle_en', 
        'subtitle_ar', 
        'image', 
        'link'
    ];
}
