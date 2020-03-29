<?php

namespace rizkyadi487\RabbitMQStreams\Services\RabbitMQ;

use Illuminate\Support\Facades\DB;
use stdClass;

class RabbitMQHandlerService{
    public function __construct(){
        //TODO memebuat koneksi database target
    }

    public function handleMessage($message){
        $dataMessage = $this->proccessAndGetData(json_decode($message, true));

        //CREATE//
        if($dataMessage[0]['type']=='insert'){
            $this->handleCreate(null, null);
        }

        //UPDATE//
        if($dataMessage[0]['type']=='update'){
            echo "\nUpdating";
        }

        //DELETE//
        if($dataMessage=='delete'){
            echo "\nDeleting";
        }
    }

    public function proccessAndGetData($data){
        //TODO processing data
        // $returnData = new stdClass();
        $returnData[]=[
            'type' => $data['type'],
            'data' => $data['data'],
        ];
        
        return $returnData;
    }

    protected function handleCreate($after, $topic){
        // $this->instanceClass->{$this->config['event_methods']['create']}($after, $topic);
        //test create
        DB::connection('mysql')->insert("INSERT INTO laravel.newtable (`data`) VALUES('berhasil insert')");
    }

    protected function handleUpdate($before, $after, $topic){
        // $this->instanceClass->{$this->config['event_methods']['update']}($before, $after, $topic);
    }

    protected function handleDelete($before, $topic){
        // $this->instanceClass->{$this->config['event_methods']['delete']}($before, $topic);
    }
}