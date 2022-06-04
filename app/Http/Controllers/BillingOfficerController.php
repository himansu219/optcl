<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Religion;
use App\Models\OfficeLastServed;
use App\Models\PensionerDesignation;
use App\Models\Pensionform;
use App\Models\PensionDocument;
use App\Models\PersonalDetails;
use App\Libraries\Util;
use Session;
use Auth;


class BillingOfficerController extends Controller { 
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function approval_list(Request $request){
        $user = Auth::user();
        DB::enableQueryLog();
        $pension_unit_id = $user->pension_unit_id;
        //dd($user);
        // Here we get list for service pensioner and family pensioner by left JOIN operation

        
        // Here we get list for service pensioner and family pensioner by left JOIN operation
        $applications = DB::table('optcl_monthly_changed_data')
                    ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_monthly_changed_data.pensioner_type')
                    ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_monthly_changed_data.appliation_type')
                    ->leftJoin('optcl_existing_user', function($join){
                        $join->on('optcl_existing_user.id', '=', 'optcl_monthly_changed_data.application_id');
                        $join->on('optcl_existing_user.application_type', '=', 'optcl_monthly_changed_data.appliation_type');
                    })
                    ->leftJoin('optcl_pension_application_form', function($join2){
                        $join2->on('optcl_pension_application_form.id', '=', 'optcl_monthly_changed_data.application_id');
                        $join2->on('optcl_pension_application_form.application_type', '=', 'optcl_monthly_changed_data.appliation_type');
                    })
                    ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', DB::raw('if(optcl_monthly_changed_data.appliation_type = 1, optcl_pension_application_form.application_status_id, optcl_existing_user.application_status_id)'))
                    ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', DB::raw('if(optcl_monthly_changed_data.appliation_type != 1, optcl_existing_user.new_ppo_no, optcl_pension_application_form.ppo_number) AS new_ppo_no'), DB::raw('if(optcl_monthly_changed_data.appliation_type != 1, optcl_existing_user.pensioner_name, (select CONCAT(COALESCE(optcl_users.first_name, ""), " ", COALESCE(optcl_users.last_name, "")) AS full_name FROM  optcl_users where optcl_users.id = optcl_pension_application_form.user_id)) AS any_pensioner_name'), 'optcl_application_status_master.status_name')
                    ->where('optcl_monthly_changed_data.is_pension_unit_checked', 1)
                    ->where('optcl_monthly_changed_data.is_billing_officer_approved', 0)
                    ->where('optcl_monthly_changed_data.status', 1)
                    ->where('optcl_monthly_changed_data.deleted', 0);

        // Old/New PPO No.
        if(!empty($request->application_no)) {
            $application_no = $request->application_no;
            //$applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
            $applications = $applications->where(function($query) use($application_no) {
                $query->orWhere('optcl_existing_user.old_ppo_no', 'like', '%' . $application_no . '%');
                $query->orWhere('optcl_existing_user.new_ppo_no', 'like', '%' . $application_no . '%');
                $query->orWhere('optcl_pension_application_form.ppo_number', 'like', '%' . $application_no . '%');
            });
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where(function($query) use($application_no) {
                $query->orWhere('optcl_existing_user.employee_code', 'like', '%' . $request->employee_code . '%');
                $query->orWhere('optcl_pension_application_form.employee_code', 'like', '%' . $request->employee_code. '%');
            });
        }

        if(!empty($request->employee_aadhaar_no)) {
            $employee_aadhaar_no = $request->employee_aadhaar_no;
            //$applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
            $applications = $applications->where(function($query) use($employee_aadhaar_no) {
                $query->orWhere('optcl_existing_user.aadhaar_no', 'like', '%' . $employee_aadhaar_no . '%');
                $query->orWhere('optcl_existing_user.mobile_number', 'like', '%' . $employee_aadhaar_no . '%');
                $query->orWhere('optcl_pension_application_form.employee_aadhaar_no', 'like', '%' . $employee_aadhaar_no . '%');
            });
        }    
        
        if(!empty($request->app_status_id)) {
            //$applications = $applications->where('optcl_existing_user.application_status_id',  $request->app_status_id);
            $applications = $applications->where(function($query){
                $query->orWhere('optcl_existing_user.application_status_id', 'like', '%' . $request->app_status_id . '%');
                $query->orWhere('optcl_pension_application_form.application_status_id', 'like', '%' . $request->app_status_id. '%');
            });
        }   
        $applications = $applications->orderBy('optcl_monthly_changed_data.id','DESC');
        $applications = $applications->paginate(10);
        //dd(DB::getQueryLog(), $applications);

        $statuslist = DB::table('optcl_application_status_master')
                            ->where('status', 1)
                            ->where('deleted', 0)->get();

        return view('user.billing_officer.approval-application-list', compact('applications', 'request', 'statuslist'));
    }

