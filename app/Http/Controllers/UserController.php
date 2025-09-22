<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = User::paginate(15);
        return view('modules.users.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed',
        ]);
    
        $input = $request->all();

        $input['password'] = Hash::make($request->password);
        if($request->hasFile('avatar')) {
            $input['avatar'] = '/upload/' . $request->avatar->storeAs('images', Str::random(10).'.jpg');
        }

        User::create($input);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
       
        $row = User::find($user->id);

        return view('modules.users.profile', compact('row'));
    }

    /**
     * Display the specified resource.
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = $request->user();

        $input = $request->all();

        if($request->has('password')){
            $input['password'] = Hash::make($request->password);
        }

        if($request->hasFile('avatar')) {
            $input['avatar'] = '/upload/' . $request->avatar->storeAs('images', Str::random(10).'.jpg');
        }

        $user->update($input);

        return redirect()->route('users.profile');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $row = User::findOrFail($id);
        return view('modules.users.edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);
    
        $input = $request->all();

        if($request->has('password')){
            $input['password'] = Hash::make($request->password);
        }

        if($request->hasFile('avatar')) {
            $input['avatar'] = '/upload/' . $request->avatar->storeAs('images', Str::random(10).'.jpg');
        }

        User::findOrFail($id)->update($input);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index');
    }
}
