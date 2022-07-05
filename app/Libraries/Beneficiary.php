<?php  

namespace App\Libraries;

use Illuminate\Support\Str;
use App\Libraries\Encryption;
use App\Models\GeneralSettingsModel;
use DB;
use Auth;
use Session;


class Beneficiary {

    private $current_date;

    public function __construct(){
        $this->current_date = date('Y-m-d H:i:s');
    }
    
    public static function newBeneficiaryDataStorage ($data){
        $user = Auth::user();  
        $remarks = $data['remarks'];
        $montly_changed_id = $data['monthly_changed_data_id'];
        $application_id = $data['application_id'];
        $pernsioner_type = $data['pensioner_type'];
        $application_type = $data['application_type'];
        // Check the application and pension type by which we can get the pensioner details according to type id
        $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                        ->where('id', $montly_changed_id)
                                        ->first();
        try {
            DB::beginTransaction();
            if($monthly_changed_data){
                // Application status update
                DB::table('optcl_pension_application_form')->where('id', $application_id)->update(['application_status_id' => 51]);
                // Monthly changed data update
                DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                
                // Get Beneficiary Details from User Table
                $application_details = DB::table('optcl_pension_application_form')
                                            ->where('id', $application_id)
                                            ->where('status', 1)->where('deleted', 0)
                                            ->first();
                $user_id = $application_details->user_id;
                $sanction_order_file_path = $application_details->sanction_order_file_path;
                $gratuity_sanction_order_file_path = $application_details->gratuity_sanction_order_file_path;
                $ppo_order_file_path = $application_details->ppo_order_file_path;
                $basic_amount = $application_details->basic_amount;
                $basic_effective_date = $application_details->basic_effective_date;
                $additional_pension_amount = $application_details->additional_pension_amount;
                $additional_pension_effective_date = $application_details->additional_pension_effective_date;
                $enhanced_pension_amount = $application_details->enhanced_pension_amount;
                $enhanced_pension_effective_date = $application_details->enhanced_pension_effective_date;
                $enhanced_pension_end_date = $application_details->enhanced_pension_end_date;
                $normal_pension_amount = $application_details->normal_pension_amount;
                $normal_pension_effective_date = $application_details->normal_pension_effective_date;
                $gross_pension_amount = $application_details->gross_pension_amount;
                $total_income = $application_details->total_income;

                $user_details = DB::table('optcl_users')
                                    ->select('optcl_users.aadhaar_no', 'optcl_users.mobile', 'optcl_users.email_id', 'optcl_users.optcl_unit_id', 'optcl_users.optcl_unit_id', DB::raw('CONCAT(COALESCE(optcl_users.first_name, ""), " ", COALESCE(optcl_users.last_name, "")) AS full_name'))
                                    ->where('id', $user_id)
                                    ->where('status', 1)->where('deleted', 0)
                                    ->first();
                $full_name = $user_details->full_name;
                // Service Pensioner PAN Details
                $employee_personal_details = DB::table('optcl_employee_personal_details AS ep')
                                                ->select('ep.*', 'bbm.branch_name', 'bbm.ifsc_code','bbm.address','bm.bank_name')
                                                ->join('optcl_bank_branch_master AS bbm', 'bbm.id', '=', 'ep.bank_branch_id')
                                                ->join('optcl_bank_master AS bm', 'bm.id', '=', 'bbm.bank_id')
                                                ->where('ep.id', $application_id)
                                                ->where('ep.status', 1)->where('ep.deleted', 0)
                                                ->first();
                $emp_pan = $employee_personal_details->pan_no;
                $savings_bank_account_no = $employee_personal_details->savings_bank_account_no;
                $bank_branch_id = $employee_personal_details->bank_branch_id;
                $branch_name = $employee_personal_details->branch_name;
                $ifsc_code = $employee_personal_details->ifsc_code;
                $address = $employee_personal_details->address;
                $bank_name = $employee_personal_details->bank_name;
                // Service Pensioner Retirement Date                    
                $retirementDetails = DB::table('optcl_nominee_master')
                                    ->where('id', $application_details->employee_id)
                                    ->where('status', 1)->where('deleted', 0)
                                    ->first();
                
                //dd($user_details);
                // Pension Calculation Details
                $pension_amount_details = DB::table('optcl_net_pension_details')
                                    ->where('application_type', 1)
                                    ->where('pension_type', 2)
                                    ->where('id', $application_id)
                                    ->where('status', 1)->where('deleted', 0)
                                    ->first();
                $net_pension_amount = $pension_amount_details->net_pension_amount;
                //$basic_amount = $pension_amount_details->basic_amount;
                //$additional_amount = $pension_amount_details->additional_amount;
                //$ti_amount = $pension_amount_details->ti_amount;
                //$gross_pension_amount = $pension_amount_details->gross_pension_amount;
                //$trust_recovery_amount = $pension_amount_details->trust_recovery_amount;
                //$other_recovery_amount = $pension_amount_details->other_recovery_amount;
                //$net_pension_amount = $pension_amount_details->net_pension_amount;
                $rebate_amount = $pension_amount_details->rebate_amount;
                $health_education_percentage = $pension_amount_details->health_education_percentage;
                $health_education_amount = $pension_amount_details->health_education_amount;
                $tot_tax_amount = $pension_amount_details->tot_tax_amount;
                $tot_tax_payable_anually = $pension_amount_details->tot_tax_payable_anually;
                $tds_amount = $pension_amount_details->tds_amount;

                // Beneficiary Details Storage
                $bData = [
                    "application_type" => $monthly_changed_data->appliation_type,
                    "pensioner_type" => $monthly_changed_data->pensioner_type,
                    "application_id" => $monthly_changed_data->application_id,
                    "pensioner_name" => $user_details->full_name,
                    "pensioner_aadhaar" => $user_details->aadhaar_no,
                    "pensioner_pan" => $employee_personal_details->pan_no,
                    "pensioner_mobile" => $user_details->mobile,
                    "optcl_unit_id" => $user_details->optcl_unit_id,
                    "pension_unit_id" => $monthly_changed_data->pension_unit_id,
                    "ppo_no" => $application_details->ppo_number,
                    "date_of_retirement" => $retirementDetails->date_of_retirement,
                    //"date_of_death" => ,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                $beneficiary_id = DB::table('optcl_employee_master')->insertGetId($bData);
                // Beneficiary Details History Storage
                $bHisData = [
                    "beneficiary_id" => $beneficiary_id,
                    "application_type" => $monthly_changed_data->appliation_type,
                    "pensioner_type" => $monthly_changed_data->pensioner_type,
                    "application_id" => $monthly_changed_data->application_id,
                    "pensioner_name" => $user_details->full_name,
                    "pensioner_aadhaar" => $user_details->aadhaar_no,
                    "pensioner_pan" => $panDetails->pan_no,
                    "pensioner_mobile" => $user_details->mobile,
                    "optcl_unit_id" => $user_details->optcl_unit_id,
                    "pension_unit_id" => $monthly_changed_data->pension_unit_id,
                    "ppo_no" => $application_details->ppo_number,
                    "date_of_retirement" => $retirementDetails->date_of_retirement,
                    //"date_of_death" => ,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                DB::table('optcl_beneficiary_details_history')->insertGetId($bHisData);
                // Beneficiary Account Details
                $bAccountDetails = [
                    "beneficiary_id" => $beneficiary_id,
                    "bank_branch_id" => $bank_branch_id,
                    "bank_name" => $bank_name,
                    "branch_name" => $branch_name,
                    "ifsc_code" => $ifsc_code,
                    "account_number" => $savings_bank_account_no,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                $ben_account_id = DB::table('optcl_beneficiary_account_details')->insertGetId($bAccountDetails);
                // Beneficiary Account History
                $bHistoryAccountDetails = [
                    "beneficiary_id" => $beneficiary_id,
                    "bank_branch_id" => $bank_branch_id,
                    "bank_name" => $bank_name,
                    "branch_name" => $branch_name,
                    "ifsc_code" => $ifsc_code,
                    "account_number" => $savings_bank_account_no,
                    "effective_from" => $this->current_date,
                    //"effective_to" => ,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                DB::table('optcl_beneficiary_account_history')->insert($bHistoryAccountDetails);
                // Beneficiary Documents
                $ben_doc_data = [
                    "beneficiary_id" => $beneficiary_id,
                    "sanction_order_file_path" => $sanction_order_file_path,
                    "gratuity_sanction_order_file_path" => $gratuity_sanction_order_file_path,
                    "ppo_order_file_path" => $ppo_order_file_path,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                DB::table('optcl_beneficiary_documents')->insert($ben_doc_data);
                // Pension Amount Details
                $ben_pen_amount_data = [
                    "beneficiary_id" => $beneficiary_id,
                    "pension_amount" => $net_pension_amount,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                DB::table('optcl_beneficiary_pension_amount_details')->insert($ben_pen_amount_data);
                // Pension Amount History
                $ben_pen_amount_history_data = [
                    "beneficiary_id" => $beneficiary_id,
                    "basic_amount" => $basic_amount,
                    "basic_amount_effective_from" => $basic_effective_date,
                    /*"basic_amount_effective_to" => $bank_branch_id, */
                    "additional_pension_amount" => $additional_pension_amount,
                    "additional_pension_effective_from" => $additional_pension_effective_date,
                    /*"additional_pension_effective_to" => $bank_branch_id, */
                    "enhanced_pension_amount" => $enhanced_pension_amount,
                    "enhanced_pension_effective_from" => $enhanced_pension_effective_date,
                    /* "enhanced_pension_effective_to" => $bank_branch_id, */
                    "normal_pension_amount" => $normal_pension_amount,
                    "normal_pension_effective_from" => $normal_pension_effective_date,
                    /* "normal_pension_effective_to" => $bank_branch_id, */
                    "gross_pension_amount" => $gross_pension_amount,
                    "gross_pension_effective_from" => $this->current_date,/*  */
                    /* "gross_pension_effective_to" => $bank_branch_id, */
                    "total_income" => $total_income,
                    "total_income_effective_from" => $this->current_date,
                    /* "total_income_effective_to" => $bank_branch_id, */
                    "taxable_amount" => $tot_tax_amount,
                    "taxable_amount_effective_from" => $this->current_date,
                    /* "taxable_amount_effective_to" => $bank_branch_id, */
                    "pension_amount" => $net_pension_amount,
                    "pension_amount_effective_from" => $this->current_date,
                    /* "pension_amount_effective_to" => $bank_branch_id, */
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ];
                DB::table('optcl_beneficiary_pension_amount_history')->insert($ben_pen_amount_history_data);
            }
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    public static function revisionBasicPensionDataStorage ($data){
        $user = Auth::user();  
        $remarks = $data['remarks'];
        $montly_changed_id = $data['montly_changed_id'];
        $application_id = $data['application_id'];
        $pernsioner_type = $data['pernsioner_type'];
        $application_type = $data['application_type'];
        // Check the application and pension type by which we can get the pensioner details according to type id
        $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                        ->where('id', $montly_changed_id)
                                        ->first();
        try {
            DB::beginTransaction();
            if($monthly_changed_data){
                $cr_id = $monthly_changed_data->cr_id;
                $rev_data = DB::table('optcl_change_data_revision_basic_pension')
                                ->where('id', $cr_id)
                                ->where('status', 1)
                                ->where('deleted', 0)->first();

                // Monthly changed data update
                DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                //dd(1, $monthly_changed_data, $data);
                $net_pension_details = DB::table('optcl_net_pension_details')
                                        ->where('application_id', $application_id)
                                        ->orderBy('id', 'DESC')
                                        ->first();
                //dd($net_pension_details);
                if($net_pension_details){
                    //dd($net_pension_details);
                    $net_pension_amount = $net_pension_details->net_pension_amount;
                    $additional_amount = $net_pension_details->additional_amount;
                    $pension_amount_details = DB::table('optcl_beneficiary_details AS bd')
                                                ->join('optcl_beneficiary_account_details AS bad', 'bad.beneficiary_id', '=', 'bd.id')
                                                ->select('bad.id AS account_details_id')
                                                ->where('bd.application_id', $application_id)
                                                ->where('bd.status', 1)
                                                ->where('bd.deleted', 0)
                                                ->first();
                    dd($rev_data, $net_pension_details, $pension_amount_details);
                    if($pension_amount_details){
                        $effective_from = $rev_data->oo_no_date;
                        $basic_amount_value = $rev_data->pensioner_basic_amount;
                        $gross_pension_amount = $rev_data->gross_pension_amount;
                        $total_income = $rev_data->total_income;
                        $taxable_amount = $rev_data->taxable_amount;
                        $ben_pre_data = DB::table('optcl_beneficiary_pension_amount_details')->where('id', $account_details_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
                        // Get updated revision amount
                        $account_details_id = $pension_amount_details->account_details_id;
                        DB::table('optcl_beneficiary_pension_amount_details')->where('id', $account_details_id)->update([
                            "basic_amount"                      => $basic_amount_value,
                            "basic_amount_effective_from"       => $effective_from,
                            "additional_pension_amount"         => $additional_amount,
                            "additional_pension_effective_from" => $effective_from,
                            "enhanced_pension_amount"           => NULL,
                            "enhanced_pension_effective_from"   => $effective_from,
                            "enhanced_pension_effective_to"     => NULL,
                            "normal_pension_amount"             => NULL,
                            "normal_pension_effective_from"     => $effective_from,
                            "gross_pension_amount"              => $gross_pension_amount,
                            "gross_pension_effective_from"      => $effective_from,
                            "total_income"                      => $total_income,
                            "total_income_effective_from"       => $effective_from,
                            "taxable_amount"                    => $taxable_amount,
                            "taxable_amount_effective_from"     => $effective_from,
                            'pension_amount'                    => $net_pension_amount,
                            "pension_amount_effective_from"     => $effective_from,
                            'updated_at'        => $this->current_date,
                            'updated_by'        => $user->id,
                        ]);
                        // Revison amount data updated history
                        $ben_pen_acc_history = [
                            "basic_amount"                      => $ben_pre_data->basic_amount,
                            "basic_amount_effective_from"       => $ben_pre_data->basic_amount_effective_from,
                            "basic_amount_effective_to"         => $this->current_date,
                            "additional_pension_amount"         => $ben_pre_data->additional_pension_amount,
                            "additional_pension_effective_from" => $ben_pre_data->additional_pension_effective_from,
                            "additional_pension_effective_to"   => $this->current_date,
                            "enhanced_pension_amount"           => $ben_pre_data->basic_amount_effective_from,
                            "enhanced_pension_effective_from"   => $ben_pre_data->basic_amount_effective_from,
                            "enhanced_pension_effective_to"     => $this->current_date,
                            "normal_pension_amount"             => $ben_pre_data->normal_pension_amount,
                            "normal_pension_effective_from"     => $ben_pre_data->normal_pension_effective_from,
                            "normal_pension_effective_to"       => $this->current_date,
                            "gross_pension_amount"              => $ben_pre_data->gross_pension_amount,
                            "gross_pension_effective_from"      => $ben_pre_data->gross_pension_effective_from,
                            "gross_pension_effective_to"        => $this->current_date,
                            "total_income"                      => $ben_pre_data->total_income,
                            "total_income_effective_from"       => $ben_pre_data->total_income_effective_from,
                            "total_income_effective_to"         => $this->current_date,
                            "taxable_amount"                    => $ben_pre_data->taxable_amount,
                            "taxable_amount_effective_from"     => $ben_pre_data->taxable_amount_effective_from,
                            "taxable_amount_effective_to"       => $this->current_date,
                            "pension_amount"                    => $ben_pre_data->pension_amount,
                            "pension_amount_effective_from"     => $ben_pre_data->pension_amount_effective_from,
                            "pension_amount_effective_to"       => $this->current_date,
                            'created_at'                        => $this->current_date,
                            'created_by'                        => $user->id,
                        ];
                        DB::table('optcl_beneficiary_pension_amount_history')->insert($ben_pen_acc_history);
                        return true;
                    }else{
                        DB::rollback();
                        return false;
                    }
                }
                                
            }
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

}