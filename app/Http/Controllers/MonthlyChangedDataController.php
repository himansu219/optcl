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


class MonthlyChangedDataController extends Controller { 
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
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
                    ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', DB::raw('if(optcl_monthly_changed_data.appliation_type = 1, optcl_pension_application_form.ppo_number, optcl_existing_user.new_ppo_no) AS new_ppo_no'), DB::raw('if(optcl_monthly_changed_data.appliation_type != 1, optcl_existing_user.pensioner_name, (select CONCAT(optcl_users.first_name, " ", optcl_users.last_name) AS full_name FROM  optcl_users where optcl_users.id = optcl_pension_application_form.user_id)) AS any_pensioner_name'), 'optcl_application_status_master.status_name', DB::raw('if(optcl_monthly_changed_data.is_changed_request = 1, optcl_change_data_master.change_type, "NA") AS change_type'))
                    ->where('optcl_monthly_changed_data.pension_unit_id', $pension_unit_id)
                    ->where('optcl_monthly_changed_data.is_pension_unit_checked', 0)
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
        // Status List
        $statuslist = DB::table('optcl_application_status_master')
                            ->where('status', 1)
                            ->where('deleted', 0)->get();

        return view('user.monthly_changed_data.application-list', compact('applications', 'request', 'statuslist'));
    }

    public function history(Request $request){
        $user = Auth::user();
        //DB::enableQueryLog();
        $pension_unit_id = $user->pension_unit_id;
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
                    ->where('optcl_monthly_changed_data.pension_unit_id', $pension_unit_id)
                    ->where('optcl_monthly_changed_data.is_pension_unit_checked', 1)
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

        /* $applications = DB::table('optcl_monthly_changed_data')
                    ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_monthly_changed_data.pensioner_type')
                    ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_monthly_changed_data.appliation_type')
                    ->join('optcl_existing_user', 'optcl_existing_user.id', '=', 'optcl_monthly_changed_data.application_id')
                    ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_existing_user.application_status_id')
                    ->select('optcl_monthly_changed_data.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name', 'optcl_existing_user.new_ppo_no', 'optcl_application_status_master.status_name')
                    ->where('optcl_monthly_changed_data.pension_unit_id', $pension_unit_id)
                    ->where('optcl_monthly_changed_data.is_pension_unit_checked', 1)
                    ->where('optcl_monthly_changed_data.status', 1)
                    ->where('optcl_monthly_changed_data.deleted', 0);

        // Old/New PPO No.
        if(!empty($request->application_no)) {
            $application_no = $request->application_no;
            //$applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
            $applications = $applications->where(function($query) use($application_no) {
                $query->orWhere('optcl_existing_user.old_ppo_no', 'like', '%' . $application_no . '%');
                $query->orWhere('optcl_existing_user.new_ppo_no', 'like', '%' . $application_no . '%');
            });
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where('optcl_existing_user.employee_code', 'like', '%' . $request->employee_code . '%');
        }

        if(!empty($request->employee_aadhaar_no)) {
            $employee_aadhaar_no = $request->employee_aadhaar_no;
            //$applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
            $applications = $applications->where(function($query) use($employee_aadhaar_no) {
                $query->orWhere('optcl_existing_user.aadhaar_no', 'like', '%' . $employee_aadhaar_no . '%');
                $query->orWhere('optcl_existing_user.mobile_number', 'like', '%' . $employee_aadhaar_no . '%');
            });
        }    
        
        if(!empty($request->app_status_id)) {
            $applications = $applications->where('optcl_existing_user.application_status_id',  $request->app_status_id);
        }  */

        $applications = $applications->orderBy('optcl_monthly_changed_data.id','DESC');
        $applications = $applications->paginate(10);
        // Status List
        $statuslist = DB::table('optcl_application_status_master')
                            ->where('status', 1)
                            ->where('deleted', 0)->get();

        return view('user.monthly_changed_data.application-history-list', compact('applications', 'request', 'statuslist'));
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
                        DB::table('optcl_pension_application_form')->where('id', $application_id)->update(['application_status_id'    => 50]);
                    }else{
                        // Service Pensioner(Existing) || Family Pensioner(Existing)
                        // Here we have updated the persion unit user checked status    
                        DB::table('optcl_existing_user')->where('id', $application_id)->update(['application_status_id'    => 50]);
                    }
                    DB::table('optcl_monthly_changed_data')->where('id', $montly_changed_id)->update(['is_pension_unit_checked' => 1]);
                }
                
                DB::table('optcl_application_status_history')->insert([
                    'is_new'            => 0,
                    'user_id'           => $user->id,
                    'application_id'    => $application_id,
                    'status_id'         => 50,
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
            Session::flash('success', 'Application(s) is/are assigned successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }
    

}