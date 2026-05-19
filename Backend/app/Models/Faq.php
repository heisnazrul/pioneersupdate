<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'category',
        'ar_category',
        'question',
        'ar_question',
        'answer',
        'ar_answer',
    ];

    // No is_active in Passport schema, removed here.

    public function scopeByCategory(Builder $query, $category)
    {
        return $query->where('category', $category);
    }
}
