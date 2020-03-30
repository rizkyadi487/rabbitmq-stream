<?php

namespace rizkyadi487\RabbitMQStreams\Services\RabbitMQ;

use Illuminate\Support\Facades\DB;
use stdClass;

class RabbitMQHandlerService{

    private $connection;
    private $id;

    public function __construct(){
        $this->connection =  env('RABBITMQ_CONNECTION', 'mysql');
        $this->id = env('RABBITMQ_PRIMARY_KEY', 'id');
    }

    public function handleMessage($message){
        $dataMessage = $this->proccessAndGetData(json_decode($message, true));

        //CREATE//
        if($dataMessage->type == 'insert'){
            $this->handleCreate($dataMessage);
        }

        //UPDATE//
        if($dataMessage->type == 'update'){
            $this->handleUpdate($dataMessage);
        }

        //DELETE//
        if($dataMessage->type == 'delete'){
            $this->handleDelete($dataMessage);
        }
    }

    protected function handleCreate($data){
        DB::connection($this->connection)->table($data->database.'.'.$data->table)->insert($data->data);
    }

    protected function handleUpdate($data){
        DB::connection($this->connection)->table($data->database.'.'.$data->table)->where($this->id,$data->data[$this->id])->update($data->data);
    }

    protected function handleDelete($data){
        DB::connection($this->connection)->table($data->database.'.'.$data->table)->where($this->id,$data->data[$this->id])->delete();
    }

    public function proccessAndGetData($data){
        $returnData = new stdClass();
        $returnData->database = $data['database'];
        $returnData->table = $data['table'];
        $returnData->type = $data['type'];
        $returnData->data = $data['data'];
        $returnData->old = isset($data['old']) ? $data['old'] : null;
        return $returnData;
    }
}