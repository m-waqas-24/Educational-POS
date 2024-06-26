<?php

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentCourse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

if(!function_exists('formatPrice')){

    function formatPrice($value)
    {
        return number_format($value, 0, '.', ',');
    }

    function getUserType(){
        return auth()->user()->type;
    }

    function sumConfirmedPayments($studentCourses)
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
    
    function uploadFile($request, $fileInputName, $targetDirectory)
    {
        $uploadedFile = $request->file($fileInputName);
        if ($uploadedFile) {
            $path = $uploadedFile->store($targetDirectory);
            return substr($path, 7); 
        }
        return null;
    }

    function arruploadFile($file, $targetDirectory)
    {
        if ($file) {
            $path = $file->store($targetDirectory);
            return substr($path, 7); 
        }
        return null;
    }

    function forgetStudentCourseIdAfterDelay(){
        Session::forget('student_course_id');
    }

    function oldPartialStudents(){
        $user = Auth::user();
        $userId = $user->id;
        $fortyFiveDaysAgo = Carbon::today()->subDays(45);
        $student = 0;
    
        if($user->type == 'csr'){
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])
                ->whereHas('student', function ($query) use ($userId) {
                    $query->where('csr_id', $userId);
                })
                ->whereHas('coursePayments', function ($query) use ($fortyFiveDaysAgo) {
                    $query->where('payment_date_first', '<', $fortyFiveDaysAgo);
                })
                ->count();
        } else {
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])
                ->whereHas('coursePayments', function ($query) use ($fortyFiveDaysAgo) {
                    $query->where('payment_date_first', '<', $fortyFiveDaysAgo);
                })
                ->count();
        }

        return $student;
    }
    
    function newPartialStudents(){
        $user = Auth::user();
        $userId = $user->id;
        $fortyFiveDaysAgo = Carbon::today()->subDays(45);
        $student = 0;
    
        if($user->type == 'csr'){
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])
                ->whereHas('student', function ($query) use ($userId) {
                    $query->where('csr_id', $userId);
                })
                ->whereHas('coursePayments', function ($query) use ($fortyFiveDaysAgo) {
                    $query->where('payment_date_first', '>=', $fortyFiveDaysAgo);
                })
                ->count();
        } else {
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])
                ->whereHas('coursePayments', function ($query) use ($fortyFiveDaysAgo) {
                    $query->where('payment_date_first', '>=', $fortyFiveDaysAgo);
                })
                ->count();
        }
    
        return $student;
    }
    

    function paidStudents(){
        $user = Auth::user();
        $userId = $user->id;
        $student = 0;
        if($user->type == 'csr'){
            $student = StudentCourse::where(['status_id' => 1, 'is_confirmed' => 1, 'is_continued' => 1])
            ->whereHas('student', function ($query) use ($userId) {
                $query->where('csr_id', $userId);
            })
            ->count();
        }else{
            $student = StudentCourse::where(['status_id' => 1, 'is_confirmed' => 1, 'is_continued' => 1])->count();
        }

        return $student;
    }

    function holdStudents()
    {
        $user = Auth::user();
        $student = 0;
        
        if ($user->type == 'csr') {
            $authenticatedUserId = Auth::id();
            $student = StudentCourse::where('is_confirmed', 0)
                ->whereHas('student', function ($query) use ($authenticatedUserId) {
                    $query->where('csr_id', $authenticatedUserId);
                })->count();
        }else{
            $student = StudentCourse::where('is_confirmed', 0)->count();
        }
    
        return $student;
    }

    function getDiscontinuedStudent(){
        $studentCourses = StudentCourse::where('is_continued',0)->orderBy('id','DESC')->count();

        return $studentCourses;
    }



    function getWorkshopsByBatchId($batch_id){
        $response = Http::get('https://workshops.niais.org/api/workshops');
  
        if ($response->successful()) {
            $workshops = json_decode($response->body());
        
            // Filter workshops based on the given batch_id
            $filteredWorkshops = [];
        
            foreach ($workshops->workshops as $workshop) {
                if ($workshop->batch_id == $batch_id) {
                    $filteredWorkshops[] = $workshop;
                }
            }
        
            return $filteredWorkshops;
        } else {
            return ['error' => 'Failed to fetch workshops.'];
        }
  
      }


    function workshopStudents($id){
        $url = 'https://workshops.niais.org/api/workshop-attendees/' . $id;
        $response = Http::get($url);
        $workshopStudents = json_decode($response->body());
        
        return $workshopStudents;
    }
    
  

}