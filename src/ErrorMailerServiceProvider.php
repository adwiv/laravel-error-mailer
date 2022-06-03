<?php

namespace Adwiv\Laravel\ErrorMailer;

use Illuminate\Support\ServiceProvider;

class ErrorMailerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/error-logging.php', 'logging.channels');
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-error-mailer.php', 'laravel-error-mailer');
        $this->loadViewsFrom(__DIR__ . '/../resources', 'error-mailer');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }
}
