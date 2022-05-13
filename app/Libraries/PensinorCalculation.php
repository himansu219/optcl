<?php  

namespace App\Libraries;

use Illuminate\Support\Str;
use App\Libraries\Encryption;
use App\Models\GeneralSettingsModel;
use DB;
use Auth;
use Session;


class PensinorCalculation {

	/**
	 * Used this function on Form-III
	 */
	public static function calculate_completed_half_years_with_service_pension($net_qualifying_years, $net_qualifying_months, $net_qualifying_days, $last_basic_pay) {
		$total_completed_years = 0;
        $service_pension = 0;

        $completed_half_years = $net_qualifying_years * 2;

        if($completed_half_years >= 50) {
            $max_completed_years = 50;
            
            if($net_qualifying_months < 3) {
                $total_completed_years = $completed_half_years;

            } elseif(($net_qualifying_months > 3 && $net_qualifying_months < 9) || ($net_qualifying_months == 3 && $net_qualifying_days > 1)) {

                $total_completed_years = $completed_half_years + 1;

            } elseif($net_qualifying_months < 9) {

                $total_completed_years = $completed_half_years + 1;

            } elseif( ($net_qualifying_months > 9) || ($net_qualifying_months == 9 && $net_qualifying_days > 1)) {

                $total_completed_years = $completed_half_years + 2;
            }

            $service_pension = ($last_basic_pay / 2) * ($max_completed_years/50);

            if($total_completed_years >= 66)  {
                $total_completed_years = 66;
            }

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

            $service_pension = ($last_basic_pay / 2) * ($total_completed_years/50);

            $max_completed_years = $total_completed_years;
        }

        return [
        	'total_completed_years' => $total_completed_years,
        	'service_pension' => $service_pension,
        	'max_completed_years' => $max_completed_years
        ];
	}

	/**
	 * Used this function on Form-III
	 */
	public static function calculate_completed_half_years_with_dcr_gratuity($net_qualifying_years, $net_qualifying_months, $net_qualifying_days, $last_basic_pay, $proposal) {

		$completed_half_years = $net_qualifying_years * 2;
		$total_dcr_gratuity = 0;
		$total_da_amount = 0;
		$get_da_percentage = DB::table('optcl_da_master')->select('id', 'percentage_of_basic_pay')->where('status', 1)->where('deleted', 0)->whereRaw("? BETWEEN start_date AND end_date", array($proposal->date_of_retirement))->first();


        if(!empty($get_da_percentage)) {
            $total_da_amount = ($last_basic_pay * $get_da_percentage->percentage_of_basic_pay) / 100;
        }

        if($completed_half_years >= 66) {
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

        return [
        	'dcr_completed_years' => $dcr_completed_years,
        	'total_dcr_gratuity' => ceil($total_dcr_gratuity),
        	'total_da_amount' => $total_da_amount
        ];
	}

    public static function get_service_pension_due($service_form, $last_basic_pay, $form_three=array()) {

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

    public static function get_dcr_gratuity($service_form, $last_basic_pay, $total_da_amount, $form_three=array()) {

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

    public static function get_death_gratuity($service_form, $last_basic_pay, $total_da_amount) {

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

    public static function get_employee_details($employee_id) {
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
                            ->where('em.id', $employee_id)
                            ->first();
        return $proposal;
    }

    public static function fp_get_employee_details($employee_id) {
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
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type', 'o.unit_name','o.unit_name as office_last_served','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','pu.pension_unit_name')
                            ->where('em.id', $employee_id)
                            ->first();
        return $proposal;
    }

    public static function get_nominee_details($employee_id) {
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

}