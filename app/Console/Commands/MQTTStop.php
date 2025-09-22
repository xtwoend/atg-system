<?php

namespace App\Console\Commands;

use App\Models\Connection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class MQTTStop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:stop {id=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQTT stop all process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        if($id == 'all') {
            Connection::where('status', 1)->update([
                'status' => 0
            ]);
            
            exec('pkill -9 -f mqtt:listen');
        }else {
            $conn = Connection::find($id);
            $command = sprintf(
                '%s -9 %s "mqtt:listen %s"',
                'pkill',
                '-f',
                $conn->id
            );
            
            $conn->update(['status' => 0]);
            // var_dump($command);
            exec($command);
        }
    }
}
