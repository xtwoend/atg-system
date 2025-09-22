<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\AtgLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class AtgController extends Controller
{
    public function show($id)
    {
        $atg = Atg::findOrFail($id);
        $headers = $this->headers();
        $chartOptions = $this->chartOptions();
        $default = [$headers[6]];
        $socket = [
            'channel' => 'atgs.calculate',
            'event' => 'AtgCalculateEvent'
        ];
        return view('atg.index', compact('atg', 'headers', 'default', 'chartOptions', 'socket'));
    }

    public function trend($id, Request $request)
    {
        $atg = Atg::findOrFail($id);
        $headers = $this->headers();
        $chartOptions = $this->chartOptions();
        $socket = [
            'channel' => 'atgs.calculate',
            'event' => 'AtgCalculateEvent'
        ];
        $default = [$headers[6]];

        return view('atg.trend', compact('atg', 'headers', 'default', 'chartOptions', 'socket'));
    }

    public function report($id, Request $request)
    {
        $atg = Atg::findOrFail($id);
        $headers = [
            ['text' => 'Date', 'value' => 'terminal_time', 'sortable' => true],
            ['text' => 'Level (mm)', 'value' => 'level',  'sortable' => true],
            ['text' => 'Temperature (°C)', 'value' => 'temp_avg',  'sortable' => true],
            ['text' => 'Density (g/mL)', 'value' => 'density', 'sortable' => true],
            ['text' => 'Factor Correction (L)', 'value' => 'fk','sortable' => true],
            ['text' => 'Volume (L)', 'value' => 'volume',  'sortable' => true],
            ['text' => 'CPO Stock (Kg)', 'value' => 'cpo', 'sortable' => true],
            ['text' => 'CPO Stock (Ton)', 'value' => 'cpo_ton', 'sortable' => true],
        ];
        $default = [$headers[6]];
        $mode = $request->input('mode','daily');
        $chartOptions = $this->chartOptions();

        return view('atg.report', compact('mode', 'atg', 'headers', 'default', 'chartOptions'));
    }

    public function logger($id, Request $request)
    {
        $atg = Atg::findOrFail($id);
        $headers = $this->headers();
        $chartOptions = $this->chartOptions();
        $socket = [
            'channel' => 'atgs.calculate',
            'event' => 'AtgCalculateEvent'
        ];
        $default = [$headers[6]];
        return view('atg.logger', compact('atg', 'headers', 'default', 'chartOptions', 'socket'));
    }

    public function trendData($id, Request $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d 00:00:00'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d 23:59:59'));

        $interval = $request->input('interval', 60);

        $from = Carbon::parse($from);
        $to = Carbon::parse($to);

        $select = $request->input('select', ['*']);
        $query = [];
        $fromClone = clone $from;
        $toClone = clone $to;

        $fromDiff = $from->format("Y-m-01 00:00:00");
        $toDiff = $to->format("Y-m-01 23:59:59");

        $count = Carbon::parse($fromDiff)->diffInMonths(Carbon::parse($toDiff));

        $formattingSelect = array_map(function($val){
            return "ct.{$val}";
        }, $select);

        $select_column = implode(',', $formattingSelect);

        $classModel = new AtgLog;
       
        for($i=0; $i <= $count; $i++) {
            $tableName = (new $classModel)->table($id, $from)->getTable();
            $query[] = "
            (select 
                (UNIX_TIMESTAMP(ct.terminal_time) * 1000) as unix_time, {$select_column}
                from {$tableName} as `ct` 
                    inner join 
                    (
                        SELECT MIN(terminal_time) as times, FLOOR(UNIX_TIMESTAMP(terminal_time)/{$interval}) AS timekey 
                        FROM {$tableName} 
                        WHERE terminal_time BETWEEN '{$fromClone->format('Y-m-d 00:00:00')}' AND '{$toClone->format('Y-m-d 23:59:59')}' 
                        GROUP BY timekey
                    ) ctx 
                    on `ct`.`terminal_time` = `ctx`.`times` order by unix_time)
            ";
            $from = $from->addMonth();
        }
        $query = implode(' UNION ', $query);
        
        $rows = DB::select($query);
        $sorted = collect($rows)->sortBy('unix_time');

        return response()->json($sorted->values()->all());
    }

    public function data($id, Request $request)
    {
        $perPage = (int) $request->input('rowsPerPage', 20);

        $from = $request->input('from', Carbon::now()->format('Y-m-d 00:00:00'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d 23:59:59'));
        $interval = $request->input('interval', 60);

        $from = Carbon::parse($from)->format('Y-m-d 00:00:00');
        $to = Carbon::parse($to)->format('Y-m-d 23:59:59');

        $classModel = new AtgLog();
        $tableName = (new $classModel)->table($id, $from)->getTable();
        $subQuery = (new $classModel)->table($id, $from)
            ->select(DB::raw("MIN(terminal_time) as times, FLOOR(UNIX_TIMESTAMP(terminal_time)/{$interval}) AS timekey"))
            ->groupBy('timekey');

        $model = (new $classModel)
            ->table($id, $from)
            ->select(DB::raw("*"))
            ->whereBetween('terminal_time', [$from, $to])
            ->joinSub($subQuery, 'ctx', function($join) use ($tableName) {
                $join->on("{$tableName}.terminal_time", "=", "ctx.times");
            });

        if($request->has('sortBy')) {
            $column = $request->input('sortBy', 'terminal_time');
            $dir = $request->input('sortType', 'desc');
            $model = $model->orderBy($column, $dir);
        }

        $rows = $model->paginate($perPage);

        return response()->json($rows);
    }

    public function download($id, Request $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d 00:00:00'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d 23:59:59'));
        $interval = $request->input('interval', 3600);

        $from = Carbon::parse($from)->format('Y-m-d 00:00:00');
        $to = Carbon::parse($to)->format('Y-m-d 23:59:59');

        $classModel = new AtgLog();
        $tableName = (new $classModel)->table($id, $from)->getTable();
        $subQuery = (new $classModel)->table($id, $from)
            ->select(DB::raw("MIN(terminal_time) as times, FLOOR(UNIX_TIMESTAMP(terminal_time)/{$interval}) AS timekey"))
            ->groupBy('timekey');

        $model = (new $classModel)
            ->table($id, $from)
            ->select(DB::raw("*"))
            ->whereBetween('terminal_time', [$from, $to])
            ->joinSub($subQuery, 'ctx', function($join) use ($tableName) {
                $join->on("{$tableName}.terminal_time", "=", "ctx.times");
            });
        
        $report = function() use ($model) {
            foreach($model->cursor() as $row) {
                yield $row;
            }
        };

        $name = Str::random(10);
        $path = (new FastExcel($report()))->export("report/report-{$name}.xlsx");

        return response()->download($path);
    }

    protected function chartOptions(): array
    {
        return [
            'chart' => [ 
                'zoomType' => 'x',
                'backgroundColor' => 'transparent',
                'style' => [
                    'color' => '#e5e7eb'
                ]
            ],
            'time' => [ 
                'useUTC' => false,
                'timezone' => 'Asia/Jakarta'
            ],
            'rangeSelector' => [
                'enabled' => false
            ],
            'xAxis' => [
                'type' => 'datetime',
                'gridLineWidth' => 0.5,
                'gridLineColor' => '#374151',
                'labels' => [
                    'style' => [
                        'color' => '#e5e7eb'
                    ]
                ]
            ],
            'yAxis' => [
                'gridLineWidth' => 0.5,
                'gridLineColor' => '#374151',
                'labels' => [
                    'style' => [
                        'color' => '#e5e7eb'
                    ]
                ]
            ],
            'tooltip' => [
                'pointFormat' => '{series.name}: <b>{point.y:,.f}</b>',
                'split' => true
            ],
            'series' => []
        ];
    }

    protected function headers(): array
    {
        return [
            ['text' => 'Datetime', 'value' => 'terminal_time', 'fixed' => true, 'width' => 155, 'sortable' => true],
            ['text' => 'Level', 'value' => 'level', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'AVG Temp', 'value' => 'temp_avg', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'Density', 'value' => 'density', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'FK', 'value' => 'fk', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'Volume', 'value' => 'volume', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'CPO Stock (Kg)', 'value' => 'cpo', 'fixed' => true, 'width' => 100, 'sortable' => true],
            ['text' => 'CPO Stock (Ton)', 'value' => 'cpo_ton', 'fixed' => true, 'width' => 100, 'sortable' => true],

            ['text' => 'Temp 01', 'value' => 'temp_1'],
            ['text' => 'Temp 02', 'value' => 'temp_2'],
            ['text' => 'Temp 03', 'value' => 'temp_3'],
            ['text' => 'Temp 04', 'value' => 'temp_4'],
            ['text' => 'Temp 05', 'value' => 'temp_5'],
            ['text' => 'Temp 06', 'value' => 'temp_6'],
            ['text' => 'Temp 07', 'value' => 'temp_7'],
            ['text' => 'Temp 08', 'value' => 'temp_8'],
            ['text' => 'Temp 09', 'value' => 'temp_9'],
            ['text' => 'Temp 10', 'value' => 'temp_10'],
            // ['text' => 'Temp 11', 'value' => 'temp_11'],
            // ['text' => 'Temp 12', 'value' => 'temp_12'],
            // ['text' => 'Temp 13', 'value' => 'temp_13'],
            // ['text' => 'Temp 14', 'value' => 'temp_14'],
        ];
    }
}
