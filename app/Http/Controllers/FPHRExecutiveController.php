<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\NomineeUtil;
use App\Libraries\PensinorCalculation;
use App\Libraries\fpdf\FPDF;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class FPHRExecutiveController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function application_details($id) {
        //Update Notification
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id])
            ->update(['view_status' => 1]);
        $last_basic_pay = DB::table('optcl_nominee_pension_service_form')
                            ->where('application_id', $id) 
                            ->value('last_basic_pay');

    	$application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

        $proposal = DB::table('optcl_nominee_master as em')
                            ->join('optcl_employee_designation_master as ud','ud.id','=','em.designation_id')
                            ->join('optcl_employee_gender_master as g', 'g.id','=','em.gender_id')
                            ->join('optcl_marital_status_master as ms','ms.id','=','em.marital_status_id')
                            ->join('optcl_religion_master as r','r.id','=','em.religion_id')
                            ->join('optcl_pf_account_type_master as a','a.id','=','em.pf_account_type_id')
                            ->join('optcl_unit_master as o','o.id','=','em.optcl_unit_id')
                            ->join('optcl_nominee_family_pensioner_form as pd', 'pd.nominee_master_id','=','em.id')
                            ->join('optcl_country_master AS c1','c1.id','=','pd.postal_addr_country_id')
                            ->join('optcl_state_master AS s','s.id','=','pd.postal_addr_state_id')
                            ->join('optcl_district_master AS d','d.id','=','pd.postal_addr_district_id')
                            ->leftJoin('optcl_relation_master AS rm','rm.id','=','pd.family_member_relation_id')
                            ->join('optcl_pension_unit_master AS pu','pu.id','=','pd.pension_unit_id')
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name','o.unit_name as office_last_served','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','pu.pension_unit_name')
                            ->where('em.id', $application->employee_id)
                            ->first();

        $employee_documents = DB::table('optcl_nominee_pension_application_document as a')
                            ->select('a.nominee_master_id', 'a.document_id', 'a.document_attachment_path', 'b.field_id', 'b.document_name')
                            ->join('optcl_pension_document_master as b', 'a.document_id', '=', 'b.id')
                            ->where('a.nominee_master_id', $application->employee_id)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();

        $employee_nominees = DB::table('optcl_nominee_nominee_details as a')
                            ->select('a.*', 'b.nominee_prefrence', 'c.gender_name', 'd.relation_name', 'e.bank_name', 'f.branch_name', 'f.ifsc_code', 'g.marital_status_name')
                            ->join('optcl_nominee_preference_master as b', 'a.nominee_preference_id', '=', 'b.id')
                            ->join('optcl_employee_gender_master as c', 'a.gender_id', '=', 'c.id')
                            ->join('optcl_relation_master as d', 'a.relationship_id', '=', 'd.id')
                            ->join('optcl_bank_master as e', 'a.bank_id', '=', 'e.id')
                            ->join('optcl_bank_branch_master as f', 'a.bank_branch_id', '=', 'f.id')
                            ->join('optcl_marital_status_master as g', 'a.marital_status_id', '=', 'g.id')
                            ->where('a.nominee_master_id', $application->employee_id)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();

        $statusHistory = DB::table('optcl_application_status_history AS sh')
                               ->join('optcl_application_status_master AS sm','sm.id','=','sh.status_id')
                               ->select('sm.status_name','sh.created_at','sh.remarks')
                               ->where('sh.application_id', $id)
                               ->where('sh.status', 1)
                               ->where('sh.deleted', 0)
                               ->where('sm.status', 1)
                               ->where('sm.deleted', 0)
                               ->get();

        $add_recovery = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->get();

        //dd($add_recovery);

        $service_form = DB::table('optcl_nominee_pension_service_form')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->first();

        $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $id)->first();
        //dd($service_form_three, $id);
        if(!empty($service_form)){
            $organisation_details = DB::table('optcl_nominee_pension_service_offices')->where('status', 1)->where('deleted', 0)->where('service_pension_form_id', $service_form->id)->get();
        }else{
            $organisation_details = array();    
        }

        // Calculation Sheet Code Started
        $get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();
        //dd($get_da_percentage);
        $basic_pay_amount_at_retirement = DB::table('optcl_nominee_pension_service_form')->where('application_id', $id)->value('last_basic_pay');
        // Service Pension Due
        $last_basic_pay = !empty($service_form_three->emolument_last_basic_pay) ? $service_form_three->emolument_last_basic_pay : $basic_pay_amount_at_retirement;

        $total_da_amount = 0;

        if(!empty($get_da_percentage)) {
            $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
        }

        $service_pension_due = PensinorCalculation::get_service_pension_due($service_form, $last_basic_pay, $service_form_three);


        $service_pension_masters = DB::table('optcl_calculation_rule_master')->where('pension_type_id', 1)
                            ->where('calculation_type_id', 1)
                            ->where('status', 1)->where('deleted', 0)->get();

        $service_pension_due_ids = $service_pension_masters->pluck('id')->toArray();


        $service_pension_due_exist = DB::table('optcl_nominee_calculation_transaction')
                ->where('application_id', $id)
                ->where('nominee_master_id', $application->employee_id)
                ->whereIn('rule_id' , $service_pension_due_ids)
                ->where('is_latest', 1)
                ->orderBy('id', 'desc')->first();

        // Commutation        
        $commutation_value = [];
        $commutation_two_value = [];
        $commutation_three_value = [];
        //$is_commutation_pension_applied = 0;
        // Get commutation percentage from pensioner
        $employee_code = DB::table('optcl_pension_application_form')
            ->where('id', $application->id)
            ->value('employee_code');
        $commutation_percentage = DB::table('optcl_employee_personal_details')
            ->where('employee_code', $employee_code)
            ->value('commutation_percentage');
        $commutation_percentage = $commutation_percentage ? $commutation_percentage : 0;
        if($commutation_percentage == 0){
            // Check any Commutation rule data available or not
            $commRuleValue = DB::table('optcl_nominee_calculation_transaction')
                                ->select('optcl_nominee_calculation_transaction.*')
                                ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                ->where('optcl_nominee_calculation_transaction.calculation_type_id', 6)
                                ->first();
            //dd($commRuleValue);
            if($commRuleValue){
                $rule_id = $commRuleValue->rule_id;
                if($rule_id == 3){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                                        ->select('optcl_nominee_calculation_transaction.*')
                                        ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                        ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                        ->where('optcl_nominee_calculation_transaction.calculation_type_id', 1)
                                        ->first();

                    if($pensionValue){
                        $pension_admissible = $pensionValue->rounded_calculation_value;
                        $commutation_percentage = $proposal->commutation_percentage;
                        $commuted_value = $pension_admissible*($commutation_percentage/100);
                        $rounded_below_value = floor($commuted_value);
                        // Fetch commutation ratio value
                        $persioner_dob = $proposal->date_of_birth;
                        $ageValue = Util::get_years_months_days($persioner_dob, date('Y-m-d'));
                        $ratio_year = $ageValue['years']+1;

                        $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $ratio_year)->first();
                        $commutation_ratio = !empty($commtation_data->commutation_ratio) ? $commtation_data->commutation_ratio : 0;
                        //$commutation_ratio = 8.194;

                        // Commutation Pension
                        $as_worked_out = $commuted_value*$commutation_ratio*12;
                        $rounded_as_worked_out = ceil($as_worked_out);
                        $reduced_pension_per_month = $pension_admissible - $commuted_value;
                        $commutation_rule_one = $commRuleValue->calculation_value."_".$commRuleValue->rounded_calculation_value."_".$application->id."_".$application->employee_id."_".$commRuleValue->rule_id."_".$commRuleValue->calculation_type_id;
                        $commutation_value = [
                            'pension_admissible'        =>  $pension_admissible,
                            'commutation_percentage'    =>  $commutation_percentage,
                            'rounded_below_value'       =>  $rounded_below_value,
                            'commuted_value'            =>  $commuted_value,
                            'commutation_ratio'         =>  $commutation_ratio,
                            /*'as_worked_out'             =>  $as_worked_out,
                            'rounded_as_worked_out'     =>  $rounded_as_worked_out,
                            'reduced_pension_per_month' =>  $reduced_pension_per_month,*/
                            'comm_rule_id'              =>  $commRuleValue->rule_id,
                            'commutation_rule_one'      =>  $commutation_rule_one,
                            'rule_description'          =>  $ruleDetails->rule_description,
                        ];
                    }else{
                        $commutation_value = [];
                    }
                }else{
                    $commutation_value = [];
                }                
            }

            // As Worked Out
            $commRuletwoValue = DB::table('optcl_nominee_calculation_transaction')
                                ->select('optcl_nominee_calculation_transaction.*')
                                ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                ->where('optcl_nominee_calculation_transaction.calculation_type_id', 2)
                                ->first();
            //dd($commRuletwoValue);
            
            if($commRuletwoValue){
                $rule_id = $commRuletwoValue->rule_id;
                if($rule_id == 2){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                                        ->select('optcl_nominee_calculation_transaction.*')
                                        ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                        ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                        ->where('optcl_nominee_calculation_transaction.calculation_type_id', 1)
                                        ->first();

                    if($pensionValue){
                        // Fetch commutation ratio value
                        $persioner_dob = $proposal->date_of_birth;
                        $ageValue = Util::get_years_months_days($persioner_dob, date('Y-m-d'));
                        $ratio_year = $ageValue['years']+1;

                        $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $ratio_year)->first();
                        $commutation_ratio = !empty($commtation_data->commutation_ratio) ? $commtation_data->commutation_ratio : 0;
                        //$commutation_ratio = 8.194;

                        // Commutation Pension
                        $as_worked_out = $commuted_value*$commutation_ratio*12;
                        $rounded_as_worked_out = ceil($as_worked_out);
                        $reduced_pension_per_month = $pension_admissible - $commuted_value;
                        $commutation_rule_one = $commRuletwoValue->calculation_value."_".$commRuletwoValue->rounded_calculation_value."_".$application->id."_".$application->employee_id."_".$commRuletwoValue->rule_id."_".$commRuletwoValue->calculation_type_id;
                        $commutation_two_value = [
                            'pension_admissible'        =>  $pension_admissible,
                            'commutation_percentage'    =>  $commutation_percentage,
                            'rounded_below_value'       =>  $rounded_below_value,
                            'commuted_value'            =>  $commuted_value,
                            'commutation_ratio'         =>  $commutation_ratio,
                            'as_worked_out'             =>  $as_worked_out,
                            'rounded_as_worked_out'     =>  $rounded_as_worked_out,
                            'comm_rule_id_two'          =>  $commRuletwoValue->rule_id,
                            'commutation_rule_two'      =>  $commutation_rule_one,
                            'rule_description'          =>  $ruleDetails->rule_description,
                        ];
                    }else{
                        $commutation_two_value = [];
                    }
                }else{
                    $commutation_two_value = [];
                }                
            }

            // Reduced pension
            $commRuleThreeValue = DB::table('optcl_nominee_calculation_transaction')
                                ->select('optcl_nominee_calculation_transaction.*')
                                ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                ->where('optcl_nominee_calculation_transaction.calculation_type_id', 7)
                                ->first();
            //dd($commRuleThreeValue);
            
            if($commRuleThreeValue){
                $rule_id = $commRuleThreeValue->rule_id;
                if($rule_id == 4){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                                        ->select('optcl_nominee_calculation_transaction.*')
                                        ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                        ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                        ->where('optcl_nominee_calculation_transaction.calculation_type_id', 1)
                                        ->first();
                    if($pensionValue){
                        // Fetch commutation ratio value
                        $persioner_dob = $proposal->date_of_birth;
                        $ageValue = Util::get_years_months_days($persioner_dob, date('Y-m-d'));
                        $ratio_year = $ageValue['years']+1;

                        $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $ratio_year)->first();
                        $commutation_ratio = !empty($commtation_data->commutation_ratio) ? $commtation_data->commutation_ratio : 0;
                        //$commutation_ratio = 8.194;

                        // Commutation Pension
                        $as_worked_out = $commuted_value*$commutation_ratio*12;
                        $rounded_as_worked_out = ceil($as_worked_out);
                        $reduced_pension_per_month = $pension_admissible - $commuted_value;

                        $commutation_rule_three = $commRuleThreeValue->calculation_value."_".$commRuleThreeValue->rounded_calculation_value."_".$application->id."_".$application->employee_id."_".$commRuleThreeValue->rule_id."_".$commRuleThreeValue->calculation_type_id;
                        $commutation_three_value = [
                            'pension_admissible'        =>  $pension_admissible,
                            'commutation_percentage'    =>  $commutation_percentage,
                            'rounded_below_value'       =>  $rounded_below_value,
                            'commuted_value'            =>  $commuted_value,
                            'commutation_ratio'         =>  $commutation_ratio,
                            'reduced_pension_per_month' =>  $reduced_pension_per_month,
                            'rounded_as_worked_out'     =>  $rounded_as_worked_out,
                            'comm_rule_id_three'        =>  $commRuleThreeValue->rule_id,
                            'commutation_rule_three'    =>  $commutation_rule_three,
                            'rule_description'          =>  $ruleDetails->rule_description,
                        ];
                    }else{
                        $commutation_three_value = [];
                    }
                }else{
                    $commutation_three_value = [];
                }
            }

        }

        // Family Pension
        $family_pension_data = [];
        // Check any family pension data available or not
        $familyPensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_nominee_calculation_transaction.calculation_type_id', 3)
                            ->first();
        
        if($familyPensionValue) {
            $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                                ->select('optcl_nominee_calculation_transaction.*')
                                ->where('optcl_nominee_calculation_transaction.application_id', $application->id)
                                ->where('optcl_nominee_calculation_transaction.nominee_master_id', $application->employee_id)
                                ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                                ->where('optcl_nominee_calculation_transaction.calculation_type_id', 1)
                                ->first();
            if($pensionValue) {

                if($familyPensionValue->rule_id == 5 || $familyPensionValue->rule_id == 11 || $familyPensionValue->rule_id == 13) {
                    $fp_basic_pay_amount = $last_basic_pay; //51100
                    $calculate_percentage = 30; // Family pension percentage
                    $fp_pension_admissible = $pensionValue->rounded_calculation_value;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                } elseif($familyPensionValue->rule_id == 8 || $familyPensionValue->rule_id == 9 || $familyPensionValue->rule_id == 10) {
                    $fp_basic_pay_amount = $last_basic_pay; //51100
                    $calculate_percentage = 50; // Family pension percentage
                    $fp_pension_admissible = $pensionValue->rounded_calculation_value;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                }
                // Get Nominee details
                /*$nomineeDetails = DB::table('optcl_employee_nominee_details')
                                    ->where('employee_id', $application->employee_id)
                                    ->where('nominee_preference_id', 1) // Preference = 1
                                    ->first();*/
                // Get rule details
                $ruleDetails = DB::table('optcl_calculation_rule_master')
                                    ->where('id', $familyPensionValue->rule_id)
                                    ->first();

                $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                    ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                    ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                    ->where('nominee_master_id', $application->employee_id)
                                    ->where('nominee_preference_id', 1) // Preference = 1
                                    ->first();
                // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                $family_pensioner_details = $familyPensionValue->calculation_value."_".$familyPensionValue->rounded_calculation_value."_".$application->id."_".$application->employee_id."_".$familyPensionValue->rule_id."_".$familyPensionValue->calculation_type_id;

                $family_pension_data = [
                    'fp_basic_pay_amount'       => $fp_basic_pay_amount,
                    'calculate_percentage'      => $calculate_percentage,
                    'fp_pension_admissible'     => $fp_pension_admissible,
                    'fp_pension_amount'         => $fp_pension_amount,
                    'fp_rounded_to'             => $fp_rounded_to,
                    'nominee_name'              => $nomineeDetails->nominee_name,
                    'nominee_dob'               => $nomineeDetails->date_of_birth,
                    'nominee_relation'          => $nomineeDetails->relation_name,
                    'Last_Full_Pension_Date'    => $Last_Full_Pension_Date,
                    'rule_id'                   => $familyPensionValue->rule_id,
                    'family_pensioner_details'  => $family_pensioner_details,
                    'rule_description'          => $ruleDetails->rule_description,
                ];
            }
        }

        $service_dcr_gratuity = DB::table('optcl_calculation_rule_master as a')
                            ->where('pension_type_id', 1)
                            ->where('calculation_type_id', 4)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->get();

        $service_dcr_gratuity_ids = $service_dcr_gratuity->pluck('id')->toArray();

        $dcr_gratuity_exist = DB::table('optcl_nominee_calculation_transaction')
                ->where('application_id', $id)
                ->where('nominee_master_id', $application->employee_id)
                ->whereIn('rule_id' , $service_dcr_gratuity_ids)
                ->where('is_latest', 1)
                ->orderBy('id', 'desc')->first();

        if(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == 7) {
            $dcr_gratuity_value = PensinorCalculation::get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $service_form_three);
        } elseif(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == 12) {
            $dcr_gratuity_value = PensinorCalculation::get_death_gratuity($service_form, $last_basic_pay, $total_da_amount);
        } else {
            $dcr_gratuity_value = PensinorCalculation::get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $service_form_three);
        }

    	return view('user.hr-wing.hr_executive.fp-application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form', 'service_form_three', 'total_da_amount', 'service_pension_due', 'family_pension_data', 'dcr_gratuity_exist', 'dcr_gratuity_value', 'service_pension_due_exist', 'commutation_value', 'commutation_two_value', 'commutation_three_value', 'organisation_details'));
    }

    public function applications_approval(Request $request) {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            
            $form_field_master = DB::table('optcl_nominee_pension_form_field_master')->where('id', $request->field_id)->first();

            if(!empty($form_field_master)) {

                $pension_application_form = DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->first();

                if(empty($request->nominee_id)) {
                    $application_form_field_status = DB::table('optcl_nominee_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('status', 1)->where('deleted', 0)->first();

                } else {
                    $application_form_field_status = DB::table('optcl_nominee_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('nominee_id', $request->nominee_id)->where('status', 1)->where('deleted', 0)->first();
                }
                // $form_field_status_master = DB::table('optcl_nominee_application_form_field_status_master')->where('status', 1)->where('deleted', 0)->where('id', $request->status_id)->first();

                if(empty($application_form_field_status)) {
                    $form_field_status = [
                        'application_id' => $request->application_id,
                        'form_id' => $form_field_master->form_id,
                        'field_id' => $request->field_id,
                        'status_id' => $request->status_id,
                        'nominee_id' => !empty($request->nominee_id) ? $request->nominee_id : NULL,
                        'remarks' => $request->remarks,
                        'status' => 1,
                        'created_at' => $this->current_date,
                        'created_by' => $user->id
                    ];

                    $form_field_status_id = DB::table('optcl_nominee_application_form_field_status')->insertGetId($form_field_status);

                } else {
                    $form_field_status = [
                        'status_id' => $request->status_id,
                        'remarks' => $request->remarks,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id
                    ];

                    DB::table('optcl_nominee_application_form_field_status')->where('id', $application_form_field_status->id)->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->update($form_field_status);
                }

                DB::commit();
                $message = 'Field has been approved successfully!';
                $status = 'success';
                return response()->json( array('status' => $status,  'message' => $message));
            } else {
                DB::commit();
                $message = 'Invalid field';
                $status = 'error';
                return response()->json( array('status' => $status,  'message' => $message));
            }
        } catch (Exception $e) {
            DB::rollback();
            $message = 'Something went wrong';
            $status = 'error';
            return response()->json( array('status' => $status,  'message' => $message));
        }
    }

    public function applications_submission(Request $request) {
        //dd($request);
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            // dd($application_form, $request->all());
            if(!empty($application_form)) {
                $application_form_field_status = DB::table('optcl_application_form_field_status')->where('application_id', $request->application_id)->where('status', 1)->where('deleted', 0)->get();

                $status = '';

                foreach ($application_form_field_status as $key => $value) {
                    
                    $form_field_status_history = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application_form->employee_id,
                        'employee_code' => $application_form->employee_code,
                        'aadhaar_no' => $application_form->employee_aadhaar_no,
                        'form_id' => $value->form_id,
                        'field_id' => $value->field_id,
                        'status_id' => $value->status_id,
                        'nominee_id' => $value->nominee_id,
                        'remarks' => $value->remarks,
                        'status' => 1,
                        'created_at' => $value->created_at,
                        'created_by' => $user->id,
                        'deleted' => 0,
                    ];

                    $form_field_status_history_id = DB::table('optcl_application_form_field_status_history')->insertGetId($form_field_status_history);
                }

                if($request->application_status == 1) {
                    $to_update_status = 29; 
                    $status = 'approved';
                    $message = "Application approved by HR Executive. Please check the application details.";
                    $message2 = "Application approved by HR Executive. Please check the application details.";
                } else {
                    $to_update_status = 30; 
                    $status = 'returned';
                    $message = "Application returned by HR Executive. Please resubmit the required application details.";
                    $message2 = "Application returned by HR Executive. Please check the application details.";
                }
                // Update Application Status
                DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                ->where('deleted', 0)->update([
                    'application_status_id' => $to_update_status
                ]);
                // Update Application Status History
                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => $to_update_status,
                    'remarks'           => $request->remarks,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);
                // Update is_latest value
                DB::table('optcl_application_form_field_status')
                    ->where('application_id', $application_form->id)
                    ->where('is_latest', 0)
                    ->update(['is_latest' => 1]);

                // Notification Area                
                $application_id = $application_form->id;
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                // Pensioner    
                Util::insert_notification($pension_user_id, $appDetails->id, $message);
                // Dealing Assistant
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);
                // Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);
                // Unit Head
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);
                // Sanction Authority
                $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);
                // HR Wing Dealing Assistant
                $n_user_id = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);

                DB::commit();
                Session::flash('success','Application has been '. $status .' successfully!');
                return redirect()->route('hr_executive_applications');
            } else {
                DB::commit();
                Session::flash('error','Application not found');
                return redirect()->back();
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function applications_store_recovery(Request $request) {
        try {
            DB::beginTransaction();
            
            $employee_recoveries = $request->add_recovery;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($employee_recoveries)) {
                foreach ($employee_recoveries as $key => $value) {
                    
                    $recovery = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'status' => 1,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id,
                        'deleted' => 0
                    ];

                    DB::table('optcl_nominee_add_recovery')->where('id', $value['id_value'])->update($recovery);

                    $recovery['recovery_id'] = $value['id_value'];
                    DB::table('optcl_nominee_add_recovery_history')->insert($recovery);
                }
            }

            $application_status = [
                'application_status_id' => 32
            ];

            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insertGetId([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => 32,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            // Notification Area  
            $message = "Recovery updated by HR Executive. Please check the application details.";          
            $application_id = $application->id;
            $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
            $pension_user_id = $appDetails->user_id;
            /*// Pensioner   
            Util::insert_notification($appDetails->user_id, $appDetails->id, $message); 
            // Dealing Assistant
            $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
            $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Sanction Authority
            $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Sanction Authority
            $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // HR Wing Dealing Assistant
            $n_user_id = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);*/

            DB::commit();
            Session::flash('success','Recovery has been updated successfully!');
            return redirect()->route('fp_hr_executive_application_details', $request->application_id);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function service_pension_form_submission(Request $request) {
        // dd($request->all());
        //print_r($request->all());

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($application_form)) {
                $form_service_period_duly = !empty($request->form_service_period_duly) ? $request->form_service_period_duly : 0;
                if($form_service_period_duly == 0){
                    $form_service_period_duly_from = NULL;
                    $form_service_period_duly_to = NULL;
                }else{
                    $form_service_period_duly_from = !empty($request->form_service_period_duly_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_from))) : NULL;
                    $form_service_period_duly_to = !empty($request->form_service_period_duly_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_to))) : NULL;
                }
                $form_service_period_absence = !empty($request->form_service_period_absence) ? $request->form_service_period_absence : 0;
                if($form_service_period_absence == 1){
                    $form_service_period_absence_from = NULL;
                    $form_service_period_absence_to = NULL;
                }else{
                    $form_service_period_absence_from = !empty($request->form_service_period_absence_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_from))) : NULL;
                    $form_service_period_absence_to = !empty($request->form_service_period_absence_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_to))) : NULL;
                }
                $form_is_checked = !empty($request->form_is_checked) ? $request->form_is_checked : 0;
                if($form_is_checked == 0){
                    $form_reason_no_demand_certificate = !empty($request->form_reason_no_demand_certificate) ? $request->form_reason_no_demand_certificate : NULL;
                    $is_recommended_provisional_pension = !empty($request->is_recommended_provisional_pension) ? $request->is_recommended_provisional_pension : 0;
                }else{
                    $form_reason_no_demand_certificate = NULL;
                    $is_recommended_provisional_pension = 0;                    
                }
                $service_pension_form = [
                    'application_id'                => $request->application_id,
                    'nominee_master_id'                   => $application_form->employee_id,
                    'is_service_period_duly'        => $form_service_period_duly,
                    'service_period_duly_from'      => $form_service_period_duly_from,
                    'service_period_duly_to'        => $form_service_period_duly_to,
                    'is_period_of_absence'          => $form_service_period_absence,
                    'service_period_absence_from'   => $form_service_period_absence_from,
                    'service_period_absence_to'     => $form_service_period_absence_to,
                    'is_departmental_or_judicial'   => !empty($request->form_status_of_departmental_judicial) ? $request->form_status_of_departmental_judicial : 0,
                    'scale_of_pay'                  => !empty($request->form_scale_of_pay) ? $request->form_scale_of_pay : NULL,
                    'last_basic_pay'                => !empty($request->form_last_basic_pay) ? str_replace(',', '', $request->form_last_basic_pay) : NULL,
                    'is_no_demand_certificate'      => $form_is_checked,
                    'reason_of_no_demand_certificate' => $form_reason_no_demand_certificate,
                    'is_recommended_provisional_pension' => $is_recommended_provisional_pension,
                    'gross_years'                   => !empty($request->gross_years) ? $request->gross_years : 0,
                    'gross_months'                  => !empty($request->gross_months) ? $request->gross_months : 0,
                    'gross_days'                    => !empty($request->gross_days) ? $request->gross_days : 0,
                    'non_qualifying_years'          => !empty($request->non_qualifying_years) ? $request->non_qualifying_years : 0,
                    'non_qualifying_months'         => !empty($request->non_qualifying_months) ? $request->non_qualifying_months : 0,
                    'non_qualifying_days'           => !empty($request->non_qualifying_days) ? $request->non_qualifying_days : 0,
                    'net_qualifying_years'          => !empty($request->net_qualifying_years) ? $request->net_qualifying_years : 0,
                    'net_qualifying_months'         => !empty($request->net_qualifying_months) ? $request->net_qualifying_months : 0,
                    'net_qualifying_days'           => !empty($request->net_qualifying_days) ? $request->net_qualifying_days : 0,
                    'non_qualifying_period_from'    => !empty($request->non_qualifying_period_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_from))) : NULL,
                    'non_qualifying_period_to'      => !empty($request->non_qualifying_period_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_to))) : NULL,
                    'modified_at'                   => $this->current_date,
                    'modified_by'                   => $user->id,
                ];
                //dd($service_pension_form);
                $whereCond = [
                    "id" => $request->service_form_id,
                ];
                DB::table('optcl_nominee_pension_service_form')->where($whereCond)->update($service_pension_form);
                // Service form history
                $service_pension_form['service_form_id'] = $request->service_form_id;
                $service_pension_form['status_id'] = 33;
                DB::table('optcl_nominee_pension_service_form_history')->insert($service_pension_form);

                $service_form = $request->service_form;

                if(!empty($service_form)) {
                    foreach ($service_form as $key => $value) {
                        $various_off = [
                            'organisation_name' => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office' => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held' => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from' => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to' => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years' => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months' => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days' => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at' => $this->current_date,
                            'modified_by' => $user->id,
                        ];
                        $whereCond2 = [
                            "id" => $value['form_part_ii_id'],
                        ];
                        DB::table('optcl_nominee_pension_service_offices')->where($whereCond2)->update($various_off);
                        // Service form organisation history
                        $office_history =  [
                            'service_office_id'             => $value['form_part_ii_id'],
                            'service_pension_form_id'       => $request->service_form_id,
                            'application_id'                => $request->application_id,
                            'organisation_name'             => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office'                => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held'                     => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from'           => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to'             => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years'           => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months'          => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days'            => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at'                   => $this->current_date,
                            'modified_by'                   => $user->id,
                        ];
                        DB::table('optcl_nominee_pension_service_offices_history')->insert($office_history);
                    }
                }
                DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->update(['application_status_id' => 33]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => 33,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);

                // Notification Area  
                $message = "Part - II form updated by HR Executive. Please check the application details.";          
                $application_id = $application_form->id;
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                // Pensioner  
                NomineeUtil::insert_notification($appDetails->user_id, $appDetails->id, $message);
                // Dealing Assistant
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Unit Head
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Sanction Authority
                $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // HR Wing Dealing Assistant
                $n_user_id = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);

                DB::commit();
                Session::flash('success','Service Pension Form submitted successfully!');
                return redirect()->route('hr_executive_applications');
            } else {
                DB::commit();
                Session::flash('error','Application not found');
                return redirect()->back();
            }

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function application_resubmission(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            $application_status = [
                'application_status_id' => 41
            ];

            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insertGetId([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => 41,
                'remarks'           => $request->remarks,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            // Notification Area
            $message = "Application resubmitted to HR Wing Sanctioning Authority. Please check the application details.";
            $application_id = $application->id;
            $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
            $pension_user_id = $appDetails->user_id;
            // Pensioner            
            $notificationData = array(
                "user_id"           => $appDetails->user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Dealing Assistant
            $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
            $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData); 
            // Sanction Authority
            $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);

            DB::commit();
            Session::flash('success','Application resubmitted successfully!');
            return redirect()->route('hr_dealing_applications');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function fp_applications_store_recovery(Request $request) {
        try {
            DB::beginTransaction();
            
            $employee_recoveries = $request->add_recovery;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($employee_recoveries)) {
                foreach ($employee_recoveries as $key => $value) {
                    
                    $recovery = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'status' => 1,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id,
                        'deleted' => 0
                    ];

                    DB::table('optcl_nominee_add_recovery')->where('id', $value['id_value'])->update($recovery);

                    $recovery['recovery_id'] = $value['id_value'];
                    DB::table('optcl_nominee_add_recovery_history')->insert($recovery);
                }
            }
            $application_status = [
                'application_status_id' => 21
            ];
            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insertGetId([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => 21,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            // Notification Area
            $message = "Recovery updated by HR Wing Dealing Assistant. Please check the application details.";
            $application_id = $application->id;
            $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
            $pension_user_id = $appDetails->user_id;
            /*// Pensioner  
            Util::insert_notification($appDetails->user_id, $appDetails->id, $message);
            // Dealing Assistant
            $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
            $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Sanction Authority
            $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);*/            

            DB::commit();
            Session::flash('success','Recovery has been updated successfully!');
            return redirect()->route('family_pension_hr_dealing_assistant', $application_id);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function fp_service_pension_form_submission(Request $request) {
        // dd($request->all());
        //print_r($request->all());

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($application_form)) {
                $form_service_period_duly = !empty($request->form_service_period_duly) ? $request->form_service_period_duly : 0;
                if($form_service_period_duly == 0){
                    $form_service_period_duly_from = NULL;
                    $form_service_period_duly_to = NULL;
                }else{
                    $form_service_period_duly_from = !empty($request->form_service_period_duly_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_from))) : NULL;
                    $form_service_period_duly_to = !empty($request->form_service_period_duly_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_to))) : NULL;
                }
                $form_service_period_absence = !empty($request->form_service_period_absence) ? $request->form_service_period_absence : 0;
                if($form_service_period_absence == 1){
                    $form_service_period_absence_from = NULL;
                    $form_service_period_absence_to = NULL;
                }else{
                    $form_service_period_absence_from = !empty($request->form_service_period_absence_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_from))) : NULL;
                    $form_service_period_absence_to = !empty($request->form_service_period_absence_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_to))) : NULL;
                }
                $form_is_checked = !empty($request->form_is_checked) ? $request->form_is_checked : 0;
                if($form_is_checked == 0){
                    $form_reason_no_demand_certificate = !empty($request->form_reason_no_demand_certificate) ? $request->form_reason_no_demand_certificate : NULL;
                    $is_recommended_provisional_pension = !empty($request->is_recommended_provisional_pension) ? $request->is_recommended_provisional_pension : 0;
                }else{
                    $form_reason_no_demand_certificate = NULL;
                    $is_recommended_provisional_pension = 0;                    
                }
                $service_pension_form = [
                    'application_id'                => $request->application_id,
                    'nominee_master_id'                   => $application_form->employee_id,
                    'is_service_period_duly'        => $form_service_period_duly,
                    'service_period_duly_from'      => $form_service_period_duly_from,
                    'service_period_duly_to'        => $form_service_period_duly_to,
                    'is_period_of_absence'          => $form_service_period_absence,
                    'service_period_absence_from'   => $form_service_period_absence_from,
                    'service_period_absence_to'     => $form_service_period_absence_to,
                    'is_departmental_or_judicial'   => !empty($request->form_status_of_departmental_judicial) ? $request->form_status_of_departmental_judicial : 0,
                    'scale_of_pay'                  => !empty($request->form_scale_of_pay) ? $request->form_scale_of_pay : NULL,
                    'last_basic_pay'                => !empty($request->form_last_basic_pay) ? str_replace(',', '', $request->form_last_basic_pay) : NULL,
                    'is_no_demand_certificate'      => $form_is_checked,
                    'reason_of_no_demand_certificate' => $form_reason_no_demand_certificate,
                    'is_recommended_provisional_pension' => $is_recommended_provisional_pension,
                    'gross_years'                   => !empty($request->gross_years) ? $request->gross_years : 0,
                    'gross_months'                  => !empty($request->gross_months) ? $request->gross_months : 0,
                    'gross_days'                    => !empty($request->gross_days) ? $request->gross_days : 0,
                    'non_qualifying_years'          => !empty($request->non_qualifying_years) ? $request->non_qualifying_years : 0,
                    'non_qualifying_months'         => !empty($request->non_qualifying_months) ? $request->non_qualifying_months : 0,
                    'non_qualifying_days'           => !empty($request->non_qualifying_days) ? $request->non_qualifying_days : 0,
                    'net_qualifying_years'          => !empty($request->net_qualifying_years) ? $request->net_qualifying_years : 0,
                    'net_qualifying_months'         => !empty($request->net_qualifying_months) ? $request->net_qualifying_months : 0,
                    'net_qualifying_days'           => !empty($request->net_qualifying_days) ? $request->net_qualifying_days : 0,
                    'non_qualifying_period_from'    => !empty($request->non_qualifying_period_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_from))) : NULL,
                    'non_qualifying_period_to'      => !empty($request->non_qualifying_period_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_to))) : NULL,
                    'modified_at'                   => $this->current_date,
                    'modified_by'                   => $user->id,
                ];
                //dd($service_pension_form);
                $whereCond = [
                    "id" => $request->service_form_id,
                ];
                DB::table('optcl_nominee_pension_service_form')->where($whereCond)->update($service_pension_form);
                // Service form history
                $service_pension_form['service_form_id'] = $request->service_form_id;
                $service_pension_form['status_id'] = 22;
                DB::table('optcl_nominee_pension_service_form_history')->insert($service_pension_form);

                $service_form = $request->service_form;

                if(!empty($service_form)) {
                    foreach ($service_form as $key => $value) {
                        $various_off = [
                            'organisation_name' => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office' => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held' => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from' => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to' => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years' => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months' => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days' => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at' => $this->current_date,
                            'modified_by' => $user->id,
                        ];
                        $whereCond2 = [
                            "id" => $value['form_part_ii_id'],
                        ];
                        DB::table('optcl_nominee_pension_service_offices')->where($whereCond2)->update($various_off);
                        // Service form organisation history
                        $office_history =  [
                            'service_office_id'             => $value['form_part_ii_id'],
                            'service_pension_form_id'       => $request->service_form_id,
                            'application_id'                => $request->application_id,
                            'organisation_name'             => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office'                => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held'                     => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from'           => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to'             => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years'           => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months'          => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days'            => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at'                   => $this->current_date,
                            'modified_by'                   => $user->id,
                        ];
                        DB::table('optcl_nominee_pension_service_offices_history')->insert($office_history);
                    }
                }
                $to_update_status = 22;
                DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->update(['application_status_id' => $to_update_status]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => $to_update_status,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);

                // Notification Area
                $message = "Part - II form updated by HR Wing Dealing Assistant. Please check the application details.";
                $application_id = $application_form->id;
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                // Pensioner 
                NomineeUtil::insert_notification($appDetails->user_id, $appDetails->id, $message);  
                // Dealing Assistant
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Unit Head
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                // Sanction Authority
                $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);

                DB::commit();
                Session::flash('success','Service Pension Form submitted successfully!');
                return redirect()->route('family_pension_hr_dealing_assistant', $application_id);
            } else {
                DB::commit();
                Session::flash('error','Application not found');
                return redirect()->back();
            }

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function service_pension_form_three_submission(Request $request) {

        try {
            DB::beginTransaction();
            // dd($request->all());

            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($application_form)) {

                $service_pension_form = [
                    'application_id' => $request->application_id,
                    'nominee_master_id' => $application_form->employee_id,

                    'interruption_service_from' => !empty($request->interruption_service_from) ? date('Y-m-d', strtotime($request->interruption_service_from)) : NULL,
                    'interruption_service_to' => !empty($request->interruption_service_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->interruption_service_to))) : NULL,
                    'interruption_service_years' => !empty($request->interruption_service_year_value) ? $request->interruption_service_year_value : 0,
                    'interruption_service_months' => !empty($request->interruption_service_month_value) ? $request->interruption_service_month_value : 0,
                    'interruption_service_days' => !empty($request->interruption_service_days_value) ? $request->interruption_service_days_value : 0,


                    'extraordinary_leave_from' => !empty($request->extraordinary_leave_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->extraordinary_leave_from))) : NULL,
                    'extraordinary_leave_to' => !empty($request->extraordinary_leave_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->extraordinary_leave_to))) : NULL,
                    'extraordinary_leave_years' => !empty($request->extraordinary_leave_year_value) ? $request->extraordinary_leave_year_value : 0,
                    'extraordinary_leave_months' => !empty($request->extraordinary_leave_month_value) ? $request->extraordinary_leave_month_value : 0,
                    'extraordinary_leave_days' => !empty($request->extraordinary_leave_days_value) ? $request->extraordinary_leave_days_value : 0,

                    'period_of_suspension_from' => !empty($request->period_of_suspension_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->period_of_suspension_from))) : NULL,
                    'period_of_suspension_to' => !empty($request->period_of_suspension_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->period_of_suspension_to))) : NULL,
                    'period_of_suspension_years' => !empty($request->period_of_suspension_year_value) ? $request->period_of_suspension_year_value : 0,
                    'period_of_suspension_months' => !empty($request->period_of_suspension_month_value) ? $request->period_of_suspension_month_value : 0,
                    'period_of_suspension_days' => !empty($request->period_of_suspension_days_value) ? $request->period_of_suspension_days_value : 0,

                    'work_charged_service_from' => !empty($request->work_charged_service_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->work_charged_service_from))) : NULL,
                    'work_charged_service_to' => !empty($request->work_charged_service_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->work_charged_service_to))) : NULL,
                    'work_charged_service_years' => !empty($request->work_charged_service_year_value) ? $request->work_charged_service_year_value : 0,
                    'work_charged_service_months' => !empty($request->work_charged_service_month_value) ? $request->work_charged_service_month_value : 0,
                    'work_charged_service_days' => !empty($request->work_charged_service_days_value) ? $request->work_charged_service_days_value : 0,

                    'boy_service_from' => !empty($request->boy_service_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->boy_service_from))) : NULL,
                    'boy_service_to' => !empty($request->boy_service_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->boy_service_to))) : NULL,
                    'boy_service_years' => !empty($request->boy_service_year_value) ? $request->boy_service_year_value : 0,
                    'boy_service_months' => !empty($request->boy_service_month_value) ? $request->boy_service_month_value : 0,
                    'boy_service_days' => !empty($request->boy_service_days_value) ? $request->boy_service_days_value : 0,


                    'any_other_service_from' => !empty($request->any_other_service_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->any_other_service_from))) : NULL,
                    'any_other_service_to' => !empty($request->any_other_service_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->any_other_service_to))) : NULL,
                    'any_other_service_years' => !empty($request->any_other_service_year_value) ? $request->any_other_service_year_value : 0,
                    'any_other_service_months' => !empty($request->any_other_service_month_value) ? $request->any_other_service_month_value : 0,
                    'any_other_service_days' => !empty($request->any_other_service_days_value) ? $request->any_other_service_days_value : 0,

                    'total_non_qualifying_years' => !empty($request->total_non_qualifying_years) ? $request->total_non_qualifying_years : 0,
                    'total_non_qualifying_months' => !empty($request->total_non_qualifying_months) ? $request->total_non_qualifying_months : 0,
                    'total_non_qualifying_days' => !empty($request->total_non_qualifying_days) ? $request->total_non_qualifying_days : 0,

                    'total_qualifying_years' => !empty($request->qualifying_service_period_year_value) ? $request->qualifying_service_period_year_value : 0,
                    'total_qualifying_months' => !empty($request->qualifying_service_period_month_value) ? $request->qualifying_service_period_month_value : 0,
                    'total_qualifying_days' => !empty($request->qualifying_service_period_days_value) ? $request->qualifying_service_period_days_value : 0,
                    
                    'addition_of_qualifying_service_from' => !empty($request->addition_of_qualifying_service_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->addition_of_qualifying_service_from))) : NULL,
                    'addition_of_qualifying_service_to' => !empty($request->addition_of_qualifying_service_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->addition_of_qualifying_service_to))) : NULL,
                    'total_addition_qualifying_years' => !empty($request->addition_of_qualifying_service_year_value) ? $request->addition_of_qualifying_service_year_value : 0,
                    'total_addition_qualifying_months' => !empty($request->addition_of_qualifying_service_month_value) ? $request->addition_of_qualifying_service_month_value : 0,
                    'total_addition_qualifying_days' => !empty($request->addition_of_qualifying_service_days_value) ? $request->addition_of_qualifying_service_days_value : 0,

                    'total_net_qualifying_years' => !empty($request->net_qualifying_service_year_value) ? $request->net_qualifying_service_year_value : 0,
                    'total_net_qualifying_months' => !empty($request->net_qualifying_service_month_value) ? $request->net_qualifying_service_month_value : 0,
                    'total_net_qualifying_days' => !empty($request->net_qualifying_service_days_value) ? $request->net_qualifying_service_days_value : 0,
                    
                    'total_qualifying_half_years' => !empty($request->total_qualifying_completed_half_years_value) ? $request->total_qualifying_completed_half_years_value : 0,
                    'emolument_last_basic_pay' => !empty($request->emolument_last_basic_pay_value) ? $request->emolument_last_basic_pay_value : 0,
                    'service_pension' => !empty($request->service_pension_value) ? $request->service_pension_value : 0,

                    'date_of_commencement_pension' => !empty($request->date_of_commencement_pension) ? date('Y-m-d', strtotime(str_replace("/","-", $request->date_of_commencement_pension))) : NULL,
                    'date_of_acknowlegement_commutation' => !empty($request->date_of_acknowlegement_commutation) ? date('Y-m-d', strtotime(str_replace("/","-", $request->date_of_acknowlegement_commutation))) : NULL,
                    'age_on_next_birthday' => !empty($request->age_on_next_birthday_value) ? $request->age_on_next_birthday_value : 0,
                    'commuted_amount_of_pension' => !empty($request->commuted_amount_pension_value) ? $request->commuted_amount_pension_value : 0,
                    'commuted_value_of_pension' => !empty($request->commuted_value_of_pension_value) ? $request->commuted_value_of_pension_value : 0,
                    'residuary_pension_commutation' => !empty($request->residuary_pension_commutation_value) ? $request->residuary_pension_commutation_value : 0,
                    'amount_of_dcrg' => !empty($request->death_retirement_dcr_gratuity_value) ? $request->death_retirement_dcr_gratuity_value : 0,
                    'total_da_amount' => !empty($request->total_da_amount_value) ? $request->total_da_amount_value : 0,
                    'enhanced_family_pension' => !empty($request->enhanced_family_pension) ? $request->enhanced_family_pension : 0,
                    'normal_family_pension' => !empty($request->normal_family_pension) ? $request->normal_family_pension : 0,
                    

                    'life_time_arrear_from' => !empty($request->life_time_arrear_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->life_time_arrear_from))) : NULL,
                    'life_time_arrear_to' => !empty($request->life_time_arrear_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->life_time_arrear_to))) : NULL,
                    'life_time_arrear_amount' => !empty($request->life_time_arrear_pension_amount) ? $request->life_time_arrear_pension_amount : 0,
                    'status' => 1,
                    'modified_at' => $this->current_date,
                    'modified_by' => $user->id,
                    'deleted' => 0
                ];

                DB::table('optcl_nominee_pension_service_form_three')->where(['application_id' => $request->application_id])->update($service_pension_form);
                unset($service_pension_form['modified_at']);
                unset($service_pension_form['modified_at']);
                $service_pension_form_id = DB::table('optcl_nominee_pension_service_form')->where('application_id', $request->application_id)->value('id');
                $service_pension_form['form_three_id'] = $service_pension_form_id;
                $service_pension_form['created_at'] = $this->current_date;
                $service_pension_form['created_by'] = $user->id;
                DB::table('optcl_nominee_pension_service_form_three_history')->insert($service_pension_form);

                $employee_recoveries = $request->add_recovery;

                foreach ($employee_recoveries as $key => $value) {
                    $where = ['id' => $value['recovery_id']];
                    $recovery = [
                        'application_id' => $request->application_id,
                        'nominee_master_id' => $application_form->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'recovery_remarks' => $value['remarks'],
                        'status' => 1,
                        'created_at' => $this->current_date,
                        'created_by' => $user->id,
                        'deleted' => 0
                    ];
                    $recovery_id = DB::table('optcl_nominee_pension_service_form_three_recovery')->where($where)->update($recovery);

                    $recovery_history = [
                        'recovery_id' => $value['recovery_id'],
                        'application_id' => $request->application_id,
                        'nominee_master_id' => $application_form->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'recovery_remarks' => $value['remarks'],
                        'status' => 1,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id,
                        'deleted' => 0
                    ];

                    DB::table('optcl_nominee_pension_service_form_three_recovery_history')->insert($recovery_history);
                }

                DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->update(['application_status_id' => 34]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => 34,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);

                // Notification Area
                $message = "Part - III form updated by HR Wing Dealing Assistant. Please check the application details.";
                $application_id = $application_form->id;
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                /*// Pensioner 
                Util::insert_notification($appDetails->user_id, $appDetails->id, $message); 
                // Dealing Assistant
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Unit Head
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Sanction Authority
                $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);*/

                DB::commit();
                Session::flash('success', 'PART - III details successfully updated by HR Wing Dealing Assistant');
                return redirect()->route('fp_hr_executive_application_details', $application_id);
            } else {
                DB::commit();
                Session::flash('error','Application not found');
                return redirect()->back();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function sanction_order_generate($id) {
        $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

        $proposal = PensinorCalculation::get_employee_details($application->employee_id);

        $service_form_three = DB::table('optcl_employee_pension_service_form_three')->where('application_id', $id)->first();

        return view('user.hr-wing.dealing-assistant.sanction-order-generate', compact('application', 'proposal', 'service_form_three'));
    }

    public function sanction_order_submit(Request $request) {

        try {
            DB::beginTransaction();
            $application_id = $request->application_id;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $application_id)->first();

            $proposal = PensinorCalculation::get_employee_details($application->employee_id);

            $service_form_three = DB::table('optcl_employee_pension_service_form_three')->where('application_id', $application_id)->first();

            //Sanction Order Generate PDF Code Start
            $subject = '';
            $reference = '';
            $name = '';
            if($application->pension_type_id == 1) {
                $subject = 'Sanction of Pension & other pensionary benefits in favour of Sri/Smt/Miss ' . $proposal->employee_name . ', ' .  $proposal->designation_name . ', ' . $proposal->office_last_served . ' retired on ' . \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') . ', ' . $proposal->account_type . ' A/C No - ' . $proposal->pf_account_no;

                $reference = 'With reference to the subject cited above, I am to intimate you that the Pensionary benefits is sanctioned in favour of Sri/Smt/Miss '.$proposal->employee_name.', ' . $proposal->designation_name . ', ' . $proposal->office_last_served . ', retired on '.  \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') . '.The Pensionary benefits shall be paid in his favour w.e.f. '.\Carbon\Carbon::parse($service_form_three->date_of_commencement_pension)->format('d/m/Y').' as per calculation given below.';

                $name = 'Sri/Smt/Miss ' . $proposal->employee_name . ', ' .  $proposal->designation_name;
            } else {
                $subject = 'Sanction of Family Pension & other pensionary benefits in favour of Sri/Smt/Miss ' . $proposal->employee_name . ', ' .  $proposal->designation_name . ', ' . $proposal->office_last_served . ' retired on ' . \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') . ', ' . $proposal->account_type . ' A/C No - ' . $proposal->pf_account_no;

                $reference = 'With reference to the subject cited above, I am to intimate you that the Pensionary benefits is sanctioned in favour of Sri/Smt/Miss '.$proposal->employee_name.', ' . $proposal->designation_name . ', ' . $proposal->office_last_served . ', retired on '.  \Carbon\Carbon::parse($proposal->date_of_retirement)->format('d/m/Y') . '.The Family Pensionary benefits shall be paid in his favour w.e.f. '.\Carbon\Carbon::parse($service_form_three->date_of_commencement_pension)->format('d/m/Y').' as per calculation given below.';

                $name = 'Sri/Smt/Miss ' . $proposal->employee_name . ', ' .  $proposal->designation_name;
            }
            
            $pdf = new FPDF;

            $pdf->SetMargins(10, 10, 10);

            $pdf->SetAutoPageBreak(true, 10);
            // AliasNbPages is optional if you want the ability to display page numbers on your PDF pages.
            $pdf->AliasNbPages();
            // set author of this pdf invoice
            $pdf->SetAuthor('OPTCL');
            // set pdf title
            $pdf->SetTitle('Sanction Order');

            $pdf->AddPage();

            $pdf->header_sanction_order_page();

            $pdf->SetFont('Helvetica', '', 12);
            // set textcolour
            // $pdf->SetTextColor(50, 60, 100);
            // display zoom mode
            $pdf->SetDisplayMode('default');
            // create a cell to fill data
            $pdf->Ln(5);
            $pdf->SetFont('Helvetica', 'UB', 10);
            $pdf->Cell(0, 40, "SANCTION ORDER", 0, 0, 'C');
            $pdf->SetFont('Helvetica', '', 10);
            // $pdf->SetFillColor(255, 255, 255);
            // $pdf->SetTextColor(0);
            $pdf->SetDrawColor(167, 167, 167);
            $pdf->Line(10, 60, 200, 60);
            $pdf->Ln(5);
            $pdf->SetFont('Helvetica', '', 10);
            // $pdf->SetTextColor(50, 60, 100);

            $pdf->Ln(5);
            // $pdf->SetFont('Helvetica', 'B', 10);
            //$pdf->Cell(0, 45, 'Application No. - ' . $application->application_no);
            // $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(10, 75);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Application No. - ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(40, 75);
            $pdf->MultiCell(0, 5, $application->application_no, 0, 'L', false);

            $pdf->SetXY(168, 75);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Date -  ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(180, 75);
            $pdf->MultiCell(0, 5, date('d/m/Y'), 0, 'L', false);

            $pdf->SetXY(10, 90);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'To, ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(10, 100);
            $pdf->MultiCell(0, 5, 'The ' . $request->sanction_order_to_name . ',', 0, 'L', false);
            
            $pdf->SetXY(10, 105);
            $pdf->MultiCell(0, 5, 'OPTCL Hqrs. Office, Bhubaneswar', 0, 'L', false);

            // $pdf->Cell(0, 45, 'Date :- ' . date('d/m/Y'), 0, 0, 'R');

            /*$pdf->Ln(5);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 60, 'To, ');
            $pdf->SetFont('Helvetica', '', 10);

            $pdf->Ln(5);
            $pdf->Cell(0, 70, 'The ' . $request->sanction_order_to_name . ',');
            $pdf->Ln(5);
            $pdf->Cell(0, 75, 'OPTCL Hqrs. Office, Bhubaneswar');*/
            

            $pdf->Ln(5);
            $pdf->SetXY(10, 113);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Sub - ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(20, 113);
            $pdf->MultiCell(0, 5, $subject, 0, 'L', false);

            $pdf->SetXY(10, 125);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Sir\Madam,', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(10, 135);
            $pdf->MultiCell(0, 5, $reference, 0, 'L', false);
            $pdf->SetXY(10, 160);

            $width_cell = array(20,110,60);

            $pdf->SetFillColor(235,236,236); 

            // Header starts /// 
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell($width_cell[0], 10, 'SL No.', 1, 0, 'C', true);
            $pdf->Cell($width_cell[1], 10, 'Particulars', 1, 0, 'C', true);
            $pdf->Cell($width_cell[2], 10, 'Amount', 1, 1, 'C', true); 
            $pdf->SetFont('Helvetica', '', 10);

            $pdf->Cell($width_cell[0], 10, '(1)', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'Pension', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->service_pension . '/- + TI' , 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '(2)', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'D.C.R. Gratuity', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->amount_of_dcrg . '/- + TI' , 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '(3)', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'Commutation Pension Value', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->commuted_value_of_pension . '/- + TI' , 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '(4)', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'Residuary Pension', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->residuary_pension_commutation . '/- + TI' , 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '(5)', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'Family Pension', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, '', 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'i) Up to 65 Years', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->enhanced_family_pension . '/- + TI', 1, 1, 'L', false);

            $pdf->Cell($width_cell[0], 10, '', 1, 0, 'C', false);
            $pdf->Cell($width_cell[1], 10, 'ii) After to 65 Years', 1, 0, 'L', false);
            $pdf->Cell($width_cell[2], 10, 'Rs ' . $service_form_three->normal_family_pension . '/- + TI', 1, 1, 'L', false);


            $pdf->SetXY(10, 245);
            $pdf->MultiCell(0, 5, 'The following documents are enclosed herewith for further necessary action', 0,'L', false);

            $pdf->SetXY(15, 255);
            $pdf->MultiCell(0, 5, '1) New Pension forms (in duplicate) Service book in original in 2 Vols.', 0,'L', false);

            $pdf->SetXY(15, 260);
            $pdf->MultiCell(0, 5, '2) No Dues Certificate', 0,'L', false);

            $pdf->SetXY(15, 265);
            $pdf->MultiCell(0, 5, '3) Last Pay Certificate', 0,'L', false);

            
            $pdf->SetXY(15, 270);
            $pdf->MultiCell(0, 5, '4) Identification documents '. $name .' (in duplicate)', 0,'L', false);

            $pdf->SetXY(20, 275);
            $pdf->MultiCell(0, 5, 'i) Single passport size photograph (3 copies)', 0,'L', false);

            $pdf->SetXY(20, 280);
            $pdf->MultiCell(0, 5, 'ii) Joint passport size photograph with spouse (3 copies)', 0,'L', false);

            $pdf->AddPage();

            $pdf->SetXY(20, 10);
            $pdf->MultiCell(0, 5, 'iii) Descriptive Roll Slips', 0,'L', false);
            
            $pdf->SetXY(20, 15);
            $pdf->MultiCell(0, 5, 'iv) Specimen Signature', 0,'L', false);

            $pdf->SetXY(20, 20);
            $pdf->MultiCell(0, 5, 'v) Left hand thumb and finger impression slips', 0,'L', false);

            $pdf->SetXY(15, 25);
            $pdf->MultiCell(0, 5, '5) History Sheet', 0,'L', false);

            $pdf->SetXY(15, 30);
            $pdf->MultiCell(0, 5, '6) Calculation Sheet', 0,'L', false);

            $pdf->SetXY(15, 35);
            $pdf->MultiCell(0, 5, '7) Photo copy of the 1st page of Bank Pass Book', 0,'L', false);

            $pdf->SetXY(15, 40);
            $pdf->MultiCell(0, 5, '8) Photo copy of Aadhaar & PAN Card', 0,'L', false);

            if(!empty($proposal->date_of_joining) && $proposal->date_of_joining <= '1991-03-31' && $proposal->pf_account_type_id == 1) {
                $pdf->SetXY(15, 45);
                $pdf->MultiCell(0, 5, '9) Indemnity Bond', 0,'L', false);
                
                $pdf->SetXY(15, 55);
                $pdf->MultiCell(0, 5, 'Action taken in the matter may please be intimated to this office for reference and record.', 0,'L', false);

                $pdf->SetXY(10, 65);
                $pdf->MultiCell(0, 5, 'Encl: As above ', 0,'L', false);

                $pdf->SetXY(170, 65);
                $pdf->MultiCell(0, 5, 'Yours Faithfully', 0,'L', false);

                $pdf->SetXY(170, 70);
                $pdf->MultiCell(0, 5, $request->sanction_faithfully, 0,'L', false);
            } else {

                /*$pdf->SetXY(15, 60);
                $pdf->MultiCell(0, 5, '9) Indemnity Bond', 0,'L', false);*/
                
                $pdf->SetXY(15, 45);
                $pdf->MultiCell(0, 5, 'Action taken in the matter may please be intimated to this office for reference and record.', 0,'L', false);

                $pdf->SetXY(10, 50);
                $pdf->MultiCell(0, 5, 'Encl: As above ', 0,'L', false);

                $pdf->SetXY(170, 55);
                $pdf->MultiCell(0, 5, 'Yours Faithfully', 0,'L', false);

                $pdf->SetXY(170, 60);
                $pdf->MultiCell(0, 5, $request->sanction_faithfully, 0,'L', false);
            }

            $sanction_order = DB::table('optcl_employee_pension_sanction_order')->where('application_id', $application_id)->where('employee_id', $application->employee_id)->first();


            if(empty($sanction_order)) {
                $sanction_order_input = [
                    'application_id' => $application_id,
                    'employee_id' => $application->employee_id,
                    'sanction_order_to_name' => $request->sanction_order_to_name,
                    'sanction_faithfully' => $request->sanction_faithfully,
                    'created_at' => $this->current_date,
                    'created_by' => $user->id,
                    'status' => 1,
                    'deleted' => 0
                ];

                DB::table('optcl_employee_pension_sanction_order')->insert($sanction_order_input);
            } else {
                $sanction_order_input = [
                    'sanction_order_to_name' => $request->sanction_order_to_name,
                    'sanction_faithfully' => $request->sanction_faithfully,
                    'modified_at' => $this->current_date,
                    'modified_by' => $user->id
                ];

                DB::table('optcl_employee_pension_sanction_order')->where('application_id', $application_id)->where('employee_id', $application->employee_id)->update($sanction_order_input);
            }

            // Generate Sanction Number
            $sanction_number = "SNOD".date('Y').sprintf('%05d',$application_id);

            DB::table('optcl_pension_application_form')->where('id', $application_id)->update([
                'application_status_id' => 25,
                'sanction_number'   => $sanction_number,
            ]);

            DB::table('optcl_application_status_history')->insert([
                'user_id'           => $user->id,
                'application_id'    => $application_id,
                'status_id'         => 25,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            $sanctionOrderDir = "public/uploads/sanction_order";

            if (!is_dir($sanctionOrderDir) && !is_writeable($sanctionOrderDir)) {
                mkdir($sanctionOrderDir);
            }

            // file path to be saved in server folder for later viewing of invoice
            $file = $sanctionOrderDir . "/sanction_order_". $application->application_no .".pdf";
            // first send the output to server folder for permanent saving the document
            $pdf->Output($file, "F");
            // then open it in browser if pdf plugin has already been installed
            // $pdf->Output("sanction_order.pdf", "D");

            // Notification Area
            $message = "Sanction order generated by HR Wing Sanctioning Authority. Please check the application details.";
            $application_id = $application->id;
            $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
            $pension_user_id = $appDetails->user_id;
            // Pensioner            
            $notificationData = array(
                "user_id"           => $appDetails->user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Dealing Assistant
            $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
            $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);


            DB::commit();
            return redirect()->route('hr_sanction_authority_application_details', array($application_id));
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function gratuity_sanction_order_generate($id) {
        $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

        $proposal = PensinorCalculation::get_employee_details($application->employee_id);

        $service_form_three = DB::table('optcl_employee_pension_service_form_three')->where('application_id', $id)->first();

        $recoveries = DB::table('optcl_employee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->get();

        return view('user.hr-wing.dealing-assistant.gratuity-sanction-order-generate', compact('application', 'proposal', 'service_form_three', 'recoveries'));
    }

    public function gratuity_sanction_order_submit(Request $request) {
        try {
            DB::beginTransaction();
            $application_id = $request->application_id;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $application_id)->first();

            $proposal = PensinorCalculation::get_employee_details($application->employee_id);

            $service_form_three = DB::table('optcl_employee_pension_service_form_three')->where('application_id', $application_id)->first();

            $recoveries = DB::table('optcl_employee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $application_id)->get();

            //Gratuity Sanction Order Generate PDF Code Start

            $amount_in_words = Util::getAmountInWords($service_form_three->amount_of_dcrg);

            $bank_branch_id = $proposal->bank_branch_id;
            $bankDetaills = DB::table('optcl_bank_branch_master as bbm')
                            ->join('optcl_bank_master as b','b.id','=','bbm.bank_id')
                            ->select('b.bank_name','bbm.branch_name','bbm.ifsc_code','bbm.micr_code')
                            ->where('bbm.status', 1)
                            ->where('bbm.deleted', 0)
                            ->where('bbm.id', $bank_branch_id)
                            ->where('b.status', 1)
                            ->where('b.deleted', 0)
                            ->first();
            if($bankDetaills){
                $bankName = $bankDetaills->bank_name;
                $branchName = $bankDetaills->branch_name;
                $ifscCode = $bankDetaills->ifsc_code;
                $micrCode = $bankDetaills->micr_code;
            }else{
                $bankName = 'NA';
                $branchName = 'NA';
                $ifscCode = 'NA';
                $micrCode = 'NA';
            }

            $point_one = '1. Sanction is hereby accorded for payment of Gratuity amounting to a sum of Rs ' . $service_form_three->amount_of_dcrg . '/- Rupees ('. trim($amount_in_words) . ')' . ' ONLY in favour of Sri/Smt/Miss ' . $proposal->employee_name . ' retired ' . $proposal->designation_name . ', of ' . $proposal->office_last_served . ' less recoveries detailed in para - 2 below.';

            $point_two = '2. The following recoveries should be affected from the payment of gratuity authorised above.';

            $net_gratuity_payable = "The Net Gratuity Payable Rs " . $service_form_three->amount_of_dcrg . '/- Rupees (' . trim($amount_in_words) . ') ONLY.';

            $point_three = '3. The net amount shall be credited to his pension A/C No. ' . $proposal->savings_bank_account_no . ' maintained at ' . $bankName . ', ' . $branchName . ', IFSC Code: ' . $ifscCode . ', MICR Code: ' . $micrCode . '.';
            
            $pdf = new FPDF;

            $pdf->SetMargins(10, 10, 10);

            $pdf->SetAutoPageBreak(true, 10);
            // AliasNbPages is optional if you want the ability to display page numbers on your PDF pages.
            $pdf->AliasNbPages();
            // set author of this pdf invoice
            $pdf->SetAuthor('OPTCL');
            // set pdf title
            $pdf->SetTitle('Sanction Order');

            $pdf->AddPage();

            $pdf->header_gratuity_sanction_order_page();

            $pdf->SetFont('Helvetica', '', 12);
            // set textcolour
            // $pdf->SetTextColor(50, 60, 100);
            // display zoom mode
            $pdf->SetDisplayMode('default');
            // create a cell to fill data
            $pdf->Ln(5);
            $pdf->SetFont('Helvetica', 'UB', 10);
            $pdf->Cell(0, 30, "GRATUITY SANCTION ORDER", 0, 0, 'C');
            $pdf->SetFont('Helvetica', '', 10);
            // $pdf->SetFillColor(255, 255, 255);
            // $pdf->SetTextColor(0);
            $pdf->SetDrawColor(167, 167, 167);
            $pdf->Line(10, 45, 200, 45);
            $pdf->Ln(5);
            $pdf->SetFont('Helvetica', '', 10);
            // $pdf->SetTextColor(50, 60, 100);

            $pdf->Ln(5);
            // $pdf->SetFont('Helvetica', 'B', 10);
            //$pdf->Cell(0, 45, 'Application No. - ' . $application->application_no);
            // $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(10, 55);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Application No. - ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(40, 55);
            $pdf->MultiCell(0, 5, $application->application_no, 0, 'L', false);

            $pdf->SetXY(168, 55);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, 'Date -  ', 0, 'L', false);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(180, 55);
            $pdf->MultiCell(0, 5, date('d/m/Y'), 0, 'L', false);

            $pdf->SetXY(10, 65);
            $pdf->MultiCell(0, 5, $point_one, 0, 'L', false);

            $pdf->SetXY(10, 85);
            $pdf->MultiCell(0, 5, $point_two, 0, 'L', false);

            $pdf->SetXY(10, 100);
            $width_cell = array(20,110,60);

            $pdf->SetFillColor(235,236,236); 

            // Header starts /// 
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell($width_cell[0], 10, 'SL No.', 1, 0, 'C', true);
            $pdf->Cell($width_cell[1], 10, 'Particulars of Recoveries', 1, 0, 'C', true);
            $pdf->Cell($width_cell[2], 10, 'Amount', 1, 1, 'C', true); 
            $pdf->SetFont('Helvetica', '', 10);

            foreach ($recoveries as $key => $recovery) {
                $pdf->Cell($width_cell[0], 10, $key + 1, 1, 0, 'C', false);
                $pdf->Cell($width_cell[1], 10, $recovery->recovery_label, 1, 0, 'L', false);
                $pdf->Cell($width_cell[2], 10, $recovery->recovery_value . '/-' , 1, 1, 'L', false);
            }

            $pdf->SetXY(10, 145);
            $pdf->MultiCell(0, 5, $net_gratuity_payable, 0, 'L', false);

            $pdf->SetXY(10, 160);
            $pdf->MultiCell(0, 5, $point_three, 0, 'L', false);

            $pdf->SetXY(160, 175);
            $pdf->MultiCell(0, 5, 'Yours Faithfully', 0,'L', false);

            $pdf->SetXY(160, 185);
            $pdf->MultiCell(0, 5, 'General Manager (Finance), Funds OPTCL, Bhubaneswar', 0,'L', false);

            DB::table('optcl_pension_application_form')->where('id', $application_id)->update([
                'application_status_id' => 26
            ]);

            DB::table('optcl_application_status_history')->insert([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => 26,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            $sanctionOrderDir = "public/uploads/gratuity_sanction_order";

            if (!is_dir($sanctionOrderDir) && !is_writeable($sanctionOrderDir)) {
                mkdir($sanctionOrderDir);
            }

            // file path to be saved in server folder for later viewing of invoice
            $file = $sanctionOrderDir . "/gratuity_sanction_order_". $application->application_no .".pdf";
            // first send the output to server folder for permanent saving the document
            $pdf->Output($file, "F");
            // then open it in browser if pdf plugin has already been installed
            // $pdf->Output("sanction_order.pdf", "D");

            // Notification Area
            $message = "Gratuity Sanction order generated by HR Wing Sanctioning Authority. Please check the application details.";
            $application_id = $application->id;
            $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
            $pension_user_id = $appDetails->user_id;
            // Pensioner            
            $notificationData = array(
                "user_id"           => $appDetails->user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Dealing Assistant
            $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
            $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            $notificationData = array(
                "user_id"           => $n_user_id,
                "application_id"    => $appDetails->id,
                "status_message"    => $message,
                "created_at"        => $this->current_date,
            );
            DB::table('optcl_user_notification')->insert($notificationData);

            DB::commit();
            return redirect()->route('hr_sanction_authority_application_details', array($application_id));
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }
}
