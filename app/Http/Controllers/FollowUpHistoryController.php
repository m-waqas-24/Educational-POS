<?php

namespace App\Http\Controllers;

use App\Models\Admin\StudentCourseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpHistoryController extends Controller
{
    public function storeFollowup(Request $request, $id){

        $request->validate([
            'comment' => 'required',
        ]);

        StudentCourseHistory::create([
            'student_course_id' => $id,
            'user_id' => Auth::user()->id,
            'comment' => $request->comment,
        ]);

        return back()->withSuccess('Follow up added successfully!');
    }
}
