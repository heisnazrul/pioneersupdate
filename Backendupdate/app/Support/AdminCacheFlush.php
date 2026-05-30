<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class AdminCacheFlush
{
    /**
     * Clear catalog API JSON cache (file + dedicated store).
     *
     * @return list<string> Human-readable steps completed
     */
    public static function flushAll(): array
    {
        $steps = [];

        $apiPath = storage_path('framework/cache/api_responses');
        if (is_dir($apiPath)) {
            File::cleanDirectory($apiPath);
        }
        $steps[] = 'API catalog cache cleared';

        try {
            Cache::store(config('api_cache.store', 'api_responses'))->flush();
            $steps[] = 'API cache store flushed';
        } catch (\Throwable) {
            // Store may not be registered in every environment.
        }

        return $steps;
    }
}
