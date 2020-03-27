<?php

namespace rizkyadi487\RabbitMQStreams\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Process\Process;

class RabbitMQService
{
    private $rabbitmqHandlerService;

    private $rabbitHost;
    private $rabbitPort;
    private $rabbitUsername;
    private $rabbitPassword;
    private $rabbitExname;
    private $rabbitExtype;

    public function __construct($exname = null, $extype = null)
    {
        $this->rabbitmqHandlerService = app()->make(RabbitMQHandlerService::class);

        $this->rabbitHost = env('RABBITMQ_HOST', '127.0.0.1');
        $this->rabbitPort = env('RABBITMQ_PORT', '5672');
        $this->rabbitUsername = env('RABBITMQ_USERNAME', 'guest');
        $this->rabbitPassword = env('RABBITMQ_PASSWORD', 'guest');
        $this->rabbitExname = $exname;
        $this->rabbitExtype = $extype;
    }

    public function consumeTopics()
    {
        $this->handleConsumer();
    }

    protected function handleConsumer()
    {
         $connection = new AMQPStreamConnection(
            $this->rabbitHost, 
            $this->rabbitPort, 
            $this->rabbitUsername, 
            $this->rabbitPassword
        );

        $channel = $connection->channel();

        $channel->exchange_declare(
            $this->rabbitExname, 
            $this->rabbitExtype, 
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

        $channel->queue_bind($queue_name, $this->rabbitExname);
        echo "Waiting for logs. To exit press CTRL+C";

        $callback = function($msg){
            $this->rabbitmqHandlerService->handleMessage($msg->body);
            // print "Read: " . $msg->body . PHP_EOL;
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
        // $process = new Process($this->binaryPath . $this->binary . ' --bootstrap-server ' . $this->kafkaServer . ' --property schema.registry.url=http://' . $this->kafkaHost . ':8081 --topic ' . $this->rabbitExname);
        // $process->setTimeout(0);

        // $process->start();

        // foreach ($process as $type => $message) {
        //     if ($process::OUT === $type) {
        //         $this->kafkaHandlerService->handleMessage($message, $this->rabbitExname);
        //     } else { // $process::ERR === $type
        //         echo "\nRead from stderr: " . $message;
        //     }
        // }
    }

    public function getTopics()
    {
        //memberikan list exchangename
    }

    // private function getTablesForStreaming()
    // {
    //     $topics = [];

    //     $tables = explode(',', env('DEBEZIUM_DB_STREAMING_TABLE'));

    //     foreach ($tables as $table) {
    //         $topic = env('DEBEZIUM_DB_SERVER_NAME') . '.' . env('DEBEZIUM_DB_DATABASE') . '.' . $table;
    //         array_push($topics, $topic);
    //     }
    //     return $topics;
    // }
}