<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchInstructor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    
}
