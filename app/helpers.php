<?php

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentCourse;
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

    function partialStudents(){
        $user = Auth::user();
        $userId = $user->id;
        $student = 0;
        if($user->type == 'csr'){
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])
            ->whereHas('student', function ($query) use ($userId) {
                $query->where('csr_id', $userId);
            })
            ->count();
        }else{
            $student = StudentCourse::where(['status_id' => 2, 'is_confirmed' => 1, 'is_continued' => 1])->count();
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
        $workshops = json_decode($response->body());
        
        // Filter workshops based on the given batch_id
        $filteredWorkshops = [];
        foreach ($workshops->workshops as $workshop) {
            if ($workshop->batch_id == $batch_id) {
                $filteredWorkshops[] = $workshop;
            }
        }
        
        return $filteredWorkshops;
    }


    function workshopStudents($id){
        $url = 'https://workshops.niais.org/api/workshop-attendees/' . $id;
        $response = Http::get($url);
        $workshopStudents = json_decode($response->body());
        
        return $workshopStudents;
    }
    
  

}