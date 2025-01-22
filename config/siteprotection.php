<?php

return [
    'enabled' => true,

    'logging' => [
        'enabled' => true,
        'driver'  => 'daily',
    ],

    'captcha' => [
        'provider'  => 'recaptcha',
        'providers' => [
            'recaptcha' => [
                'site_key'   => env('RECAPTCHA_SITE_KEY'),
                'secret_key' => env('RECAPTCHA_SECRET_KEY'),
            ],
        ],
    ],

    'bots' => [
        'blocked' => ['BadBot', 'EvilScraper'],
        'allowed' => ['Googlebot', 'Bingbot'],
    ],

    'rate_limiting' => [
        'max_requests_per_second' => 5,
        'block_time_minutes'      => 15,
    ],

    'geo_protection' => [
        'enabled'         => true,
        'block_countries' => ['RU', 'CN'],
    ],

    'exclude_paths' => [
        '/health-check',
    ],
];
