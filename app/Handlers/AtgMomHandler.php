<?php

namespace App\Handlers;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\AtgLog;
use App\Models\VolumeTable;
use App\Models\AtgDischarge;
use App\Models\DensityTable;
use App\Events\AtgCalculateEvent;
use App\Handlers\HandlerInterface;
use Illuminate\Support\Facades\Redis;

final class AtgMomHandler extends Handler implements HandlerInterface
{
    protected $conversi = 10;
    protected $levels = [0, 1000, 2000, 3000, 4000, 5000, 6000, 8000, 10000, 12000];
    protected $fCorection = 0.0000348;
    protected $tempCorrection = 50;

    public function handle(): void
    {
        $device = $this->device();
        $data = $this->message();
        
        $atg = Atg::where('device_id', $device->id)->first();
    
        list($avg_temp, $volume, $cpo, $density, $fk) = $this->getVolume($data, $atg, $data['level']);
     
        $pump_discharge = (Redis::get('pump_motor1') == 1 || Redis::get('pump_motor2') == 1);
        $ost = ((int) Redis::get('selectswitch_ost1_or_osst2') == 1) ? 1 : 2;
        
        $data = [
            'atg_id' => $atg->id,
            'terminal_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'level' => $data['level'] ?? 0,
            'percentage' => $data['percentage'] ?? 0,
            'temp_avg' => $avg_temp,    
            'temp_1' => $data['temp_1'] ?? 0,
            'temp_2' => $data['temp_2'] ?? 0,
            'temp_3' => $data['temp_3'] ?? 0,
            'temp_4' => $data['temp_4'] ?? 0,
            'temp_5' => $data['temp_5'] ?? 0,
            'temp_6' => $data['temp_6'] ?? 0,
            'temp_7' => $data['temp_7'] ?? 0,
            'temp_8' => $data['temp_8'] ?? 0,
            'temp_9' => $data['temp_9'] ?? 0,
            'temp_10' => $data['temp_10'] ?? 0,
            'volume' => (float) $volume,
            'cpo' => (float) $cpo,
            'cpo_ton' => (float) ($cpo / 1000), 
            'density' => (float) $density,
            'fk' => (float) $fk,
            'pump_discharge' => (int) ($pump_discharge && $ost == $atg->id) ? 1 : 0
        ];
        
        $datax = array_merge($data, [
            'status' => $device->status, 
            'ost_selected' => Redis::get('selectswitch_ost1_or_osst2') ?: null,
            'pump1_run' => Redis::get('pump_motor1') ?: 0, 
            'pump1_run_text' => Redis::get('pump_motor1') == 1 ? 'On' : 'Off', 
            'pump2_run' => Redis::get('pump_motor2') ?: 0,
            'pump2_run_text' => Redis::get('pump_motor2') == 1 ? 'On' : 'Off',
            'sp_ost1_kg' => Redis::get('sp_ost1_kg') ?: 0,
            'sp_ost1_mm' => Redis::get('sp_ost1_mm') ?: 0,
            'sp_ost2_kg' => Redis::get('sp_ost2_kg') ?: 0,
            'sp_ost2_mm' => Redis::get('sp_ost2_mm') ?: 0
        ]);

        // event broadcast 
        AtgCalculateEvent::dispatch($datax);
        
        // create log
        $exists = AtgLog::table($atg->id, $data['terminal_time'])->latest()->first();
        
        if($exists && Carbon::parse($exists->terminal_time) >= Carbon::parse($data['terminal_time'])->subMinutes(1)) {
        }else {
            
            AtgLog::table($atg->id, $data['terminal_time'])->create($data);
            // var_dump($data['pump_discharge'])
            // set discharge volume
            if($data['pump_discharge'] == 1) {
                $onload = AtgDischarge::where('on_counter', 1)->where('atg_id', $atg->id)->first(); 
                if($onload) {
                    $onload->update([
                        'ended_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'ended_volume' => $cpo,
                    ]);
                }else{
                    AtgDischarge::create([
                        'on_counter' => 1,
                        'atg_id' => $atg->id,
                        'started_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'started_volume' => $cpo,
                    ]);
                }       
            }else{
                AtgDischarge::where('atg_id', $atg->id)->update(['on_counter' => 0]);
            }
        }

        $send = [
            'temp_avg' => (string) $data['temp_avg'], 
            'volume' =>  (string)  $data['volume'], 
            'cpo' =>  (string) $data['cpo'], 
            'density' =>  (string) $data['density'],  
            'fk' =>  (string) $data['fk']
        ];
        
        // send back to mqtt
        $topic = explode('/', $this->topic());
        array_pop($topic);
        $topic = implode("/", $topic);
        
        foreach($send as $key => $val) {
            $topicx = "{$topic}/{$key}";
            $this->sendToMqtt($topicx, $device, [$key => $val]);
        }
    }

    protected function getVolume($data, $atg, $height): array {

        if($height <= 0) {
            return [0,0,0,0,0];
        }

        $this->tempCorrection = $atg->temperature ?? $this->tempCorrection;
        $this->fCorection = $this->fCorection;

        $heightCm = floor($height / $this->conversi);
        $heightCmRound = round(($height / $this->conversi) - $heightCm, 1, PHP_ROUND_HALF_UP);
        $volumeTable = VolumeTable::where('atg_id', $atg->id)->where('height', $heightCm)->first();
        $volumeTank = $volumeTable->volume ?? 0;
        $different = $volumeTable->different ?? 0;
        $volume = $volumeTank + ($heightCmRound * $different);
        // $volume = $volumeTank;
        
        // density
        $sensor = 1;
        $temperatures = [];
        foreach($this->levels as $lv) {
            if($height >= $lv) {
                $tx = "temp_{$sensor}";
                $temperatures[] = $data[$tx];
                $sensor++;
            }
        }

        if(count($temperatures) > 0) {
            $avgTemp = round((array_sum($temperatures) / count($temperatures)), 0);
        }else{
            $avgTemp = $data['temp_1'];
        }

        if($avgTemp > 75) {
            $avgTemp = $data['temp_avg'] ?: 50;
        }
        
        if($avgTemp < 30) {
            $avgTemp = 30;
        }

        $temp_tank_round = ceil($avgTemp);
        $lockup_den = DensityTable::where('atg_id', $atg->id)->where('temperature', $temp_tank_round)->first();
        $density = $lockup_den->density ?? 0;
        
        // factor correction
        $fCorection = 1 + ($this->fCorection * ($avgTemp - $this->tempCorrection)); 
        // $fCorection = $lockup_den->fk ?? $fCorection;

        $cpo = ($height > 0) ? ($volume * $density * $fCorection) : 0;
        
        return (array) [$avgTemp, $volume, $cpo, $density, $fCorection];
    }
}