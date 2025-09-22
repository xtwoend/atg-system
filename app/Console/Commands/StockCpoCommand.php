<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\AtgLog;
use App\Models\StockCpo;
use Illuminate\Console\Command;

class StockCpoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpo:stock-daily {id} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cutoff CPO Daily';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ?:  Carbon::now()->format('Y-m-d H:i:s');
        $atgId = $this->argument('id');

        if($atgId == 'all') {
            $atgs = Atg::all();
            foreach($atgs as $atg) {
               $this->findAndUpdate($date, $atg); 
            }
        }else{
            $atg = Atg::find($atgId);
            if($atg) {
                $this->findAndUpdate($date, $atg); 
            }
        }
    }

    protected function findAndUpdate($date, $atg)
    {
        $from = Carbon::parse($date)->subHours(1)->format('Y-m-d H:i:s');
        $to = Carbon::parse($date)->addMinutes(5)->format('Y-m-d H:i:s');
        $log = AtgLog::table($atg->id, Carbon::parse($date)->format('Y-m-d'))
            ->whereBetween('terminal_time', [$from, $to])
            ->orderBy('terminal_time', 'desc')
            ->first();

        $stock = [
            'atg_id' => $atg->id,
            'stock_date' => Carbon::parse($date)->subDay()->format('Y-m-d'),
            'stock' => $log?->cpo ?: 0,
            'data_log' => (array) $log?->toArray(),
        ];
        
        StockCpo::updateOrCreate([
            'atg_id' => $atg->id,
            'stock_date' => Carbon::parse($date)->subDay()->format('Y-m-d')
        ], $stock);
    }
}
