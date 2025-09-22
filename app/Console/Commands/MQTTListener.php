<?php

namespace App\Console\Commands;

use App\Models\Connection;
use App\Services\MQTTSubscribe;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;

class MQTTListener extends Command //implements SignalableCommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:listen {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen MQTT Subscribe';

    /**
     * 
     */
    protected $shouldExit = false;

    protected $setting;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('starting mqtt : '.now()->format('Y-m-d H:i:s'));
        try {
            $this->setting = Connection::find($this->argument('id'));
            (new MQTTSubscribe($this->setting))->listen();
    
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        } finally {
            if($this->setting) {
                $this->setting->update(['status' => 0, 'error_message' => 'Disconnected']);
            }
        }

        $this->info('finished mqtt: '.now()->format('H:i:s'));
    }

    // public function getSubscribedSignals(): array
    // {
    //     return [SIGINT, SIGTERM];
    // }

    // public function handleSignal(int $signal, $code = 0): int|false
    // {
    //     $this->shouldExit = true;
    //     $this->info('Cleaning up: signal received: ' . $signal);
    //     return false;
    // }
}
