<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\NomineeUtil;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class FPUnitHeadController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function applications(Request $request) {
    	$user = Auth::user();

    	$applications = DB::table('optcl_pension_application_form as a')
    				->select('a.id', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name', 'a.recovery_attachment')
    				->join('optcl_nominee_master as b', 'a.employee_id', '=', 'b.id')
    				->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
    				->where('b.optcl_unit_id', $user->optcl_unit_id)
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

    	return view('user.unit_head.fp-application-list', compact('applications', 'request'));
    }

    public function application_details($id) {
        //Update Notification
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id,'application_type' => 'family'])
            ->update(['view_status' => 1]);

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
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type', 'o.unit_name','o.unit_name as office_last_served','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','pu.pension_unit_name')
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
            $organisation_details = DB::table('optcl_employee_pension_service_offices')->where('status', 1)->where('deleted', 0)->where('service_pension_form_id', $service_form->id)->get();
        }else{
            $organisation_details = array();    
        }

    	return view('user.unit_head.fp-application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form', 'organisation_details'));
    }

    public function applications_approval(Request $request) {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            //dd($request);
            $form_field_master = DB::table('optcl_nominee_form_field_master')->where('id', $request->field_id)->first();
            //dd($form_field_master);
            if(!empty($form_field_master)) {

                if(empty($request->nominee_id)) {
                    //dd(1);
                    $application_form_field_status = DB::table('optcl_nominee_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('status', 1)->where('deleted', 0)->first();

                } else {
                    //dd(2);
                    $application_form_field_status = DB::table('optcl_nominee_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('nominee_id', $request->nominee_id)->where('status', 1)->where('deleted', 0)->first();
                }
                //dd($application_form_field_status);
                // $form_field_status_master = DB::table('optcl_application_form_field_status_master')->where('status', 1)->where('deleted', 0)->where('id', $request->status_id)->first();

                if(empty($application_form_field_status)) {
                    //dd(1);
                    $form_field_status = [
                        'application_id' => $request->application_id,
                        'form_id' => $form_field_master->form_id,
                        'field_id' => $request->field_id,
                        'status_id' => $request->status_id,
                        'status_id' => $request->status_id,
                        'nominee_id' => !empty($request->nominee_id) ? $request->nominee_id : NULL,
                        'remarks' => $request->remarks,
                        'status' => 1,
                        'created_at' => $this->current_date,
                        'created_by' => $user->id
                    ];
                    //dd($form_field_status);
                    //DB::enableQueryLog();
                    $form_field_status_id = DB::table('optcl_nominee_application_form_field_status')->insert($form_field_status);
                    //dd(DB::getQueryLog());
                    //dd($form_field_status_id);
                } else {
                    //dd(2);
                    $form_field_status = [
                        'status_id' => $request->status_id,
                        'remarks' => $request->remarks,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id
                    ];
                    //dd($form_field_status);
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
        try {
            DB::beginTransaction();
            $user = Auth::user();
            //dd($request);
            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            if(!empty($application_form)) {
                
                if($request->application_status == 1) {
                    $status_updated_to = 14;
                    $status = 'approved';
                    $alert_type = 'success';
                    $message = "One application has been approved by Unit Head with application no ".$application_form->application_no.". Please check the application.";
                    $message1 = "One application has been approved by Unit Head with application no ".$application_form->application_no.". Please check the application details.";
                    $message2 = "Your application has been approved by Unit Head. Please check the application details.";
                    // Notify Sanction Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->where('status', 1)->where('deleted', 0)->value('id');
                    Util::insert_notification($n_user_id, $application_form->id, $message1);
                } else {
                    $status_updated_to = 15;
                    $status = 'returned';
                    $alert_type = 'success';
                    $message = "One application has been returned by Unit Head with application no ".$application_form->application_no.". Please resubmit the application.";
                    $message1 = "One application has been returned by Unit Head with application no ".$application_form->application_no.". Please check the application details.";
                    $message2 = "Your application has been returned by Unit Head. Please check the application details.";
                }

                // Update application history
                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => $status_updated_to,
                    'remarks'           => $request->remarks,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);
                // Update the pension table
                DB::table('optcl_pension_application_form')
                    ->where('id', $request->application_id)
                    ->where('status', 1)
                    ->where('deleted', 0)->update([
                    'application_status_id' => $status_updated_to
                ]);
                // Update is_latest value
                DB::table('optcl_nominee_application_form_field_status')
                    ->where('application_id', $application_form->id)
                    ->where('is_latest', 0)
                    ->update(['is_latest' => 1]);

                // Notification Area
                // Notify to Dealing assistant
                $UserDetails = DB::table('optcl_users')->where('id',$application_form->user_id)->first();
                $optcl_unit_id = $UserDetails->optcl_unit_id;
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $application_form->id, $message);
                // Notify to Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $application_form->id, $message1);
                // Notify to Pensioner
                NomineeUtil::insert_notification($application_form->user_id, $application_form->id, $message2);
                // HR Wing Sanctioning Authority
                $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                Util::insert_notification($n_user_id, $application_form->id, $message);

                DB::commit();
                Session::flash($alert_type,'Application '. $status .' by Unit Head successfully!');
                return redirect()->route('family_pension_unit_head_application_details', $request->application_id);
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

}
