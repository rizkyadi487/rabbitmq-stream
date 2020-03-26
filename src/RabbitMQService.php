<?php

namespace rizkyadi487\RabbitMQStreams;

use Symfony\Component\Process\Process;

class RabbitMQService
{
    private $rabbitmqHandlerService;

    private $rabbitServer;
    private $rabbitHost;
    private $binary;
    private $binaryPath;
    private $topic;

    public function __construct($topic = null)
    {
        // $this->rabbitHandlerService = app()->make(RabbitMQHandlerService::class);

        $this->rabbitServer = env('RABBITMQ_HOST', '127.0.0.1') . ':' . env('RABBITMQ_PORT', '5672');
        $this->rabbitHost = env('RABBITMQ_HOST', '127.0.0.1');
        $this->binaryPath = env('RABBITMQ_CONSUMER_BINARY_PATH', '');
        $this->binary = env('RABBITMQ_CONSUMER_BINARY', 'rabbit-console-consumdasder');
        $this->topic = $topic;

    }

    public function consumeTopics()
    {
        if(empty($this->topic))
            throw new \Exception('please specify a topic to consumer with --topic options');

        $this->handleConsumer();
    }

    protected function handleConsumer()
    {
        $process = new Process($this->binaryPath . $this->binary . ' --bootstrap-server ' . $this->kafkaServer . ' --property schema.registry.url=http://' . $this->kafkaHost . ':8081 --topic ' . $this->topic);
        $process->setTimeout(0);

        $process->start();

        foreach ($process as $type => $message) {
            if ($process::OUT === $type) {
                $this->kafkaHandlerService->handleMessage($message, $this->topic);
            } else { // $process::ERR === $type
                echo "\nRead from stderr: " . $message;
            }
        }
    }

    public function getTopics()
    {
        $process = new Process($this->binaryPath . 'kafka-topics --list --zookeeper ' . $this->kafkaHost . ':2181');
        $process->start();
        $topics = [];

        $i = 0;
        foreach ($process as $type => $message) {
            if ($process::OUT === $type) {
                if ($message != "\n") {
                    $topics[$i]['topic_name'] = trim(preg_replace('/\s+/', ' ', $message));
                    $i++;
                }
            } else { // $process::ERR === $type
                echo "\nRead from stderr: " . $message;
            }
        }

        return $topics;
    }

    private function getTablesForStreaming()
    {
        $topics = [];

        $tables = explode(',', env('DEBEZIUM_DB_STREAMING_TABLE'));

        foreach ($tables as $table) {
            $topic = env('DEBEZIUM_DB_SERVER_NAME') . '.' . env('DEBEZIUM_DB_DATABASE') . '.' . $table;
            array_push($topics, $topic);
        }
        return $topics;
    }
}