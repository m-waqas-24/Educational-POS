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

            $currentYear = date('Y'); 
            $csrs = User::where(['type' => 'csr', 'status' => 1])->get();

            //=============================get csr total and uncalled data each months start=====================
            $csrDataCounts = CsrStudent::join('import_students', 'csr_students.student_id', '=', 'import_students.id')
            ->join('users', 'csr_students.csr_id', '=', 'users.id')
            ->select(
                'users.name as csr_name',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CASE WHEN csr_students.called_at IS NULL THEN 1 ELSE 0 END) as uncalled_count'),
                DB::raw('MONTH(import_students.datetime) as month'),
                DB::raw('YEAR(import_students.datetime) as year')
            )
            ->groupBy('users.name', DB::raw('YEAR(import_students.datetime)'), DB::raw('MONTH(import_students.datetime)'))
            ->get();

            $csrData = [];
            foreach ($csrDataCounts as $count) {
                $csrName = $count->csr_name;
                $month = $count->month;
                $year = $count->year;

                if (!isset($csrData[$csrName])) {
                    $csrData[$csrName] = [];
                }

                if (!isset($csrData[$csrName][$year])) {
                    $csrData[$csrName][$year] = [
                        'total' => array_fill(1, 12, 0),
                        'uncalled' => array_fill(1, 12, 0)
                    ];
                }

                $csrData[$csrName][$year]['total'][$month] = $count->total_count;
                $csrData[$csrName][$year]['uncalled'][$month] = $count->uncalled_count;
            }
            //=============================get csr data total and uncalled data for each months end=====================

            // Initialize an array to hold the counts for each month for each CSR
            $filtercsrs = User::where('type', 'csr')->pluck('name', 'id');
            $enrollStudentCountsByCSR = [];
            foreach ($filtercsrs as $csrId => $csrName) {
                $enrollStudentCountsByCSR[$csrName] = array_fill(1, 12, 0);
            }
            
            $monthlyEnrollStudentCountsByCSR = StudentCourse::join('student_course_payments', 'student_courses.id', '=', 'student_course_payments.student_course_id')
            ->join('students', 'students.id', '=', 'student_courses.student_id')
            ->join('users', 'users.id', '=', 'students.csr_id')
            ->select(
                'users.id as csr_id',
                'users.name as csr_name',
                DB::raw('MONTH(student_course_payments.payment_date_first) as month'),
                DB::raw('count(DISTINCT student_courses.id) as count')
            )
            ->whereYear('student_course_payments.payment_date_first', $currentYear)
            ->whereIn('students.csr_id', $filtercsrs->keys())
            ->groupBy('users.id', 'users.name', DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->orderBy('users.name')
            ->orderBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->get();
        
        $csrEnrollData = [];
        foreach ($monthlyEnrollStudentCountsByCSR as $data) {
            $csrName = $data->csr_name;
            $month = $data->month;
            $year = $currentYear;
        
            if (!isset($csrEnrollData[$csrName])) {
                $csrEnrollData[$csrName] = [];
            }
        
            if (!isset($csrEnrollData[$csrName][$year])) {
                $csrEnrollData[$csrName][$year] = array_fill(1, 12, 0);
            }
        
            $csrEnrollData[$csrName][$year][$month] = $data->count;
        }

        $partialEnrollStudentCountsByCSR = [];
        foreach ($filtercsrs as $csrId => $csrName) {
            $partialEnrollStudentCountsByCSR[$csrName] = array_fill(1, 12, 0);
        }

        $monthlyPartialEnrollStudentCountsByCSR = StudentCourse::join('student_course_payments', 'student_courses.id', '=', 'student_course_payments.student_course_id')
    ->join('students', 'students.id', '=', 'student_courses.student_id')
    ->join('users', 'users.id', '=', 'students.csr_id')
    ->select(
        'users.id as csr_id',
        'users.name as csr_name',
        DB::raw('MONTH(student_course_payments.payment_date_first) as month'),
        DB::raw('count(DISTINCT CASE WHEN student_courses.status_id = 2 THEN student_courses.id END) as count')
    )
    ->whereYear('student_course_payments.payment_date_first', $currentYear)
    ->whereIn('students.csr_id', $filtercsrs->keys())
    ->groupBy('users.id', 'users.name', DB::raw('MONTH(student_course_payments.payment_date_first)'))
    ->orderBy('users.name')
    ->orderBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
    ->get();

    $csrPartialEnrollData = [];
foreach ($monthlyPartialEnrollStudentCountsByCSR as $data) {
    $csrName = $data->csr_name;
    $month = $data->month;
    $year = $currentYear;

    if (!isset($csrPartialEnrollData[$csrName])) {
        $csrPartialEnrollData[$csrName] = [];
    }

    if (!isset($csrPartialEnrollData[$csrName][$year])) {
        $csrPartialEnrollData[$csrName][$year] = array_fill(1, 12, 0);
    }

    $csrPartialEnrollData[$csrName][$year][$month] = $data->count;
}
    
            return view('admin.dashboard.dashboard', compact(
                'actionStatus',  'totalCallToday', 'csrId', 'csrData', 'csrEnrollData', 'csrs', 'csrPartialEnrollData'
            ));
        }else{

            $currentYear = date('Y');  // Assuming you have the current year set
            // Get the current year
            $currentYear = Carbon::now()->year;

            //============== fetch month wise monthlydisrtributeddata total start
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
            //============== fetch month wise monthlydisrtributeddata total end


            //====================get monthwise distributed data that is due start
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
            //===================get monthwise distributed data that is due end


            //========Get month-wise student enrollments based on the first payment date, grouped by student_course_id start========
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
            //===========Get month-wise student enrollments based on the first payment date, grouped by student_course_id end===========


            $actionStatus = CsrActionStatus::all();
            $csrs = User::where(['type' => 'csr', 'status' => 1])->get();

            //=============================get csr total and uncalled data each months start=====================
            $csrDataCounts = CsrStudent::join('import_students', 'csr_students.student_id', '=', 'import_students.id')
            ->join('users', 'csr_students.csr_id', '=', 'users.id')
            ->select(
                'users.name as csr_name',
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CASE WHEN csr_students.called_at IS NULL THEN 1 ELSE 0 END) as uncalled_count'),
                DB::raw('MONTH(import_students.datetime) as month'),
                DB::raw('YEAR(import_students.datetime) as year')
            )
            ->groupBy('users.name', DB::raw('YEAR(import_students.datetime)'), DB::raw('MONTH(import_students.datetime)'))
            ->get();

            $csrData = [];
            foreach ($csrDataCounts as $count) {
                $csrName = $count->csr_name;
                $month = $count->month;
                $year = $count->year;

                if (!isset($csrData[$csrName])) {
                    $csrData[$csrName] = [];
                }

                if (!isset($csrData[$csrName][$year])) {
                    $csrData[$csrName][$year] = [
                        'total' => array_fill(1, 12, 0),
                        'uncalled' => array_fill(1, 12, 0)
                    ];
                }

                $csrData[$csrName][$year]['total'][$month] = $count->total_count;
                $csrData[$csrName][$year]['uncalled'][$month] = $count->uncalled_count;
            }
            //=============================get csr data total and uncalled data for each months end=====================

            // Initialize an array to hold the counts for each month for each CSR
            $filtercsrs = User::where('type', 'csr')->pluck('name', 'id');
            $enrollStudentCountsByCSR = [];
            foreach ($filtercsrs as $csrId => $csrName) {
                $enrollStudentCountsByCSR[$csrName] = array_fill(1, 12, 0);
            }
            
            $monthlyEnrollStudentCountsByCSR = StudentCourse::join('student_course_payments', 'student_courses.id', '=', 'student_course_payments.student_course_id')
            ->join('students', 'students.id', '=', 'student_courses.student_id')
            ->join('users', 'users.id', '=', 'students.csr_id')
            ->select(
                'users.id as csr_id',
                'users.name as csr_name',
                DB::raw('MONTH(student_course_payments.payment_date_first) as month'),
                DB::raw('count(DISTINCT student_courses.id) as count')
            )
            ->whereYear('student_course_payments.payment_date_first', $currentYear)
            ->whereIn('students.csr_id', $filtercsrs->keys())
            ->groupBy('users.id', 'users.name', DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->orderBy('users.name')
            ->orderBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
            ->get();
        
        $csrEnrollData = [];
        foreach ($monthlyEnrollStudentCountsByCSR as $data) {
            $csrName = $data->csr_name;
            $month = $data->month;
            $year = $currentYear;
        
            if (!isset($csrEnrollData[$csrName])) {
                $csrEnrollData[$csrName] = [];
            }
        
            if (!isset($csrEnrollData[$csrName][$year])) {
                $csrEnrollData[$csrName][$year] = array_fill(1, 12, 0);
            }
        
            $csrEnrollData[$csrName][$year][$month] = $data->count;
        }

        $partialEnrollStudentCountsByCSR = [];
        foreach ($filtercsrs as $csrId => $csrName) {
            $partialEnrollStudentCountsByCSR[$csrName] = array_fill(1, 12, 0);
        }

        $monthlyPartialEnrollStudentCountsByCSR = StudentCourse::join('student_course_payments', 'student_courses.id', '=', 'student_course_payments.student_course_id')
    ->join('students', 'students.id', '=', 'student_courses.student_id')
    ->join('users', 'users.id', '=', 'students.csr_id')
    ->select(
        'users.id as csr_id',
        'users.name as csr_name',
        DB::raw('MONTH(student_course_payments.payment_date_first) as month'),
        DB::raw('count(DISTINCT CASE WHEN student_courses.status_id = 2 THEN student_courses.id END) as count')
    )
    ->whereYear('student_course_payments.payment_date_first', $currentYear)
    ->whereIn('students.csr_id', $filtercsrs->keys())
    ->groupBy('users.id', 'users.name', DB::raw('MONTH(student_course_payments.payment_date_first)'))
    ->orderBy('users.name')
    ->orderBy(DB::raw('MONTH(student_course_payments.payment_date_first)'))
    ->get();

    $csrPartialEnrollData = [];
foreach ($monthlyPartialEnrollStudentCountsByCSR as $data) {
    $csrName = $data->csr_name;
    $month = $data->month;
    $year = $currentYear;

    if (!isset($csrPartialEnrollData[$csrName])) {
        $csrPartialEnrollData[$csrName] = [];
    }

    if (!isset($csrPartialEnrollData[$csrName][$year])) {
        $csrPartialEnrollData[$csrName][$year] = array_fill(1, 12, 0);
    }

    $csrPartialEnrollData[$csrName][$year][$month] = $data->count;
}
// dd($csrPartialEnrollData);


        return view('admin.dashboard.admin-dashboard', compact( 'csrs', 'actionStatus','dataDistributedCountsByMonth', 'dueDataCountsByMonth', 'enrollStudentCountsByMonth', 'csrData', 'csrEnrollData', 'csrPartialEnrollData'));  
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
