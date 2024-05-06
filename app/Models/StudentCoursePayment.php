<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCoursePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function studentCourse(){
        return $this->belongsTo(StudentCourse::class, 'student_course_id', 'id');
    }

    public function modeOne(){
        return $this->belongsTo(Bank::class, 'mode_first', 'id');
    }

    public function modeTwo(){
        return $this->belongsTo(Bank::class, 'mode_second', 'id');
    }

    public function remaining(){
        $course = $this->studentCourse; 
    
        $payments = $course->coursePayments;
    
        $payment_first = $payments->sum('payment_first');
        $payment_second = $payments->sum('payment_second');
        $totalPaid = $payment_first + $payment_second;
    
        $fee = $course->fee;
    
        $remainingFee = $fee - $totalPaid;
    
        return $remainingFee;
    }
    

}
