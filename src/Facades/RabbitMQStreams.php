<?php

namespace rizkyadi487\RabbitMQStreams\Facades;

use Illuminate\Support\Facades\Facade;

class RabbitMQStreams extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rabbitmqstreams';
    }
}
