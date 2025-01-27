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

| Ключ                             | Тип      | Значение            | Что делает                                                                                                           |  
|----------------------------------|----------|---------------------|----------------------------------------------------------------------------------------------------------------------|  
| `enabled`                        | `bool`   | `true/false`        | включение защиты сайта                                                                                               |  
| `theme_color`                    | `string` | `white/black`       | цвет темы на странице проверки капчи                                                                                 |  
| `description`                    | `string` | `<p>Мы заме..</p>`  | текст, выводится на странице капчи                                                                                   |  
| `captcha.provider`               | `string` | `recaptcha/yandex`  | сервис капчи                                                                                                         |  
| `bots.enabled_all`               | `bool`   | `true/false`        | включение отправки на капчу всех, у кого в useragent есть вхождение - bot                                            |  
| `bots.blocked`                   | `array`  | `['Bytespider'...]` | название ботов для блокировки                                                                                        |  
| `bots.allowed`                   | `array`  | `['Yandexbot'...]`  | название ботов, которых пропускам(в случа если включен bots.enabled_all, но нужно пропустить ботов, например яндекс) |  
| `rate_limiting.max_requests`     | `int`    | `5`                 | максимальное кол-во запросов                                                                                         |  
| `rate_limiting.time`             | `int`    | `1`                 | за какое время (секунды)                                                                                             |  
| `404_protection.max_404_errors`  | `int`    | `5`                 | максимальное кол-во ответов 404                                                                                      |  
| `404_protection.time`            | `int`    | `15`                | за какое время (секунды)                                                                                             |
| `exclude_paths`                  | `array`  | `['panel'...]`      | список страниц, которые нужно исключить из защиты                                                                    |

### Рекомендации

Сохранение текущего состояния пользователя(кол-во заходов, черный/белый список) находится в кэше  
Рекомендуется установить кеширование в redis

```
CACHE_DRIVER=redis
```

## Сообщить о проблеме

Если вы обнаружите ошибку или у вас есть предложения по улучшению библиотеки,
пожалуйста [напишите нам](https://github.com/wilfreedi/laravel-site-protection/issues/new/choose)