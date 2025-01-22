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
                             __DIR__.'/../config/siteprotection.php' => config_path('siteprotection.php'),
                         ], 'config');

        // Загрузка маршрутов
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Загрузка представлений
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'siteprotection');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/siteprotection.php',
            'siteprotection'
        );

        $this->app->singleton(ExceptionHandler::class, Handler::class);

    }
}