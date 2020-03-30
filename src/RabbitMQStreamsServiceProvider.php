<?php

namespace rizkyadi487\RabbitMQStreams;

use Illuminate\Support\ServiceProvider;

class RabbitMQStreamsServiceProvider extends ServiceProvider{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(){
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(){
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
    public function provides(){
        return ['rabbitmqstreams'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(){
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/rabbitmqstreams.php' => config_path('rabbitmqstreams.php'),
        ], 'rabbitmqstreams.config');

        $this->commands([
            \rizkyadi487\RabbitMQStreams\Commands\RabbitMQListen::class,
        ]);
    }
}
