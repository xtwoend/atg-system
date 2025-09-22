<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Atg;
use Illuminate\Support\Str;
use App\Models\AtgDischarge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class DischargeController extends Controller
{
    public function index($id, Request $request)  
    {
        $atg = Atg::findOrFail($id);
        $headers = $this->headers();
        
        return view('atg.discharge', compact('atg', 'headers'));   
    }

    public function data($id, Request $request)
    {
        $perPage = (int) $request->input('rowsPerPage', 20);
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        $atg = Atg::findOrFail($id);

        $model = AtgDischarge::where('atg_id', $atg->id)->whereBetween(DB::raw('DATE(started_at)'), [$from, $to]);
        
        if($request->has('sortBy')) {
            $column = $request->input('sortBy');
            $dir = $request->input('sortType');
            $model = $model->orderBy($column, $dir);
        }

        $rows = $model->paginate($perPage);

        return response()->json($rows, 200);
    }

    public function download($id, Request $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        $model = AtgDischarge::where('atg_id', $id)->whereBetween(DB::raw('DATE(started_at)'), [$from, $to])->orderBy('started_at');
        
        $report = function() use ($model) {
            foreach($model->cursor() as $row) {
                yield $row;
            }
        };

        $name = Str::random(10);
        $path = (new FastExcel($report()))->export("report/report-discharge-{$name}.xlsx");

        return response()->download($path);
    }

    protected function headers(): array
    {
        return [
            ['text' => 'Started At', 'value' => 'started_at', 'sortable' => true],
            ['text' => 'CPO Start Discharge', 'value' => 'started_volume', 'sortable' => true],
            ['text' => 'Ended At', 'value' => 'ended_at', 'sortable' => true],
            ['text' => 'CPO End Discharge', 'value' => 'ended_volume', 'sortable' => true],
            ['text' => 'Total CPO Discharge', 'value' => 'volume', 'sortable' => true],
        ];
    }
}
