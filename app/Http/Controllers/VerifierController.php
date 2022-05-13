<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\PensinorCalculation;
use App\Libraries\fpdf\FPDF;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class VerifierController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function applications(Request $request) {
    	$user = Auth::user();

        $applications = DB::table('optcl_pension_application_form as a')
                    ->select('a.id', 'a.pension_type_id', 'pt.pension_type', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name', 'a.recovery_attachment')
                    ->leftJoin('optcl_employee_master as b', 'a.employee_id', '=', 'b.id')
                    ->leftJoin('optcl_nominee_master as n', 'a.employee_id', '=','n.id')
                    ->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
                    ->join('optcl_pension_type_master as pt','pt.id','=','a.pension_type_id')
                    ->join('optcl_application_user_assignments as d', 'd.application_id', '=', 'a.id')
                    ->where('b.optcl_unit_id', $user->optcl_unit_id)
                    ->orWhere('n.optcl_unit_id', $user->optcl_unit_id)
                    // ->where('a.application_status_id', 14)
                    ->where('a.status', 1)
                    ->where('a.deleted', 0)
                    ->orderBy('id', 'desc');

        if(!empty($request->application_no)) {
            $applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where('b.employee_code', 'like', '%' . $request->employee_code . '%');
        }

        if(!empty($request->employee_aadhaar_no)) {
            $applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
        }

        $applications = $applications->paginate(10);

    	return view('user.pension-section.verifier.application-list', compact('applications', 'request'));
    }

    public function application_details($id) {
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id])
            ->update(['view_status' => 1]);
            
    	$application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name', 'a.recovery_attachment')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

        $proposal = DB::table('optcl_employee_master as em')
                            ->join('optcl_employee_designation_master as ud','ud.id','=','em.designation_id')
                            ->join('optcl_employee_gender_master as g', 'g.id','=','em.gender_id')
                            ->join('optcl_marital_status_master as ms','ms.id','=','em.marital_status_id')
                            ->join('optcl_religion_master as r','r.id','=','em.religion_id')
                            ->join('optcl_pf_account_type_master as a','a.id','=','em.pf_account_type_id')
                            ->join('optcl_unit_master as o','o.id','=','em.optcl_unit_id')
                            ->join('optcl_employee_personal_details as pd', 'pd.employee_id','=','em.id')
                            ->join('optcl_country_master AS c1','c1.id','=','pd.permanent_addr_country_id')
                            ->join('optcl_country_master AS c2','c2.id','=','pd.present_addr_country_id')
                            ->join('optcl_state_master AS s','s.id','=','pd.permanent_addr_state_id')
                            ->join('optcl_district_master AS d','d.id','=','pd.permanent_addr_district_id')
                            ->join('optcl_state_master AS s2','s2.id','=','pd.present_addr_state_id')
                            ->join('optcl_district_master AS d2','d2.id','=','pd.present_addr_district_id')
                            ->leftJoin('optcl_relation_master AS rm','rm.id','=','pd.family_member_relation_id')
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name as office_last_served','pd.permanent_addr_at','pd.permanent_addr_post','pd.permanent_addr_pincode','pd.permanent_addr_country_id','pd.permanent_addr_state_id','pd.permanent_addr_district_id','pd.present_addr_at','pd.present_addr_post','pd.present_addr_pincode','pd.present_addr_country_id','pd.present_addr_state_id','pd.present_addr_district_id','pd.telephone_std_code','pd.mobile_no','pd.email_address','pd.pan_no','pd.savings_bank_account_no','pd.bank_branch_id','pd.basic_pay_amount_at_retirement','pd.pension_unit_id','pd.is_civil_service_amount_received','pd.civil_service_name','pd.civil_service_received_amount','pd.is_family_pension_received_by_family_members','pd.admission_source_of_family_pension','pd.family_member_relation_id','pd.family_member_name','pd.is_commutation_pension_applied','pd.commutation_percentage','s.state_name','d.district_name','s2.state_name as sName','d2.district_name as dName','c1.country_name as cName','c2.country_name','rm.relation_name')
                            ->where('em.id', $application->employee_id)
                            ->first();

        $employee_documents = DB::table('optcl_pension_application_document as a')
                            ->select('a.employee_id', 'a.document_id', 'a.document_attachment_path', 'b.field_id', 'b.document_name')
                            ->join('optcl_pension_document_master as b', 'a.document_id', '=', 'b.id')
                            ->where('a.employee_id', $application->employee_id)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();

        $employee_nominees = DB::table('optcl_employee_nominee_details as a')
                            ->select('a.*', 'b.nominee_prefrence', 'c.gender_name', 'd.relation_name', 'e.bank_name', 'f.branch_name', 'f.ifsc_code', 'g.marital_status_name')
                            ->join('optcl_nominee_preference_master as b', 'a.nominee_preference_id', '=', 'b.id')
                            ->join('optcl_employee_gender_master as c', 'a.gender_id', '=', 'c.id')
                            ->join('optcl_relation_master as d', 'a.relationship_id', '=', 'd.id')
                            ->join('optcl_bank_master as e', 'a.bank_id', '=', 'e.id')
                            ->join('optcl_bank_branch_master as f', 'a.bank_branch_id', '=', 'f.id')
                            ->join('optcl_marital_status_master as g', 'a.marital_status_id', '=', 'g.id')
                            ->where('a.employee_id', $application->employee_id)
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

        $add_recovery = DB::table('optcl_employee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->get();

        $service_form = DB::table('optcl_employee_pension_service_form')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->first();

        $service_form_three = DB::table('optcl_employee_pension_service_form_three')->where('application_id', $id)->first();



        // Calculation Sheet Code Started
        $get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();

        // Service Pension Due
        $last_basic_pay = !empty($service_form_three->emolument_last_basic_pay) ? $service_form_three->emolument_last_basic_pay : $proposal->basic_pay_amount_at_retirement;

        $total_da_amount = 0;

        if(!empty($get_da_percentage)) {
            $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
        }

        $service_pension_due = PensinorCalculation::get_service_pension_due($service_form, $last_basic_pay, $service_form_three);


        $service_pension_masters = DB::table('optcl_calculation_rule_master')->where('pension_type_id', 1)
                            ->where('calculation_type_id', 1)
                            ->where('status', 1)->where('deleted', 0)->get();

        $service_pension_due_ids = $service_pension_masters->pluck('id')->toArray();


        $service_pension_due_exist = DB::table('optcl_pension_calculation_transaction')
                ->where('application_id', $id)
                ->where('employee_id', $application->employee_id)
                ->whereIn('rule_id' , $service_pension_due_ids)
                ->where('is_latest', 1)
                ->orderBy('id', 'desc')->first();

        // Commutation        
        $commutation_value = [];
        $commutation_two_value = [];
        $commutation_three_value = [];
        if($proposal->is_commutation_pension_applied == 1){
            // Check any Commutation rule data available or not
            $commRuleValue = DB::table('optcl_pension_calculation_transaction')
                                ->select('optcl_pension_calculation_transaction.*')
                                ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                ->where('optcl_pension_calculation_transaction.calculation_type_id', 6)
                                ->first();
            //dd($commRuleValue);
            if($commRuleValue){
                $rule_id = $commRuleValue->rule_id;
                if($rule_id == 3){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_pension_calculation_transaction')
                                        ->select('optcl_pension_calculation_transaction.*')
                                        ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                        ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                        ->where('optcl_pension_calculation_transaction.calculation_type_id', 1)
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
            $commRuletwoValue = DB::table('optcl_pension_calculation_transaction')
                                ->select('optcl_pension_calculation_transaction.*')
                                ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                ->where('optcl_pension_calculation_transaction.calculation_type_id', 2)
                                ->first();
            //dd($commRuletwoValue);
            
            if($commRuletwoValue){
                $rule_id = $commRuletwoValue->rule_id;
                if($rule_id == 2){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_pension_calculation_transaction')
                                        ->select('optcl_pension_calculation_transaction.*')
                                        ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                        ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                        ->where('optcl_pension_calculation_transaction.calculation_type_id', 1)
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
            $commRuleThreeValue = DB::table('optcl_pension_calculation_transaction')
                                ->select('optcl_pension_calculation_transaction.*')
                                ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                ->where('optcl_pension_calculation_transaction.calculation_type_id', 7)
                                ->first();
            //dd($commRuleThreeValue);
            
            if($commRuleThreeValue){
                $rule_id = $commRuleThreeValue->rule_id;
                if($rule_id == 4){
                    // Get rule details
                    $ruleDetails = DB::table('optcl_calculation_rule_master')
                                        ->where('id', $rule_id)
                                        ->first();
                    $pensionValue = DB::table('optcl_pension_calculation_transaction')
                                        ->select('optcl_pension_calculation_transaction.*')
                                        ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                        ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                        ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                        ->where('optcl_pension_calculation_transaction.calculation_type_id', 1)
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
        $familyPensionValue = DB::table('optcl_pension_calculation_transaction')
                            ->select('optcl_pension_calculation_transaction.*')
                            ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                            ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                            ->where('optcl_pension_calculation_transaction.is_latest', 1)
                            ->where('optcl_pension_calculation_transaction.calculation_type_id', 3)
                            ->first();
        
        if($familyPensionValue) {
            $pensionValue = DB::table('optcl_pension_calculation_transaction')
                                ->select('optcl_pension_calculation_transaction.*')
                                ->where('optcl_pension_calculation_transaction.application_id', $application->id)
                                ->where('optcl_pension_calculation_transaction.employee_id', $application->employee_id)
                                ->where('optcl_pension_calculation_transaction.is_latest', 1)
                                ->where('optcl_pension_calculation_transaction.calculation_type_id', 1)
                                ->first();
            if($pensionValue) {

                if($familyPensionValue->rule_id == 5 || $familyPensionValue->rule_id == 11 || $familyPensionValue->rule_id == 13) {
                    $fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 30; // Family pension percentage
                    $fp_pension_admissible = $pensionValue->rounded_calculation_value;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                } elseif($familyPensionValue->rule_id == 8 || $familyPensionValue->rule_id == 9 || $familyPensionValue->rule_id == 10) {
                    $fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
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

                $nomineeDetails = DB::table('optcl_employee_nominee_details')
                                    ->select('optcl_employee_nominee_details.nominee_name', 'optcl_employee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                    ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_employee_nominee_details.relationship_id')
                                    ->where('employee_id', $application->employee_id)
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

        $dcr_gratuity_exist = DB::table('optcl_pension_calculation_transaction')
                ->where('application_id', $id)
                ->where('employee_id', $application->employee_id)
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

    	return view('user.pension-section.verifier.application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form', 'service_form_three', 'total_da_amount', 'service_pension_due', 'family_pension_data', 'dcr_gratuity_exist', 'dcr_gratuity_value', 'service_pension_due_exist', 'commutation_value', 'commutation_two_value', 'commutation_three_value'));
    }

    public function applications_approval(Request $request) {

    	try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            if(!empty($application_form)) {

                if($request->application_status == 1) {
                    DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                    ->where('deleted', 0)->update([
                        'application_status_id' => 42
                    ]);

                    DB::table('optcl_application_status_history')->insert([
                        'user_id'           => $user->id,
                        'application_id'    => $request->application_id,
                        'status_id'         => 42,
                        'remarks'           => $request->remarks,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                        'status'            => 1,
                        'deleted'           => 0
                    ]);

                    $message = 'Verifier has been approved the application no - ' . $application_form->application_no . '. Please check the application details.';

                    self::application_notification($request->application_id, $message);

                    $status = 'approved';
                } else {
                    DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                    ->where('deleted', 0)->update([
                        'application_status_id' => 43
                    ]);
                    DB::table('optcl_application_status_history')->insert([
                        'user_id'           => $user->id,
                        'application_id'    => $request->application_id,
                        'status_id'         => 43,
                        'remarks'           => $request->remarks,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                        'status'            => 1,
                        'deleted'           => 0
                    ]);

                    $message = 'Verifier has been returned the application no - ' . $application_form->application_no . '. Please check the application details.';

                    self::application_notification($request->application_id, $message);

                    $status = 'returned';
                }               

                DB::commit();
                Session::flash('success','Application has been '. $status .' successfully!');
                return redirect()->route('verifier_applications');
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

    public function application_notification($application_id, $message) {

        $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');

        // Pensioner            
        Util::insert_notification($appDetails->user_id, $application_id, $message);

        // Dealing Assistant
        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');

        if(!empty($n_user_id)) {

            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // Finance Executive
        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
        
        if(!empty($n_user_id)) {

            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // Unit Head
        $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');

        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }

        //HR Wing Dealing Assistant
        $n_user_id = DB::table('optcl_application_user_assignments')->where('application_id', $application_id)->value('user_id');

        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // Sanction Authority
        $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
        
        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // HR Executive Authority
        $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->where('optcl_unit_id', $optcl_unit_id)->value('id');
        
        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // Pension Section Initiator
        $n_user_id = DB::table('optcl_users')->where('designation_id', 8)->value('id');
        
        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }

        // Pension Section Approver
        $n_user_id = DB::table('optcl_users')->where('designation_id', 10)->value('id');
        
        if(!empty($n_user_id)) {
            Util::insert_notification($n_user_id, $application_id, $message);
        }
    }
}
