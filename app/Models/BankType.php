<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankType extends Model
{
    //use HasFactory;
    protected $table = "optcl_bank_type_master";
    public $timestamps = false;

    public function bank_name() {
        return $this->hasMany(BankNameMaster::class);
    }
}
