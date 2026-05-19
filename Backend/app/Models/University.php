<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
        'logo',
        'cover_image',
        'country_id',
        'city_id',
        'type',
        'established_year',
        'website',
        'qs_ranking',
        'the_ranking',
        'shanghai_ranking',
        'famous_for',
        'ar_famous_for',
        'fees',
        'ar_fees',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'established_year' => 'integer',
        'qs_ranking' => 'integer',
        'the_ranking' => 'integer',
        'shanghai_ranking' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (University $university) {
            $university->slug = static::generateSlug($university->slug, $university->name);
        });

        static::updating(function (University $university) {
            $university->slug = static::generateSlug($university->slug, $university->name, $university->id);
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function courses()
    {
        return $this->hasMany(UniversityCourse::class);
    }

    public function campuses()
    {
        return $this->hasMany(UniversityCampus::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public static function generateSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = trim($slug ?: Str::slug($title));
        $base = $base !== '' ? Str::limit($base, 255, '') : Str::random(8);

        $candidate = $base;
        $suffix = 1;
        while (
            static::withTrashed()
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
