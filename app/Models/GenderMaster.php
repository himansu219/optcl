<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderMaster extends Model
{
    //use HasFactory;
    protected $table= "optcl_employee_gender_master";
    public $timestamps = false;
}
