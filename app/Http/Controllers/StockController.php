<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\StockCpo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class StockController extends Controller
{

    public function trend($id, Request $request)
    {
        $mode = $request->input('mode', 'daily');
        $atg = Atg::findOrFail($id);

        if($mode == 'monthly') {
            return $this->trendMonthly($atg, $request);
        } elseif($mode == 'yearly') {
            return $this->trendYearly($atg, $request);
        }

        return $this->trendDaily($atg, $request);
    }

    public function stock($id, Request $request)
    {
        $mode = $request->input('mode', 'daily');
        $atg = Atg::findOrFail($id);

        if($mode == 'monthly') {
            return $this->monthly($atg, $request);
        } elseif($mode == 'yearly') {
            return $this->yearly($atg, $request);
        }

        return $this->daily($atg, $request);
    }

    public function download($id, Request $request)
    {
        $mode = $request->input('mode', 'daily');
        $atg = Atg::findOrFail($id);
        
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        if($mode == 'daily') {
            $model = StockCpo::select(DB::raw("CONCAT(stock_date, ' ', stock_time) as terminal_time, level, temp_avg, density, fk, volume, cpo" ))
                ->where('atg_id', $atg->id)
                ->whereBetween('stock_date', [$from, $to])
                ->orderBy('terminal_time');
        }elseif($mode == 'monthly') {
            $model = StockCpo::select(DB::raw("CONCAT(stock_date, ' ', stock_time) as terminal_time, stock_date, stock_time, level, temp_avg, density, fk, volume, cpo" ))
                ->where('atg_id', $atg->id)
                ->where(DB::raw('HOUR(stock_time)'), Carbon::parse($atg->sounding_time)->format('H'))
                ->whereRaw("DATE_FORMAT(stock_date, '%Y-%m') BETWEEN ? AND ?", [$from, $to])
                ->orderBy('terminal_time');
        }
        
        $report = function() use ($model) {
            foreach($model->cursor() as $row) {
                yield $row;
            }
        };

        $name = Str::random(10);
        $path = (new FastExcel($report()))->export("report/report-{$name}.xlsx");

        return response()->download($path);
    }

    protected function daily($atg, $request)
    {
        $perPage = (int) $request->input('rowsPerPage', 20);
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));
        
        $model = StockCpo::select(DB::raw("id, atg_id, stock_date, stock_time, level, temp_avg, density, fk, volume, cpo, CONCAT(stock_date, ' ', stock_time) as terminal_time" ))
            ->where('atg_id', $atg->id)
            ->whereBetween('stock_date', [$from, $to]);
        
        if($request->has('sortBy')) {
            $column = $request->input('sortBy');
            $dir = $request->input('sortType');
            $model = $model->orderBy($column, $dir);
        }

        $rows = $model->paginate($perPage);

        return response()->json($rows, 200);
    }

    protected function monthly($atg, $request)
    {
        $perPage = (int) $request->input('rowsPerPage', 20);
        $from = $request->input('from', Carbon::now()->format('Y-m'));
        $to = $request->input('to', Carbon::now()->format('Y-m'));
        
        $model = StockCpo::select(DB::raw("id, atg_id, stock_date, stock_time, level, temp_avg, density, fk, volume, cpo, CONCAT(stock_date, ' ', stock_time) as terminal_time" ))
            ->where('atg_id', $atg->id)
            ->where(DB::raw('HOUR(stock_time)'), Carbon::parse($atg->sounding_time)->format('H'))
            ->whereRaw("DATE_FORMAT(stock_date, '%Y-%m') BETWEEN ? AND ?", [$from, $to])
            ->orderBy('terminal_time');
        
        if($request->has('sortBy')) {
            $column = $request->input('sortBy');
            $dir = $request->input('sortType');
            $model = $model->orderBy($column, $dir);
        }

        $rows = $model->paginate($perPage);
        
        return response()->json($rows);
    }

    protected function yearly($atg, $request)
    {
        
    }

    protected function trendDaily($atg, $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        $rows = StockCpo::select(DB::raw("id, atg_id, level, temp_avg, density, fk, volume, cpo, CONCAT(stock_date, ' ', stock_time) as terminal_time" ))
            ->where('atg_id', $atg->id)
            ->whereBetween('stock_date', [$from, $to])
            ->orderBy('terminal_time')
            ->get();

        return response()->json($rows);
    }

    protected function trendMonthly($atg, $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        $rows = StockCpo::select(DB::raw("id, atg_id, stock_date, stock_time, level, temp_avg, density, fk, volume, cpo, CONCAT(stock_date, ' ', stock_time) as terminal_time" ))
            ->where('atg_id', $atg->id)
            ->where(DB::raw('HOUR(stock_time)'), Carbon::parse($atg->sounding_time)->format('H'))
            ->whereRaw("DATE_FORMAT(stock_date, '%Y-%m') BETWEEN ? AND ?", [$from, $to])
            ->orderBy('terminal_time')
            ->get();

        return response()->json($rows);
    }

    protected function trendYearly($atg, $request)
    {
        
    }
}
