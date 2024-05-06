<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Orientation;
use App\Models\Visitor;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class OrientationController extends Controller
{
    public function index(){
        $orientations = Orientation::orderBy('id','DESC')->get();
        $batches = Batch::all();

        return view('admin.orientation.index', compact('orientations', 'batches'));
    }

    public function store(Request $request){
        $request->validate([
            'batch_id' => 'required',
            'trainer' => 'required',
            'venue' => 'required',
            'dateTime' => 'required',
            'desc' => 'required',
        ]);

        Orientation::create([
            'batch_id' => $request->batch_id,
            'trainer' => $request->trainer,
            'venue' => $request->venue,
            'dateTime' => $request->dateTime,
            'desc' => $request->desc,
        ]);

        return back()->withSuccess('Orientation created successfully!');
    }

    public function edit(Request $request){
        $id = $request->oriId;
        $orientation = Orientation::find($id);

        return response()->json(['orientation' => $orientation]);
    }

    public function update(Request $request, $id){

        $request->validate([
            'batch_id' => 'required',
            'trainer' => 'required',
            'venue' => 'required',
            'dateTime' => 'required',
            'desc' => 'required',
        ]);

        $orientation = Orientation::find($id);

        $orientation->update([
            'batch_id' => $request->batch_id,
            'trainer' => $request->trainer,
            'venue' => $request->venue,
            'dateTime' => $request->dateTime,
            'desc' => $request->desc,
        ]);

        return back()->withSuccess('Orientation updated successfully!');
    }


    public function register(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'cnic' => 'required',
            'phone' => 'required',
        ]);

        Visitor::create([
            'orientation_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'cnic' => $request->cnic,
            'phone' => $request->phone,
        ]);

        return back()->withSuccess('Register successfully!');
    }

}
