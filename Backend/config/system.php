<?php

return [
    'email_verification_roles' => [
        'agent',
    ],

    'smtp' => [
        'host' => env('MAIL_HOST', '127.0.0.1'),
        'port' => env('MAIL_PORT', 1025),
        'encryption' => env('MAIL_ENCRYPTION'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'from_name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

    'branding' => [
        'app_name' => env('APP_NAME', 'Laravel'),
        'primary_color' => '#6366f1',
        'logo' => null,
        'favicon' => null,
    ],

    'logo_disk' => env('SYSTEM_SETTINGS_LOGO_DISK', 'public'),

    // Google defaults (optional fallback)
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
    ],
];
