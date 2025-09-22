<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Connection;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Device::paginate(15);
        return view('modules.devices.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $connections = Connection::all();
        return view('modules.devices.create', compact('connections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'serial_number' => 'required',
            'topic' => 'required',
            'connection_id' => 'required',
            'handler' => 'required'
        ]);

        Device::create($request->all());

        return redirect()->route('devices.index');
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
        $row = Device::findOrFail($id);
        $connections = Connection::all();
        return view('modules.devices.edit', compact('row', 'connections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'serial_number' => 'required',
            'topic' => 'required',
            'connection_id' => 'required',
            'handler' => 'required'
        ]);

        Device::findOrFail($id)->update($request->all());

        return redirect()->route('devices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Device::findOrFail($id)->delete();

        return redirect()->route('devices.index');
    }
}
