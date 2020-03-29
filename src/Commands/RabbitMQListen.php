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
    protected $signature = 'rabbitmq:listen {--exname=} {--type=}';

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
        $exname = $this->option('exname');
        $extype = $this->option('type');
        if($exname==null){
            $exname = env('RABBITMQ_EXCHANGE_NAME', 'maxwell');
            if($exname==null){
                throw new \Exception('please specify a exchange name to consumer with --exname options');
            }
        }
        if($extype==null){
            $extype = env('RABBITMQ_EXCHANGE_TYPE', 'fanout');
            if($extype==null){
                throw new \Exception('please specify a exchange name to consumer with --extype options');
            }
        }
        $this->info("Init rabbitmq consumer work : { $exname : $extype }");
        $service = new RabbitMQService($exname,$extype);
        $service->consumeTopics();
    }
}
