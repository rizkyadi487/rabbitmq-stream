<?php

namespace rizkyadi487\RabbitMQStreams\Commands;

use Illuminate\Console\Command;
use \rizkyadi487\RabbitMQStreams\Services\RabbitMQ\RabbitMQService;

class RabbitMQListen extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen {--queuename=} {--debug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listening RabbitMQ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $qname = $this->option('queuename');
        $debug = $this->option('debug');
        if($qname==null){
            $qname = env('RABBITMQ_QUEUE_NAME', 'maxwell');
            if($qname==null){
                throw new \Exception('please specify a queue name to consumer with --exname options');
            }
        }
        $this->info("Init rabbitmq consumer work : { $qname,$debug }");
        $service = new RabbitMQService($qname,$debug);
        $service->consumeTopics();
    }
}
