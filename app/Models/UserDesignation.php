<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    //use HasFactory;
    protected $table = "optcl_user_designation_master";
    public $timestamps = false;

    public function user() {
        return $this->hasMany(AdminUser::class);
    }

}
