<?php

return [
    'enabled'     => true,//включить|выключить защиту сайта true|false

    'theme_color' => 'white',//цвет темы на странице проверки капчи white|black

    'description' => '<p>Мы заметили подозрительную активность с вашего IP адреса. Пожалуйста, подтвердите, что вы не робот</p>',//текст, выводится на странице капчи

    'captcha' => [
        'provider'  => 'recaptcha',//сервис капчи recaptcha|yandex
        'providers' => [
            'recaptcha' => [
                'site_key'   => env('RECAPTCHA_SITE_KEY'),
                'secret_key' => env('RECAPTCHA_SECRET_KEY'),
            ],
            'yandex' => [
                'site_key'   => env('YANDEX_SITE_KEY'),
                'secret_key' => env('YANDEX_SECRET_KEY'),
            ],
        ],
    ],

    'bots' => [
        'enabled_all' => true,//включить|выключить отправку на капчу всех, у кого в useragent есть вхождение - bot true|false
        'blocked'     => ['BadBot', 'EvilScraper'],//название ботов для блокировки
        'allowed'     => ['Googlebot', 'Yandexbot'],//название ботов, которых пропускам
    ],

    'rate_limiting' => [
        'max_requests' => 5,//максимальное кол-во запросов
        'time'         => 1//за какое время (секунды)
    ],

    '404_protection' => [
        'max_404_errors' => 5,//максимальное кол-во ответов 404
        'time'           => 15//за какое время (секунды)
    ],

    'exclude_paths' => [
        'health-check',//список урл, к которым не будет применена проверка
    ],
];
