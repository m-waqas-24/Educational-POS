<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Instructor;
use App\Models\Course;
use App\Models\InstructorBatch;
use App\Models\InstructorCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{

    public function index(){
        $instructors = User::where('type', 'instructor')->orderBy('id','DESC')->get();
        $courses  = Course::all();

        return view('admin.instructor.index', compact('instructors', 'courses'));
    }

    public function store(Request $request) {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'courses' => 'required|array',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mob' => $request->mob,
            'type' => 'instructor',
        ]);
    
        foreach ($request->courses as $courseId) {
            $exists = InstructorCourse::where('instructor_id', $user->id)
                                      ->where('course_id', $courseId)
                                      ->exists();
            
            if (!$exists) {
                InstructorCourse::create([
                    'instructor_id' => $user->id,
                    'course_id' => $courseId,
                ]);
            }
        }
    
        return back()->withSuccess('Instructor added successfully!');
    }

    public function edit($id){
        $user = User::with('instructorCourses')->find($id);
        $courses  = Course::all();

        return view('admin.instructor.edit', compact('user', 'courses'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'courses' => 'required|array',
        ]);
    
        // Find the user by ID
        $user = User::find($id);
    
        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'mob' => $request->phone,
        ]);
    
        // Get the existing course IDs associated with the instructor
        $existingCourseIds = $user->instructorCourses->pluck('course_id')->toArray();
    
        // Determine the courses to be added and removed
        $coursesToAdd = array_diff($request->courses, $existingCourseIds);
        $coursesToRemove = array_diff($existingCourseIds, $request->courses);
    
        // Add new courses
        foreach ($coursesToAdd as $courseId) {
            InstructorCourse::create([
                'instructor_id' => $user->id,
                'course_id' => $courseId,
            ]);
        }
    
        // Remove courses that are no longer selected
        InstructorCourse::where('instructor_id', $user->id)
                        ->whereIn('course_id', $coursesToRemove)
                        ->delete();
    
        // Return back with a success message
        return redirect()->route('admin.index.instructors')->withSuccess('Instructor updated successfully!');
    }

}


