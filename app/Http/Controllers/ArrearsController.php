<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;

class ArrearsController extends Controller
{

    public function index(){
        return view('user.arrears.list');
    }

    public function add(){
        return view('user.arrears.add');
    }
    // Pensioner Details
    public function pensioner_details(Request $request){
        $ppo_no = $request->ppo_no;
        $response = [];
        //DB::enableQueryLog();
        $pensioner_data = DB::table('optcl_ppo_no_list AS pnl')
                ->leftJoin('optcl_pension_application_form AS np', function($join){
                    $join->on('np.application_type', '=', 'pnl.application_type');
                    $join->on('np.pension_type_id', '=', 'pnl.pensioner_type');
                    $join->on('np.id', '=', 'pnl.application_id');
                })
                ->leftJoin('optcl_existing_user AS ep', function($join2){
                    $join2->on('ep.application_type', '=', 'pnl.application_type');
                    $join2->on('ep.pensioner_type', '=', 'pnl.pensioner_type');
                    $join2->on('ep.id', '=', 'pnl.application_id');
                })
                ->where('pnl.ppo_no', $ppo_no)
                ->first();
        //dd(DB::getQueryLog(), $pensioner_data);
        if($pensioner_data){
            $response = [
            "basic_amount" => $pensioner_data->basic_amount,
            "employee_no" => $pensioner_data->employee_code ? $pensioner_data->employee_code : 'NA',
            "pensioner_name" => $pensioner_data->pensioner_name,
            "application_type" => $pensioner_data->application_type,
            "pensioner_type" => $pensioner_data->pensioner_type,
            "application_id" =>  $pensioner_data->application_id,
            ];
        }
        return response()->json($response);
    }
    // Form Submission
    public function arrear_submission(Request $request){
        $validation = [];
        $arrear_ppo_no = $request->arrear_ppo_no;
        if($arrear_ppo_no == ""){
            $validation['error'][] = array("id" => "arrear_ppo_no-error","eValue" => "Please enter PPO no");
        }
        $arrear_pensioner_name = $request->arrear_pensioner_name;
        if($arrear_pensioner_name == ""){
            $validation['error'][] = array("id" => "arrear_pensioner_name-error","eValue" => "Please enter pensioner name");
        }
        $arraer_from_date = $request->arraer_from_date;
        if($arraer_from_date == ""){
            $validation['error'][] = array("id" => "arraer_from_date-error","eValue" => "Please select from date");
        }
        $arrear_to_date = $request->arrear_to_date;
        if($arrear_to_date == ""){
            $validation['error'][] = array("id" => "arrear_to_date-error","eValue" => "Please select to date");
        }
        $due_arrear_ti_percentage = $request->due_arrear_ti_percentage;
        if($due_arrear_ti_percentage == ""){
            $validation['error'][] = array("id" => "due_arrear_ti_percentage-error","eValue" => "Please enter TI percentage");
        }
        $due_arrear_basic_pension = $request->due_arrear_basic_pension;
        if($due_arrear_basic_pension == ""){
            $validation['error'][] = array("id" => "due_arrear_basic_pension-error","eValue" => "Please enter basic pension");
        }
        $due_arrear_ti_percentage = $request->due_arrear_ti_percentage;
        if($due_arrear_ti_percentage == ""){
            $validation['error'][] = array("id" => "due_arrear_ti_percentage-error","eValue" => "Please enter TI percentage");
        }
        $due_arrear_ti_percentage = $request->due_arrear_ti_percentage;
        if($due_arrear_ti_percentage == ""){
            $validation['error'][] = array("id" => "due_arrear_ti_percentage-error","eValue" => "Please enter TI percentage");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->dropped_case_death_case_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $dcdc_ppo_number,
                    "pensioner_emp_no" => $dcdc_pension_emp_no,
                    "pensioner_name" =>  $dcdc_name_pensioner,
                    "dod" => date('Y-m-d', strtotime(str_replace("/", "-", $dcdc_dod))),
                    "remark_value" => $dcdc_remark,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_dropped_death_case')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);
                // Update Monthly Changed Data
                $ppo_details = DB::table('optcl_ppo_no_list')
                                ->where('ppo_no', $dcdc_ppo_number)
                                ->where('status', 1)
                                ->where('deleted', 0)
                                ->first();
                if($ppo_details){
                    $application_type = $ppo_details->application_type;
                    $pensioner_type = $ppo_details->pensioner_type;
                    $application_id = $ppo_details->application_id;
                    $monthly_changed_data_revised_pension = [
                        'appliation_type' => $application_type,
                        'pensioner_type' => $pensioner_type,
                        'is_changed_request' => 1,
                        'cr_type_id' => 3,
                        'application_id' => $application_id,
                        'pension_unit_id' => Auth::user()->pension_unit_id,
                        'created_by' => Auth::user()->id,
                        'created_at' => $this->current_date,
                    ];
                    $monthly_changed_data_id = DB::table('optcl_monthly_changed_data')->insertGetId($monthly_changed_data_revised_pension);
                    // Mothers Changed Data Mapping
                    if($application_type == 1 && $pensioner_type == 1){
                        $application_pensioner_type_id = 1;
                    }else if($application_type == 1 && $pensioner_type == 2){
                        $application_pensioner_type_id = 2;
                    }else if($application_type == 2 && $pensioner_type == 1){
                        $application_pensioner_type_id = 3;
                    }else{
                        $application_pensioner_type_id = 4;
                    }
                    $monthlyChangedMappingData = [
                        "monthly_changed_data_id"   => $monthly_changed_data_id,
                        "application_pensioner_type_id"   => $application_pensioner_type_id,
                        "application_id"    => $application_id,
                        "created_by"        => Auth::user()->id,
                        "created_at"        => $this->current_date,
                    ];
                    DB::table('optcl_application_monthly_changed_data_mapping')->insert($monthlyChangedMappingData);
                }

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

}
?>