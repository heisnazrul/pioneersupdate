<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
        'description',
        'ar_description',
        'color',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'display_order' => 'int',
    ];

    public static function booted(): void
    {
        static::creating(function (BlogCategory $category): void {
            $category->slug = static::generateSlug($category->slug, $category->name);
        });

        static::updating(function (BlogCategory $category): void {
            $category->slug = static::generateSlug($category->slug, $category->name, $category->id);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public static function generateSlug(?string $proposed, string $fallback, ?int $ignoreId = null): string
    {
        $slug = trim($proposed ?: Str::slug($fallback));
        $slug = $slug !== '' ? Str::limit($slug, 255, '') : Str::random(8);

        $base = $slug;
        $suffix = 1;

        while (
            static::query()
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $candidate = Str::limit("{$base}-{$suffix}", 255, '');
            if ($candidate === '') {
                $candidate = Str::random(8);
            }
            $slug = $candidate;
            $suffix++;
        }

        return $slug;
    }
}
