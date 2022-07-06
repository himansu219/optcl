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
use App\Libraries\Beneficiary;
use Session;
use Auth;
use App\Exports\BillExport;
use Maatwebsite\Excel\Facades\Excel;


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
                    ->leftJoin('optcl_change_data_master', function($join3){
                        $join3->on('optcl_change_data_master.id', '=', 'optcl_monthly_changed_data.cr_type_id');
                        $join3->on('optcl_monthly_changed_data.is_changed_request', '=', DB::raw(1));
                    })
                    ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', DB::raw('if(optcl_monthly_changed_data.appliation_type = 1, optcl_pension_application_form.application_status_id, optcl_existing_user.application_status_id)'))
                    ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', DB::raw('if(optcl_monthly_changed_data.appliation_type != 1, optcl_existing_user.new_ppo_no, optcl_pension_application_form.ppo_number) AS new_ppo_no'), DB::raw('if(optcl_monthly_changed_data.appliation_type != 1, optcl_existing_user.pensioner_name, (select CONCAT(COALESCE(optcl_users.first_name, ""), " ", COALESCE(optcl_users.last_name, "")) AS full_name FROM  optcl_users where optcl_users.id = optcl_pension_application_form.user_id)) AS any_pensioner_name'), 'optcl_application_status_master.status_name', DB::raw('if(optcl_monthly_changed_data.is_changed_request = 1, optcl_change_data_master.change_type, "NA") AS change_type'))
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

    public function monthly_changed_data_approval_service_pensioner(Request $request) {
        // This is only for Service Pensioner (New User)
        try {
            DB::beginTransaction();
            $user = Auth::user();            
            
                //dd($request);
                /* 
                   (montly-changed-id)_(application-id)_(pernsioner-type)_(application-type)
                */
                $remarks = $request->remarks;
                $montly_changed_id = $request->monthly_changed_data_id;
                $application_id = $request->application_id;
                $pernsioner_type = $request->pensioner_type;
                $application_type = $request->application_type;
                // Check the application and pension type by which we can get the pensioner details according to type id
                $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                                ->where('id', $montly_changed_id)
                                                ->first();
                if($monthly_changed_data){
                    // Application status update
                    DB::table('optcl_existing_user')->where('id', $application_id)->update(['application_status_id' => 51]);
                    // Monthly changed data update
                    DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                    // Get Beneficiary Details from User Table
                    $application_details = DB::table('optcl_existing_user')
                                                ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id', '=', 'optcl_existing_user.bank_branch_id')
                                                ->join('optcl_bank_master', 'optcl_bank_master.id', '=', 'optcl_bank_branch_master.bank_id')
                                                ->select('optcl_existing_user.*', 'optcl_bank_branch_master.branch_name', 'optcl_bank_branch_master.ifsc_code','optcl_bank_branch_master.address','optcl_bank_master.bank_name')
                                                ->where('optcl_existing_user.id', $application_id)
                                                ->where('optcl_existing_user.status', 1)->where('optcl_existing_user.deleted', 0)
                                                ->first();
                    $user_id = $application_details->user_id;
                    //$sanction_order_file_path = $application_details->sanction_order_file_path;
                    //$gratuity_sanction_order_file_path = $application_details->gratuity_sanction_order_file_path;
                    //$ppo_order_file_path = $application_details->ppo_order_file_path;
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
                    $total_income_month = $application_details->total_income_month;
                    $taxable_amount = $application_details->taxable_amount;

                    $user_details = DB::table('optcl_users')
                                        ->select('optcl_users.aadhaar_no', 'optcl_users.mobile', 'optcl_users.email_id', 'optcl_users.optcl_unit_id', 'optcl_users.optcl_unit_id', DB::raw('CONCAT(COALESCE(optcl_users.first_name, ""), " ", COALESCE(optcl_users.last_name, "")) AS full_name'))
                                        ->where('id', $user_id)
                                        ->where('status', 1)->where('deleted', 0)
                                        ->first();
                    //dd($user_details);
                    $full_name = $application_details->pensioner_name;
                    $aadhaar_no = $application_details->aadhar_no;
                    $mobile = $application_details->mobile_number;
                    $optcl_unit_id = NULL;
                    $pension_unit_id = $application_details->pension_unit_id;
                    $pan_no = $application_details->pan_no;
                    $new_ppo_number = $application_details->new_ppo_no;

                    // Service Pensioner PAN Details
                    /* $employee_personal_details = DB::table('optcl_employee_personal_details AS ep')
                                                    ->select('ep.*', 'bbm.branch_name', 'bbm.ifsc_code','bbm.address','bm.bank_name')
                                                    ->join('optcl_bank_branch_master AS bbm', 'bbm.id', '=', 'ep.bank_branch_id')
                                                    ->join('optcl_bank_master AS bm', 'bm.id', '=', 'bbm.bank_id')
                                                    ->where('ep.id', $application_id)
                                                    ->where('ep.status', 1)->where('ep.deleted', 0)
                                                    ->first(); */
                    $emp_pan = $application_details->pan_no;
                    $savings_bank_account_no = $application_details->acc_number;
                    $bank_branch_id = $application_details->bank_branch_id;
                    $branch_name = $application_details->branch_name;
                    $ifsc_code = $application_details->ifsc_code;
                    $address = $application_details->address;
                    $bank_name = $application_details->bank_name;
                    $date_of_retirement = $application_details->date_of_retirement;
                    // Service Pensioner Retirement Date                    
                    /* $retirementDetails = DB::table('optcl_employee_master')
                                        ->where('id', $application_details->employee_id)
                                        ->where('status', 1)->where('deleted', 0)
                                        ->first(); */
                    
                    //dd($user_details);
                    // Pension Calculation Details
                    $pension_amount_details = DB::table('optcl_net_pension_details')
                                        ->where('application_type', $application_type)
                                        ->where('pension_type', $pernsioner_type)
                                        ->where('application_id', $application_id)
                                        ->where('status', 1)->where('deleted', 0)
                                        ->first();
                    //dd($pension_amount_details, $application_id, $application_type, $pernsioner_type);
                    if($pension_amount_details){
                        $net_pension_amount = $pension_amount_details->net_pension_amount;
                    }else{
                        //dd(123);
                        DB::rollback();
                        Session::flash('error','Something went wrong!');
                        return redirect()->back();
                    }
                    
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
                        "pensioner_name" => $full_name,
                        "pensioner_aadhaar" => $aadhaar_no,
                        "pensioner_pan" => $pan_no,
                        "pensioner_mobile" => $mobile,
                        "optcl_unit_id" => $optcl_unit_id,
                        "pension_unit_id" => $monthly_changed_data->pension_unit_id,
                        "ppo_no" => $application_details->new_ppo_no,
                        "date_of_retirement" => $date_of_retirement,
                        //"date_of_death" => ,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                    ];
                    $beneficiary_id = DB::table('optcl_beneficiary_details')->insertGetId($bData);
                    // Beneficiary Details History Storage
                    $bHisData = [
                        "beneficiary_id" => $beneficiary_id,
                        "pensioner_name" => $full_name,
                        "pensioner_aadhaar" => $aadhaar_no,
                        "pensioner_pan" => $pan_no,
                        "pensioner_mobile" => $mobile,
                        "optcl_unit_id" => $optcl_unit_id,
                        "pension_unit_id" => $monthly_changed_data->pension_unit_id,
                        "ppo_no" => $new_ppo_number,
                        "date_of_retirement" => $date_of_retirement,
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
                    if($monthly_changed_data->appliation_type == 1){
                        // Beneficiary Documents for New Pensioner
                        $ben_doc_data = [
                            "beneficiary_id" => $beneficiary_id,
                            "sanction_order_file_path" => $sanction_order_file_path,
                            "gratuity_sanction_order_file_path" => $gratuity_sanction_order_file_path,
                            "ppo_order_file_path" => $ppo_order_file_path,
                            'created_at'        => $this->current_date,
                            'created_by'        => $user->id,
                        ];
                        DB::table('optcl_beneficiary_documents')->insert($ben_doc_data);
                    }
                    
                    // Pension Amount Details
                    $ben_pen_amount_data = [
                        "beneficiary_id"    => $beneficiary_id,
                        "basic_amount"                      => $basic_amount,
                        "basic_amount_effective_from"       => $basic_effective_date,
                        "additional_pension_amount"         => $additional_pension_amount,
                        "additional_pension_effective_from" => $additional_pension_effective_date,
                        "enhanced_pension_amount"           => $enhanced_pension_amount,
                        "enhanced_pension_effective_from"   => $enhanced_pension_effective_date,
                        "enhanced_pension_effective_to"     => $enhanced_pension_end_date,
                        "normal_pension_amount"             => $normal_pension_amount,
                        "normal_pension_effective_from"     => $normal_pension_effective_date,
                        "gross_pension_amount"              => $gross_pension_amount,
                        "total_income"                      => $total_income,
                        "taxable_amount"                    => $taxable_amount,
                        "pension_amount"                    => $net_pension_amount,
                        "created_at"                        => $this->current_date,
                        "created_by"                        => $user->id,
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
           
            
            DB::commit();
            Session::flash('success', 'Application(s) is/are approved successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }
    // Monthly Changed Data Service Pensioner
    public function monthly_changed_data_approval_existing_service_pensioner(Request $request) {
        // This is only for Service Pensioner (New User)
        try {
            DB::beginTransaction();
            $user = Auth::user();            
            
                //dd($application_id);
                /* 
                   (montly-changed-id)_(application-id)_(pernsioner-type)_(application-type)
                */
                $remarks = $request->remarks;
                $montly_changed_id = $request->monthly_changed_data_id;
                $application_id = $request->application_id;
                $pernsioner_type = $request->pensioner_type;
                $application_type = $request->application_type;
                // Check the application and pension type by which we can get the pensioner details according to type id
                $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                                ->where('id', $montly_changed_id)
                                                ->first();
                if($monthly_changed_data){
                    // Application status update
                    DB::table('optcl_pension_application_form')->where('id', $application_id)->update(['application_status_id' => 51]);
                    // Monthly changed data update
                    DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                    // Get Beneficiary Details from User Table
                    $application_details = DB::table('optcl_existing_user AS eu')
                                                ->select('eu.*', 'bbm.branch_name', 'bbm.ifsc_code','bbm.address','bm.bank_name')
                                                ->join('optcl_bank_branch_master AS bbm', 'bbm.id', '=', 'eu.bank_branch_id')
                                                ->join('optcl_bank_master AS bm', 'bm.id', '=', 'bbm.bank_id')
                                                ->where('id', $application_id)
                                                ->where('status', 1)->where('deleted', 0)
                                                ->first();
                    $user_id = $application_details->user_id;
                    $sanction_order_file_path = NULL;
                    $gratuity_sanction_order_file_path = NULL;
                    $ppo_order_file_path = NULL;
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
                    $emp_pan = $application_details->pan_no;
                    $savings_bank_account_no = $application_details->acc_number;
                    $bank_branch_id = $application_details->bank_branch_id;
                    $branch_name = $application_details->branch_name;
                    $ifsc_code = $application_details->ifsc_code;
                    $address = $application_details->address;
                    $bank_name = $application_details->bank_name;
                    // Service Pensioner Retirement Date                    
                    $date_of_retirement = $application_details->date_of_retirement;
                    // Pension Calculation Details
                    $pension_amount_details = DB::table('optcl_net_pension_details')
                                        ->where('application_type', 2)
                                        ->where('pension_type', 1)
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
           
            
            DB::commit();
            Session::flash('success', 'Application(s) is/are approved successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }
    // Monthly Changed Data Family Pensioner
    public function monthly_changed_data_approval_existing_family_pensioner(Request $request) {
        // This is only for Service Pensioner (New User)
        try {
            DB::beginTransaction();
            $user = Auth::user();            
            
                //dd($application_id);
                /* 
                   (montly-changed-id)_(application-id)_(pernsioner-type)_(application-type)
                */
                $remarks = $request->remarks;
                $montly_changed_id = $request->monthly_changed_data_id;
                $application_id = $request->application_id;
                $pernsioner_type = $request->pensioner_type;
                $application_type = $request->application_type;
                // Check the application and pension type by which we can get the pensioner details according to type id
                $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                                ->where('id', $montly_changed_id)
                                                ->first();
                if($monthly_changed_data){
                    // Application status update
                    DB::table('optcl_pension_application_form')->where('id', $application_id)->update(['application_status_id' => 51]);
                    // Monthly changed data update
                    DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_billing_officer_approved' => 1]);
                    // Get Beneficiary Details from User Table
                    $application_details = DB::table('optcl_existing_user AS eu')
                                                ->select('eu.*', 'bbm.branch_name', 'bbm.ifsc_code','bbm.address','bm.bank_name')
                                                ->join('optcl_bank_branch_master AS bbm', 'bbm.id', '=', 'eu.bank_branch_id')
                                                ->join('optcl_bank_master AS bm', 'bm.id', '=', 'bbm.bank_id')
                                                ->where('id', $application_id)
                                                ->where('status', 1)->where('deleted', 0)
                                                ->first();
                    $user_id = $application_details->user_id;
                    $sanction_order_file_path = NULL;
                    $gratuity_sanction_order_file_path = NULL;
                    $ppo_order_file_path = NULL;
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
                    $emp_pan = $application_details->pan_no;
                    $savings_bank_account_no = $application_details->acc_number;
                    $bank_branch_id = $application_details->bank_branch_id;
                    $branch_name = $application_details->branch_name;
                    $ifsc_code = $application_details->ifsc_code;
                    $address = $application_details->address;
                    $bank_name = $application_details->bank_name;
                    // Service Pensioner Retirement Date                    
                    $date_of_retirement = $application_details->date_of_retirement;
                    // Pension Calculation Details
                    $pension_amount_details = DB::table('optcl_net_pension_details')
                                        ->where('application_type', 2)
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
           
            
            DB::commit();
            Session::flash('success', 'Application(s) is/are approved successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }
    public function monthly_changed_data_service_pensioner_approve_details($approvalID) {
        return redirect()->route('billing_officer_sp_application_view', array($approvalID));
    }

    public function monthly_changed_data_family_pensioner_approve_details($approvalID) {
        return redirect()->route('billing_officer_sp_application_view', array($approvalID));
    }
    // Revision of Basic Pension View Page Billing Officer
    public function revision_basic_pension_view_page($appID) {        
        $mcd_data = DB::table('optcl_monthly_changed_data')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        //dd($mcd_data);
        if($mcd_data){
            $request_details = DB::table('optcl_change_data_revision_basic_pension')
                                            ->where('id', $mcd_data->cr_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
            //dd($request_details, $cr_data, $cr_application_id, $cr_data);
            if($request_details){
                return view('user.application_view.revision_basic_pension_view', compact('request_details', 'appID'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('billing_officer_approval_list_list');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('billing_officer_approval_list_list');
        }        
    }

    public function monthly_changed_data_approval_family_pensioner(Request $request) {
        // This is only for Family Pensioner (New User)
        try {
            DB::beginTransaction();
            $user = Auth::user();            
            
                //dd($application_id);
                /* 
                   (montly-changed-id)_(application-id)_(pernsioner-type)_(application-type)
                */
                $remarks = $request->remarks;
                $montly_changed_id = $request->monthly_changed_data_id;
                $application_id = $request->application_id;
                $pernsioner_type = $request->pensioner_type;
                $application_type = $request->application_type;
                //dd($request->all());
                $parameter_data = [
                    "remarks" => $remarks,
                    "montly_changed_id" => $montly_changed_id,
                    "application_id" => $application_id,
                    "pernsioner_type" => $pernsioner_type,
                    "application_type" => $application_type,
                ];
                // Check the application and pension type by which we can get the pensioner details according to type id
                $monthly_changed_data = DB::table('optcl_monthly_changed_data')
                                                ->where('id', $montly_changed_id)
                                                ->first();
                //dd($monthly_changed_data);
                if($monthly_changed_data){
                    if($monthly_changed_data->is_changed_request == 1){
                        if($monthly_changed_data->cr_type_id == 1){
                            // Addition of Pensioner New Pensioner

                        }else if($monthly_changed_data->cr_type_id == 2){
                            // Revision of Basic Pension
                            Beneficiary::revisionBasicPensionDataStorage($parameter_data);
                        }
                    }else{
                        // New User and Service Pensioner or Nominee
                        if($monthly_changed_data->appliation_type == 1 && ($monthly_changed_data->pensioner_type == 1 || $monthly_changed_data->pensioner_type == 2) ){
                            Beneficiary::newBeneficiaryDataStorage($parameter_data);
                        }else{
                            // Existing User
                        }
                    }
                    
                    
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
           
            
            DB::commit();
            Session::flash('success', 'Application(s) is/are approved successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
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
        $mcd_details = DB::table('optcl_monthly_changed_data')->where('id', $appID)->where('status', 1)->where('deleted', 0)->first();
        $application_id = $mcd_details->application_id;
        $is_changed_request = $mcd_details->is_changed_request;
        $cr_type_id = $mcd_details->cr_type_id;
        $cr_id = $mcd_details->cr_id;
        $appliation_type = $mcd_details->appliation_type;
        $pensioner_type = $mcd_details->pensioner_type;
        if($is_changed_request  == 1){
            // Monthly Changed Data Update
            if($cr_type_id == 1){
                // Addition of Pensioner New Pensioner

            }else if($cr_type_id == 2){
                // Revision of Basic Pension
                $applicationDetails = DB::table('optcl_change_data_revision_basic_pension AS rbp')
                                        ->join('optcl_existing_user AS eu', 'rbp.application_id', '=', 'eu.id')
                                        ->join('optcl_pension_type_master AS ptm', 'ptm.id', '=', 'eu.pensioner_type')
                                        ->select('eu.id', 'rbp.pensioner_basic_amount AS total_income_month', 'eu.additional_pension_amount', 'eu.ti_amount', 'eu.pensioner_name', 'eu.new_ppo_no', 'eu.taxable_amount', 'eu.pensioner_type', 'eu.tax_type_id', 'eu.date_of_birth', 'ptm.pension_type')
                                        ->where('rbp.id', '=', $cr_id)->first();
                //dd($applicationDetails);
                /* $applicationDetails = DB::table('optcl_existing_user AS eu')
                                ->join('optcl_pension_type_master AS ptm', 'ptm.id', '=', 'eu.pensioner_type')
                                ->select('eu.id', 'eu.total_income_month', 'eu.additional_pension_amount', 'eu.ti_amount', 'eu.pensioner_name', 'eu.new_ppo_no', 'eu.taxable_amount', 'eu.pensioner_type', 'eu.tax_type_id', 'eu.date_of_birth', 'ptm.pension_type')
                                ->where('eu.id', '=', $application_id)
                                ->first(); */
            }
        }else{
            // Application
            if($appliation_type == 1){
                // New Pensioner

            }else{
                // Existing User
                $applicationDetails = DB::table('optcl_existing_user AS eu')
                                ->join('optcl_pension_type_master AS ptm', 'ptm.id', '=', 'eu.pensioner_type')
                                ->select('eu.id', 'eu.total_income_month', 'eu.additional_pension_amount', 'eu.ti_amount', 'eu.pensioner_name', 'eu.new_ppo_no', 'eu.taxable_amount', 'eu.pensioner_type', 'eu.tax_type_id', 'eu.date_of_birth', 'ptm.pension_type')
                                ->where('eu.id', '=', $application_id)
                                ->first();
            }
        }        
        
        //dd($mcd_details, $applicationDetails);
        $response = [];
        $commutations = [];
        $taxList = [];
        if($applicationDetails){
            $basic_amount = $applicationDetails->total_income_month;
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
                            ->whereRaw("('".$age_value."' between `from_age` and `to_age`)")
                            ->where('is_new', $applicationDetails->tax_type_id)
                            ->get();
                //dd(DB::getQueryLog(), $taxList);
            }
            $tax_calculation_details = DB::table('optcl_taxable_details_master')->where('id', 1)->first();
        }
        //dd($response);
        return view('user.billing_officer.net-pension-details', compact('response', 'commutations', 'taxList', 'tax_calculation_details','mcd_details'));
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
        $monthly_changed_data_id = $request->monthly_changed_data_id;

        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
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
                // Update monthly changed data
                DB::table('optcl_monthly_changed_data')->where('id', $monthly_changed_data_id)->update(['is_net_pension_calculated' => 1]);
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

    public function list(Request $request){
        $check_download = DB::table('optcl_bill_generation')
                            ->where('is_downloaded', 0)
                            ->where('deleted', 0)
                            ->first();
        return view('user.billing_officer.application-list', compact('check_download'));
    }

    public function generate_bill(Request $request) {       
        try{
            DB::beginTransaction();
            $year_value = $request->year_id;
            $month_value = $request->month_id;
            $bill_generation_status = DB::table('optcl_bill_generation')
                                            ->where('year_value', $year_value)
                                            ->where('month_value', $month_value)
                                            ->where('deleted', 0)
                                            ->get();
            if($bill_generation_status->count() > 0){
                Session::flash('error','Bill already generated.');
                return redirect()->back();
            }else{
                //DB::enableQueryLog();
                            //dd($request->all());
                $check_download = DB::table('optcl_bill_generation')
                                    ->where('is_downloaded', 0)
                                    ->where('deleted', 0)
                                    ->count();
                if($check_download > 0){
                    Session::flash('error','Previous bill is not downloaded');
                    return redirect()->back();
                }else{
                    $bill_list = DB::table('optcl_beneficiary_details')
                    ->join('optcl_beneficiary_account_details', 'optcl_beneficiary_account_details.beneficiary_id', '=', 'optcl_beneficiary_details.id')
                    ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id', '=', 'optcl_beneficiary_account_details.bank_branch_id')
                    ->join('optcl_beneficiary_pension_amount_details', 'optcl_beneficiary_pension_amount_details.beneficiary_id', '=', 'optcl_beneficiary_details.id')
                    ->select('optcl_beneficiary_details.*', 'optcl_beneficiary_account_details.bank_branch_id', 'optcl_beneficiary_account_details.bank_name', 'optcl_beneficiary_account_details.branch_name', 'optcl_beneficiary_account_details.ifsc_code', 'optcl_beneficiary_account_details.account_number', 'optcl_bank_branch_master.bank_id', 'optcl_bank_branch_master.address', 'optcl_beneficiary_pension_amount_details.pension_amount')
                    ->where('optcl_beneficiary_details.is_dead', 0)
                    ->where('optcl_beneficiary_details.life_certificate_status', 0)
                    ->where('optcl_beneficiary_details.status', 1)
                    ->where('optcl_beneficiary_details.deleted', 0)
                    ->get()->groupBy('bank_id');
                    //dd($bill_list, DB::getQueryLog());
                    if($bill_list->count() > 0){
                        //dd($bill_list);

                        // optcl_bill_generation
                        $data_bill_gen = [
                            "year_value"            => $year_value,
                            "month_value"           => $month_value,
                            "created_by"            => Auth::user()->id,
                            "created_at"            => $this->current_date,
                        ];
                        $bill_gen_id = DB::table('optcl_bill_generation')->insertGetId($data_bill_gen);
                        foreach($bill_list as $bill_value){
                            //dd($bill_value);
                            $ben_count = 0;
                            $ben_ids = [];
                            foreach($bill_value as $bill_data){
                                //dd($bill_data);
                                $ben_name = $bill_data->pensioner_name;
                                $ben_ppo_no = $bill_data->ppo_no;

                                $bank_name = $bill_data->bank_name;
                                $branch_name = $bill_data->branch_name;
                                $ifsc_code = $bill_data->ifsc_code;
                                $account_number = $bill_data->account_number;
                                $bank_id = $bill_data->bank_id;
                                $bank_address = $bill_data->address;
                                $pension_amount = $bill_data->pension_amount;
                                $ben_id = $bill_data->id;
                                // optcl_bill_ben_details
                                $data_bill_ben = [
                                    "ben_id"            => $ben_id,
                                    //"bill_bank_id"      => $bill_bank_id,
                                    "bank_name"         => $bank_name,
                                    "branch_name"       => $branch_name,
                                    "ifsc_code"         => $ifsc_code,
                                    "branch_address"    => $bank_address,
                                    "ben_name"          => $ben_name,
                                    "ben_ppo_no"        => $ben_ppo_no,
                                    "ben_acc_no"        => $account_number ,
                                    "pension_amount"    => $pension_amount,
                                    "created_by"        => Auth::user()->id,
                                    "created_at"        => $this->current_date,
                                ];
                                $ben_ids[] = DB::table('optcl_bill_ben_details')->insertGetId($data_bill_ben);
                                $ben_count += 1;
                            }
                            // optcl_bill_bank_wise
                            $data_bill_bank = [
                                "bill_gen_id"       => $bill_gen_id,
                                "no_of_ben"         => $ben_count,
                                "bank_id"           => $bank_id,
                                "created_by"        => Auth::user()->id,
                                "created_at"        => $this->current_date,
                            ];
                            $bill_bank_id = DB::table('optcl_bill_bank_wise')->insertGetId($data_bill_bank);
                            DB::table('optcl_bill_ben_details')->whereIn('id', $ben_ids)->update(['bill_bank_id' => $bill_bank_id]);                
                        }
                        Session::flash('success','Bill generated successfully');
                    }else{
                        Session::flash('error','No data found');
                    }                    
                    DB::commit();   
                    return redirect()->back();
                }
            }
        }catch (\Throwable $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            throw $e;            
            return redirect()->back();
        }
    }

    public function download_bill(){
        //dd(123);
        $bill_name = "BILL".time();
        $filename =  $bill_name.'.xlsx';
        // storage path
        $file_path = 'bill_file/'.$filename;
        $check_download = DB::table('optcl_bill_generation')
                            ->where('is_downloaded', 0)
                            ->where('deleted', 0)
                            ->first();
        if($check_download){
            $bill_id = $check_download->id;
            $month_value = $check_download->month_value;
            $year_value = $check_download->year_value;

            DB::table('optcl_bill_generation')
                ->where('is_downloaded', 0)
                ->where('id', $bill_id)
                ->update([
                    'bill_no' => $bill_name,
                    'is_downloaded' => 1,
                    'download_path' => $file_path,
                ]);
        }
        // To Store in a perticular format
        Excel::store(new BillExport($bill_id, $month_value, $year_value), $file_path);
        return Excel::download(new BillExport($bill_id, $month_value, $year_value), $filename);
        /* if(Excel::download(new BillExport, $filename)){
            Session::flash('success','Bill generated successfully');
            return  redirect()->route('billing_officer_list');
        } */        
    }

    public function billing_history(Request $request){
        $applications = DB::table('optcl_bill_generation');
        if(!empty($request->year_id)) {
            $applications = $applications->where('year_value', $request->year_id);
        }
        if(!empty($request->month_id)) {
            $applications = $applications->where('month_value', $request->month_id);
        }
        $applications = $applications->where('deleted', 0)
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
        return view('user.billing_officer.billing-history', compact('applications', 'request'));
    }

}