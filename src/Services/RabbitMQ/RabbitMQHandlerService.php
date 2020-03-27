<?php

namespace rizkyadi487\RabbitMQStreams\Services\RabbitMQ;

class RabbitMQHandlerService{
    public function __construct()
    {
        //TODO memebuat koneksi database target
    }

    public function handleMessage($message){
        $dataMessage = $this->proccessAndGetData(json_decode($message, true));
        //CREATE//
        if($dataMessage=='insert'){
            echo "\nCreating";
        }
        //UPDATE//
        if($dataMessage=='update'){
            echo "\nUpdating";
        }
        //DELETE//
        if($dataMessage=='delete'){
            echo "\nDeleting";
        }
    }

    public function proccessAndGetData($data){
        //TODO processing data
        return $data['type'];
    }

    public function handleCreate(){
        
    }
    public function handleUpdate(){
        
    }
    public function handleDelete(){
        
    }
}