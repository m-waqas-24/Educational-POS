<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CsrActionStatus;
use App\Models\Admin\CsrStudent;
use App\Models\Bank;
use App\Models\Batch;
use App\Models\Course;
use App\Models\ProvinceCity;
use App\Models\Qualification;
use App\Models\Source;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Ui\Presets\React;

class CSRDataController extends Controller
{
    public function index(){
        $city = null;
        $user = Auth::user();
        Session::forget('filtered_city');
        $students = CsrStudent::where(['csr_id' => $user->id, 'action_status_id' => 0 , 'called_at' => null])->orderBy('id','DESC')->get();
        
        return view('admin.csr.csr-data', compact('students', 'city'));
    }

    public function filterCsrData(Request $req){
        $user = Auth::user();
        $city = $req->city;
        $req->session()->put('filtered_city', $city);
        $students = CsrStudent::where([
            'csr_id' => $user->id,
            'action_status_id' => 0
        ])->whereHas('student', function ($query) use ($req) {
            $query->where('city', $req->city);
        })->orderBy('id', 'DESC')->get();

        return view('admin.csr.csr-data', compact('students', 'city'));
    }

    public function showStudent($id){
        $student = CsrStudent::find($id);
        $csrActions = CsrActionStatus::all();

        return view('admin.csr.show-student', compact('student', 'csrActions'));
    }

    public function updateStudent(Request $request, $id){
        // dd($request->all(), $id);

        $request->validate([
            'status' => 'required',
        ]);

        $student = CsrStudent::find($id);
        $student->update([
            'action_status_id' => $request->status,
            'comments' => $request->comments,
            'called_at' => Carbon::now(),
        ]);

        $city = session('filtered_city');
        if ($city) {
            return redirect()->route('admin.csr-data.filter', ['city' => $city])->withSuccess('Status successfully updated!');
        }else{
            $request->session()->forget('filtered_city');
            return redirect()->route('admin.csr-data.index')->withSuccess('Status successfully updated!');
        }
    }

    public function enrollForm($id=null){
        
        $student = CsrStudent::find($id);
        $qualifications = Qualification::all();
        $provinces = ProvinceCity::where('province_id', '!=' , null)->get();
        $courses = Course::all();
        $batches = Batch::with('course')->where('is_open',1)->get();
        $modes = Bank::all();
        $sources = Source::all();
        $csrs = User::where(['type' => 'csr', 'status' => 1])->get();

        return view('admin.csr.enroll-student', compact('student', 'csrs', 'qualifications', 'sources', 'provinces', 'courses', 'batches', 'modes'));
    }

    public function filterActionStatusesTodayData($id,$csr){
        $user = Auth::user();
        $students = CsrStudent::where([
            'csr_id' => $csr,
            'action_status_id' => $id
        ])->whereDate('called_at', Carbon::today()->toDateString())->get();
        $action = CsrActionStatus::find($id);

        return view('admin.csr.filter-action-status', compact('students', 'action', 'csr'));
    }


    public function getFollowUpData(){
        $students = CsrStudent::where('remarks', '!=', null)->get();

        return view('admin.csr.remarks-data', compact('students'));
    }

    public function teamLeadFollowUp( Request $request ,$id){
        $student = CsrStudent::find($id);
        // dd($student);

        $student->update([
            'remarks' => $request->remarks,
            'remark_user' => Auth::user()->id,
            'remark_date' => now(),
        ]);

        return back()->withSuccess('Remarks added successfully!');
    }

    public function filterActionStatuses($id){
        $user = Auth::user();
        $students = CsrStudent::where(['csr_id' => $user->id, 'action_status_id' => $id])->get();
        $action = CsrActionStatus::find($id);

        return view('admin.csr.filter-action-status', compact('students', 'action'));
    }

    public function filterStudent(Request $request){
        $user = Auth::user();
        $stu = Student::where('csr_id', $user->id);
    
        if ($request->name) {
            $stu->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->cnic) {
            $stu->where('cnic', $request->cnic);
        }
        
        if ($request->email) {
            $stu->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            });
        }
        
        $student = $stu->first();
    
        if(empty($student)){
            return back()->withErrors('Student Not Exist!');
        }
        return view('admin.csr.filter-student', compact('student'));
    }
    

    public function showStudentCourses($id){
        $student = Student::find($id);
        $modes = Bank::all();

        return view('admin.csr.filter-student-courses', compact('student', 'modes'));
    }
    
    public function filterFollowup(Request $request, $id)
    {
        $from = Carbon::createFromFormat('m/d/Y', $request->from)->startOfDay()->toDateTimeString();
        $to = Carbon::createFromFormat('m/d/Y', $request->to)->endOfDay()->toDateTimeString();
    
        $user = Auth::user();
        $csr = $request->csr_id;
        $students = CsrStudent::where(['csr_id' => $csr, 'action_status_id' => $id]);
        $action = CsrActionStatus::find($id);
    
        if ($from && $to) {
            // Apply the whereBetween filter
            $students->whereBetween('called_at', [$from, $to]);
        }
    
        // Fetch the filtered students
        $students = $students->get();
    
        return view('admin.csr.filter-action-status', compact('students', 'action', 'csr'));
    }
    


}
