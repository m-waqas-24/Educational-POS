<?php

namespace App\Models\Admin;

use App\Models\ImportStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsrStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function csr(){
        return $this->belongsTo(User::class, 'csr_id', 'id');
    }

    public function student(){
        return $this->belongsTo(ImportStudent::class, 'student_id', 'id');
    }

    public function enrollStudent(){
        return $this->belongsTo(Student::class, 'enroll_student_id', 'id');
    }

    public function remarkUser(){
        return $this->belongsTo(User::class, 'remark_user', 'id');
    }

    public function statusAction(){
        return $this->belongsTo(CsrActionStatus::class, 'action_status_id', 'id');
    }

}
