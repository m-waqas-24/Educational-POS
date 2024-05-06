<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CSR;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CsrManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $csrs = User::where('type', 'csr')->orderBy('id','DESC')->get();
        return view('admin.csr.index', compact('csrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
       $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|',
            'mob' => 'required',
       ]);

       User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mob' => $request->mob,
            'password' => Hash::make($request->password),
            'type' => 'csr',
       ]);

       return back()->withSuccess('Csr created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $userid = $request->csrId;
        $user = User::find($userid);

        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mob' => 'required',
       ]);
       $user = User::find($id);

       $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mob' => $request->mob,
            'password' => Hash::make($request->password),
       ]);

       return back()->withSuccess('Csr updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function blockUser($id){
        $user = User::find($id);
        $user->update([
            'status' => 0,
        ]);

        return back()->withSuccess('CSR blocked successfully!');
    }

    public function UnblockUser($id){
        $user = User::find($id);
        $user->update([
            'status' => 1,
        ]);

        return back()->withSuccess('CSR active successfully!');
    }
}
