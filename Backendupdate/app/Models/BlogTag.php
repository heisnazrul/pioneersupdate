<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'ar_name', 'description', 'ar_description',
        'color', 'display_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_blog_tag');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
