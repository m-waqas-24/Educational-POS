<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CsrActionStatus;
use App\Models\Admin\CsrStudent;
use App\Models\ImportStudent;
use App\Models\Student;
use App\Models\StudentCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function dashboard(){
        if(getUserType() == 'csr'){

            $actionStatus = CsrActionStatus::all();
            $totalCallToday = CsrStudent::where('csr_id', auth()->user()->id)
            ->whereDate('called_at', Carbon::today()->toDateString())
            ->count();
         
        
            $csrId = null;
    
            return view('admin.dashboard.dashboard', compact(
                'actionStatus',  'totalCallToday', 'csrId'
            ));
        }else{

            // Get the current year
            $currentYear = Carbon::now()->year;

            // fetch month wise monthlydisrtributeddata total
            $monthlyDataDistributed = ImportStudent::select(DB::raw('MONTH(dateTime) as month'), DB::raw('count(*) as count'))
                ->whereYear('dateTime', $currentYear)
                ->groupBy(DB::raw('MONTH(dateTime)'))
                ->orderBy(DB::raw('MONTH(dateTime)'))
                ->where('is_distributed' , 1)
                ->get()
                ->keyBy('month')
                ->toArray();
            // Initialize an array to hold the counts for each month
            $dataDistributedCountsByMonth = array_fill(1, 12, 0);
            // Fill the countsByMonth array with the actual data
            foreach ($monthlyDataDistributed as $month => $data) {
                $dataDistributedCountsByMonth[$month] = $data['count'];
            }


            //get monthwise distributed data that is due
            $monthlyDistributedDueData = ImportStudent::select(DB::raw('MONTH(dateTime) as month'), DB::raw('count(*) as count'))
            ->whereYear('dateTime', $currentYear)
            ->where('is_distributed', 1)
            ->whereHas('csr', function ($query) {
                $query->whereNull('called_at');
            })
            ->groupBy(DB::raw('MONTH(dateTime)'))
            ->orderBy(DB::raw('MONTH(dateTime)'))
            ->get()
            ->keyBy('month')
            ->toArray();
            $dueDataCountsByMonth = array_fill(1, 12, 0);
            foreach ($monthlyDistributedDueData as $month => $data) {
                $dueDataCountsByMonth[$month] = $data['count'];
            }


            // Get month-wise student enrollments based on the first payment date, grouped by student_course_id
            $monthlyEnrollStudentCounts = StudentCourse::join('student_course_payments', 'student_courses.id', '=', 'student_course_payments.student_course_id')
            ->select(DB::raw('MONTH(MIN(student_course_payments.payment_date_first)) as month'), DB::raw('count(DISTINCT student_courses.id) as count'))
            ->whereYear('student_course_payments.payment_date_first', $currentYear)
            ->groupBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->orderBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->get()
            ->keyBy('month')
            ->toArray();

            // Initialize an array to hold the counts for each month
            $enrollStudentCountsByMonth = array_fill(1, 12, 0);

            // Fill the enrollStudentCountsByMonth array with the actual data
            foreach ($monthlyEnrollStudentCounts as $month => $data) {
            $enrollStudentCountsByMonth[$month] = $data['count'];
            }
            $actionStatus = CsrActionStatus::all();
            $csrs = User::where('type', 'csr')->get();

            //=============================get csr data for each months start=====================
            $csrDataCounts = CsrStudent::join('import_students', 'csr_students.student_id', '=', 'import_students.id')
            ->join('users', 'csr_students.csr_id', '=', 'users.id')
            ->select('users.name as csr_name', DB::raw('COUNT(*) as count'), DB::raw('MONTH(import_students.datetime) as month'), DB::raw('YEAR(import_students.datetime) as year'))
            ->groupBy('users.name', DB::raw('YEAR(import_students.datetime)'), DB::raw('MONTH(import_students.datetime)'))
            ->get();
        
            // Organize data into the desired array structure
            $csrData = [];
            foreach ($csrDataCounts as $count) {
                $csrName = $count->csr_name;
                $month = $count->month;
                $year = $count->year;
            
                if (!isset($csrData[$csrName])) {
                    $csrData[$csrName] = [];
                }
            
                if (!isset($csrData[$csrName][$year])) {
                    $csrData[$csrName][$year] = array_fill(1, 12, 0);
                }
            
                $csrData[$csrName][$year][$month] = $count->count;
            }
             //=============================get csr data for each months end=====================




        return view('admin.dashboard.admin-dashboard', compact( 'csrs', 'actionStatus','dataDistributedCountsByMonth', 'dueDataCountsByMonth', 'enrollStudentCountsByMonth', 'csrData'));  
        }

    }

    public function adminViewCsrDashboard($id){
        $actionStatus = CsrActionStatus::all();
        $user = Auth::user();
        $totalCallToday = CsrStudent::where('csr_id', $id)->whereDate('called_at', Carbon::today()->toDateString())->count();
        $students = CsrStudent::where(['csr_id' => $id, 'action_status_id' => 0 ])->orderBy('id','DESC')->count();
        $csrId = $id;
        $totalData = CsrStudent::where('csr_id', $id)->count();


        return view('admin.dashboard.dashboard', compact(
            'actionStatus',  'totalCallToday', 'csrId', 'students', 'totalData'
        ));
    }



    public function workshops($id){
        $workshops = getWorkshopsByBatchId($id);
        
        return view('admin.workshops.index', compact('workshops'));
    }

}
