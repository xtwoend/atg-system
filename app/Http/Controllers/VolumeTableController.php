<?php

namespace App\Http\Controllers;

use App\Models\Atg;
use App\Models\VolumeTable;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;


class VolumeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rows = new VolumeTable;
        if($request->has('atg_id')) {
            $rows = $rows->where('atg_id', $request->atg_id);
        }
        $rows = $rows->paginate(15);

        return view('modules.volume.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $atgs = Atg::orderBy('name')->get();
        return view('modules.volume.create', compact('atgs'));
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
        VolumeTable::where('atg_id', $atgId)->delete();

        $volumes = (new FastExcel)->import($file, function ($line) use ($atgId) {
            return [
                'atg_id' => $atgId,
                'height' => $line['height'],
                'different' => $line['different'],
                'volume' => $line['volume']
            ];
        });

        foreach($volumes->chunk(1000) as $rows) {
            $data = $rows->toArray();
            VolumeTable::query()->insert($data);
        }

        return redirect()->route('atgsetting.table-volume.index');
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
