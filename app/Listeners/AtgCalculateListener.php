<?php

namespace App\Listeners;

use App\Services\MQTTSubscribe;
use App\Events\AtgCalculateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AtgCalculateListener
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
    public function handle(AtgCalculateEvent $event): void
    {
        $data = $event->data;
    }
}
