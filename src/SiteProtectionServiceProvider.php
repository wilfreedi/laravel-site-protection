<?php

namespace Wilfreedi\SiteProtection;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Wilfreedi\SiteProtection\Exceptions\Handler;

class SiteProtectionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Публикация конфигурации
        $this->publishes([
            __DIR__ . '/../config/siteprotection.php' => config_path('siteprotection.php'),
        ], 'config');

        // Публикация JS-файла
        $this->publishes([
            __DIR__ . '/../public/site-protection/js/script.js' => public_path('site-protection/js/script.js'),
        ], 'site-protection-assets');

        // Загрузка маршрутов
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Загрузка представлений
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'siteprotection');

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/siteprotection.php',
            'siteprotection'
        );

        $this->app->singleton(ExceptionHandler::class, Handler::class);

    }
}