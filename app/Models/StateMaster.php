<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_state_master";
    public $timestamps = false;

    public function country() {
        return $this->belongsTo(CountryMaster::class);
    }

    public function district() {
        return $this->hasMany(DistrictMaster::class);
    }

}
