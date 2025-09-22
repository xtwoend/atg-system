<?php

namespace App\Console\Commands;

use App\Models\Connection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class MQTTStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:start {id=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQTT run all process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        if($id == 'all') {
            foreach(Connection::all() as $conn) {
                $command = sprintf(
                    '%s %s mqtt:listen %s 2>&1 > /dev/null &',
                    config('laravel-async.php_path'),
                    base_path('artisan'),
                    $conn->id
                );
                // dd($command);
                Process::run($command);
            }
        }else {
            $conn = Connection::find($id);
            $command = sprintf(
                '%s %s mqtt:listen %s 2>&1 > /dev/null &',
                config('laravel-async.php_path'),
                base_path('artisan'),
                $conn->id
            );
            Process::run($command);
        }
    }
}
