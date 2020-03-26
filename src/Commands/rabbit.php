<?php

namespace rizkyadi487\RabbitMQStreams\Commands;

use Illuminate\Console\Command;

class rabbit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:rabbitmq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        define('RABBITMQ_HOST', '172.17.1.35');
        define('RABBITMQ_PORT', '5672');
        define('RABBITMQ_USERNAME', 'rizky');
        define('RABBITMQ_PASSWORD', 'rizky123123');
        define('EXCHANGE_NAME', 'maxwell');

        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
            RABBITMQ_HOST, 
            RABBITMQ_PORT, 
            RABBITMQ_USERNAME, 
            RABBITMQ_PASSWORD
        );

        $channel = $connection->channel();

        # Create the exchange if it doesnt exist already.
        $channel->exchange_declare(
            EXCHANGE_NAME, 
            'fanout', # type
            false,    # passive
            false,    # durable
            false     # auto_delete
        );

        list($queue_name, ,) = $channel->queue_declare(
            "",    # queue
            false, # passive
            false, # durable
            true,  # exclusive
            false  # auto delete
        );

        $channel->queue_bind($queue_name, 'maxwell');
        print 'Waiting for logs. To exit press CTRL+C' . PHP_EOL;

        $callback = function($msg){
            print "Read: " . $msg->body . PHP_EOL;
        };

        $channel->basic_consume(
            $queue_name, 
            '', 
            false, 
            true, 
            false, 
            false, 
            $callback
        );

        while (count($channel->callbacks)) 
        {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
