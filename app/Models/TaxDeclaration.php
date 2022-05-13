<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDeclaration extends Model
{
    //use HasFactory;
    protected $table = "optcl_tax_declaration_master";
    public $timestamps = false;
}
