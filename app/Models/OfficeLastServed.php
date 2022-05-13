<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLastServed extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $table = "optcl_unit_master";

    public function pensionForm() {
    	return $this->hasMany(Pensionform::class, 'name_of_office_dept', 'code');
    }
}
