<?php

return [
    'enabled' => true,

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
        'allowed' => ['Googlebot', 'Yandexbot'],
    ],

    'rate_limiting' => [
        'max_requests_per_second' => 5
    ],

    '404_protection' => [
        'max_404_errors' => 3
    ],

    'exclude_paths' => [
        '/health-check',
    ],
];
