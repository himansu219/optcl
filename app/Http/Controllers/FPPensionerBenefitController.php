<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\NomineeUtil;
use App\Libraries\PensinorCalculation;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class FPPensionerBenefitController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }


    public function calculate_pensionar_benefits($id) {
        //dd(1);
    	$application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

        $proposal = self::get_employee_details($application->employee_id);
        //dd($application->employee_id);
        $last_basic_pay = DB::table('optcl_nominee_pension_service_form')
                            ->where('application_id', $id) 
                            ->value('last_basic_pay');

        $service_form = DB::table('optcl_nominee_pension_service_form')->where('application_id', $id)->first();

        $get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();

        $form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $id)->first();


        // Service Pension Due
        $last_basic_pay = !empty($last_basic_pay) ? $last_basic_pay : $proposal->basic_pay_amount_at_retirement;

        $total_da_amount = 0;

        if(!empty($get_da_percentage)) {
            $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
        }

        $service_pension_due = self::get_service_pension_due($service_form, $last_basic_pay, $form_three);

        $service_pension = $service_pension_due['service_pension'];

        // Commutation
        $pension_admissible = $service_pension;
        $commutation_value = [];
        

        // Family Pension
        //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
        $fp_basic_pay_amount = $form_three->emolument_last_basic_pay; //51100
        $calculate_percentage = 30;
        $fp_pension_admissible = $pension_admissible;
        $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
        $fp_rounded_to = ceil($fp_pension_amount);
        $fp_date_of_retirement = $proposal->date_of_retirement;
        // Next pension date if employee died before 65 years
        $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
        // Get Nominee details
        $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                            ->where('nominee_master_id', $application->employee_id)
                            ->where('nominee_preference_id', 1) // Preference = 1
                            ->first();
        $family_pension_data = [
            'fp_basic_pay_amount'       => $fp_basic_pay_amount,
            'calculate_percentage'      => $calculate_percentage,
            'fp_pension_admissible'     => $fp_pension_admissible,
            'fp_pension_amount'         => $fp_pension_amount,
            'fp_rounded_to'             => $fp_rounded_to,
            'nominee_name'              => $nomineeDetails->nominee_name,
            'nominee_dob'               => $nomineeDetails->date_of_birth,
            'Last_Full_Pension_Date'    => $Last_Full_Pension_Date
        ];

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
            $dcr_gratuity_value = self::get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three);
        } elseif(!empty($dcr_gratuity_exist) && $dcr_gratuity_exist->rule_id == 12) {
            $dcr_gratuity_value = self::get_death_gratuity($service_form, $last_basic_pay, $total_da_amount);
        } else {
            $dcr_gratuity_value = self::get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three);
        }

        // Commutation        
        $commutation_value = [];
        $commutation_two_value = [];
        $commutation_three_value = [];
        //dd($application);
        $commutationDetails = DB::table('optcl_pension_application_form')
                                ->where('employee_code', $application->employee_code)
                                ->where('pension_type_id', 1)
                                ->get();
        if($commutationDetails->count() > 0){
            $is_commutation_pension_applied = $commutationDetails->first()->is_commutation_received;
        }else{
            $is_commutation_pension_applied = 0;
        }
        if($is_commutation_pension_applied == 0){
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
                        // Get commutation percentage from pensioner
                        $employee_code = DB::table('optcl_pension_application_form')
                            ->where('id', $application->id)
                            ->value('employee_code');
                        $commutation_percentage = DB::table('optcl_employee_personal_details')
                            ->where('employee_code', $employee_code)
                            ->value('commutation_percentage');
                        $commutation_percentage = $commutation_percentage ? $commutation_percentage : 0;

                        $pension_admissible = $pensionValue->rounded_calculation_value;
                        //$commutation_percentage = $proposal->commutation_percentage;
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

        // Rule for family pension6
        $rule_family = DB::table('optcl_calculation_rule_master')->where('pension_type_id' , 1)->where('calculation_type_id' , 3)->where('status', 1)->where('deleted', 0)->get();

        $rule_one_commutation = DB::table('optcl_calculation_rule_master')->where('pension_type_id' , 1)->where('calculation_type_id' , 6)->where('status', 1)->where('deleted', 0)->get();

        $rule_two_commutated_value = DB::table('optcl_calculation_rule_master')->where('pension_type_id' , 1)->where('calculation_type_id' , 2)->where('status', 1)->where('deleted', 0)->get();

        $rule_three_reduced_pension_value = DB::table('optcl_calculation_rule_master')->where('pension_type_id' , 1)
        ->where('calculation_type_id', 7)->where('status', 1)->where('deleted', 0)->get();

        $recovery_details = DB::table('optcl_employee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->get();
        //dd($recovery_details);

        return view('user.hr-wing.fp_calculation_sheet.pensioner_benefits', compact('application', 'proposal', 'service_form', 'commutation_value','family_pension_data', 'service_pension_due', 'dcr_gratuity_value', 'total_da_amount', 'service_pension_masters', 'service_dcr_gratuity', 'service_pension_due_exist', 'dcr_gratuity_exist', 'rule_family','rule_one_commutation', 'rule_two_commutated_value', 'rule_three_reduced_pension_value', 'commutation_two_value', 'commutation_three_value', 'recovery_details', 'form_three'));
    }

    public function get_employee_details($employee_id) {
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
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name as office_last_served','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','pu.pension_unit_name')
                            ->where('em.id', $employee_id)
                            ->first();
        return $proposal;
    }

    public function get_service_pension_due($service_form, $last_basic_pay, $form_three=array()) {

        if(empty($form_three)) {
            $net_qualifying_years = !empty($service_form->net_qualifying_years) ? $service_form->net_qualifying_years : 0;
            $net_qualifying_months = !empty($service_form->net_qualifying_months) ? $service_form->net_qualifying_months : 0;
            $net_qualifying_days = !empty($service_form->net_qualifying_days) ? $service_form->net_qualifying_days : 0;
        } else {
            $net_qualifying_years = !empty($form_three->total_net_qualifying_years) ? $form_three->total_net_qualifying_years : 0;
            $net_qualifying_months = !empty($form_three->total_net_qualifying_months) ? $form_three->total_net_qualifying_months : 0;
            $net_qualifying_days = !empty($form_three->total_net_qualifying_days) ? $form_three->total_net_qualifying_days : 0;
        }

        $total_completed_years = 0;
        $service_pension = 0;

        $completed_half_years = $net_qualifying_years * 2;

        if($completed_half_years >= 50) {
            $total_completed_years = 50;
        } else {
            if($net_qualifying_months < 3) {

                $total_completed_years = $completed_half_years;

            } elseif(($net_qualifying_months > 3 && $net_qualifying_months < 9) || ($net_qualifying_months == 3 && $net_qualifying_days > 1)) {

                $total_completed_years = $completed_half_years + 1;

            } elseif($net_qualifying_months < 9) {

                $total_completed_years = $completed_half_years + 1;

            } elseif( ($net_qualifying_months > 9) || ($net_qualifying_months == 9 && $net_qualifying_days > 1)) {

                $total_completed_years = $completed_half_years + 2;
            }
        }

        $service_pension = ($last_basic_pay / 2) * ($total_completed_years/50);

        $service_pension_due = [
            'service_pension' =>  $service_pension,
            'last_basic_pay' => $last_basic_pay,
            'total_completed_years' => $total_completed_years
        ];

        return $service_pension_due;
    }

    public function get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three=array()) {

        if(empty($form_three)) {
            $net_qualifying_years = !empty($service_form->net_qualifying_years) ? $service_form->net_qualifying_years : 0;
            $net_qualifying_months = !empty($service_form->net_qualifying_months) ? $service_form->net_qualifying_months : 0;
            $net_qualifying_days = !empty($service_form->net_qualifying_days) ? $service_form->net_qualifying_days : 0;
        } else {
            $net_qualifying_years = !empty($form_three->total_net_qualifying_years) ? $form_three->total_net_qualifying_years : 0;
            $net_qualifying_months = !empty($form_three->total_net_qualifying_months) ? $form_three->total_net_qualifying_months : 0;
            $net_qualifying_days = !empty($form_three->total_net_qualifying_days) ? $form_three->total_net_qualifying_days : 0;
        }

        $total_completed_years = 0;
        $service_pension = 0;

        $completed_half_years = $net_qualifying_years * 2;

        //DCR Gratuity
        $dcr_completed_years = 0;
        $total_dcr_gratuity = 0;

        if($completed_half_years > 66) {
            $dcr_completed_years = 66;
        } else {
            if($net_qualifying_months < 3) {

                $dcr_completed_years = $completed_half_years;

            } elseif(($net_qualifying_months > 3 && $net_qualifying_months < 9) || ($net_qualifying_months == 3 && $net_qualifying_days > 1)) {

                $dcr_completed_years = $completed_half_years + 1;

            } elseif($net_qualifying_months < 9) {

                $dcr_completed_years = $completed_half_years + 1;

            } elseif( ($net_qualifying_months > 9) || ($net_qualifying_months == 9 && $net_qualifying_days > 1)) {

                $dcr_completed_years = $completed_half_years + 2;
            }
        }

        $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/4 * $dcr_completed_years);

        $dcr_gratuity_value = [
            'dcr_completed_years' =>  $dcr_completed_years,
            'last_basic_pay' => $last_basic_pay,
            'total_dcr_gratuity' => $total_dcr_gratuity
        ];

        return $dcr_gratuity_value;
    }

    public function get_death_gratuity($service_form, $last_basic_pay, $total_da_amount) {

        $net_qualifying_years = !empty($service_form->net_qualifying_years) ? $service_form->net_qualifying_years : 0;
        $net_qualifying_months = !empty($service_form->net_qualifying_months) ? $service_form->net_qualifying_months : 0;
        $net_qualifying_days = !empty($service_form->net_qualifying_days) ? $service_form->net_qualifying_days : 0;

        $total_completed_years = 0;
        $service_pension = 0;

        $completed_half_years = $net_qualifying_years * 2;

        //DCR Gratuity
        $dcr_completed_years = 0;
        $total_dcr_gratuity = 0;

        if($completed_half_years > 66) {
            $dcr_completed_years = 66;
            $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/2 * $dcr_completed_years);
        } else {
            //$dcr_completed_years = $completed_half_years;
            if($net_qualifying_months < 3) {

                $dcr_completed_years = $completed_half_years;
                $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/2 * $dcr_completed_years);

            } elseif(($net_qualifying_months > 3 && $net_qualifying_months < 9) || ($net_qualifying_months == 3 && $net_qualifying_days > 1)) {

                $dcr_completed_years = $completed_half_years + 1;
                $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/2 * $dcr_completed_years);

            } elseif($net_qualifying_months < 9) {

                $dcr_completed_years = $completed_half_years + 1;
                $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/2 * $dcr_completed_years);

            } elseif( ($net_qualifying_months > 9) || ($net_qualifying_months == 9 && $net_qualifying_days > 1)) {

                $dcr_completed_years = $completed_half_years + 2;
                $total_dcr_gratuity = (($last_basic_pay + $total_da_amount) * 1/2 * $dcr_completed_years);
            }
        }

        $dcr_gratuity_value = [
            'dcr_completed_years' =>  $dcr_completed_years,
            'last_basic_pay' => $last_basic_pay,
            'total_dcr_gratuity' => $total_dcr_gratuity
        ];

        return $dcr_gratuity_value;
    }

    public function calculate_rules(Request $request) {
    	$rule_id = $request->rule_id;
    	$application_id = $request->application_id;
    	$employee_id = $request->employee_id;

    	try {
    		$user = Auth::user();
    		
    		$proposal = self::get_employee_details($employee_id);

    		$rule_master = DB::table('optcl_calculation_rule_master')->where('status', 1)->where('deleted', 0)->where('id', $rule_id)->first();

    		$service_form = DB::table('optcl_nominee_pension_service_form')->where('application_id', $application_id)->first();

            $form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $application_id)->first();

            if(empty($form_three)) {
                $net_qualifying_years = !empty($service_form->net_qualifying_years) ? $service_form->net_qualifying_years : 0;
                $net_qualifying_months = !empty($service_form->net_qualifying_months) ? $service_form->net_qualifying_months : 0;
                $net_qualifying_days = !empty($service_form->net_qualifying_days) ? $service_form->net_qualifying_days : 0;
                $last_basic_pay = $proposal->basic_pay_amount_at_retirement;
            } else {
                $net_qualifying_years = !empty($form_three->total_net_qualifying_years) ? $form_three->total_net_qualifying_years : 0;
                $net_qualifying_months = !empty($form_three->total_net_qualifying_months) ? $form_three->total_net_qualifying_months : 0;
                $net_qualifying_days = !empty($form_three->total_net_qualifying_days) ? $form_three->total_net_qualifying_days : 0;

                $last_basic_pay = $form_three->emolument_last_basic_pay;

            }

	        $total_completed_years = 0;
	        $service_pension = 0;

	        $completed_half_years = $net_qualifying_years * 2;

            // Service Pension Due
    		if($rule_id == 1) {

                $service_pension_due = self::get_service_pension_due($service_form, $last_basic_pay, $form_three);

		        $logic = '('.$last_basic_pay.' X 1/2) X (' . $service_pension_due['total_completed_years'].'/50) = Rs ' . number_format($service_pension_due['service_pension'], 2) . '/-';

                $service_pension_due['rule_logic'] = $logic;
                $service_pension_due['rule_desc'] = $rule_master->rule_description;
                $service_pension_due['calculation_type_id'] = $rule_master->calculation_type_id;

		        return response()->json(['status' => 'success', 'service_pension_due' => $service_pension_due]);
    		}

    		if($rule_id == 7) {
    			$get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();

                $recovery_details = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $application_id)->get();

		        $total_da_amount = 0;

		        if(!empty($get_da_percentage)) {
		            $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
		        }

                $dcr_gratuity = self::get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three);

		        $logic = '( Rs.' . $last_basic_pay . ' + ' . $total_da_amount . ' ) X 1/4 X ' . $dcr_gratuity['dcr_completed_years'] . ' = ' . ceil($dcr_gratuity['total_dcr_gratuity']);

                $recovery = '<br>';
                $total_recovery = 0;

                foreach ($recovery_details as $key => $recovery_detail) {
                    $recovery = $recovery . '<span>' . $recovery_detail->recovery_label . '(Recovery) : ' . $recovery_detail->recovery_value.'</span><br>';
                    $total_recovery = $total_recovery + $recovery_detail->recovery_value;
                }

                $dcr_gratuity['total_gratuity'] = ($dcr_gratuity['total_dcr_gratuity'] - $total_recovery);

                $net_gratuity = 'Net Gratuity : ' . $dcr_gratuity['total_dcr_gratuity'] .' - ' . $total_recovery .' = ' . $dcr_gratuity['total_gratuity'];

                $dcr_gratuity['total_da_amount'] = $total_da_amount;
                $dcr_gratuity['rule_logic'] = $logic . $recovery . $net_gratuity;
                $dcr_gratuity['rule_desc'] = $rule_master->rule_description;
                $dcr_gratuity['calculation_type_id'] = $rule_master->calculation_type_id;

		        return response()->json(['status' => 'success', 'dcr_gratuity' => $dcr_gratuity]);
    		}

            if($rule_id == 12) {
                $get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();

                $recovery_details = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $application_id)->get();

                $total_da_amount = 0;

                if(!empty($get_da_percentage)) {
                    $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
                }

                $dcr_gratuity = self::get_death_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three);

                $logic = '( Rs.' . $last_basic_pay . ' + ' . $total_da_amount . ' ) X 1/2 X ' . $dcr_gratuity['dcr_completed_years'] . ' = ' . ceil($dcr_gratuity['total_dcr_gratuity']);

                $recovery = '<br>';
                $total_recovery = 0;

                foreach ($recovery_details as $key => $recovery_detail) {
                    $recovery = $recovery . '<span>' . $recovery_detail->recovery_label . '(Recovery) : ' . $recovery_detail->recovery_value.'</span><br>';
                    $total_recovery = $total_recovery + $recovery_detail->recovery_value;
                }

                $dcr_gratuity['total_gratuity'] = ($dcr_gratuity['total_dcr_gratuity'] - $total_recovery);

                $net_gratuity = 'Net Gratuity : ' . $dcr_gratuity['total_dcr_gratuity'] .' - ' . $total_recovery .' = ' . $dcr_gratuity['total_gratuity'];

                $dcr_gratuity['total_da_amount'] = $total_da_amount;
                $dcr_gratuity['rule_logic'] = $logic . $recovery . $net_gratuity;
                $dcr_gratuity['rule_desc'] = $rule_master->rule_description;
                $dcr_gratuity['calculation_type_id'] = $rule_master->calculation_type_id;

                return response()->json(['status' => 'success', 'dcr_gratuity' => $dcr_gratuity]);
            }
    	} catch (Exception $e) {
    		return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
    	}
    }

    public function calculate_service_pension_save(Request $request) {
    	$rule_id = $request->rule_id;
    	$application_id = $request->application_id;
    	$employee_id = $request->employee_id;
    	$pension_value = $request->pension_value;
    	$last_basic_pay = $request->last_basic_pay;
    	$total_completed_years = $request->total_completed_years;
        $calculation_type_id = $request->calculation_type_id;

    	try {
    		DB::beginTransaction();

    		$user = Auth::user();

    		$proposal = self::get_employee_details($employee_id);

    		if(!empty($rule_id) && !empty($application_id)) {
	    		$transaction = DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)
                    ->where('nominee_master_id', $employee_id)->where('rule_id', $rule_id)
                    ->where('calculation_type_id', $calculation_type_id)
                    ->where('is_latest', 1)->first();
	    		
	    		if(empty($transaction)) {
                    $checkCondType = DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)->where('nominee_master_id', $employee_id)->where('calculation_type_id', $calculation_type_id)->where('is_latest', 1)->get();

                    if($checkCondType->count() > 0) {
                        DB::table('optcl_nominee_calculation_transaction')
                            ->where('application_id', $application_id)
                            ->where('nominee_master_id', $employee_id)
                            ->where('calculation_type_id', $calculation_type_id)
                            ->where('is_latest', 1)
                            ->update(['is_latest' => 0]);
                    }

		    		$calculation_transactions = [
		    			'application_id' => $application_id,
		    			'nominee_master_id' => $employee_id,
		    			'rule_id' => $rule_id,
                        'calculation_type_id' => $calculation_type_id,
		    			'calculation_value' => $pension_value,
		    			'rounded_calculation_value' => ceil($pension_value),
		    			'status' => 1,
		                'created_at' => $this->current_date,
		                'created_by' => $user->id,
		                'deleted' => 0
		    		];

		    		$transaction_id = DB::table('optcl_nominee_calculation_transaction')->insertGetId($calculation_transactions);

		    		$logic = '('.$last_basic_pay.' X 1/2) X (' . $total_completed_years.'/50) = Rs ' . number_format($pension_value, 2) . '/-';

		    		/*$service_pension_due = [
			            'service_pension' =>  $pension_value,
			            'last_basic_pay' => $last_basic_pay,
			            'total_completed_years' => $total_completed_years,
			            'rule_logic' => $logic,
			            'wef' => Carbon::parse($proposal->date_of_retirement)->addDay()->format('d/m/Y')
			        ];*/
			    } else {
                    $transaction_id = $transaction->id;

			    	$calculation_transactions = [
		    			'application_id' => $application_id,
		    			'nominee_master_id' => $employee_id,
		    			'rule_id' => $rule_id,
                        'calculation_type_id' => $calculation_type_id,
		    			'calculation_value' => $pension_value,
		    			'rounded_calculation_value' => ceil($pension_value),
		    			'status' => 1,
		                'modified_at' => $this->current_date,
		                'modified_by' => $user->id,
		                'deleted' => 0
		    		];

		    		DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)
		    		->where('nominee_master_id', $employee_id)->where('rule_id', $rule_id)
                    ->where('calculation_type_id', $calculation_type_id)
                    ->where('is_latest', 1)
		    		->where('status', 1)->where('deleted', 0)->update($calculation_transactions);

		    		$logic = '('.$last_basic_pay.' X 1/2) X (' . $total_completed_years.'/50) = Rs ' . number_format($pension_value, 2) . '/-';

		    		/*$service_pension_due = [
			            'service_pension' =>  $pension_value,
			            'last_basic_pay' => $last_basic_pay,
			            'total_completed_years' => $total_completed_years,
			            'rule_logic' => $logic,
			            'wef' => Carbon::parse($proposal->date_of_retirement)->addDay()->format('d/m/Y')
			        ];*/
			    }

                $calculation_transaction_history = [
                    'application_id' => $application_id,
                    'pension_tranasction_id' => $transaction_id,
                    'calculted_value' => $pension_value,
                    'rounded_calculated_value' => ceil($pension_value),
                    'status' => 1,
                    'created_at' => $this->current_date,
                    'created_by' => $user->id,
                    'deleted' => 0
                ];

                DB::table('optcl_nominee_calculation_history')->insert($calculation_transaction_history);

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Service pension due has been updated successfully!']);
                // return response()->json(['status' => 'success', 'message' => 'Service pension due has been updated successfully!', 'service_pension_due' => $service_pension_due]);
			} else {
				DB::commit();
	    		return response()->json(['status' => 'erorr', 'message' => 'Invalid Data']);
			}
    		
    	} catch (Exception $e) {
    		DB::rollback();
    		return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);	
    	}
    }
	
	public function calculate_dcr_gratuity_save(Request $request) {
    	$rule_id = $request->rule_id;
    	$application_id = $request->application_id;
    	$employee_id = $request->employee_id;
    	$last_basic_pay = $request->last_basic_pay;
    	$dcr_completed_years = $request->total_completed_years;
    	$total_dcr_gratuity = $request->total_dcr_gratuity;
    	$total_da_amount = $request->da_amount;
        $calculation_type_id = $request->calculation_type_id;

    	try {
    		DB::beginTransaction();

    		$user = Auth::user();

    		$proposal = self::get_employee_details($employee_id);

    		if(!empty($rule_id) && !empty($application_id)) {
	    		$transaction = DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)->where('nominee_master_id', $employee_id)->where('rule_id', $rule_id)->where('is_latest', 1)->where('status', 1)->where('deleted', 0)->first();
	    		
	    		if(empty($transaction)) {

                    $checkCondType = DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)->where('nominee_master_id', $employee_id)->where('calculation_type_id', $calculation_type_id)->where('is_latest', 1)->get();

                    if($checkCondType->count() > 0) {
                        DB::table('optcl_nominee_calculation_transaction')
                            ->where('application_id', $application_id)
                            ->where('nominee_master_id', $employee_id)
                            ->where('calculation_type_id', $calculation_type_id)
                            ->where('is_latest', 1)
                            ->update(['is_latest' => 0]);
                    }

		    		$calculation_transactions = [
		    			'application_id' => $application_id,
		    			'nominee_master_id' => $employee_id,
		    			'rule_id' => $rule_id,
                        'calculation_type_id' => $calculation_type_id,
		    			'calculation_value' => $total_dcr_gratuity,
		    			'rounded_calculation_value' => round($total_dcr_gratuity),
		    			'status' => 1,
		                'created_at' => $this->current_date,
		                'created_by' => $user->id,
		                'deleted' => 0
		    		];

		    		$transaction_id = DB::table('optcl_nominee_calculation_transaction')->insertGetId($calculation_transactions);
			    } else {
                    $transaction_id = $transaction->id;
			    	$calculation_transactions = [
		    			'application_id' => $application_id,
		    			'nominee_master_id' => $employee_id,
		    			'rule_id' => $rule_id,
                        'calculation_type_id' => $calculation_type_id,
		    			'calculation_value' => $total_dcr_gratuity,
		    			'rounded_calculation_value' => ceil($total_dcr_gratuity),
		    			'status' => 1,
		                'modified_at' => $this->current_date,
		                'modified_by' => $user->id,
		                'deleted' => 0
		    		];

		    		DB::table('optcl_nominee_calculation_transaction')->where('application_id', $application_id)->where('nominee_master_id', $employee_id)->where('rule_id', $rule_id)->where('is_latest', 1)->where('status', 1)->where('deleted', 0)->update($calculation_transactions);
			    }

                $calculation_transaction_history = [
                    'application_id' => $application_id,
                    'pension_tranasction_id' => $transaction_id,
                    'calculted_value' => $total_dcr_gratuity,
                    'rounded_calculated_value' => round($total_dcr_gratuity),
                    'status' => 1,
                    'created_at' => $this->current_date,
                    'created_by' => $user->id,
                    'deleted' => 0
                ];

                DB::table('optcl_nominee_calculation_history')->insert($calculation_transaction_history);

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'DCR Gratuity due has been updated successfully!']);
			} else {
				DB::commit();
	    		return response()->json(['status' => 'erorr', 'message' => 'Invalid Data']);
			}
    	} catch (Exception $e) {
    		DB::rollback();
    		return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);	
    	}
    }

    // Family and Commutation
    public function get_family_pension_details(Request $request){
        $rule_id = $request->rule_id;
        $name_value = $request->name_value;
        $family_pension_data = [];
        $details = explode('_', $name_value);
        $application_id = $details[1];
        $fp_basic_pay_amount = DB::table('optcl_nominee_pension_service_form')
                            ->where('application_id', $application_id) 
                            ->value('last_basic_pay');

        $rule_master = DB::table('optcl_calculation_rule_master')->where('status', 1)->where('deleted', 0)->where('id', $rule_id)->first();

        if($rule_id == 5){
            $details = explode('_', $name_value);
            if(count($details) == 2) {
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first(); 

                if($pensionValue){
                    


                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 30; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;

                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();

                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>In the event of death of pensioner before attaining the age of 65 years Rs. '.number_format($fp_pension_admissible, 2).' + TI is payable to <strong>'.$nomineeDetails->nominee_name.'</strong> <br>DOB ('.date("d/m/Y", strtotime($nomineeDetails->date_of_birth)).') '.$nomineeDetails->relation_name.' of pensioner, from day following the day of death of pensioner upto '.$Last_Full_Pension_Date.' and <br>thereafter @ Rs. '.number_format($fp_pension_amount, 2).'/- till her death or remarriage whichever is earlier.</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                    
                } 
            }        
        } elseif ($rule_id == 8) {
            $details = explode('_', $name_value);
            if(count($details) == 2){
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();  

                if($pensionValue) {
                    

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 50; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();

                    $date_of_retirement = Carbon::parse($fp_date_of_retirement)->format('d/m/Y');
                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>This Family pension allowed for 10 years from the next day of the date of death ('.$date_of_retirement.').</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                    
                } 
            } 
        } elseif ($rule_id == 9) {
            $details = explode('_', $name_value);
            if(count($details) == 2){
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();  

                if($pensionValue) {
                    

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 50; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();
                    
                    $date_of_retirement = Carbon::parse($fp_date_of_retirement)->format('d/m/Y');

                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>This Family pension allowed for 7 years or upto 65 years of SP if allived which ever is erlier from the next day of the date of death ('. $date_of_retirement .').</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                    
                } 
            } 
        } elseif ($rule_id == 10) {
            $details = explode('_', $name_value);
            if(count($details) == 2) {
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();  

                if($pensionValue) {
                    

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 50; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();

                    $date_of_retirement = Carbon::parse($fp_date_of_retirement)->format('d/m/Y');

                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>This Family pension allowed till the service pensioner complete the  65 years if allived form the next day of death of SP ('. $date_of_retirement .').</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                    
                } 
            } 
        } elseif ($rule_id == 11) {
            $details = explode('_', $name_value);
            if(count($details) == 2) {
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();  

                if($pensionValue) {
                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 30; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();

                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>This Family pension allowed till the service pensioner complete the  65 years if allived form the next day of death of FP.</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                    
                } 
            } 
        } elseif ($rule_id == 13) {
            $details = explode('_', $name_value);
            if(count($details) == 2) {
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);
                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();  

                if($pensionValue) {
                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$fp_basic_pay_amount = $proposal->basic_pay_amount_at_retirement; //51100
                    $calculate_percentage = 30; // Family pension percentage
                    $fp_pension_admissible = $pension_admissible;
                    $fp_pension_amount = $fp_basic_pay_amount*($calculate_percentage/100);
                    $fp_rounded_to = ceil($fp_pension_amount);
                    $fp_date_of_retirement = $proposal->date_of_retirement;
                    // Next pension date if employee died before 65 years
                    $Last_Full_Pension_Date = date('d/m/Y',strtotime("+5 year",strtotime($fp_date_of_retirement)));
                    // Get Nominee details
                    $nomineeDetails = DB::table('optcl_nominee_nominee_details')
                                        ->select('optcl_nominee_nominee_details.nominee_name', 'optcl_nominee_nominee_details.date_of_birth', 'optcl_relation_master.relation_name')
                                        ->join('optcl_relation_master','optcl_relation_master.id','=','optcl_nominee_nominee_details.relationship_id')
                                        ->where('nominee_master_id', $employee_id)
                                        ->where('nominee_preference_id', 1) // Preference = 1
                                        ->first();

                    $family_pension_show = '
                            <li><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$rule_master->rule_description.'"></i> Pension Amount: Rs. '.number_format($fp_pension_amount, 2).'</li>
                            <li>Rounded To: Rs. '.number_format($fp_rounded_to, 2).'</li>
                            <li>Subject to Minimum Rs. 8300/- </li><!-- Dummy amount -->
                            <li>Service Pensioner died within in 7 Years of Service.</li>
                       ';
                    // detail_value    = <pension_amount>_<pension_rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $family_pension_data = [
                        'fp_pension_amount'         => $fp_pension_amount,
                        'fp_rounded_to'             => $fp_rounded_to,
                        'detail_value'              => $fp_pension_amount."_".$fp_rounded_to."_".$application_id."_".$employee_id."_".$rule_id."_".$rule_master->calculation_type_id,
                        'view_details'              => $family_pension_show,
                    ];
                } 
            } 
        } else {
            $family_pension_data = [];
        }
        return response()->json($family_pension_data);
    }

    public function save_transaction_details(Request $request){
        try {
            DB::beginTransaction();
            $transaction_val = $request->transaction_val;
            $transaction_details = explode("_", $transaction_val);

            if(count($transaction_details) == 6){
                $pension_amount = $transaction_details[0];
                $pension_rounded_amount = $transaction_details[1];
                $application_id = $transaction_details[2];
                $employee_id = $transaction_details[3];
                $rule_id = $transaction_details[4];
                $calculation_type_id = $transaction_details[5];
                // Check data availability
                $checkCond = [
                    "application_id"            => $application_id,
                    "nominee_master_id"               => $employee_id,
                    "rule_id"                   => $rule_id,
                    "calculation_type_id"       => $calculation_type_id,
                    "is_latest"                 => 1
                ];
                
                $dataGet = DB::table('optcl_nominee_calculation_transaction')->where($checkCond)->first();
                    
                if($dataGet) {
                    $pension_transaction_id = $dataGet->id;
                    $cond_tran = ['id' => $pension_transaction_id];
                    $transaction_data = [
                        "application_id"            => $application_id,
                        "nominee_master_id"               => $employee_id,
                        "rule_id"                   => $rule_id,
                        "calculation_type_id"       => $calculation_type_id,
                        "calculation_value"         => $pension_amount,
                        "rounded_calculation_value" => $pension_rounded_amount,
                        "modified_by"               => Auth::user()->id,
                        "modified_at"               => $this->current_date,
                    ];
                    DB::table('optcl_nominee_calculation_transaction')->where($cond_tran)->update($transaction_data);                    
                } else {
                     $checkCondType = [
                        "application_id"            => $application_id,
                        "nominee_master_id"               => $employee_id,
                        "calculation_type_id"       => $calculation_type_id,
                        "is_latest"                 => 1
                    ];

                    $condType = DB::table('optcl_nominee_calculation_transaction')->where($checkCondType)->get();
                    
                    if($condType->count() > 0) {
                        DB::table('optcl_nominee_calculation_transaction')->where($checkCondType)->update([
                            'is_latest' => 0
                        ]);
                    }

                    $transaction_data = [
                        "application_id"            => $application_id,
                        "nominee_master_id"               => $employee_id,
                        "rule_id"                   => $rule_id,
                        "calculation_type_id"       => $calculation_type_id,
                        "calculation_value"         => $pension_amount,
                        "rounded_calculation_value" => $pension_rounded_amount,
                        "status"                    => 1,
                        "created_by"                => Auth::user()->id,
                        "created_at"                => $this->current_date,
                        "deleted"                   => 0,
                    ];

                    $pension_transaction_id = DB::table('optcl_nominee_calculation_transaction')->insertGetId($transaction_data);
                }

                //  Transaction History
                $transaction_history_data = [
                    "application_id"            => $application_id,
                    "pension_tranasction_id"    => $pension_transaction_id,
                    "calculted_value"           => $pension_amount,
                    "rounded_calculated_value"  => $pension_rounded_amount,
                    "status"                    => 1,
                    "created_by"                => Auth::user()->id,
                    "created_at"                => $this->current_date,
                    "deleted"                   => 0,
                ];

                DB::table('optcl_nominee_calculation_history')->insertGetId($transaction_history_data);
            }
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }

    public function get_commutation_rule_one(Request $request){
        $rule_id = $request->rule_id;
        $name_value = $request->name_value;
        $commutation_data = [];

        $rule_master = DB::table('optcl_calculation_rule_master')->where('status', 1)->where('deleted', 0)->where('id', $rule_id)->first();

        //$rule_id = $commRuletwoValue->rule_id;
        if($rule_id == 3){
            // Get rule details
            $ruleDetails = DB::table('optcl_calculation_rule_master')
                                ->where('id', $rule_id)
                                ->first();
            $details = explode('_', $name_value);
            if(count($details) == 2){
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);

                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();
                //dd($pensionValue, $application_id, $employee_id);

                if($pensionValue){
                    // Get commutation percentage from pensioner
                    $employee_code = DB::table('optcl_pension_application_form')
                        ->where('id', $application_id)
                        ->value('employee_code');
                    $commutation_percentage = DB::table('optcl_employee_personal_details')
                        ->where('employee_code', $employee_code)
                        ->value('commutation_percentage');
                    $commutation_percentage = $commutation_percentage ? $commutation_percentage : 0;

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$commutation_percentage = $proposal->commutation_percentage; //51100

                    $commuted_value = $pension_admissible*($commutation_percentage/100);
                    $rounded_below_value = floor($commuted_value);
                    $calculation_li_value = '<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$ruleDetails->rule_description.'"></i> Commuted Value: Rs. '.number_format($pension_admissible, 2).' X '.$commutation_percentage.'% = Rs. '.number_format($commuted_value, 2);
                    $calculation_rounded_li_value = 'Rounded to below: Rs. '.number_format($rounded_below_value, 2);

                    // detail_value    = <main_amount>_<rounded_amount>_<application_id>_<employee_id>_<rule_id>
                    $commuted_rule_one_details = $commuted_value.'_'.$rounded_below_value.'_'.$application_id.'_'.$employee_id.'_'.$rule_id."_".$rule_master->calculation_type_id;
                    $commutation_data = [
                        'calculation_li_value'          => $calculation_li_value,
                        'calculation_rounded_li_value'  => $calculation_rounded_li_value,
                        'commuted_rule_one_details'     => $commuted_rule_one_details,
                    ];                    
                } 
            }         
        }else{
            $commutation_data = [];
        }
        return response()->json($commutation_data);
    }

    public function get_commutation_rule_two(Request $request){
        $rule_id = $request->rule_id;
        $name_value = $request->name_value;
        $commutation_data = [];

        $rule_master = DB::table('optcl_calculation_rule_master')->where('status', 1)->where('deleted', 0)->where('id', $rule_id)->first();

        if($rule_id == 2){
            // Get rule details
            $ruleDetails = DB::table('optcl_calculation_rule_master')
                                ->where('id', $rule_id)
                                ->first();
            $details = explode('_', $name_value);
            if(count($details) == 2){
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);

                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();

                if($pensionValue){
                    // Get commutation percentage from pensioner
                    $employee_code = DB::table('optcl_pension_application_form')
                        ->where('id', $application_id)
                        ->value('employee_code');
                    $commutation_percentage = DB::table('optcl_employee_personal_details')
                        ->where('employee_code', $employee_code)
                        ->value('commutation_percentage');
                    $commutation_percentage = $commutation_percentage ? $commutation_percentage : 0;

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$commutation_percentage = $proposal->commutation_percentage; //51100

                    $commuted_value = $pension_admissible*($commutation_percentage/100);
                    /*$rounded_below_value = floor($commuted_value);*/

                    $persioner_dob = $proposal->date_of_birth;
                    $ageValue = Util::get_years_months_days($persioner_dob, date('Y-m-d'));
                    $ratio_year = $ageValue['years']+1;

                    $commtation_data = DB::table('optcl_commutation_master')->where('age_as_next_birthday', $ratio_year)->first();
                    $commutation_ratio = !empty($commtation_data->commutation_ratio) ? $commtation_data->commutation_ratio : 0;
                    //$commutation_ratio = 8.194;
                    // Commutation Pension
                    $as_worked_out = $commuted_value*$commutation_ratio*12;
                    $rounded_as_worked_out = ceil($as_worked_out);
                    //$reduced_pension_per_month = $pension_admissible - $commuted_value;

                    $calculatedValue = '<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$ruleDetails->rule_description.'"></i> As Worked Out Rs. '.number_format($commuted_value, 2).'/- X '.$commutation_ratio.' X 12 = Rs. '.number_format($as_worked_out, 2);

                    $roundedValue = 'Rounded to: Rs. '.number_format($rounded_as_worked_out, 2);

                    $commuted_rule_two_details = $as_worked_out.'_'.$rounded_as_worked_out.'_'.$application_id.'_'.$employee_id.'_'.$rule_id."_".$rule_master->calculation_type_id;
                    $commutation_data = [
                        'calculation_li_value_two'          => $calculatedValue,
                        'calculation_rounded_li_value_two'  => $roundedValue,
                        'commuted_rule_two_details'         => $commuted_rule_two_details,
                    ];                    
                } 
            }         
        }else{
            $commutation_data = [];
        }
        return response()->json($commutation_data);
    }

    public function get_commutation_rule_three(Request $request){
        $rule_id = $request->rule_id;
        $name_value = $request->name_value;
        $commutation_data = [];

        $rule_master = DB::table('optcl_calculation_rule_master')->where('status', 1)->where('deleted', 0)->where('id', $rule_id)->first();

        if($rule_id == 4){
            // Get rule details
            $ruleDetails = DB::table('optcl_calculation_rule_master')
                                ->where('id', $rule_id)
                                ->first();
            $details = explode('_', $name_value);
            if(count($details) == 2){
                // employeeID_applicationID
                $employee_id = $details[0];
                $application_id = $details[1];
                $proposal = self::get_employee_details($employee_id);

                // Get Pension admissible value
                $pensionValue = DB::table('optcl_nominee_calculation_transaction')
                            ->select('optcl_nominee_calculation_transaction.*','optcl_calculation_rule_master.calculation_type_id')
                            ->join('optcl_calculation_rule_master', 'optcl_calculation_rule_master.id','=','optcl_nominee_calculation_transaction.rule_id')
                            ->where('optcl_nominee_calculation_transaction.application_id', $application_id)
                            ->where('optcl_nominee_calculation_transaction.nominee_master_id', $employee_id)
                            ->where('optcl_nominee_calculation_transaction.is_latest', 1)
                            ->where('optcl_calculation_rule_master.calculation_type_id', 1)
                            ->first();

                if($pensionValue){
                    // Get commutation percentage from pensioner
                    $employee_code = DB::table('optcl_pension_application_form')
                        ->where('id', $application_id)
                        ->value('employee_code');
                    $commutation_percentage = DB::table('optcl_employee_personal_details')
                        ->where('employee_code', $employee_code)
                        ->value('commutation_percentage');
                    $commutation_percentage = $commutation_percentage ? $commutation_percentage : 0;

                    $pension_admissible = $pensionValue->rounded_calculation_value;
                    //$commutation_percentage = $proposal->commutation_percentage; //51100

                    $commuted_value = $pension_admissible*($commutation_percentage/100);

                    $reduced_pension_per_month = $pension_admissible - $commuted_value;

                    $calculatedValue = '<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" id="info-title-commutation-one" data-original-title="'.$ruleDetails->rule_description.'"></i> Reduced pension per month: '.number_format($pension_admissible, 2).' -  '.number_format($commuted_value, 2).' = <strong> Rs. '.number_format($reduced_pension_per_month, 2).'/-</strong>';

                    $commuted_rule_three_details = $reduced_pension_per_month.'_'.$reduced_pension_per_month.'_'.$application_id.'_'.$employee_id.'_'.$rule_id."_".$rule_master->calculation_type_id;
                    $commutation_data = [
                        'calculation_li_value_three'  => $calculatedValue,
                        'commuted_rule_three_details' => $commuted_rule_three_details,
                    ];
                } 
            }         
        } else {
            $commutation_data = [];
        }
        return response()->json($commutation_data);
    }

    public function calculation_sheet_submitted(Request $request) {

        try {
            DB::beginTransaction();
            $application_id = $request->application_id;
            $employee_id = $request->employee_id;

            $application_form = DB::table('optcl_pension_application_form')->where('id', $application_id)->where('employee_id', $employee_id)->first();
            
            if(!empty($application_form)) {
                
                if($application_form->application_status_id == 34){
                    // HR Executive(HR Wing) Update
                    $to_update_status_id = 35;
                    $url = route('fp_hr_executive_application_details', $application_id);
                }else{
                    // DA(HR Wing) Generation
                    $to_update_status_id = 24;
                    $url = route('family_pension_hr_dealing_assistant', $application_id);
                }


                DB::table('optcl_pension_application_form')->where('id', $application_id)->where('employee_id', $employee_id)->update([
                    'application_status_id' => $to_update_status_id
                ]);
                // Status History
                DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => Auth::user()->id,
                    'application_id'    => $application_id,
                    'status_id'         => $to_update_status_id,
                    'created_at'        => $this->current_date,
                    'created_by'        => Auth::user()->id,
                ]);

                if($application_form->application_status_id == 34){
                    // Notification Area  
                    $message = "Calculation sheet updated by HR Executive. Please check the application details.";          
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
                    // Sanction Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                    NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                    // HR Wing Dealing Assistant
                    $n_user_id = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
                    NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);
                    // HR Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                    NomineeUtil::insert_notification($n_user_id, $appDetails->id, $message);

                }else{
                    // Notification Area
                    $message = "Calculation sheet generated by HR Wing Dealing Assistant. Please check the application details.";
                    $application_id = $application_form->id;
                    $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                    $pension_user_id = $appDetails->user_id;
                    // Pensioner            
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
                }
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Calculation Sheet Generated', 'url' => $url]);

            } else {
                DB::commit();
                return response()->json(['status' => 'error', 'message' => 'Application not found']);
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}