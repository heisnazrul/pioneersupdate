<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'exchange_rates' => [
        'base_url' => env('EXCHANGE_RATE_API_BASE_URL', 'https://v6.exchangerate-api.com/v6'),
        'api_key' => env('EXCHANGE_RATE_API_KEY'),
        'timeout' => env('EXCHANGE_RATE_API_TIMEOUT', 15),
        'target_currencies' => env('EXCHANGE_RATE_TARGET_CURRENCIES', 'INR,CNY,JPY,PKR,BDT,ZAR,SGD,SAR,KWD,OMR,BHD,QAR,EGP,LYD,IRR,MYR,THB,IDR,PHP,VND,KOR,TWD,AUD,NZD,AFN,LKR,USD,EUR,CAD,CHF,AED'),
        'refresh_time' => env('EXCHANGE_RATE_REFRESH_TIME', '02:00'),
    ],

];
