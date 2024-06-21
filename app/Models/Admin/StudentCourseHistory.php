<?php

namespace App\Models\Admin;

use App\Models\StudentCourse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourseHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function studentCourse(){
        return $this->belongsTo(StudentCourse::class, 'student_course_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
