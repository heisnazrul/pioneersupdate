<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'established_year' => 'integer',
        'qs_ranking' => 'integer',
        'the_ranking' => 'integer',
        'shanghai_ranking' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
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

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function courses()
    {
        return $this->hasMany(UniversityCourse::class);
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    public static function generateSlug(?string $slug, string $name, ?int $ignoreId = null): string
    {
        $base = trim($slug ?: Str::slug($name));
        $base = $base !== '' ? Str::limit($base, 255, '') : Str::random(8);
        $candidate = $base;
        $suffix = 1;

        while (
            static::withTrashed()
                ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = Str::limit("{$base}-{$suffix}", 255, '');
            $suffix++;
        }

        return $candidate;
    }
}
