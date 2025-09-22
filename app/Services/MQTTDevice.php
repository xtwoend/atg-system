<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\ErrorLog;
use Illuminate\Database\Eloquent\Model;

class MQTTDevice
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Model $device, 
        public string $topic, 
        public string $message)
    {}

    public function handle()
    {
        $topic = $this->topic;
        $message = $this->message;
        $device = $this->device;
    
        $device->update([
            'status' => 1, 
            'last_connected' => date('Y-m-d H:i:s'), 
            'data' => json_decode($message, 0)
        ]);

        $classHandler = $device->handler;
        if(! class_exists($classHandler)) return;
        
        try {
            (new $classHandler($device, $topic, $message))->handle();
        } catch (\Throwable $th) {
            ErrorLog::where('created_at', '<=', Carbon::now()->subDay()->format('Y-m-d H:i:s'))->delete();
            ErrorLog::create([
                'device_id' => $device->id,
                'topic' => $topic,
                'message' => $message,
                'file' => $th->getFile(),
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}