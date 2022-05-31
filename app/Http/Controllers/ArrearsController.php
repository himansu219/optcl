<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;
use App\Libraries\Util;
use Session;
use Auth; 

class ArrearsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }
    public function index(Request $request){
        $applications = DB::table('optcl_arrear')
                ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_arrear.pensioner_type')
                ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_arrear.application_type')
                ->leftJoin('optcl_existing_user', function($join){
                    $join->on('optcl_existing_user.id', '=', 'optcl_arrear.application_id');
                    $join->on('optcl_existing_user.application_type', '=', 'optcl_arrear.application_type');
                })
                ->leftJoin('optcl_pension_application_form', function($join2){
                    $join2->on('optcl_pension_application_form.id', '=', 'optcl_arrear.application_id');
                    $join2->on('optcl_pension_application_form.application_type', '=', 'optcl_arrear.application_type');
                })
                ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', DB::raw('if(optcl_arrear.application_type = 1, optcl_pension_application_form.application_status_id, optcl_existing_user.application_status_id)'))
                ->select('optcl_arrear.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', DB::raw('if(optcl_arrear.application_type = 1, optcl_pension_application_form.ppo_number, optcl_existing_user.new_ppo_no) AS new_ppo_no'), 'optcl_application_status_master.status_name')
                ->where('optcl_arrear.status', 1)
                ->where('optcl_arrear.deleted', 0);

        // Old/New PPO No.
        if(!empty($request->search_ppo_no)) {
            $search_ppo_no = $request->search_ppo_no;
            //$applications = $applications->where('a.search_ppo_no', 'like', '%' . $request->search_ppo_no . '%');
            $applications = $applications->where(function($query) use($search_ppo_no) {
                $query->orWhere('optcl_existing_user.new_ppo_no', 'like', '%' . $search_ppo_no . '%');
                $query->orWhere('optcl_pension_application_form.ppo_number', 'like', '%' . $search_ppo_no . '%');
            });
        }

        $applications = $applications->orderBy('optcl_arrear.id','DESC');
        $applications = $applications->paginate(10);

        return view('user.arrears.list', compact('applications', 'request'));
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
        $due_arrear_additional_pension_amount = $request->due_arrear_additional_pension_amount;
        if($due_arrear_additional_pension_amount == ""){
            $validation['error'][] = array("id" => "due_arrear_additional_pension_amount-error","eValue" => "PPlease enter additional pension");
        }
        $due_arrear_commutation_amount = $request->due_arrear_commutation_amount;
        if($due_arrear_commutation_amount == ""){
            $validation['error'][] = array("id" => "due_arrear_commutation_amount-error","eValue" => "Please enter commutation");
        }
        $drawn_ti_percentage = $request->drawn_ti_percentage;
        if($drawn_ti_percentage == ""){
            $validation['error'][] = array("id" => "drawn_ti_percentage-error","eValue" => "Please enter TI percentage");
        }
        $drawn_besic_pension = $request->drawn_besic_pension;
        if($drawn_besic_pension == ""){
            $validation['error'][] = array("id" => "drawn_besic_pension-error","eValue" => "Please enter basic pension");
        }
        $drawn_additional_pension = $request->drawn_additional_pension;
        if($drawn_additional_pension == ""){
            $validation['error'][] = array("id" => "drawn_additional_pension-error","eValue" => "Please enter additional pension");
        }
        $drawn_commutation = $request->drawn_commutation;
        if($drawn_commutation == ""){
            $validation['error'][] = array("id" => "drawn_commutation-error","eValue" => "Please enter commutation");
        }

        // Check PPO number with other details
        /* ---------Code---------- */
        $application_id = $request->application_id;
        $application_type = $request->application_type;
        $pensioner_type = $request->pensioner_type;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                // Arrear Data
                $arreat_data = [
                    "application_type" => $application_type,
                    "pensioner_type" => $pensioner_type,
                    "application_id" => $application_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $array_id = DB::table('optcl_arrear')->insertGetId($arreat_data);
                // Arrear Section Data
                $arrear_section_data = [
                    "arraer_id"  => $array_id,
                    "from_date"  => date('Y-m-d', strtotime(str_replace("/", "-", $arraer_from_date))),
                    "to_date"  => date('Y-m-d', strtotime(str_replace("/", "-", $arrear_to_date))),
                ];
                $array_section_id = DB::table('optcl_arrear_section')->insertGetId($arrear_section_data);
                // Arrear Section Listing
                // Drawn Details
                $drawn_total_besic_amount = $drawn_besic_pension + $drawn_additional_pension;
                $drawn_ti_amount = ($drawn_total_besic_amount/100) * $drawn_ti_percentage;
                $drawn_gross_pension = $drawn_total_besic_amount + $drawn_ti_amount;
                $drawn_net_pension = ($drawn_total_besic_amount + $drawn_ti_amount) - $drawn_commutation;
                // Due Details
                $due_total_besic_amount = $due_arrear_basic_pension + $due_arrear_additional_pension_amount;
                $due_ti_amount = ($due_total_besic_amount/100) * $due_arrear_ti_percentage;
                $due_gross_pension = $due_total_besic_amount + $due_ti_amount;
                $due_net_pension = ($due_total_besic_amount + $due_ti_amount) - $due_arrear_commutation_amount;
                
                $from_year = date('Y', strtotime(str_replace("/", "-", $arraer_from_date)));
                $from_month = date('m', strtotime(str_replace("/", "-", $arraer_from_date)));
                $to_year = date('Y', strtotime(str_replace("/", "-", $arrear_to_date)));
                $to_month = date('m', strtotime(str_replace("/", "-", $arrear_to_date)));
                for($year = $from_year; $year <= $to_year; $year++){
                    if($year == $from_year){
                        $month_value = $from_month;
                    }else{
                        $month_value = 1;
                    }
                    if($year == $to_year){
                        $month_max = $to_month;
                    }else{
                        $month_max = 12;
                    }
                    for($month = $month_value; $month <= $month_max; $month++){                        
                        $arrear_section_listing = [
                            "arrear_id" => $array_id,
                            "arrear_section_id" => $array_section_id,
                            "year_value" => $year,
                            "month_value" => $month,
                            "drawn_ti_percentage" => $drawn_ti_percentage,
                            "drawn_ti_amount" => $drawn_ti_amount,
                            "drawn_basic_amount" => $drawn_besic_pension,
                            "drawn_gross_pension" => $drawn_gross_pension,
                            "drawn_additional_amount" => $drawn_additional_pension,
                            "drawn_comm_amount" => $drawn_commutation,
                            "drawn_net_pension" => $drawn_net_pension,
                            "due_ti_percentage" => $due_arrear_ti_percentage,
                            "due_ti_amount" => $due_ti_amount,
                            "due_basic_amount" => $due_arrear_basic_pension,
                            "due_gross_amount" => $due_gross_pension,
                            "due_additional_amount" => $due_arrear_additional_pension_amount,
                            "due_comm_amount" => $drawn_commutation,
                            "due_net_pension" => $due_net_pension,
                            "created_by" => Auth::user()->id,
                            "created_at" => $this->current_date,
                        ];
                        DB::table('optcl_arrear_section_list')->insert($arrear_section_listing);
                    }
                }
                //dd($request->all());    
                $section_list = DB::table('optcl_arrear_section_list')->where('status', 1)->where('status', 1)->get();
                $results = "";
                $i = 1;
                foreach($section_list as $key=>$section_data){
                    $results .= "<tr>";
                    $results .= "<td>".($i++)."</td>";
                    $results .= "<td>".$section_data->drawn_ti_percentage."</td>";
                    $results .= "<td>".$section_data->due_ti_percentage."</td>";
                    $results .= "<td>".date('M',$section_data->month_value)."-".$section_data->year_value."</td>";
                    $results .= "<td>".$section_data->due_basic_amount."</td>";
                    $results .= "<td>".$section_data->due_ti_amount."</td>";
                    $results .= "<td>".$section_data->due_gross_amount."</td>";
                    $results .= "<td>".$section_data->due_comm_amount."</td>";
                    $results .= "<td>".$section_data->due_net_pension."</td>";
                    $results .= "<td>".$section_data->drawn_basic_amount."</td>";
                    $results .= "<td>".$section_data->drawn_ti_amount."</td>";
                    $results .= "<td>".$section_data->drawn_gross_pension."</td>";
                    $results .= "<td>".$section_data->drawn_comm_amount."</td>";
                    $results .= "<td>".$section_data->drawn_net_pension."</td>";
                    $results .= "</tr>";
                }     
                $validation['results'] = $results;
                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function arrear_data_details($appID){
        //dd(123);
        $cr_data = DB::table('optcl_arrear')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){

            return view('user.arrears.arrear_listing');
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('billing_officer_arrears');
        }
    }

}

       
?>