<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsrActionStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function CsrStudent(){
        return $this->hasMany(CsrStudent::class, 'action_status_id' , 'id');
    }

}