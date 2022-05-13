<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankNameMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_bank_master";
    public $timestamps = false;

    public function bank_type() {
        return $this->belongsTo(BankType::class);
    }
    public function bank_branch() {
        return $this->hasMany(BankBranchNameMaster::class);
    }
}
