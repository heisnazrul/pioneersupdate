<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ar_name', 'auxiliary_name', 'ar_auxiliary_name', 'slug', 'flag', 'country_code',
        'is_popular', 'currency_code', 'phone_code',
        'description', 'ar_description', 'capital', 'continent',
        'display_order', 'is_active',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    /**
     * Admin-uploaded flag path, or default SVG from storage/app/public/flags/{code}.svg
     */
    public function resolveFlagPath(): ?string
    {
        if (filled($this->flag)) {
            return $this->flag;
        }

        $code = strtolower(trim((string) $this->country_code));
        if ($code === '') {
            return null;
        }

        foreach (['svg', 'png', 'jpg', 'jpeg', 'webp'] as $extension) {
            $path = "flags/{$code}.{$extension}";
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    protected function resolvedFlag(): Attribute
    {
        return Attribute::get(fn () => $this->resolveFlagPath());
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }
}
