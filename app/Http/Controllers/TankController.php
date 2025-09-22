<?php

namespace App\Http\Controllers;

use App\Models\Atg;
use App\Models\Device;
use Illuminate\Http\Request;

class TankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Atg::paginate(15);
        return view('modules.tank.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::orderBy('serial_number')->get();
        return view('modules.tank.create', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'device_id' => 'required',
        ]);

        $input = $request->all();

        if($request->has('svg_path') && $request->hasFile('svg_path')) {
            $name = $request->svg_path->getClientOriginalName();
            $path = $request->svg_path->storeAs('svg', $name);
            $input['svg_path'] = '/upload/' . $path;
        }

        Atg::create($input);

        return redirect()->route('atgsetting.tank.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $devices = Device::orderBy('serial_number')->get();
        $row = Atg::findOrFail($id);
        return view('modules.tank.edit', compact('devices', 'row'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'device_id' => 'required'
        ]);

        $input = $request->all();

        if($request->has('svg_path') && $request->hasFile('svg_path')) {
            $name = $request->svg_path->getClientOriginalName();
            $path = $request->svg_path->storeAs('svg', $name);
            $input['svg_path'] = '/upload/' . $path;
        }

        Atg::findOrFail($id)->update($input);

        return redirect()->route('atgsetting.tank.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Atg::findOrFail($id)->delete();

        return redirect()->route('atgsetting.tank.index');
    }
}
