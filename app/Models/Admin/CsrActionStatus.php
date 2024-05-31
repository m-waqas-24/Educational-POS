<?php

namespace App\Models\Admin;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CsrActionStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function csrStudent(){
        return $this->hasMany(CsrStudent::class, 'action_status_id' , 'id');
    }
    
    public function getCallCount()
    {
        $today = Carbon::today();
        
        return CsrStudent::where('action_status_id', $this->id)
                         ->where('csr_id', Auth::user()->id)
                         ->whereDate('called_at', $today)
                         ->count();
    }

}