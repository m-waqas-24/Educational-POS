<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orientation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }
}
