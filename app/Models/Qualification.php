<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent()
    { 
        return $this->belongsTo(Qualification::class, 'parent_id', 'id');
    }
}
