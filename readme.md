# RabbitMQStreams

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Requirements
Laravel 5.8

## Installation

Via Composer

``` bash
$ composer require rizkyadi487/rabbitmqstreams
```

## Configure RabbitMQ and RabbitMQStreams
``` bash
RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USERNAME=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_QUEUE_NAME=demoqueue
RABBITMQ_CONNECTION=mysql
RABBITMQ_PRIMARY_KEY=id
```

## Command
``` bash
php artisan rabbitmq:listen 
```
| option | function |
--- | --- |
| --queuename | for specific queue |
| --debug | for showing the messages |

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits


## License

license. Please see the [license file](license.md) for more information.

This package inspired from https://github.com/michael158/laravel-debeziumstream
[ico-version]: https://img.shields.io/packagist/v/rizkyadi487/rabbitmqstreams.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rizkyadi487/rabbitmqstreams.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rizkyadi487/rabbitmqstreams/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/rizkyadi487/rabbitmqstreams
[link-downloads]: https://packagist.org/packages/rizkyadi487/rabbitmqstreams
[link-travis]: https://travis-ci.org/rizkyadi487/rabbitmqstreams
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/rizkyadi487
[link-contributors]: ../../contributors