    public function history(Request $request){
        $user = Auth::user();
        //DB::enableQueryLog();
        //dd($user);
        // Here we get list for service pensioner and family pensioner by left JOIN operation
        $applications = DB::table('optcl_monthly_changed_data')
                    ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_monthly_changed_data.pensioner_type')
                    ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_monthly_changed_data.appliation_type')
                    ->leftJoin('optcl_existing_user', function($join){
                        $join->on('optcl_existing_user.id', '=', 'optcl_monthly_changed_data.application_id');
                        $join->on('optcl_existing_user.application_type', '=', 'optcl_monthly_changed_data.appliation_type');
                    })
                    ->leftJoin('optcl_pension_application_form', function($join2){
                        $join2->on('optcl_pension_application_form.id', '=', 'optcl_monthly_changed_data.application_id');
                        $join2->on('optcl_pension_application_form.application_type', '=', 'optcl_monthly_changed_data.appliation_type');
                    })
                    ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', DB::raw('if(optcl_monthly_changed_data.appliation_type = 1, optcl_pension_application_form.application_status_id, optcl_existing_user.application_status_id)'))
                    ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', DB::raw('if(optcl_monthly_changed_data.appliation_type = 1, optcl_pension_application_form.ppo_number, optcl_existing_user.new_ppo_no) AS new_ppo_no'), 'optcl_application_status_master.status_name')
                    ->where('optcl_monthly_changed_data.is_pension_unit_checked', 1)
                    ->where('optcl_monthly_changed_data.is_billing_officer_approved', 1)
                    ->where('optcl_monthly_changed_data.status', 1)
                    ->where('optcl_monthly_changed_data.deleted', 0);

        // Old/New PPO No.
        if(!empty($request->application_no)) {
            $application_no = $request->application_no;
            //$applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
            $applications = $applications->where(function($query) use($application_no) {
                $query->orWhere('optcl_existing_user.old_ppo_no', 'like', '%' . $application_no . '%');
                $query->orWhere('optcl_existing_user.new_ppo_no', 'like', '%' . $application_no . '%');
                $query->orWhere('optcl_pension_application_form.ppo_number', 'like', '%' . $application_no . '%');
            });
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where(function($query) use($application_no) {
                $query->orWhere('optcl_existing_user.employee_code', 'like', '%' . $request->employee_code . '%');
                $query->orWhere('optcl_pension_application_form.employee_code', 'like', '%' . $request->employee_code. '%');
            });
        }

        if(!empty($request->employee_aadhaar_no)) {
            $employee_aadhaar_no = $request->employee_aadhaar_no;
            //$applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
            $applications = $applications->where(function($query) use($employee_aadhaar_no) {
                $query->orWhere('optcl_existing_user.aadhaar_no', 'like', '%' . $employee_aadhaar_no . '%');
                $query->orWhere('optcl_existing_user.mobile_number', 'like', '%' . $employee_aadhaar_no . '%');
                $query->orWhere('optcl_pension_application_form.employee_aadhaar_no', 'like', '%' . $employee_aadhaar_no . '%');
            });
        }    
        
        if(!empty($request->app_status_id)) {
            //$applications = $applications->where('optcl_existing_user.application_status_id',  $request->app_status_id);
            $applications = $applications->where(function($query){
                $query->orWhere('optcl_existing_user.application_status_id', 'like', '%' . $request->app_status_id . '%');
                $query->orWhere('optcl_pension_application_form.application_status_id', 'like', '%' . $request->app_status_id. '%');
            });
        } 

        $applications = $applications->orderBy('optcl_monthly_changed_data.id','DESC');
        $applications = $applications->paginate(10);
        //dd($applications);

        $statuslist = DB::table('optcl_application_status_master')
                            ->where('status', 1)
                            ->where('deleted', 0)->get();
        return view('user.billing_officer.application-history-list', compact('applications', 'request', 'statuslist'));
    }

