<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    
    public function switchCourse(){
        return $this->belongsTo(Course::class, 'switch_from', 'id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function coursePayments(){
        return $this->hasMany(StudentCoursePayment::class, 'student_course_id', 'id');
    }

    public function comments(){
        return $this->hasMany(StudentCourseComment::class, 'student_course_id', 'id');
    }

    public function attendance(){
        return $this->hasMany(StudentLectureAttendance::class, 'student_course_id', 'id');
    }

    public function getLatestPaymentDate()
    {
        $latestDate = null;
    
        foreach ($this->coursePayments as $payment) {
            if ($payment->payment_date_first && $payment->payment_date_second) {
                $latestDate = max($latestDate, $payment->payment_date_first, $payment->payment_date_second);
            } elseif ($payment->payment_date_first) {
                $latestDate = max($latestDate, $payment->payment_date_first);
            } elseif ($payment->payment_date_second) {
                $latestDate = max($latestDate, $payment->payment_date_second);
            }
        }
    
        return $latestDate;
    }
    
    public function getStatusStyleAttribute()
    {
        if ($this->status_id == 2 &&  $this->is_continued && $this->getLatestPaymentDate() != null && now()->diffInDays($this->getLatestPaymentDate()) > 28) {
            return 'background: rgb(143, 14, 14); color: #fff;';
        }
        return '';
    }

    public function isPaidAllFee(StudentCoursePayment $newPayment = null)
    {
        $totalPaid = 0;
    
        foreach ($this->coursePayments as $payment) {
            $totalPaid += $payment->payment_first + $payment->payment_second;
        }
        
        if ($newPayment) {
            $totalPaid += $newPayment->payment_first + $newPayment->payment_second;
        }

        //1 for paid and 2 for partial
        $totalFee = $this->fee + $this->student->card;
        $this->status_id = ($totalPaid >= $totalFee) ? 1 : 2;
    
        $this->save();
    }

    public function remainingfee(){
        $amount1 = $this->coursePayments->sum('payment_first');
        $amount2 = $this->coursePayments->sum('payment_second');
        $totalPaid = $amount1 + $amount2;
        $remaining = $this->fee + $this->student->card - $totalPaid;

        return $remaining;
    }

    public function totalPaidByStudent(){
        $amount1 = $this->coursePayments->sum('payment_first');
        $amount2 = $this->coursePayments->sum('payment_second');
        $totalPaid = $amount1 + $amount2;

        return $totalPaid;
    }
}