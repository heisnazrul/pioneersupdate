<?php

/** Catalog API cache TTL: 30 days (schools, courses, institutes — not auth/booking). */
$catalogCacheTtl = 2592000;

return [

    /*
    |--------------------------------------------------------------------------
    | Public API response cache (file store — no database)
    |--------------------------------------------------------------------------
    |
    | Caches JSON for catalog/read-only GET endpoints (schools, courses, etc.).
    | Stored on disk: storage/framework/cache/api_responses/
    | Auth, booking, OTP, interactions, and referrals are never cached.
    |
    */

    'enabled' => env('API_RESPONSE_CACHE_ENABLED', true),

    'store' => env('API_RESPONSE_CACHE_STORE', 'api_responses'),

    'default_ttl' => (int) env('API_RESPONSE_CACHE_TTL', $catalogCacheTtl),

    /*
    | Path patterns relative to site root (e.g. api/coursesat/home).
    | * matches any segment.
    */
    'patterns' => [
        'api/coursesat/home*',
        'api/coursesat/language-institutes*',
        'api/courseenglish/utilities',
        'api/courseenglish/home/*',
        'api/courseenglish/certificates',
        'api/courseenglish/reviews',
        'api/courseenglish/faqs',
        'api/courseenglish/offers',
        'api/courseenglish/offer',
        'api/courseenglish/language-institutes*',
        'api/courseenglish/online-courses*',
        'api/courseenglish/summer-programs*',
        'api/courseenglish/training-and-professional-courses*',
        'api/courseenglish/articles*',
        'api/courseenglish/about',
        'api/courseenglish/university-admissions',
        'api/courseenglish/travel-and-tourism',
        'api/courseenglish/contact-us',
    ],

    /*
    | Per-pattern TTL overrides (seconds). First match wins.
    | Catalog data defaults to 30 days — flush via admin or cache:clear-api after edits.
    */
    'ttl_rules' => [
        'api/coursesat/home*' => $catalogCacheTtl,
        'api/coursesat/language-institutes*' => $catalogCacheTtl,
        'api/courseenglish/language-institutes*' => $catalogCacheTtl,
        'api/courseenglish/home/*' => $catalogCacheTtl,
        'api/courseenglish/online-courses*' => $catalogCacheTtl,
        'api/courseenglish/summer-programs*' => $catalogCacheTtl,
        'api/courseenglish/training-and-professional-courses*' => $catalogCacheTtl,
        'api/courseenglish/articles*' => $catalogCacheTtl,
        'api/courseenglish/utilities' => $catalogCacheTtl,
        'api/courseenglish/certificates' => $catalogCacheTtl,
        'api/courseenglish/reviews' => $catalogCacheTtl,
        'api/courseenglish/faqs' => $catalogCacheTtl,
        'api/courseenglish/offers*' => $catalogCacheTtl,
        'api/courseenglish/about' => $catalogCacheTtl,
        'api/courseenglish/university-admissions' => $catalogCacheTtl,
        'api/courseenglish/travel-and-tourism' => $catalogCacheTtl,
        'api/courseenglish/contact-us' => $catalogCacheTtl,
    ],

];
