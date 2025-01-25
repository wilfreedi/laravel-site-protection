<h1 align="center">
  <a href="https://github.com/wilfreedi/laravel-site-protection">
    Laravel Site Protection
  </a>
</h1>
<p align="center">
  <a href="LICENSE"><img alt="Packagist License" src="https://img.shields.io/packagist/l/wilfreedi/laravel-site-protection"></a>
  <a href="https://packagist.org/packages/wilfreedi/laravel-site-protection"><img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/wilfreedi/laravel-site-protection"></a>
  <a href="https://packagist.org/packages/wilfreedi/laravel-site-protectioni"><img alt="Packagist Version" src="https://img.shields.io/packagist/v/wilfreedi/laravel-site-protection"></a>
</p>
<p align="center">
Базовая защита сайта на laravel
</p>

# Простоя защита сайта на laravel 

Возможности
1) Установить лимит на кол-во заходов пользователя за n времени
2) Установить лимит на кол-во заходов пользователя на старницу 404
3) Идентификация ботов по useragent  

При достижении лимитов или идентификации бота, происходит редирект на страницу проверки с капчей, после успешной прохождении капчи, ip адрес заносится в белый список на 1 час

## Установка

```composer require wilfreedi/laravel-site-protection```

### Добавление middleware

Добавить middleware в `app/Http/Kernel.php` или на конкретный маршрут

```php
protected $middlewareGroups = [
    'web' => [
        ...
        \Wilfreedi\SiteProtection\Middleware\SiteProtectionMiddleware::class
    ],
    ...
];
```

## Публикация файла настроек

``php artisan vendor:publish --provider="Wilfreedi\SiteProtection\SiteProtectionServiceProvider"``

### Что входит в настройки

`enabled` - включение защиты сайта `true|false`  
`theme_color` - цвет темы на странице проверки капчи `white|black`  
`description` - текст, выводится на странице капчи `string`  
`captcha.provider` - сервис капчи `recaptcha|yandex`  
`bots.enabled_all` - включить|выключить отправку на капчу всех, у кого в useragent есть вхождение - bot `true|false`  
`bots.blocked` - название ботов для блокировки `array`  
`bots.allowed` - название ботов, которых пропускам(в случа если включен bots.enabled_all, но нужно пропустить ботов, например яндекс) `array`  
`rate_limiting.max_requests` - максимальное кол-во запросов `int`  
`rate_limiting.time` - за какое время (секунды) `int`  
`404_protection.max_404_errors` - максимальное кол-во ответов 404 `int`  
`404_protection.time` - за какое время (секунды) `int`  

Сохранение текущего состояния пользователя(кол-во заходов, черный/белый список) находится в кэше  
Рекомендуется установить кеширование в redis

```
CACHE_DRIVER=redis
```

## Сообщить о проблеме

Если вы обнаружите ошибку или у вас есть предложения по улучшению библиотеки,
пожалуйста [напишите нам](https://github.com/wilfreedi/laravel-site-protection/issues/new/choose)