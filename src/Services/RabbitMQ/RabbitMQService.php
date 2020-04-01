<?php

namespace rizkyadi487\RabbitMQStreams\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\Process\Process;

class RabbitMQService{
    private $rabbitmqHandlerService;

    private $rabbitHost;
    private $rabbitPort;
    private $rabbitUsername;
    private $rabbitPassword;
    private $rabbitQname;
    private $rabbitDebug;

    public function __construct($qname = null,$debug = null){
        $this->rabbitmqHandlerService = app()->make(RabbitMQHandlerService::class);

        $this->rabbitHost = env('RABBITMQ_HOST', '127.0.0.1');
        $this->rabbitPort = env('RABBITMQ_PORT', '5672');
        $this->rabbitUsername = env('RABBITMQ_USERNAME', 'guest');
        $this->rabbitPassword = env('RABBITMQ_PASSWORD', 'guest');
        $this->rabbitQname = $qname;
        $this->rabbitDebug = $debug;
    }

    public function consumeTopics(){
        $this->handleConsumer();
    }

    protected function handleConsumer(){
         $connection = new AMQPStreamConnection(
            $this->rabbitHost, 
            $this->rabbitPort, 
            $this->rabbitUsername, 
            $this->rabbitPassword
        );

        $channel = $connection->channel();

        $channel->queue_declare(
        $queue = $this->rabbitQname,
        $passive = false,
        $durable = true,
        $exclusive = false,
        $auto_delete = false,
        $nowait = false,
        $arguments = null,
        $ticket = null);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function($msg){
            $this->rabbitmqHandlerService->handleMessage($msg->body);
            if($this->rabbitDebug == 'true'){
            echo " [x] Received ", $msg->body, "\n";
            echo " [x] Done", "\n";
            }
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);

        $channel->basic_consume(
            $queue = $this->rabbitQname,
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );

        while (count($channel->callbacks)) 
        {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function getTopics(){
        //memberikan list exchangename
    }

    private function getTablesForStreaming(){

    }
}