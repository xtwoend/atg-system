<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Atg;
use App\Models\StockCpo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $tanks = Atg::all();
        $chartOptions = $this->chartOptions($request);
        $date = Carbon::now()->subDays(30)->format('Y-m-d');
        $rows = [];
        
        $rawQuery = [];
        foreach($tanks as $tank) {
            $rawQuery[] = " SUM(CASE WHEN atg_id={$tank->id} THEN cpo ELSE 0 END) stock_st{$tank->id}";
        }
        $rawQuery = implode(',', $rawQuery);

        if(! $tanks->isEmpty()) {
            $rows = StockCpo::select(DB::raw($rawQuery . ", stock_date, HOUR(stock_time) as stock_time"))
                ->where('stock_date', $request->input('date', Carbon::now()->format('Y-m-d')))
                // ->groupBy('atg_id')
                ->groupBy('stock_date')
                ->groupBy(DB::raw('HOUR(stock_time)'))
                ->get();
        }

        return view('home', compact('tanks', 'chartOptions', 'date', 'rows'));
    }

    protected function chartOptions($request): array
    {
        $series = [];
        $date = Carbon::now()->subDays(30);
        foreach(Atg::all() as $key => $atg) {
            $stocks = StockCpo::where('atg_id', $atg->id)->where('stock_date', '>=', $date->format('Y-m-d'))->get();
            $series[$key]= [
                'name' => $atg->name,
                'pointInterval' => 24 * 3600 * 1000
            ];
            foreach($stocks as $stock) {
                $series[$key]['data'][] = [Carbon::parse($stock->stock_date)->setTimezone('UTC')->getTimestampMs(), $stock->stock];
            }
        }
        return $series;
    }
}
