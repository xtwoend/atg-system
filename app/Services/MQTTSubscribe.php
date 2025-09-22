<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Events\MQTTReceived;
use App\Services\MQTTDevice;

class MQTTSubscribe
{
    protected $setting;
    protected $mqtt;
    

    public function __construct($setting) {
        $this->setting = $setting;
        $this->init();
    }

    public function listen()
    {
        if($this->mqtt) {
            $devices = $this->setting->devices;
            $this->setting->update(['status' => 1, 'error_message' => null]);
            
            foreach($devices as $device) {
                $this->mqtt->subscribe($device->topic, function ($topic, $message) use ($device) {
                    if(is_null($message) || $message == '')  return;
                    
                    // dispatch new event
                    // (new MQTTDevice($device, $topic, $message))->handle();
                    MQTTReceived::dispatch($device, $topic, $message);
                }, 0);
            }
            
            $this->mqtt->loop(true);
            $this->mqtt->disconnect();
        }
    }

    public function publish($topic, $message) {
        if($this->mqtt) {
            $this->mqtt->publish($topic, json_encode($message), 0);
        }
    }

    public function init()
    {
        try { 

            // pcntl_async_signals(true);
            
            $server = $this->setting->host;
            $port = $this->setting->port;
            $clientId = Str::random(10);
            $username = $this->setting->username;
            $password = $this->setting->password;

            $setting = (new \PhpMqtt\Client\ConnectionSettings)
                ->setUsername($username)
                ->setPassword($password)
                ->setConnectTimeout(60)
                ->setKeepAliveInterval(60);

            $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId, \PhpMqtt\Client\MqttClient::MQTT_3_1);
            
            $mqtt->connect($setting, true);
                
            // pcntl_signal(SIGINT, function (int $signal, $info) use ($mqtt) {
            //     $mqtt->interrupt();
            // });

            $this->mqtt = $mqtt;

        } catch (\Throwable $th) {
            $this->setting->update(['status' => 0, 'error_message' => $th->getMessage()]);
        }
    }
}