<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(){
        $banks = Bank::orderBy('id', 'DESC')->get();

        return view('admin.banks.index', compact('banks'));
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'acc_no' => 'required',
        ]);

        Bank::create([
            'name' => $request->name,
            'acc_no' => $request->acc_no,
        ]);

        return back()->withSuccess('Bank created successfully!');
    }

    public function edit(Request $request){
        $id = $request->bankId;
        $bank = Bank::find($id);

        return response()->json(['bank' => $bank]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'acc_no' => 'required',
        ]);

        $bank = Bank::find($id);

        $bank->update([
            'name' => $request->name,
            'acc_no' => $request->acc_no,
        ]);

        return back()->withSuccess('Bank updated successfully!');
    }
}
