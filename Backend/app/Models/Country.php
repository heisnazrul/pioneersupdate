<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ar_name',
        'slug',
        'flag',
        'country_code',
        'currency_code',
        'phone_code',
        'description',
        'ar_description',
        'capital',
        'continent',
        'display_order',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'is_popular' => 'bool',
        'display_order' => 'int',
    ];

    protected static function booted(): void
    {
        static::creating(function (Country $country): void {
            $country->slug = static::generateSlug($country->slug, $country->name);
        });

        static::updating(function (Country $country): void {
            $country->slug = static::generateSlug($country->slug, $country->name, $country->id);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function languageSchoolBranches()
    {
        return $this->hasManyThrough(LanguageSchoolBranch::class, City::class, 'country_id', 'city_id');
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
