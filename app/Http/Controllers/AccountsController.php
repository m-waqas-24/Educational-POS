<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentCourse;
use App\Models\StudentCoursePayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function index(){
        $from = null;
        $to = null;
        $csrs = User::where('type', 'csr')->get(); 
        $courses = Course::all();
        $batches = Batch::all();
        $studentCourse = StudentCourse::all();

        return view('accounts.index', compact('csrs', 'courses', 'batches', 'studentCourse', 'from', 'to'));
    }

    public function filterPayment(Request $request){

        $csrs = User::where('type', 'csr')->get(); 
        $courses = Course::all();
        $batches = Batch::all();
        $studentCourse = StudentCourse::query();
        $from = null;
        $to = null;

        if ($request->course) {
            $studentCourse = $studentCourse->where('course_id', $request->course);
        }
        
        if ($request->from && $request->to) {
            $from = Carbon::createFromFormat('m/d/Y', $request->from)->startOfDay()->format('Y-m-d');
            $to = Carbon::createFromFormat('m/d/Y', $request->to)->endOfDay()->format('Y-m-d');
        
            $studentCourse->whereHas('coursePayments', function ($query) use ($from, $to) {
                $query->whereBetween('payment_date_first', [$from, $to]);
            });
        }

        $studentCourse =  $studentCourse->get();

        return view('accounts.index', compact('csrs', 'courses', 'batches', 'from', 'to', 'studentCourse'));
    }

}
