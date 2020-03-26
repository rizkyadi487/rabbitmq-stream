<?php

namespace rizkyadi487\RabbitMQStreams;

use Illuminate\Support\ServiceProvider;

class RabbitMQStreamsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'rizkyadi487');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'rizkyadi487');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rabbitmqstreams.php', 'rabbitmqstreams');

        // Register the service the package provides.
        $this->app->singleton('rabbitmqstreams', function ($app) {
            return new RabbitMQStreams;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['rabbitmqstreams'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/rabbitmqstreams.php' => config_path('rabbitmqstreams.php'),
        ], 'rabbitmqstreams.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/rizkyadi487'),
        ], 'rabbitmqstreams.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/rizkyadi487'),
        ], 'rabbitmqstreams.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/rizkyadi487'),
        ], 'rabbitmqstreams.views');*/

        // Registering package commands.
        // $this->commands([]);
        $this->commands([
            \rizkyadi487\RabbitMQStreams\Commands\rabbit::class,
        ]);
    }
}
