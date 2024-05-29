<?php

namespace App\Models;

use App\Models\Admin\CsrStudent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function csr(){
        return $this->hasOne(CsrStudent::class, 'student_id', 'id');
    }
}