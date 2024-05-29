<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $batches = Batch::orderBy('id','DESC')->get();

        return view('admin.batches.index',compact('batches'));
    }
    public function create()
    {
        $batches = Batch::count();
        $courses = Course::all();

        return view('admin.batches.create',compact('courses'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'number' => "required",
            'starting_date' => "required",
            'adm_closing_date' => "required",
        ]);
        
        $exists = Batch::where(['number' => $request->number, 'course_id' => $request->course_id])->first();
        if($exists){
            return back()->withErrors('Batch ' . $request->number .  ' for this course already exists!');
        }

        Batch::create([
            'course_id' => $request->course_id,
            'number' => $request->number,
            'starting_date' => $request->starting_date, 
            'ending_date' => $request->ending_date, 
            'adm_closing_date' => $request->adm_closing_date,
            'is_open' => $request->is_open ? true : false,
            'is_active' => $request->is_active ? true : false,
        ]);

        return redirect()->route('admin.index.batches')->withSuccess('Batch has been Created Successfully');
    }

    public function edit($id)
    {
        $batch = Batch::find($id);
        $courses = Course::all();
        return view('admin.batches.edit',compact('batch', 'courses'));
    }


    public function update(Request $request)
    {
        
        $id = $request->id;

       $batch = Batch::find($id);
       $batch->course_id=$request->course_id;
       $batch->number=$request->number;
       $batch->starting_date= $request->starting_date;
       $batch->ending_date= $request->ending_date;
       $batch->adm_closing_date = $request->adm_closing_date;
       $batch->is_open = $request->is_open ? true : false ;
       $batch->is_active = $request->is_active ? true : false;
       $batch->save();

       return redirect()->route('admin.index.batches')->withSuccess('Batch has been Updated Successfully');
    }
}
