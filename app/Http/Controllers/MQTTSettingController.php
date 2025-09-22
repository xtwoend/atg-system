<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MQTTSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Connection::paginate(15);
        return view('modules.mqtt.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.mqtt.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'host' => 'required',
            'port' => 'required'
        ]);

        Connection::create($request->all());

        return redirect()->route('connections.mqtt.index');
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
        $row = Connection::findOrFail($id);
        return view('modules.mqtt.edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'host' => 'required',
            'port' => 'required'
        ]);

        Connection::findOrFail($id)->update($request->all());

        return redirect()->route('connections.mqtt.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Connection::findOrFail($id)->delete();

        return redirect()->route('connections.mqtt.index');
    }

    /**
     * run command
     */
    public function reload()
    {
        dispatch(function () {
            Artisan::call('mqtt:stop');
            Artisan::call('mqtt:start');
        });

        return redirect()->route('connections.mqtt.index');
    }

    public function start($id) 
    {
        dispatch(function () use ($id) {
            Artisan::call('mqtt:start ' . $id);
        });

        return redirect()->route('connections.mqtt.index');
    }

    public function stop($id) 
    {
        dispatch(function () use ($id) {
            Artisan::call('mqtt:stop ' . $id);
        });

        return redirect()->route('connections.mqtt.index');
    }
}
