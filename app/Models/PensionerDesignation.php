<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionerDesignation extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $table = "optcl_employee_designation_master";
}
