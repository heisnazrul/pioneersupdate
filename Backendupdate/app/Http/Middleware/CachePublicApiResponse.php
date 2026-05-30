<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CachePublicApiResponse
{
    /** @var list<string> */
    private const NEVER_CACHE_PREFIXES = [
        'api/auth',
        'api/courseenglish/auth',
        'api/university/auth',
        'api/courseenglish/booking',
        'api/courseenglish/interactions',
        'api/coursesat/bookings',
        'api/student',
        'api/agent',
    ];

    /** @var list<string> */
    private const NEVER_CACHE_EXACT = [
        'api/courseenglish/referrals/track-click',
        'api/courseenglish/referrals/resolve',
        'api/courseenglish/contact-us/submit',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldAttemptCache($request)) {
            return $next($request);
        }

        $ttl = $this->ttlFor($request);
        if ($ttl <= 0) {
            return $next($request);
        }

        $store = (string) config('api_cache.store', 'api_responses');
        $key = $this->cacheKey($request);

        $cached = Cache::store($store)->get($key);
        if (is_array($cached) && isset($cached['body'], $cached['status'])) {
            return response($cached['body'], (int) $cached['status'], $cached['headers'] ?? [])
                ->header('X-Cache', 'HIT')
                ->header('X-Cache-TTL', (string) $ttl)
                ->header('Content-Type', 'application/json');
        }

        /** @var Response $response */
        $response = $next($request);

        if ($this->shouldStore($response)) {
            Cache::store($store)->put($key, [
                'body' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => [
                    'Content-Type' => $response->headers->get('Content-Type', 'application/json'),
                ],
            ], $ttl);

            $response->headers->set('X-Cache', 'MISS');
            $response->headers->set('X-Cache-TTL', (string) $ttl);
        }

        return $response;
    }

    private function shouldAttemptCache(Request $request): bool
    {
        if (! config('api_cache.enabled', true)) {
            return false;
        }

        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->bearerToken() || $request->user()) {
            return false;
        }

        $path = strtolower(trim($request->path(), '/'));

        foreach (self::NEVER_CACHE_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return false;
            }
        }

        if (in_array($path, self::NEVER_CACHE_EXACT, true)) {
            return false;
        }

        return $this->matchesCacheablePattern($path);
    }

    private function matchesCacheablePattern(string $path): bool
    {
        foreach (config('api_cache.patterns', []) as $pattern) {
            if ($this->pathMatches($path, strtolower((string) $pattern))) {
                return true;
            }
        }

        return false;
    }

    private function pathMatches(string $path, string $pattern): bool
    {
        $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';

        return (bool) preg_match($regex, $path);
    }

    private function ttlFor(Request $request): int
    {
        $path = strtolower(trim($request->path(), '/'));
        $rules = config('api_cache.ttl_rules', []);

        foreach ($rules as $pattern => $ttl) {
            if ($this->pathMatches($path, strtolower((string) $pattern))) {
                return max(0, (int) $ttl);
            }
        }

        return max(0, (int) config('api_cache.default_ttl', 2592000));
    }

    private function shouldStore(Response $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));

        return str_contains($contentType, 'json') || $contentType === '';
    }

    private function cacheKey(Request $request): string
    {
        $locale = strtolower((string) ($request->header('X-Lang')
            ?: $request->header('Accept-Language')
            ?: 'en'));

        $locale = substr(preg_replace('/[^a-z-]/', '', explode(',', $locale)[0] ?? 'en') ?: 'en', 0, 8);

        $query = $request->getQueryString() ?? '';

        return 'api_resp:' . sha1(strtolower($request->path()) . '|' . $query . '|' . $locale);
    }
}
