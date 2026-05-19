<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
        'description',
        'ar_description',
        'country_id',
        'latitude',
        'longitude',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'display_order' => 'int',
        'is_active' => 'bool',
    ];

    protected static function booted(): void
    {
        static::creating(function (City $city): void {
            $city->slug = static::generateSlug($city->slug, $city->name);
        });

        static::updating(function (City $city): void {
            $city->slug = static::generateSlug($city->slug, $city->name, $city->id);
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function languageSchoolBranches()
    {
        return $this->hasMany(LanguageSchoolBranch::class, 'city_id');
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
