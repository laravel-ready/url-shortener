<?php

namespace LaravelReady\UrlShortener;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap of package services
     *
     * @return  void
     */
    public function boot(Router $router): void
    {
        $this->bootPublishes();
        $this->loadRoutes();
    }

    /**
     * Register any application services
     *
     * @return  void
     */
    public function register(): void
    {        // package config file
        $this->mergeConfigFrom(__DIR__ . '/../config/url-shortener.php', 'url-shortener');
    }

    /**
     * Publishes resources on boot
     *
     * @return  void
     */
    private function bootPublishes(): void
    {        // package configs
        $this->publishes([
            __DIR__ . '/../config/url-shortener.php' => $this->app->configPath('url-shortener.php'),
        ], 'url-shortener-config');
        // migrations
        $migrationsPath = __DIR__ . '/../database/migrations/';

        $this->publishes([
            $migrationsPath => database_path('migrations/laravel-ready/url-shortener')
        ], 'url-shortener-migrations');

        $this->loadMigrationsFrom($migrationsPath);
    }
    /**
     * Load pacakge-specific routes
     *
     * @return  void
     */
    private function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
