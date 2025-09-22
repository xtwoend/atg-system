<?php

namespace App\Handlers;

use App\Handlers\Handler;
use App\Handlers\HandlerInterface;
use Illuminate\Support\Facades\Redis;


final class PumpHandler extends Handler implements HandlerInterface
{
    public function handle(): void
    {
        $device = $this->device();
        $data = $this->message();

        unset($data['_terminalTime']);
        unset($data['_groupName']);
            
        foreach($data as $tag => $value) {
            Redis::set($tag, $value);
        }
    }
}