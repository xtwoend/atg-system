<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\AtgLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AtgController extends Controller
{
    public function data($id, Request $request)
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
}