    public function multiple_application_assignment(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_id_list = $request->application_id_list;
            $remarks = $request->remarks;

            $applications = explode(',', $application_id_list);
            foreach($applications as $application_id){
                //dd($application_id);
                /* 
                   (montly-changed-id)_(application-id)_(pernsioner-type)_(application-type)
                */
                $explde_data = explode('_', $application_id);
                $montly_changed_id = $explde_data[0];
                $application_id = $explde_data[1];
                $pernsioner_type = $explde_data[2];
                $application_type = $explde_data[3];
                // Check the application and pension type by which we can get the pensioner details according to type id
                $application_type_details = DB::table('optcl_application_monthly_changed_data_mapping')
                                            ->where('monthly_changed_data_id', $montly_changed_id)
                                            ->first();
                if($application_type_details){
                    if($application_type_details->application_pensioner_type_id == 1 || $application_type_details->application_pensioner_type_id == 2){
                        // 	Service Pensioner(New) || Family Pensioner(New)
                        DB::table('optcl_pension_application_form')->where('id', $application_id)->update(['application_status_id' => 51]);
                    }else{
                        // Service Pensioner(Existing) || Family Pensioner(Existing)
                        // Here we have updated the persion unit user checked status    
                        DB::table('optcl_existing_user')->where('id', $application_id)->update(['application_status_id' => 51]);
                    }
                    DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                }
                DB::table('optcl_application_status_history')->insert([
                    'is_new'            => 0,
                    'user_id'           => $user->id,
                    'application_id'    => $application_id,
                    'status_id'         => 51,
                    'remarks'           => $remarks,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                ]);

                /* // Notification Area
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                // Pensioner
                $message = "Application assigned by Sanctioning Authority. Please check the application details.";
                Util::insert_notification($appDetails->user_id, $appDetails->id, $message);
                // Dealing Assistant
                $message = "Application assigned by Sanctioning Authority with application no ".$appDetails->application_no.". Please check the application details.";
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $appDetails->user_id)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Unit Head
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // HR wing Dealing Assistant
                Util::insert_notification($dealing_assistant_list, $appDetails->id, $message); */
            }
            
            DB::commit();
            Session::flash('success', 'Application(s) is/are approved successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function list(Request $request){
        $user = Auth::user();
        //DB::enableQueryLog();
        $pension_unit_id = $user->pension_unit_id;
        //dd($user);
        // Here we get list for service pensioner and family pensioner by left JOIN operation
        $applications = DB::table('optcl_monthly_changed_data')
                        ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_monthly_changed_data.pensioner_type')
                        ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_monthly_changed_data.appliation_type')
                        ->join('optcl_existing_user', 'optcl_existing_user.id', '=', 'optcl_monthly_changed_data.application_id')
                        ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_existing_user.application_status_id')
                        ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', 'optcl_existing_user.new_ppo_no', 'optcl_application_status_master.status_name')
                        ->where('optcl_monthly_changed_data.is_pension_unit_checked', 1)
                        ->where('optcl_monthly_changed_data.is_billing_officer_approved', 1)
                        ->where('optcl_monthly_changed_data.status', 1)
                        ->where('optcl_monthly_changed_data.deleted', 0);

        /*if(!empty($request->application_no)) {
            $applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where('b.employee_code', 'like', '%' . $request->employee_code . '%');
        }

        if(!empty($request->employee_aadhaar_no)) {
            $applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
        }*/        
        $applications = $applications->orderBy('optcl_monthly_changed_data.id','DESC');
        $applications = $applications->paginate(10);

        $statuslist = DB::table('optcl_application_status_master')
                            ->where('status', 1)
                            ->where('deleted', 0)->get();
        return view('user.billing_officer.application-list', compact('applications', 'request', 'statuslist'));
    }

    public function get_net_amount_details(Request $request) {
        //dd($request);
        $response = [];
        $application_id = $request->application_id;
        $applicationDetails = DB::table('optcl_existing_user')->where('id', '=', $application_id)->first();
        if($applicationDetails){
            $basic_amount = $applicationDetails->basic_amount;
            $additional_pension_amount = $applicationDetails->additional_pension_amount == NULL ? 0 : $applicationDetails->additional_pension_amount;
            $ti_amount = $applicationDetails->ti_amount;
            $total = $basic_amount + $additional_pension_amount + $ti_amount;
            $response = [
                "basic_amount" => $basic_amount,
                "additional_pension_amount" => $additional_pension_amount,
                "ti_amount" => $ti_amount, 
                "total" => $total,
            ];
        }
        return response()->json($response);
    }

    public function show_net_pension($appID) {
        //dd($appID);
        $application_id = $appID;
        $applicationDetails = DB::table('optcl_existing_user')
                                ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_existing_user.pensioner_type')
                                ->select('optcl_existing_user.*', 'optcl_pension_type_master.pension_type')
                                ->where('optcl_existing_user.id', '=', $application_id)
                                ->first();
        $response = [];
        $commutations = [];
        $taxList = [];
        if($applicationDetails){
            $basic_amount = $applicationDetails->basic_amount;
            $additional_pension_amount = $applicationDetails->additional_pension_amount == NULL ? 0 : $applicationDetails->additional_pension_amount;
            $ti_amount = $applicationDetails->ti_amount;
            $total = $basic_amount + $additional_pension_amount + $ti_amount;
            $response = [
                "pensioner_name" => $applicationDetails->pensioner_name,
                "ppo_number" => $applicationDetails->new_ppo_no,
                "pension_type" => $applicationDetails->pension_type,
                "basic_amount" => $basic_amount,
                "additional_pension_amount" => $additional_pension_amount,
                "ti_amount" => $ti_amount, 
                "total" => $total,
                "taxable_amount" => $applicationDetails->taxable_amount,
                "application_id" => $applicationDetails->id,
                "application_type" => 2,
                "pension_type_id" => $applicationDetails->pensioner_type,
            ];
            $commutations = DB::table('optcl_existing_user_commutation')->where('existing_user_id', '=', $application_id)->get();
            // Tax list
            if($applicationDetails->tax_type_id == 1){
                // New Regime
                $taxList = DB::table('optcl_tax_slab_master')
                            ->where('is_new', $applicationDetails->tax_type_id)
                            ->get();
            }else{
                // Old Regime
                $from = $applicationDetails->date_of_birth;
                $to = date('Y-m-d');
                $age_data = Util::get_years_months_days($from, $to);
                $age_value = $age_data['years'];
                //dd($age_data['years']);
                DB::enableQueryLog();
                $taxList = DB::table('optcl_tax_slab_master')
                            //->where(DB::raw($age_value between ('from_age', 'to_age')))
                            ->whereRaw("('".$age_value."' between `from_age` and `to_age`)")
                            //->where('to_age', '<=', $age_value)
                            ->where('is_new', $applicationDetails->tax_type_id)
                            ->get();
                //dd(DB::getQueryLog(), $taxList);
            }
            $tax_calculation_details = DB::table('optcl_taxable_details_master')->where('id', 1)->first();
        }
        //dd($response);
        return view('user.billing_officer.net-pension-details', compact('response', 'commutations', 'taxList', 'tax_calculation_details'));
    }

    public function show_net_pension_view($appID) {
        //dd($appID);
        $application_id = $appID;
        $applicationDetails = DB::table('optcl_existing_user')
                                ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_existing_user.pensioner_type')
                                ->select('optcl_existing_user.*', 'optcl_pension_type_master.pension_type')
                                ->where('optcl_existing_user.id', '=', $application_id)
                                ->first();
        $response = [];
        $commutations = [];
        $taxList = [];
        if($applicationDetails){
            
            // Net Pension Details
            $net_pension_details = DB::table('optcl_net_pension_details')
                                    ->where('application_type', 2)
                                    ->where('pension_type', $applicationDetails->pensioner_type)
                                    ->where('application_id', $applicationDetails->id)
                                    ->first();
            // Net pension commutation list
            $net_pension_commutation_list = DB::table('optcl_net_pension_commutation_list')
                                                ->where('net_pension_details_id', $net_pension_details->id)
                                                ->get();
            // Tax list
            $net_pension_tax_list = DB::table('optcl_net_pension_tax_details')
                                                ->where('net_pension_details_id', $net_pension_details->id)
                                                ->get();
            // Tax list
            if($applicationDetails->tax_type_id == 1){
                // New Regime
                $taxList = DB::table('optcl_tax_slab_master')
                            ->where('is_new', $applicationDetails->tax_type_id)
                            ->get();
            }else{
                // Old Regime
                $from = $applicationDetails->date_of_birth;
                $to = date('Y-m-d');
                $age_data = Util::get_years_months_days($from, $to);
                $age_value = $age_data['years'];
                //dd($age_data['years']);
                DB::enableQueryLog();
                $taxList = DB::table('optcl_tax_slab_master')
                            //->where(DB::raw($age_value between ('from_age', 'to_age')))
                            ->whereRaw("('".$age_value."' between `from_age` and `to_age`)")
                            //->where('to_age', '<=', $age_value)
                            ->where('is_new', $applicationDetails->tax_type_id)
                            ->get();
                //dd(DB::getQueryLog(), $taxList);
            }
            $tax_calculation_details = DB::table('optcl_taxable_details_master')->where('id', 1)->first();
        }
        //dd($response);
        return view('user.billing_officer.net-pension-details-view', compact('applicationDetails', 'net_pension_details', 'net_pension_commutation_list', 'net_pension_tax_list'));
    }

    public function save_net_pension(Request $request) {       
        
        //dd($request->all());

        $validation = [];
        $trust_recovery_amount = $request->trust_recovery_amount;
        if($trust_recovery_amount == ""){
            $validation['error'][] = array("id" => "trust_recovery_amount-error","eValue" => "Please enter trust recovery amount");
        }
        $other_recovery_amount = $request->other_recovery_amount;
        if($other_recovery_amount == ""){
            $validation['error'][] = array("id" => "other_recovery_amount-error","eValue" => "Please enter other recovery amount");
        }
        $tds_value = $request->tds_value;
        if($tds_value == ""){
            $validation['error'][] = array("id" => "tds_value-error","eValue" => "Please enter TDS amount");
        }
        
        if(!isset($validation['error'])){
            try{
                //DB::beginTransaction();
                $pensionAmountDetails = [
                    "application_type"              => $request->application_type,
                    "pension_type"                  => $request->pensioner_type,
                    "application_id"                => $request->application_id,
                    "basic_amount"                  => $request->gross_basic,
                    "additional_amount"             => $request->additional_pension,
                    "ti_amount"                     => $request->ti_amount,
                    "gross_pension_amount"          => $request->gross_pension,
                    "trust_recovery_amount"         => $trust_recovery_amount,
                    "other_recovery_amount"         => $other_recovery_amount,
                    "net_pension_amount"            => $request->net_pension,
                    "tds_amount"                    => $tds_value,
                    "rebate_amount"                 => $request->hidden_rebet_amount,
                    "health_education_percentage"   => $request->hidden_h_e_cess_percentage,
                    "health_education_amount"       => $request->hidden_h_e_cess_value,
                    "tot_tax_amount"                => $request->hidden_total_tax,
                    "tot_tax_payable_anually"       => $request->hidden_total_tax_payable,
                    "created_by"                    => Auth::user()->id,
                    "created_at"                    => $this->current_date,
                ];
                $net_pension_id = DB::table('optcl_net_pension_details')->insertGetId($pensionAmountDetails);
                // Net Pension Commutation list
                $commutations = $request->commutation_id;
                foreach($commutations as $key=>$commutation){
                    $comm_data = [
                        "net_pension_details_id" => $net_pension_id,
                        "comm_id" => $request->commutation_id[$key],
                        "comm_date" => $request->commutation_date[$key],
                        "comm_amount" => $request->commutation_amount[$key],
                        "created_by" => Auth::user()->id,
                        "created_at" => $this->current_date,
                    ];
                    DB::table('optcl_net_pension_commutation_list')->insert($comm_data);
                }
                // All Tax details
                $hidden_tax_froms = $request->hidden_tax_from;
                foreach($hidden_tax_froms as $key=>$hidden_tax_data){
                    $tax_data = [
                        "net_pension_details_id"    => $net_pension_id,
                        "tax_stab_id"               => $request->hidden_tax_id[$key],
                        "tax_slab_from"             => $request->hidden_tax_from[$key],
                        "tax_slab_to"               => $request->hidden_tax_to[$key],
                        "tax_slab_per"              => $request->hidden_tax_percentage[$key],
                        "tax_slab_amount"           => $request->hidden_tax_per_value[$key],
                        "created_by"                => Auth::user()->id,
                        "created_at"                => $this->current_date,
                    ];
                    DB::table('optcl_net_pension_tax_details')->insert($tax_data);
                }

                Session::flash('success','Net pension calculated successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }


}