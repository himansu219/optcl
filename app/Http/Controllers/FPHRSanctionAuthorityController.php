<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\fpdf\FPDF;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

use App\Libraries\PensinorCalculation;

class FPHRSanctionAuthorityController extends Controller
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
                    ->where('a.status', 1)
                    ->where('a.deleted', 0)->orderBy('id', 'DESC');

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

    	return view('user.hr-wing.sanction_authority.application-list', compact('applications', 'request'));
    }

    public function assignments(Request $request) {
        $user = Auth::user();

        $applications = DB::table('optcl_pension_application_form as a')
                    ->select('a.id', 'a.pension_type_id', 'pt.pension_type', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name', 'a.recovery_attachment')
                    ->leftJoin('optcl_employee_master as b', 'a.employee_id', '=', 'b.id')
                    ->leftJoin('optcl_nominee_master as n', 'a.employee_id', '=','n.id')
                    ->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
                    ->join('optcl_pension_type_master as pt','pt.id','=','a.pension_type_id')
                    ->where('a.application_status_id', 14)
                    ->where('a.status', 1)
                    ->where('a.deleted', 0)->orderBy('id', 'DESC');

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
        // $applications = $applications->toSql();
        // dd($applications);
        return view('user.hr-wing.sanction_authority.assignment-list', compact('applications', 'request'));
    }

    public function assignment_history() {
        $user = Auth::user();

        $applications = DB::table('optcl_pension_application_form as a')
                    ->select('a.id', 'a.pension_type_id', 'pt.pension_type', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name', 'a.recovery_attachment','au.first_name','au.last_name')
                    ->leftJoin('optcl_employee_master as b', 'a.employee_id', '=', 'b.id')
                    ->leftJoin('optcl_nominee_master as n', 'a.employee_id', '=','n.id')
                    ->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
                    ->join('optcl_pension_type_master as pt','pt.id','=','a.pension_type_id')
                    ->join('optcl_application_user_assignments as ua','a.id', '=','ua.application_id')
                    ->join('optcl_users as au','au.id', '=','ua.user_id')
                    ->where('a.status', 1)
                    ->where('a.deleted', 0)->orderBy('id', 'DESC');

        // if(!empty($request->application_no)) {
        //     $applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
        // }

        // if(!empty($request->employee_code)) {
        //     $applications = $applications->where('b.employee_code', 'like', '%' . $request->employee_code . '%');
        // }

        // if(!empty($request->employee_aadhaar_no)) {
        //     $applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
        // }

        $applications = $applications->paginate(10);
        //dd($applications);
        return view('user.hr-wing.sanction_authority.history_page', compact('applications'));
    }

    public function application_approval($id) {
        //Update Notification
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id])
            ->update(['view_status' => 1]);
        $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name')
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

        return view('user.hr-wing.sanction_authority.application-approval', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery'));
    }

    public function fp_application_details($id) {
        //Update Notification
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id,'application_type' => 'family'])
            ->update(['view_status' => 1]);
        $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.id', 'a.application_no', 'a.application_status_id', 'a.pension_type_id', 'a.employee_id', 'a.employee_code', 'a.employee_aadhaar_no', 'a.created_at', 'b.status_name')
                        ->join('optcl_application_status_master as b', 'b.id', '=', 'a.application_status_id')
                        ->where('a.id', $id)->first();

                        //dd($application);

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

        $service_form = DB::table('optcl_nominee_pension_service_form')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->first();
        if(!empty($service_form)){
            $organisation_details = DB::table('optcl_nominee_pension_service_offices')->where('status', 1)->where('deleted', 0)->where('service_pension_form_id', $service_form->id)->get();
        }else{
            $organisation_details = array();    
        }

        $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $id)->first();
        //dd($service_form_three);

        return view('user.hr-wing.sanction_authority.fp-application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form_three', 'service_form', 'organisation_details'));
    }
    
    public function application_assignment(Request $request) {
        //dd($request);
        $application_id = $request->assigned_application_id;
        $dealing_assistant_list = $request->dealing_assistant_list;
        try {
            DB::beginTransaction();
            $user = Auth::user(); 
            $pension_type_id = DB::table('optcl_pension_application_form')->where('id', $application_id)->value('pension_type_id');           
            $is_application_assigned = DB::table('optcl_application_user_assignments')
                                        ->where('application_id', $application_id)
                                        ->first();
            if(empty($is_application_assigned)) {
                $data_assignment = [
                    "application_id"    => $application_id,
                    "user_id"           => $dealing_assistant_list,
                    "created_by"        => Auth::user()->id,
                    "created_at"        => $this->current_date,
                ];
                DB::table('optcl_application_user_assignments')->insert($data_assignment);

                DB::table('optcl_pension_application_form')->where('id', $application_id)->update([
                    'application_status_id' => 20
                ]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $application_id,
                    'status_id'         => 20,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);
                
                // Notification Area
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;

                // Pensioner
                $message = "Application assigned by Sanctioning Authority with application no ".$appDetails->application_no.". Please check the application details.";
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
                // HR Wing Dealing Assistant
                Util::insert_notification($dealing_assistant_list, $appDetails->id, $message);

                DB::commit();
                Session::flash('success', 'Application assigned successfully');
                // For Family Pensioner
                if($pension_type_id == 1){
                    $rURL = 'hr_sanction_authority_application_details';
                }else{
                    $rURL = 'family_pension_hr_sanctioning_authority_assignment_app_details';
                }
                return redirect()->route($rURL, $application_id);
            } else {
                DB::rollback();
                Session::flash('error', 'Application already assigned');
                if($pension_type_id == 1){
                    $rURL = 'hr_sanction_authority_application_details';
                }else{
                    $rURL = 'family_pension_hr_sanctioning_authority_assignment_app_details';
                }
                return redirect()->route($rURL, $application_id);
            }
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function multiple_application_assignment(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_id_list = $request->application_id_list;
            $dealing_assistant_list = $request->dealing_assistant_list;

            $applications = explode(',', $application_id_list);
            foreach($applications as $application_id){
                //echo $application_id;
                $is_application_assigned = DB::table('optcl_application_user_assignments')
                                            ->where('application_id', $application_id)
                                            ->first();
                if(empty($is_application_assigned)) {
                    $data_assignment = [
                        "application_id"    => $application_id,
                        "user_id"           => $dealing_assistant_list,
                        "created_by"        => Auth::user()->id,
                        "created_at"        => $this->current_date,
                    ];
                    DB::table('optcl_application_user_assignments')->insert($data_assignment);


                    DB::table('optcl_pension_application_form')->where('id', $application_id)->update([
                        'application_status_id' => 20
                    ]);

                    DB::table('optcl_application_status_history')->insert([
                        'user_id'           => $user->id,
                        'application_id'    => $application_id,
                        'status_id'         => 20,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                        'status'            => 1,
                        'deleted'           => 0
                    ]);
                }

                // Notification Area
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                // Pensioner
                $message = "Application assigned by Sanctioning Authority with application no ".$appDetails->application_no.". Please check the application details.";
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
                // HR wing Dealing Assistant
                Util::insert_notification($dealing_assistant_list, $appDetails->id, $message);
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

    public function submit_application_approval(Request $request) {
        //dd($request);
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            // dd($application_form, $request->all());
            if(!empty($application_form)) {
                $application_form_field_status = DB::table('optcl_nominee_application_form_field_status')->where('application_id', $request->application_id)->where('status', 1)->where('deleted', 0)->get();

                $status = '';

                foreach ($application_form_field_status as $key => $value) {
                    
                    $form_field_status_history = [
                        'application_id' => $request->application_id,
                        'nominee_master_id' => $application_form->employee_id,
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

                    $form_field_status_history_id = DB::table('optcl_nominee_application_form_field_status_history')->insertGetId($form_field_status_history);
                }

                if($request->application_status == 1) {
                    $to_update_status = 39;

                    $status = 'approved';
                    $message = "Application approved by HR Wing Sanctioning Authority. Please check the application details.";
                    $message2 = "Application approved by HR Wing Sanctioning Authority. Please check the application details.";
                } else {
                    $to_update_status = 40;                  

                    $status = 'returned';
                    $message = "Application returned by HR Wing Sanctioning Authority. Please resubmit the application with required details.";
                    $message2 = "Application returned by HR Wing Sanctioning Authority. Please check the application details.";
                }
                // Application Status Update
                DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                ->where('deleted', 0)->update([
                    'application_status_id' => $to_update_status
                ]);
                // Application status history
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
                DB::table('optcl_nominee_application_form_field_status')
                    ->where('application_id', $application_form->id)
                    ->where('is_latest', 0)
                    ->update(['is_latest' => 1]); 

                // Notification Area
                $application_id = $application_form->id;
                $appDetails = DB::table('optcl_pension_application_form')->where('id', $application_id)->first();
                $pension_user_id = $appDetails->user_id;
                /*// Pensioner 
                Util::insert_notification($appDetails->user_id, $appDetails->id, $message2);
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
                // HR Wing Dealing Assistant
                $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
                Util::insert_notification($dealing_assistant_list, $appDetails->id, $message);
                // HR Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message2);*/

                DB::commit();
                Session::flash('success','Application has been '. $status .' successfully!');
                return redirect()->route('hr_sanction_authority_applications');
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

    public function application_forward_initiator($application_id) {
        //dd($request);
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->where('id', $application_id)->where('status', 1)->where('deleted', 0)->first();

            // dd($application_form, $request->all());
            if(!empty($application_form)) {

                DB::table('optcl_pension_application_form')->where('id', $application_id)->where('status', 1)
                    ->where('deleted', 0)->update([
                        'application_status_id' => 36
                    ]);
                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $application_id,
                    'status_id'         => 36,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);     

                // Notification Area
                $message = "Application forwarded to Initiator with application no ".$application_form->application_no.". Please check the application details.";
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
                // HR Wing Dealing Assistant
                $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
                Util::insert_notification($dealing_assistant_list, $appDetails->id, $message);
                // HR Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);
                // Initiator
                $n_user_id = DB::table('optcl_users')->where('designation_id', 8)->value('id');
                Util::insert_notification($n_user_id, $appDetails->id, $message);

                DB::commit();
                Session::flash('success','Application forwarded successfully');
                return redirect()->route('hr_sanction_authority_applications');
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

            $application = DB::table('optcl_pension_application_form')
                                ->where('status', 1)->where('deleted', 0)
                                ->where('id', $request->application_id)->first();
            $application_status_id = $application->application_status_id;

            if($application_status_id == 43){
                // 43 - Verifier Returned
                // 45 - HR Wing (SA) Resubmited to Verifier
                $to_update_status = 45;
                $message = "Application resubmitted to Verifier with application no ".$application->application_no.". Please check the application details.";
                $message2 = "Application resubmitted with application no ".$application->application_no.". Please check the application details.";
                // Initiator
                $n_user_id = DB::table('optcl_users')->where('designation_id', 8)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
                // Verifier
                $n_user_id = DB::table('optcl_users')->where('designation_id', 9)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
            }else if($application_status_id == 47){
                // 47 - Approver Returned
                // 48 - HR Wing (SA) Resubmited to Approver
                $to_update_status = 48;
                $message = "Application resubmitted to Approver with application no ".$application->application_no.". Please check the application details.";
                $message2 = "Application resubmitted with application no ".$application->application_no.". Please check the application details.";
                // Initiator
                $n_user_id = DB::table('optcl_users')->where('designation_id', 8)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
                // Verifier
                $n_user_id = DB::table('optcl_users')->where('designation_id', 9)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
                // Approver
                $n_user_id = DB::table('optcl_users')->where('designation_id', 10)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
            }else{
                // 44 - HR Wing (SA) Resubmited to Initiator
                $to_update_status = 44;
                $message = "Application resubmitted to Initiator with application no ".$application->application_no.". Please check the application details.";
                $message2 = "Application resubmitted with application no ".$application->application_no.". Please check the application details.";
                // Initiator
                $n_user_id = DB::table('optcl_users')->where('designation_id', 8)->value('id');
                Util::insert_notification($n_user_id, $application->id, $message2);
            }

            $application_status = [
                'application_status_id' => $to_update_status
            ];

            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insertGetId([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => $to_update_status,
                'remarks'           => $request->remarks,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            // Notification Area            
            $application_id = $application->id;
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
            // HR Wing Dealing Assistant
            $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $appDetails->id)->value('user_id');
            Util::insert_notification($dealing_assistant_list, $appDetails->id, $message);
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);

            DB::commit();
            Session::flash('success','Application resubmitted successfully!');
            return redirect()->route('hr_sanction_authority_applications');
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

        $proposal = PensinorCalculation::fp_get_employee_details($application->employee_id);

        $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $id)->first();

        return view('user.hr-wing.sanction_authority.sanction-order-generate', compact('application', 'proposal', 'service_form_three'));
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

            $proposal = PensinorCalculation::fp_get_employee_details($application->employee_id);

            $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $application_id)->first();

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

            $sanction_order = DB::table('optcl_nominee_pension_sanction_order')->where('application_id', $application_id)->where('nominee_master_id', $application->employee_id)->first();


            if(empty($sanction_order)) {
                $sanction_order_input = [
                    'application_id' => $application_id,
                    'nominee_master_id' => $application->employee_id,
                    'sanction_order_to_name' => $request->sanction_order_to_name,
                    'sanction_faithfully' => $request->sanction_faithfully,
                    'created_at' => $this->current_date,
                    'created_by' => $user->id,
                    'status' => 1,
                    'deleted' => 0
                ];

                DB::table('optcl_nominee_pension_sanction_order')->insert($sanction_order_input);
            } else {
                $sanction_order_input = [
                    'sanction_order_to_name' => $request->sanction_order_to_name,
                    'sanction_faithfully' => $request->sanction_faithfully,
                    'modified_at' => $this->current_date,
                    'modified_by' => $user->id
                ];

                DB::table('optcl_nominee_pension_sanction_order')->where('application_id', $application_id)->where('nominee_master_id', $application->employee_id)->update($sanction_order_input);
            }

            
            $sanctionOrderDir = "public/uploads/family_pension/sanction_order";

            if (!is_dir($sanctionOrderDir) && !is_writeable($sanctionOrderDir)) {
                mkdir($sanctionOrderDir);
            }

            // file path to be saved in server folder for later viewing of invoice
            $file = $sanctionOrderDir . "/sanction_order_". $application->application_no .".pdf";
            // first send the output to server folder for permanent saving the document
            $pdf->Output($file, "F");
            // then open it in browser if pdf plugin has already been installed
            // $pdf->Output("sanction_order.pdf", "D");

            // Generate Sanction Number
            $sanction_number = "SNOD".date('Y').sprintf('%05d',$application_id);
            DB::table('optcl_pension_application_form')->where('id', $application_id)->update([
                'application_status_id' => 25,
                'sanction_number'   => $sanction_number,
                'sanction_order_file_path'   => $file,
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
            // Notification Area
            $message = "Sanction order generated by HR Wing Sanctioning Authority with application no ".$application->application_no.". Please check the application details.";
            $application_id = $application->id;

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
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);

            Session::flash('success', 'Sanction order generated successfully');
            DB::commit();
            return redirect()->route('family_pension_hr_sanctioning_authority', array($application_id));
            
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

        $proposal = PensinorCalculation::fp_get_employee_details($application->employee_id);

        $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $id)->first();
        //dd($service_form_three);

        $recoveries = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->get();

        return view('user.hr-wing.sanction_authority.gratuity-sanction-order-generate', compact('application', 'proposal', 'service_form_three', 'recoveries'));
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

            $proposal = PensinorCalculation::fp_get_employee_details($application->employee_id);
            //dd($proposal);
            $service_form_three = DB::table('optcl_nominee_pension_service_form_three')->where('application_id', $application_id)->first();

            $recoveries = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $application_id)->get();

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

            $point_three = '3. The net amount shall be credited to his pension A/C No. ' . $proposal->saving_bank_ac_no . ' maintained at ' . $bankName . ', ' . $branchName . ', IFSC Code: ' . $ifscCode . ', MICR Code: ' . $micrCode . '.';
            
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

            $sanctionOrderDir = "public/uploads/family_pension/gratuity_sanction_order";

            if (!is_dir($sanctionOrderDir) && !is_writeable($sanctionOrderDir)) {
                mkdir($sanctionOrderDir);
            }

            // file path to be saved in server folder for later viewing of invoice
            $file = $sanctionOrderDir . "/gratuity_sanction_order_". $application->application_no .".pdf";
            // first send the output to server folder for permanent saving the document
            $pdf->Output($file, "F");
            // then open it in browser if pdf plugin has already been installed
            // $pdf->Output("sanction_order.pdf", "D");
            // Update Gratuity Sanction Order File Path
            DB::table('optcl_pension_application_form')->where('id', $request->application_id)->update([
                'gratuity_sanction_order_file_path'   => $file,
            ]);
            // Notification Area
            $message = "Gratuity Sanction order generated by HR Wing Sanctioning Authority with application no ".$application->application_no.". Please check the application details.";
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
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Finance Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // Unit Head
            $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);
            // HR Executive
            $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
            Util::insert_notification($n_user_id, $appDetails->id, $message);

            Session::flash('success', 'Gratuity sanction order generated successfully');
            DB::commit();
            return redirect()->route('family_pension_hr_sanctioning_authority', array($application_id));
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

}
