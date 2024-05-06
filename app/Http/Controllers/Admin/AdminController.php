<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CsrStudent;
use App\Models\Bank;
use App\Models\StudentCourse;
use App\Models\StudentCoursePayment;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;

class AdminController extends Controller
{

    public function superAdminDashboard(){
        return view('admin.dashboard.admin-dashboard');
    }

    public function index(){

        if(getUserType() == 'admin' || getUserType() == 'superadmin'){
            $studentCourses = StudentCourse::where('is_confirmed', 0)->orderBy('id', 'DESC')->get();
        }elseif(getUserType() == 'csr'){
            $authenticatedUserId = Auth::id();
            $studentCourses = StudentCourse::where('is_confirmed', 0)
            ->whereHas('student', function($query) use ($authenticatedUserId) {
                $query->where('csr_id', $authenticatedUserId);
            })->orderBy('id', 'DESC')->get();
        }
        $modes = Bank::all();

        return view('admin.students.hold', compact('studentCourses', 'modes'));
    }

    public function confirmStudent($id, $confirmationType){
        $studentPayment = StudentCoursePayment::find($id);
        $courseId = $studentPayment->student_course_id;
    
        if ($confirmationType == 'first' && $studentPayment->is_confirmed_first == 0) {
            $studentPayment->update([
                'is_confirmed_first' => 1,
            ]);
        } elseif ($confirmationType == 'second' && $studentPayment->is_confirmed_second == 0) {
            $studentPayment->update([
                'is_confirmed_second' => 1,
            ]);
        } else {
            // Both fields are already confirmed, no need to update anything
            return redirect()->route('admin.hold.students')->withSuccess('Student course has already been approved!');
        }

        $studentCourse = StudentCourse::find($studentPayment->student_course_id);
        $allConfirmed = true;

        foreach ($studentCourse->coursePayments as $payment) {
            if (
                ($payment->payment_first && !$payment->is_confirmed_first) 
                    || 
                ($payment->payment_second && !$payment->is_confirmed_second)) 
            {
                $allConfirmed = false;
                break; 
            }
        }
        
        if ($allConfirmed) {
            $studentCourse->update(['is_confirmed' => 1]);
        }
        
        FacadesSession::put('student_course_id', $courseId);
    
        return redirect()->route('admin.hold.students')->withSuccess('Student payment approved successfully!');
    }
    

}
