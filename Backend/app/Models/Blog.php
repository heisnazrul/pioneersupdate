<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'ar_title',
        'slug',
        'summary',
        'ar_summary',
        'content',
        'ar_content',
        'category_id',
        'audience_scope',
        'featured_image',
        'published_at',
        'publisher_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public const AUDIENCE_OPTIONS = ['university', 'school', 'all'];

    protected static function booted(): void
    {
        static::creating(function (Blog $blog): void {
            $blog->slug = static::generateSlug($blog->slug, $blog->title);
        });

        static::updating(function (Blog $blog): void {
            $blog->slug = static::generateSlug($blog->slug, $blog->title, $blog->id);
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public static function generateSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = trim($slug ?: Str::slug($title));
        $base = $base !== '' ? Str::limit($base, 255, '') : Str::random(8);

        $candidate = $base;
        $suffix = 1;
        while (
            static::query()
                ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = Str::limit("{$base}-{$suffix}", 255, '');
            if ($candidate === '') {
                $candidate = Str::random(8);
            }
            $suffix++;
        }

        return $candidate;
    }
}
