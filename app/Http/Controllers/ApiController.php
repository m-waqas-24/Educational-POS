<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCourses(){
        $courses = Course::orderBy('id', 'DESC')->get();

        return response()->json(['courses' => $courses]);
    }

    public function getBatches(){
        $batches = Batch::with('course')->orderBy('id', 'DESC')->get();

        return response()->json(['batches' => $batches]);
    }
}
