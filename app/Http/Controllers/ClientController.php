<?php

namespace App\Http\Controllers;

use App\Models\ClientKey;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = ClientKey::paginate(15);
        return view('modules.client.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
    
        $input = $request->all();
        $input['active'] = $request->input('active') == 'on' ? true : false; 

        ClientKey::create($input);

        return redirect()->route('client.index');
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
        $row = ClientKey::findOrFail($id);
        return view('modules.client.edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();
        $input['active'] = $request->input('active') == 'on' ? true : false; 

        ClientKey::findOrFail($id)->update($input);

        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ClientKey::findOrFail($id)->delete();

        return redirect()->route('client.index');
    }
}
