<?php

namespace App\Http\Controllers;

use App\Models\Atg;
use App\Models\DensityTable;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class DensityTableController extends Controller
{
    /**
     * 
     */
    public function __construct() 
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $rows = new DensityTable;
        if($request->has('atg_id')) {
            $rows = $rows->where('atg_id', $request->atg_id);
        }
        $rows = $rows->paginate(15);

        return view('modules.density.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $atgs = Atg::orderBy('name')->get();
        return view('modules.density.create', compact('atgs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'atg_id' => 'required',
            'file' => 'required'
        ]);

        if (! $request->file('file')->isValid()) {
            return redirect()->back()->withErrors(['file' => 'File not valid']);
        }
        
        $atgId = $request->input('atg_id');
        $file = $request->file->path();
     
        // clear all atg table
        DensityTable::where('atg_id', $atgId)->delete();

        $density = (new FastExcel)->import($file, function ($line) use ($atgId) {
            return DensityTable::create([
                'atg_id' => $atgId,
                'temperature' => $line['temperature'],
                'density' => $line['density'],
                'fk' => $line['fk'] ?? NULL
            ]);
        });

        return redirect()->route('atgsetting.table-density.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
