<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Admin\StudentLectureAttendanceStatus;
use App\Models\Batch;
use App\Models\BatchInstructor;
use App\Models\BatchLecture;
use App\Models\Course;
use App\Models\InstructorCourse;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\StudentLectureAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function dashboard(){
        $instructorCourses = InstructorCourse::where('instructor_id', Auth::user()->id)->get();

        return view('instructor.dashboard', compact('instructorCourses'));
    }

    public function instructorBatches($instructorId, $courseId){
        $batches = BatchInstructor::where(['instructor_id' => $instructorId, 'course_id' => $courseId])->orderBy('id', 'DESC')->get();
        $course = Course::find($courseId);

       return view('instructor.courses.all-batches', compact('course', 'batches'));
    }


    public function batchLectures($batchId){
        $lectures = BatchLecture::where('batch_id', $batchId)->orderBy('date_time', 'asc')->get();
        if($lectures->count() == 0){
            return redirect()->back()->withErrors('Lecture not scheduled yet!.');
        }
        $batch = Batch::find($batchId);
        $students = StudentCourse::where('batch_id', $batchId)->get();

        return view('instructor.courses.batch-lecture', compact('lectures', 'batch', 'students'));
    }

    public function studentAttendance($lectureId)
    {
        $lecture = BatchLecture::find($lectureId);
    
        // Check if lecture exists
        if (!$lecture) {
            return redirect()->back()->withErrors('Lecture not found.');
        }
    
        // // Check if the lecture date is today
        // $lectureDate = \Carbon\Carbon::parse($lecture->date_time);
        // if (!$lectureDate->isToday()) {
        //     return redirect()->back()->withErrors('Attendance can only be marked on the lecture date.');
        // }
    
        // Get the students for the batch
        $students = StudentCourse::where('batch_id', $lecture->batch_id)->get();
        $attendanceStatuses = StudentLectureAttendanceStatus::all();
    
        return view('instructor.courses.batch-students', compact('students', 'lecture', 'attendanceStatuses'));
    }
    

    public function storeStudentAttendance(Request $request, $lectureId)
    {
        $lecture = BatchLecture::find($lectureId);
        $lectureDate = \Carbon\Carbon::parse($lecture->date_time);
        if (!$lectureDate->isToday()) {
            return redirect()->back()->withErrors('Attendance can only be marked on the lecture date.');
        }
    
        // Loop through each student
        foreach ($request->students as $studentId => $attendanceStatus) {
            $studentCourse = StudentCourse::find($studentId);
    
            // Check if attendance record already exists
            $attendance = StudentLectureAttendance::where('lecture_id', $lectureId)
                ->where('student_course_id', $studentId)
                ->where('batch_id', $lecture->batch_id)
                ->first();
    
            if ($attendance) {
                // Update existing attendance record
                $attendance->update([
                    'user_id' => Auth::user()->id,
                    'status' => $attendanceStatus,
                    'topic' => $request->topic,
                ]);
            } else {
                // Create new attendance record
                StudentLectureAttendance::create([
                    'user_id' => Auth::user()->id,
                    'lecture_id' => $lectureId,
                    'student_id' => $studentCourse->student->id,
                    'student_course_id' => $studentId,
                    'batch_id' => $lecture->batch_id,
                    'course_id' => $lecture->batch->course_id,
                    'status' => $attendanceStatus,
                    'topic' => $request->topic,
                ]);
            }
        }
    
        // Redirect or return as needed
        return redirect()->route('instructor.batch.lecture', $lecture->batch_id)->with('success', 'Attendance saved successfully for ' . Carbon::parse($lecture->date_time)->format('d F, Y'));
    }
    
    


}
