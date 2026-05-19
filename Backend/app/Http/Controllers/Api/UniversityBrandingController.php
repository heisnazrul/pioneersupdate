<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class UniversityBrandingController extends Controller
{
    private function shouldNormalizeAssetKey(?string $key): bool
    {
        if (!$key) {
            return false;
        }

        $assetKeys = [
            'logo',
            'main',
            'ar',
            'icon',
            'flag',
            'image',
            'thumbnail',
            'favicon',
            'logo_url',
            'favicon_url',
        ];

        return in_array($key, $assetKeys, true);
    }

    private function resolveCmsAsset(?string $path): ?string
    {
        if (!$path) {
            return $path;
        }

        $trimmed = trim($path);
        if ($trimmed === '') {
            return $trimmed;
        }

        if (Str::startsWith($trimmed, ['http://', 'https://'])) {
            return $trimmed;
        }

        // Keep frontend-static paths as-is (e.g. /logo.png, /assets/flags/gb.svg).
        // Only prefix backend origin for storage assets.
        if (Str::startsWith($trimmed, '/storage/')) {
            return rtrim(config('app.url'), '/') . $trimmed;
        }

        if (Str::startsWith($trimmed, '/')) {
            return $trimmed;
        }

        $clean = ltrim($trimmed, '/');
        if (Str::startsWith($clean, 'storage/')) {
            return rtrim(config('app.url'), '/') . '/' . $clean;
        }

        return rtrim(config('app.url'), '/') . '/storage/' . $clean;
    }

    private function normalizeCmsAssets($value, ?string $key = null)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normalizeCmsAssets($item, is_string($key) ? $key : null);
            }

            return $value;
        }

        if (is_string($value)) {
            if ($this->shouldNormalizeAssetKey($key)) {
                return $this->resolveCmsAsset($value);
            }

            return $value;
        }

        return $value;
    }

    public function show(): JsonResponse
    {
        $branding = Setting::get('branding_university', []);
        if (empty($branding)) {
            $branding = Setting::get('branding', []);
        }

        return response()->json([
            'branding' => $this->normalizeCmsAssets($branding),
        ]);
    }
}
