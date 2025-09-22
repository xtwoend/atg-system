<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\AtgLog;
use App\Models\StockCpo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CutOffHourlyStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpo:hourly {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save CPO record Hourly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $atgs = Atg::get();
        $date = $this->option('date') ?:  Carbon::now()->format('Y-m-d');

        foreach($atgs as $atg) {
            $rows = $this->getData($atg->id, $date);
            foreach($rows as $row) {
                $terminal_date = Carbon::parse($row->terminal_time);
                StockCpo::updateOrCreate([
                    'atg_id' => $atg->id,
                    'stock_date' => $terminal_date->format('Y-m-d'),
                    'stock_time' => $terminal_date->format('H:i:s')
                ], [
                    'level' => $row->level,
                    'temp_avg' => $row->temp_avg,
                    'density' => $row->density,
                    'fk' => $row->fk,
                    'volume' => $row->volume,
                    'cpo' => $row->cpo,
                    'data_log' => $row->toArray(),
                ]);
            }
        }
    }

    public function getData($id, $date)
    {
        $interval = 3600;
        $classModel = new AtgLog;
        $tableName = (new $classModel)->table($id, $date)->getTable();
        $subQuery = (new $classModel)->table($id, $date)
            ->select(DB::raw("MIN(terminal_time) as times, MIN(HOUR(terminal_time)) as hours, FLOOR(UNIX_TIMESTAMP(terminal_time)/{$interval}) AS timekey"))
            ->groupBy('timekey');

        $model = (new $classModel)
            ->table($id, $date)
            ->select(DB::raw("*"))
            ->whereDate('terminal_time', $date)
            ->joinSub($subQuery, 'ctx', function($join) use ($tableName) {
                $join->on("{$tableName}.terminal_time", "=", "ctx.times");
            });

        $model = $model->get();

        return $model;
    }
}
