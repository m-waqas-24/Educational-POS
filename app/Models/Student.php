<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function courses(){
        return $this->hasMany(StudentCourse::class, 'student_id', 'id');
    }

    public function city(){
        return $this->belongsTo(ProvinceCity::class, 'city_id', 'id');
    }

    public function source(){
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }

    public function csr(){
        return $this->belongsTo(User::class, 'csr_id', 'id');
    }

    public function qualification(){
        return $this->belongsTo(Qualification::class, 'qualification_id', 'id');
    }
    
    public function courseStatus()
    {
        $courseStatuses = $this->courses()->pluck('status_id');

        if ($courseStatuses->contains(2)) {
            return 2;
        }
        if ($courseStatuses->every(function ($status) {
            return $status == 1;
        })) {
            return 1;
        }
    }
    
}
