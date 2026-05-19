<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    private const CACHE_KEY = 'settings.cache';
    private const CACHE_TTL = 3600;

    public static function get(string $key, $default = null)
    {
        $settings = self::allCached();

        return $settings[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::query()
                ->get()
                ->keyBy('key')
                ->map(fn(Setting $setting) => $setting->value)
                ->toArray();
        });
    }
}
