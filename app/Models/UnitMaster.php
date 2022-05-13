<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_unit_master";
    public $timestamps = false;
  
   public function users() {
        return $this->hasMany(AdminUser::class);
    }
}
