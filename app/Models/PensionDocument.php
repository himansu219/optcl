<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PensionDocument extends Model
{
    //use HasFactory;
    protected $table = "optcl_employee_document_details";
    public $timestamps = false;

    protected $fillable = ['proposal_id', 'attached_recent_passport', 'attached_dob_certificate', 'attached_recent_passport', 'attached_undertaking_declaration', 'attached_bank_passbook', 'attached_cancelled_chqeue', 'attached_indemnity_bond', 'attached_descriptive_roll_slips', 'created_by', 'created_at', 'modified_by', 'modified_at', 'status', 'deleted'];

    public function pension_form() {
    	return $this->belongsTo(PensionForm::class);
    }
}
