<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_country_master";
    public $timestamps = false;

    public function state() {
        return $this->hasMany(StateMaster::class);
    }
}
