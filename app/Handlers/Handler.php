<?php

namespace App\Handlers;

use App\Services\MQTTSubscribe;
use Illuminate\Database\Eloquent\Model;

abstract class Handler 
{
    protected $device;
    protected $topic;
    protected $message;

    public function __construct($device, $topic, $message) {
        $this->device = $device;
        $this->topic = $topic;
        $this->message = $message;
    }

    public function device(): Model {
        return $this->device;
    }

    public function topic(): string {
        return $this->topic;
    }

    public function message(): array {
        return json_decode($this->message, true);
    }

    public function sendToMqtt($topic, $device, $data): void {
        $connection = $device->connection;
        $mqtt = (new MQTTSubscribe($connection));
        $mqtt->publish($topic, $data);
    }
}