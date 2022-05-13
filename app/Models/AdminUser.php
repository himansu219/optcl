<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $table = "optcl_users";

    public function designation() {
        return $this->belongsTo(UserDesignation::class);
    }
    public function optcl_unit() {
        return $this->belongsTo(UnitMaster::class);
    }
}
