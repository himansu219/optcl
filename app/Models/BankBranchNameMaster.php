<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranchNameMaster extends Model
{
    //use HasFactory;
    protected $table = "optcl_bank_branch_master";
    public $timestamps = false;

    public function bank() {
        return $this->belongsTo(BankNameMaster::class);
    }
}
