<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_district_master";
    public $timestamps = false;

    public function state() {
        return $this->belongsTo(StateMaster::class);
    }

}
