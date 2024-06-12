<?php

namespace App\Models\Admin;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taskType(){
        return $this->belongsTo(TaskStatus::class, 'type', 'id');
    }
}
