<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\NomineeUtil;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class FPFinanceExecutiveController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
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
            $organisation_details = DB::table('optcl_nominee_pension_service_offices')->where('status', 1)->where('deleted', 0)->where('service_pension_form_id', $service_form->id)->get();
        }else{
            $organisation_details = array();    
        }

    	return view('user.finance_executive.fp-application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form', 'organisation_details'));
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
                $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', $application_form->user_id)->value('optcl_unit_id');
                if($request->application_status == 1) {                    
                    $to_update_status = 10;
                    $status = 'approved';
                    $message = "Application has been approved by Finance Executive with application no ".$application_form->application_no.". Please check the application details.";
                    // Notification - Unit Head
                    // Unit Head                    
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    NomineeUtil::insert_notification($n_user_id, $application_form->id, $message);
                } else {
                    $to_update_status = 11;                    
                    $status = 'returned';
                    $message = "Application has been returned with application no ".$application_form->application_no.". Please check the application details.";
                }
                // Update application history
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
                // Update the pension table
                DB::table('optcl_pension_application_form')
                    ->where('id', $request->application_id)
                    ->where('status', 1)
                    ->where('deleted', 0)->update([
                    'application_status_id' => $to_update_status
                ]);
                // Update is_latest value
                DB::table('optcl_nominee_application_form_field_status')
                    ->where('application_id', $application_form->id)
                    ->where('is_latest', 0)
                    ->update(['is_latest' => 1]);
                // Notification Area
                NomineeUtil::insert_notification($application_form->user_id, $application_form->id, $message);
                // Dealing Assistant
                $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                NomineeUtil::insert_notification($n_user_id, $application_form->id, $message);
                

                DB::commit();
                Session::flash('success','Application '. $status .' by Dealing Assistant successfully!');
                return redirect()->route('family_pension_fe_application_details', $request->application_id);
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

    public function applications_store_recovery(Request $request) {

        // dd($request->all());
        try {
            DB::beginTransaction();
            
            $employee_recoveries = $request->add_recovery;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($employee_recoveries)) {
                foreach ($employee_recoveries as $key => $value) {
                    $recovery = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'status' => 1,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id,
                        'deleted' => 0
                    ];
                    DB::table('optcl_nominee_add_recovery')->where('id', $value['id_value'])->update($recovery);
                    // Recovery History
                    $recovery['recovery_id'] = $value['id_value'];
                    DB::table('optcl_nominee_add_recovery_history')->insert($recovery);
                }
            }
            $to_update_status = 18;
            $application_status = [
                'application_status_id' => $to_update_status,
            ];

            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insert([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => $to_update_status,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);
            // Notification Area
            $message = "Recovery details added by the Dealing Assistant. Please check the application details.";
            Util::insert_notification($application->user_id, $application->id, $message);

            DB::commit();
            Session::flash('success','Recovery details updated by Finance Executive');
            return redirect()->route('family_pension_fe_application_details', $request->application_id);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }
    }

    public function get_year_month_day(Request $request) {
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

        if(!empty($from) && !empty($to)) {

            $response = Util::get_years_months_days($from, $to);

            return response()->json($response);

        }
    }

    public function service_pension_form_submission(Request $request) {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($application_form)) {
                $service_form_id = DB::table('optcl_nominee_pension_service_form')->where('application_id', $application_form->id)->value('id');


                $service_pension_form = [
                    'application_id' => $request->application_id,
                    'nominee_master_id' => $application_form->employee_id,
                    'is_service_period_duly' => !empty($request->form_service_period_duly) ? $request->form_service_period_duly : 0,
                    'service_period_duly_from' => !empty($request->form_service_period_duly_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_from))) : NULL,
                    'service_period_duly_to' => !empty($request->form_service_period_duly_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_to))) : NULL,
                    'is_period_of_absence' => !empty($request->form_service_period_absence) ? $request->form_service_period_absence : 0,
                    'service_period_absence_from' => !empty($request->form_service_period_absence_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_from))) : NULL,
                    'service_period_absence_to' => !empty($request->form_service_period_absence_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_to))) : NULL,
                    'is_departmental_or_judicial' => !empty($request->form_status_of_departmental_judicial) ? $request->form_status_of_departmental_judicial : 0,
                    'scale_of_pay' => !empty($request->form_scale_of_pay) ? $request->form_scale_of_pay : NULL,
                    'last_basic_pay' => !empty($request->form_last_basic_pay) ? str_replace(',', '', $request->form_last_basic_pay) : NULL,
                    'is_no_demand_certificate' => !empty($request->form_is_checked) ? $request->form_is_checked : 0,
                    'reason_of_no_demand_certificate' => !empty($request->form_reason_no_demand_certificate) ? $request->form_reason_no_demand_certificate : NULL,
                    'is_recommended_provisional_pension' => !empty($request->is_recommended_provisional_pension) ? $request->is_recommended_provisional_pension : 0,
                    'gross_years' => !empty($request->gross_years) ? $request->gross_years : 0,
                    'gross_months' => !empty($request->gross_months) ? $request->gross_months : 0,
                    'gross_days' => !empty($request->gross_days) ? $request->gross_days : 0,
                    'non_qualifying_years' => !empty($request->non_qualifying_years) ? $request->non_qualifying_years : 0,
                    'non_qualifying_months' => !empty($request->non_qualifying_months) ? $request->non_qualifying_months : 0,
                    'non_qualifying_days' => !empty($request->non_qualifying_days) ? $request->non_qualifying_days : 0,
                    'net_qualifying_years' => !empty($request->net_qualifying_years) ? $request->net_qualifying_years : 0,
                    'net_qualifying_months' => !empty($request->net_qualifying_months) ? $request->net_qualifying_months : 0,
                    'net_qualifying_days' => !empty($request->net_qualifying_days) ? $request->net_qualifying_days : 0,
                    'non_qualifying_period_from' => !empty($request->non_qualifying_period_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_from))) : NULL,
                    'non_qualifying_period_to' => !empty($request->non_qualifying_period_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_to))) : NULL,
                    'modified_at' => $this->current_date,
                    'modified_by' => $user->id,
                ];

                $service_pension_form_id = DB::table('optcl_nominee_pension_service_form')->where('id', $service_form_id)->update($service_pension_form);

                $service_pension_form_history = [
                    'service_form_id' => $service_form_id,
                    'application_id' => $request->application_id,
                    'nominee_master_id' => $application_form->employee_id,
                    'is_service_period_duly' => !empty($request->form_service_period_duly) ? $request->form_service_period_duly : 0,
                    'service_period_duly_from' => !empty($request->form_service_period_duly_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_from))) : NULL,
                    'service_period_duly_to' => !empty($request->form_service_period_duly_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_duly_to))) : NULL,
                    'is_period_of_absence' => !empty($request->form_service_period_absence) ? $request->form_service_period_absence : 0,
                    'service_period_absence_from' => !empty($request->form_service_period_absence_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_from))) : NULL,
                    'service_period_absence_to' => !empty($request->form_service_period_absence_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->form_service_period_absence_to))) : NULL,
                    'is_departmental_or_judicial' => !empty($request->form_status_of_departmental_judicial) ? $request->form_status_of_departmental_judicial : 0,
                    'scale_of_pay' => !empty($request->form_scale_of_pay) ? $request->form_scale_of_pay : NULL,
                    'last_basic_pay' => !empty($request->form_last_basic_pay) ? str_replace(',', '', $request->form_last_basic_pay) : NULL,
                    'is_no_demand_certificate' => !empty($request->form_is_checked) ? $request->form_is_checked : 0,
                    'reason_of_no_demand_certificate' => !empty($request->form_reason_no_demand_certificate) ? $request->form_reason_no_demand_certificate : NULL,
                    'is_recommended_provisional_pension' => !empty($request->is_recommended_provisional_pension) ? $request->is_recommended_provisional_pension : 0,
                    'gross_years' => !empty($request->gross_years) ? $request->gross_years : 0,
                    'gross_months' => !empty($request->gross_months) ? $request->gross_months : 0,
                    'gross_days' => !empty($request->gross_days) ? $request->gross_days : 0,
                    'non_qualifying_years' => !empty($request->non_qualifying_years) ? $request->non_qualifying_years : 0,
                    'non_qualifying_months' => !empty($request->non_qualifying_months) ? $request->non_qualifying_months : 0,
                    'non_qualifying_days' => !empty($request->non_qualifying_days) ? $request->non_qualifying_days : 0,
                    'net_qualifying_years' => !empty($request->net_qualifying_years) ? $request->net_qualifying_years : 0,
                    'net_qualifying_months' => !empty($request->net_qualifying_months) ? $request->net_qualifying_months : 0,
                    'net_qualifying_days' => !empty($request->net_qualifying_days) ? $request->net_qualifying_days : 0,
                    'non_qualifying_period_from' => !empty($request->non_qualifying_period_from) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_from))) : NULL,
                    'non_qualifying_period_to' => !empty($request->non_qualifying_period_to) ? date('Y-m-d', strtotime(str_replace("/","-", $request->non_qualifying_period_to))) : NULL,
                    'status_id' => 13,
                    'status' => 1,
                    'modified_at' => $this->current_date,
                    'modified_by' => $user->id,
                    'deleted' => 0
                ];

                DB::table('optcl_nominee_pension_service_form_history')->insert($service_pension_form_history);

                $service_form = $request->service_form;

                if(!empty($service_form)) {
                    foreach ($service_form as $key => $value) {
                        $nominee_pension_service_office_id = DB::table('optcl_nominee_pension_service_offices')->where('application_id', $application_form->id)->value('id');

                        $various_off = [
                            'service_pension_form_id' => $service_form_id,
                            'application_id' => $request->application_id,
                            'organisation_name' => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office' => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held' => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from' => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to' => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years' => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months' => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days' => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at' => $this->current_date,
                            'modified_by' => $user->id,
                        ];

                        //dd($various_off);

                        $service_office_id = DB::table('optcl_nominee_pension_service_offices')->where('id', $nominee_pension_service_office_id)->update($various_off);

                        $various_off_history = [
                            'service_office_id' => $nominee_pension_service_office_id,
                            'service_pension_form_id' => $service_form_id,
                            'application_id' => $request->application_id,
                            'organisation_name' => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office' => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held' => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from' => !empty($value['form_period_from']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_from']))) : NULL,
                            'service_period_to' => !empty($value['form_period_to']) ? date('Y-m-d', strtotime(str_replace("/","-", $value['form_period_to']))) : NULL,
                            'total_service_years' => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months' => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days' => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                            'modified_at' => $this->current_date,
                            'modified_by' => $user->id,
                        ];

                        DB::table('optcl_nominee_pension_service_offices_history')->insert($various_off_history);
                    }
                }
                $to_update_status = 19;
                DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->update(['application_status_id' => $to_update_status]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => $to_update_status,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);

                /*// Notification Area
                $message = "Part - II form submitted by the Dealing Assistant. Please check the application details.";
                $notificationData = array(
                    "user_id"           => $application_form->user_id,
                    "application_id"    => $application_form->id,
                    "status_message"    => $message,
                    "created_at"        => $this->current_date,
                );
                DB::table('optcl_user_notification')->insert($notificationData);
                // Notify Finance Executive
                $message = "Part - II form submitted by the Dealing Assistant. Please check the application details.";
                $UserDetails = DB::table('optcl_users')->where('id',$application_form->user_id)->first();
                $optcl_unit_id = $UserDetails->optcl_unit_id;
                $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                $notificationData = array(
                    "user_id"           => $n_user_id,
                    "application_id"    => $application_form->id,
                    "status_message"    => $message,
                    "created_at"        => $this->current_date,
                );
                DB::table('optcl_user_notification')->insert($notificationData);*/

                DB::commit();
                Session::flash('success', 'PART  - II details successfully updated by Finance Executive');
                return redirect()->route('family_pension_fe_application_details', $request->application_id);
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

    public function applications_resubmission (Request $request){
        try {
            DB::beginTransaction();
            $user = Auth::user();
            //dd($request);
            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            if(!empty($application_form)) {
                $application_form_field_status = DB::table('optcl_application_form_field_status')->where('application_id', $request->application_id)->where('status', 1)->where('deleted', 0)->get();

                DB::table('optcl_pension_application_form')
                    ->where('id', $request->application_id)->where('status', 1)
                    ->where('deleted', 0)
                    ->update(['application_status_id' => 27]);

                DB::table('optcl_application_status_history')->insert([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => 27,
                    'remarks'           => $request->remarks,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);
                // Notification Area
                // Unit Head
                $user_id_value = $application_form->user_id;
                $optcl_unit_id = DB::table('optcl_users')->where('id', $user_id_value)->value('optcl_unit_id');
                $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                $message = "Application resubmitted by Dealing Assistant with application no ".$application_form->application_no.". Please check the application details.";
                Util::insert_notification($n_user_id, $application_form->id, $message);

                DB::commit();
                Session::flash('success','Application submitted successfully!');
                return redirect()->route('dealing_applications');
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
