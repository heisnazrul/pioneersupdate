<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', 'ar_category',
        'question', 'ar_question',
        'answer', 'ar_answer',
        'display_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
