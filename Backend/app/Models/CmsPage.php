<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'app',
        'slug',
        'title',
        'ar_title',
        'content',
        'ar_content',
        'meta_title',
        'meta_description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeForApp($query, string $app)
    {
        return $query->where('app', $app);
    }
}
