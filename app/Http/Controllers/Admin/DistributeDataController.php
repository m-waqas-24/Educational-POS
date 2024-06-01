<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CsrStudent;
use App\Models\DataDistributionRecord;
use App\Models\ImportStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributeDataController extends Controller
{
    public function index(){
        $csrs = User::where(['type' => 'csr', 'status' => 1])->orderBy('id','DESC')->get();
        $courses = ImportStudent::where('is_distributed', 0)->distinct()->pluck('course');
        // dd($courses);

        return view('admin.distribute-data.index', compact('csrs', 'courses'));
    }

    public function getTotalData(Request $request){
        $from = Carbon::createFromFormat('m/d/Y', $request->from)->startOfDay()->format('Y-m-d H:i:s');
        $to = Carbon::createFromFormat('m/d/Y', $request->to)->endOfDay()->format('Y-m-d H:i:s');
    
        $studentsCountofDates = 0;
        $studentsCountofCourse = 0;
    
        $studentsCountofDates = ImportStudent::where('is_distributed', 0)
                                              ->whereBetween('datetime', [$from, $to])
                                              ->count();
    
        if ($request->has('course')) {
            $studentsCountofCourse = ImportStudent::where('is_distributed', 0)
            ->where('course', 'like', '%' . $request->course . '%')
                                                  ->whereBetween('datetime', [$from, $to])
                                                  ->count();
        }
    
        return response()->json(['studentsCountofDates' => $studentsCountofDates, 'studentsCountofCourse' => $studentsCountofCourse]);
    }
    
    

    public function distribute(Request $request)
    {
        $request->validate([
            'from' => 'required',
            'to' => 'required',
            'csr' => 'required|array', // csr should be an array
        ]);
        
        $from = Carbon::createFromFormat('m/d/Y', $request->from)->startOfDay()->format('Y-m-d H:i:s');
        $to = Carbon::createFromFormat('m/d/Y', $request->to)->endOfDay()->format('Y-m-d H:i:s');
        
        $csrCount = count($request->csr);
        $csrIndex = 0;
    
        // Initialize an array to keep track of the distribution count per CSR
        $csrDistribution = array_fill_keys($request->csr, 0);
    
        $students = ImportStudent::where('is_distributed', 0)
            ->where('course', 'like', '%' . $request->course . '%')
            ->whereBetween('datetime', [$from, $to])
            ->get();
        
        $totalStudents = $students->count();
        
        if ($totalStudents > 0) {
            foreach ($students as $stu) {
                $csrId = $request->csr[$csrIndex];
        
                $stu->update([
                    'is_distributed' => 1,
                ]);
        
                CsrStudent::create([
                    'csr_id' => $csrId,
                    'student_id' => $stu->id,
                ]);
    
                // Increment the count for the current CSR
                $csrDistribution[$csrId]++;
        
                // Move to the next CSR or go back to the first one in a round-robin manner
                $csrIndex = ($csrIndex + 1) % $csrCount;
            }
            
            // Create the data distribution record
            DataDistributionRecord::create([
                'user_id' => Auth::user()->id,
                'total' => $totalStudents,
                'team' => json_encode($csrDistribution), // Store the CSR IDs and their counts as a JSON string
                'from' => $from,
                'to' => $to,
            ]);
    
            return back()->withSuccess('Data Distributed Successfully!');
        } else {
            return back()->withErrors('There is no data between this date range!');
        }
    }
    
    
    
    
    public function distributionRecord(){
        $reports = DataDistributionRecord::all();

        return view('admin.distribute-data.recoords', compact('reports'));
    }
    
    public function filterDistributedData(Request $request) {
        $importedStudents = ImportStudent::orderBy('id', 'DESC')->where('is_distributed', 0)->get();
        
        $query = ImportStudent::where('is_distributed', 1);
        
        if ($request->from && $request->to) {
            $from = Carbon::createFromFormat('m/d/Y', $request->from)->startOfDay()->format('Y-m-d H:i:s');
            $to = Carbon::createFromFormat('m/d/Y', $request->to)->endOfDay()->format('Y-m-d H:i:s');
            $query->whereBetween('datetime', [$from, $to]);
        } else {
            $from = $to = null;
        }
    
        if ($request->course) {
            $course = $request->course;
            $query->where('course', 'like', '%' . $course . '%');
        }else{
            $course = null;
        }   
    
        $distributedStudents = $query->get();
        
        $courses = ImportStudent::distinct()->pluck('course');
        
        return view('admin.importdata.index', compact('importedStudents', 'distributedStudents', 'from', 'to', 'courses', 'course'));
    }
    
    
    


}
