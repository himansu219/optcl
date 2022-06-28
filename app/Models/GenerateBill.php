<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateBill extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $table = "optcl_bill_ben_details";

}
