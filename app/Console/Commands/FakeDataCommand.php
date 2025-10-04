<?php

namespace App\Console\Commands;

use App\Services\MQTTSubscribe;
use Illuminate\Console\Command;

class FakeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fake-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Fake Data ATG';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanks = \App\Models\Atg::all();
        foreach ($tanks as $tank) {
            $this->sendFakeData($tank);
        }
    }

    public function sendFakeData($tank)
    {
        $faker = \Faker\Factory::create();
        $data = [
            'level' => $faker->numberBetween(100, 10000),
            'percentage' => $faker->numberBetween(40, 90),
            'water_level' => $faker->numberBetween(0, 10),
            'temp_avg' => $faker->numberBetween(30, 40),
            'temp_1' => $faker->numberBetween(30, 40),
            'temp_2' => $faker->numberBetween(30, 40),
            'temp_3' => $faker->numberBetween(30, 40),
            'temp_4' => $faker->numberBetween(30, 40),
            'temp_5' => $faker->numberBetween(30, 40),
            'temp_6' => $faker->numberBetween(30, 40),
            'temp_7' => $faker->numberBetween(30, 40),
            'temp_8' => $faker->numberBetween(30, 40),
            'temp_9' => $faker->numberBetween(30, 40),
            'temp_10' => $faker->numberBetween(30, 40),
            'temp_11' => $faker->numberBetween(30, 40),
            'temp_12' => $faker->numberBetween(30, 40),
            'temp_13' => $faker->numberBetween(30, 40),
            'temp_14' => $faker->numberBetween(30, 40),
            'ts' => now()->toAtomString(),
        ];

        $device = $tank->device;

        // var_dump($data);

        $mqtt = (new MQTTSubscribe($device->connection));
        $mqtt->publish($device->topic, $data);
        
        $this->info("Fake data sent for tank ID: {$tank->id}");
    }
}
