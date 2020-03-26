<?php

namespace rizkyadi487\RabbitMQStreams\Commands;

use Illuminate\Console\Command;
use \rizkyadi487\RabbitMQStreams\RabbitMQService;

class RabbitMQListen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen {exname=maxwell}';

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
        $exname = $this->argument('exname');
        $this->info("Init rabbitmq consumer work : $exname");
        $service = new RabbitMQService($exname);
        $service->consumeTopics();
    }
}
