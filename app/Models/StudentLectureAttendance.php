<?php

namespace App\Models;

use App\Models\Admin\StudentLectureAttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLectureAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attendanceStatus(){
        return $this->belongsTo(StudentLectureAttendanceStatus::class, 'status', 'id');
    }

}

