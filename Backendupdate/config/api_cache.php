<?php

/** Catalog API cache TTL: 30 days (schools, courses, institutes — not auth/booking). */
const API_CATALOG_CACHE_TTL = 2592000;

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

    'default_ttl' => (int) env('API_RESPONSE_CACHE_TTL', API_CATALOG_CACHE_TTL),

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
        'api/coursesat/home*' => API_CATALOG_CACHE_TTL,
        'api/coursesat/language-institutes*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/language-institutes*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/home/*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/online-courses*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/summer-programs*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/training-and-professional-courses*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/articles*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/utilities' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/certificates' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/reviews' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/faqs' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/offers*' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/about' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/university-admissions' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/travel-and-tourism' => API_CATALOG_CACHE_TTL,
        'api/courseenglish/contact-us' => API_CATALOG_CACHE_TTL,
    ],

];
