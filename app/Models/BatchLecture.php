<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchLecture extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }
    
    public function lectureAttendances(){
        return $this->hasMany(StudentLectureAttendance::class, 'lecture_id', 'id');
    }

}

