<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Logger;
use App\Models\ErrorLog;
use App\Events\MQTTReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MQTTReceivedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MQTTReceived $event): void
    {
        $topic = $event->topic;
        $message = $event->message;
        $device = $event->device;
        
        if(env('DEBUG_ENABLE', false)){
            Logger::create([
                'topic' => $topic,
                'message' => $message
            ]);
        }

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
