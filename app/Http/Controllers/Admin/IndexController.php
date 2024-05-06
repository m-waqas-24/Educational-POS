<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CsrActionStatus;
use App\Models\Admin\CsrStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return view('admin.dashboard.admin-dashboard');  
        }

    }

    public function adminViewCsrDashboard($id){
        $actionStatus = CsrActionStatus::all();
        $user = Auth::user();
        $totalCallToday = CsrStudent::where('csr_id', $id)->where('called_at', \Carbon\Carbon::today())->count();
        $students = CsrStudent::where(['csr_id' => $id, 'action_status_id' => 0 ])->orderBy('id','DESC')->count();
        $csrId = $id;

        return view('admin.dashboard.dashboard', compact(
            'actionStatus',  'totalCallToday', 'csrId', 'students'
        ));
    }

    public function workshops($id){
        $workshops = getWorkshopsByBatchId($id);
        dd($workshops);
        
        return view('admin.workshops.index', compact('workshops'));
    }

}
