<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(){
        $courses = Course::orderBy('id', 'DESC')->get();
        return view('admin.course.index', compact('courses'));
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',
            'fee' => 'required',
            'card_fee' => 'required',
            'duration' => 'required',
        ]);

        Course::create([
            'name' => $request->name,
            'fee' => $request->fee,
            'card_fee' => $request->card_fee,
            'duration' => $request->duration,
        ]);

        return back()->withSuccess('Course created successfully!');
    }

    public function edit(Request $request){
        $id = $request->courseId;

        $course = Course::find($id);

        return response()->json(['course' => $course]);
    }

    public function update(Request $request, $id){
        
        $request->validate([
            'name' => 'required',
            'fee' => 'required',
            'card_fee' => 'required',
            'duration' => 'required',
        ]);

        $course = Course::find($id);

        $course->update([
            'name' => $request->name,
            'fee' => $request->fee,
            'card_fee' => $request->card_fee,
            'duration' => $request->duration,
        ]);

        return back()->withSuccess('Course updated successfully!');
    }

    public function courseBatches($id){
        $batches = Batch::where('course_id', $id)->get();
        $course = Course::find($id);

        return view('admin.course.batches', compact('batches','course'));
    }

    public function batchesStudent($id){
        if(getUserType() == 'admin' || getUserType() == 'superadmin'){
            $studentCourses = StudentCourse::where(['batch_id' => $id, 'is_continued' => 1])->get();
            $discontinuedStudentCourses = StudentCourse::where(['batch_id' => $id, 'is_continued' => 0])->get();
            //total fees of students
            $totalFees = $studentCourses->sum('fee');
            $totalRecovered = $this->sumConfirmedPayments($studentCourses);
            //csr student count
            $csrStudentCounts = $studentCourses->groupBy('student.csr.name')
            ->map->count()
            ->toArray();
            // dd($csrStudentCounts);
            $batch = Batch::find($id);
            $workshops = getWorkshopsByBatchId($id);
            // $workshops = null;

            return view('admin.course.batches-students', compact('studentCourses', 'discontinuedStudentCourses', 'batch', 'totalRecovered', 'csrStudentCounts', 'totalFees', 'workshops'));
        }else if(getUserType() == 'csr'){
            $userId = Auth::id();
            $studentCourses = StudentCourse::where('batch_id',$id)->whereHas('student', function ($query) use ($userId) {
                $query->where('csr_id', $userId);
            })->get();
            $discontinuedStudentCourses = StudentCourse::where(['batch_id' => $id, 'is_continued' => 0])->whereHas('student', function ($query) use ($userId) {
                $query->where('csr_id', $userId);
            })->get();
            $modes = Bank::all();
            $batch = Batch::find($id);

            return view('admin.course.batches-students', compact('studentCourses', 'discontinuedStudentCourses', 'batch', 'modes'));
        }
    }

    private function sumConfirmedPayments($studentCourses)
    {
        $totalPayments = 0;

        foreach ($studentCourses as $course) {
            $totalPayments += $course->coursePayments()
                ->where('is_confirmed_first', true)
                ->sum('payment_first');

            $totalPayments += $course->coursePayments()
                ->where('is_confirmed_second', true)
                ->sum('payment_second');
        }

        return $totalPayments;
    }


}
