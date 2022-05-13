<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $table = "optcl_religion_master";
}
