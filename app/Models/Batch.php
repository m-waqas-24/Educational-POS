<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function student(){
        return $this->hasMany(StudentCourse::class, 'batch_id', 'id');
    }

    public function lectures(){
        return $this->hasMany(BatchLecture::class, 'batch_id', 'id');
    }


    public function taskReports(){
        return $this->hasMany(TaskReport::class, 'batch_id', 'id');
    }

}
