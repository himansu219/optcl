<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use Session;
use Auth;
use DB;

class UnitHeadController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function applications(Request $request) {
        $user = Auth::user();
        $optcl_unit_id = $user->optcl_unit_id;
        // Here we get list for service pensioner and family pensioner by left JOIN operation
        $applications = DB::table('optcl_pension_application_form as a')
                    ->select('a.id', 'a.pension_type_id', 'pt.pension_type', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name', 'a.recovery_attachment')
                    ->leftJoin('optcl_employee_master as b', 'a.employee_id', '=', 'b.id')
                    ->leftJoin('optcl_nominee_master as n', 'a.employee_id', '=','n.id')
                    ->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
                    ->join('optcl_pension_type_master as pt','pt.id','=','a.pension_type_id')
                    ->where(function($query) use ($optcl_unit_id){
                         $query->orWhere('b.optcl_unit_id', $optcl_unit_id);
                         $query->orWhere('n.optcl_unit_id', $optcl_unit_id);
                    })
                    ->where('a.is_existing', 0)
                    ->where('a.status', 1)
                    ->where('a.deleted', 0);

        if(!empty($request->application_no)) {
            $applications = $applications->where('a.application_no', 'like', '%' . $request->application_no . '%');
        }

        if(!empty($request->employee_code)) {
            $applications = $applications->where('b.employee_code', 'like', '%' . $request->employee_code . '%');
        }

        if(!empty($request->employee_aadhaar_no)) {
            $applications = $applications->where('b.aadhaar_no', 'like', '%'. $request->employee_aadhaar_no . '%');
        }
        $applications = $applications->orderBy('a.id','DESC');
        $applications = $applications->paginate(10);

        return view('user.unit_head.application-list', compact('applications', 'request'));
    }

    public function application_details($id) {
        //Update Notification
        DB::table('optcl_user_notification')
            ->where(['user_id' => Auth::user()->id])
            ->update(['view_status' => 1]);
        $application = DB::table('optcl_pension_application_form as a')
                        ->select('a.*', 'b.status_name')
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
                            ->join('optcl_pension_unit_master AS pu','pu.id','=','pd.pension_unit_id')
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name as office_last_served','pd.permanent_addr_at','pd.permanent_addr_post','pd.permanent_addr_pincode','pd.permanent_addr_country_id','pd.permanent_addr_state_id','pd.permanent_addr_district_id','pd.present_addr_at','pd.present_addr_post','pd.present_addr_pincode','pd.present_addr_country_id','pd.present_addr_state_id','pd.present_addr_district_id','pd.telephone_std_code','pd.mobile_no','pd.email_address','pd.pan_no','pd.savings_bank_account_no','pd.bank_branch_id','pd.basic_pay_amount_at_retirement','pd.pension_unit_id','pd.is_civil_service_amount_received','pd.civil_service_name','pd.civil_service_received_amount','pd.is_family_pension_received_by_family_members','pd.admission_source_of_family_pension','pd.family_member_relation_id','pd.family_member_name','pd.is_commutation_pension_applied','pd.commutation_percentage','s.state_name','d.district_name','s2.state_name as sName','d2.district_name as dName','c1.country_name as cName','c2.country_name','rm.relation_name','pu.pension_unit_name')
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

        return view('user.unit_head.application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form'));
    }

    public function applications_submission(Request $request) {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $return_remark_value = $request->return_remark_value;
            $application_id = $request->application_id;
            $application_status = $request->application_status;
            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            $status_id = $application_status == 1 ? 1 : 2;
            
            if(!empty($application_form)) {
                if($request->application_status == 1) {
                    $status_updated_to = 14;
                    $message = "One application has been approved by Unit Head with application no ".$application_form->application_no.". Please check the application.";
                    $message1 = "One application has been approved by Unit Head with application no ".$application_form->application_no.". Please check the application details.";
                    $message2 = "Your application has been approved by Unit Head. Please check the application details.";
                    // Notify Sanction Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->where('status', 1)->where('deleted', 0)->value('id');
                    Util::insert_notification($n_user_id, $application_form->id, $message1);
                } else {
                    $status_updated_to = 15;
                    $message = "One application has been returned by Unit Head with application no ".$application_form->application_no.". Please resubmit the application.";
                    $message1 = "One application has been returned by Unit Head with application no ".$application_form->application_no.". Please check the application details.";
                    $message2 = "Your application has been returned by Unit Head. Please check the application details.";
                }
                DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                ->where('deleted', 0)->update([
                    'application_status_id' => $status_updated_to
                ]);
                DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => $status_updated_to,
                    'remarks'           => $return_remark_value,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);
                // Notification Area
                // Notify to Dealing assistant
                $UserDetails = DB::table('optcl_users')->where('id',$application_form->user_id)->first();
                $optcl_unit_id = $UserDetails->optcl_unit_id;
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $application_form->id, $message);
                // Notify to Finance Executive
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                Util::insert_notification($n_user_id, $application_form->id, $message1);
                // Notify to Pensioner
                Util::insert_notification($application_form->user_id, $application_form->id, $message2);
                
                DB::commit();
                if($request->application_status == 1) {
                    Session::flash('success','Application has been approved successfully!');
                } else {
                    Session::flash('success','Application has been returned successfully!');
                }          
                return redirect()->route('unit_head_applications');
            } else {
                DB::commit();
                $message = 'Application Not Found';
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

}
