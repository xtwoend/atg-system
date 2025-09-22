<?php

use Carbon\Carbon;
use App\Models\Scheduler;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::call(function(){
    Artisan::call('cpo:hourly');
})->hourlyAt(2);

Schedule::call(function () {
    DB::table('devices')->where('last_connected', '<=', Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s'))->update(['status' => 0]);
})->everyMinute();

// if(Schema::hasTable('schedulers')) {

//     $schedules = Scheduler::active()->get();

//     foreach($schedules as $schedule) {
//         if($schedule->is_daily) {
//             if(! is_null($schedule->command)) {
//                 Schedule::command($schedule->command)
//                     ->name($schedule->name)
//                     ->dailyAt($schedule->hour)
//                     ->onOneServer();
//             }else{
//                 Schedule::call(new $schedule->handler)
//                     ->name($schedule->name)
//                     ->dailyAt($schedule->hour)
//                     ->onOneServer();
//             }
//         }
//     }
// }