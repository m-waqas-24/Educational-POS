<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use App\Models\StudentCourseComment;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function addComment(Request $request){
        $commentContent = $request->comment;
        $studentCourseId = $request->studentCourseId;
    
        $comment = StudentCourseComment::create([
            'student_course_id' => $studentCourseId,
            'user_id' => auth()->id(),
            'comments' => $commentContent,
        ]);
    
        return response()->json(['comment' => $comment]);
    }
    

    public function getComments(Request $request){
        $studentCourseId = $request->studentCourseId;
        
        $comments = StudentCourseComment::with('user')->where('student_course_id', $studentCourseId)->get();
    
        foreach ($comments as $comment) {
            if ($comment->is_read == 0) {
                $comment->is_read = 1;
                $comment->save();
            }
        }
    
        return response()->json(['comments' => $comments]);
    }
    
}
