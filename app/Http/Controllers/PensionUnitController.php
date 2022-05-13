<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PensionUnit;
use App\Libraries\Util;
use Session;
use Auth;
//use DB;
use Carbon\Carbon;


class PensionUnitController extends Controller
{   
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }
    // admin module pension unit part start

    public function index(){
        //$district=DistrictMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.pension_unit');
    }

   public function pension_unit_submit(Request $request){
    $request->validate([

       // 'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);

    //dd("1");
    $res= new PensionUnit;
    // $res->country_id= $request->country;
    // $res->state_id= $request->state;
    //$res->district_id= $request->district;
    $res->unit_code= $request->unit_code;
    $res->pension_unit_name= $request->unit_name;
    $res->save();
    //return redirect('pension_unit_details');
    //$request->session()->flash('msg','Data added');
    Session::flash('success', 'Data added successfully');
    return redirect()->route('pension_unit_details');
   }
    

   public function fetch_pension_unit(){
        $data['result'] = PensionUnit::where('deleted',0)->paginate(10);
    
        //return redirect()->route('pension_unit_details')->with($data);
        return view('admin.pension_unit_details',$data);
   }

   public function edit_pension_unit(Request $request,$id){
        //$district=DistrictMaster::where('status',1)->where('deleted',0)->get();
        $result=PensionUnit::where('status',1)->where('deleted',0)->where('id',$id)->get();
       return view('admin.pension_unit_edit')->with(compact('result'));
        //return redirect()->route('pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_pension_unit(Request $request,$id){

    $request->validate([

        //'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);
        
    $data=array(    
        //'district_id'=>$request->input('district'),
        'unit_code'=>$request->input('unit_code'),
        'pension_unit_name'=>$request->input('unit_name')
    
        );
        PensionUnit::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data update successfully');
        return redirect()->route('pension_unit_details');


   }

   public function delete_pension_unit(Request $request,$id){

    PensionUnit::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data delete successfully');
    return redirect()->route('pension_unit_details');

   }

   public function changeStatus(Request $request){
        //dd($request->all());
        $user = PensionUnit::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

    }

    // admin module pension unit part end

  public function applications(Request $request) {
    	$user = Auth::user();

    	$applications = DB::table('optcl_pension_application_form as a')
    				->select('a.id', 'a.application_no', 'a.application_status_id', 'a.created_at', 'c.status_name')
    				->join('optcl_employee_personal_details as b', 'a.employee_id', '=', 'b.id')
    				->join('optcl_application_status_master as c', 'a.application_status_id', '=', 'c.id')
    				->where('b.pension_unit_id', $user->pension_unit_id)
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
        //dd($applications);

    	return view('user.pension_unit.application-list', compact('applications', 'request'));
    }

    public function application_details($id) {
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

        $service_form = DB::table('optcl_employee_pension_service_form')->where('status', 1)->where('deleted', 0)->where('application_id', $id)->first();

    	return view('user.pension_unit.application-details', compact('application', 'proposal', 'employee_documents', 'employee_nominees', 'statusHistory', 'add_recovery', 'service_form'));
    }

    public function applications_approval(Request $request) {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            
            $form_field_master = DB::table('optcl_pension_form_field_master')->where('id', $request->field_id)->first();

            if(!empty($form_field_master)) {

                $pension_application_form = DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->first();

                if(empty($request->nominee_id)) {
                    $application_form_field_status = DB::table('optcl_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('created_by', $user->id)->where('status', 1)->where('deleted', 0)->first();

                } else {
                    $application_form_field_status = DB::table('optcl_application_form_field_status')->select('id', 'status_id')->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->where('nominee_id', $request->nominee_id)->where('created_by', $user->id)->where('status', 1)->where('deleted', 0)->first();
                }
                // $form_field_status_master = DB::table('optcl_application_form_field_status_master')->where('status', 1)->where('deleted', 0)->where('id', $request->status_id)->first();

                if(empty($application_form_field_status)) {
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

                    $form_field_status_id = DB::table('optcl_application_form_field_status')->insertGetId($form_field_status);

                } else {
                    $form_field_status = [
                        'status_id' => $request->status_id,
                        'remarks' => $request->remarks,
                        'modified_at' => $this->current_date,
                        'modified_by' => $user->id
                    ];

                    DB::table('optcl_application_form_field_status')->where('id', $application_form_field_status->id)->where('application_id', $request->application_id)->where('form_id', $form_field_master->form_id)->where('field_id', $request->field_id)->update($form_field_status);
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

            $application_form = DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)->where('deleted', 0)->first();

            if(!empty($application_form)) {
                $application_form_field_status = DB::table('optcl_application_form_field_status')->where('application_id', $request->application_id)->where('created_by', $user->id)->where('status', 1)->where('deleted', 0)->get();

                $status = '';
                foreach ($application_form_field_status as $key => $value) {
                    
                    $form_field_status_history = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application_form->employee_id,
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

                    $form_field_status_history_id = DB::table('optcl_application_form_field_status_history')->insertGetId($form_field_status_history);
                }

                if($request->application_status == 1) {
                    DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                    ->where('deleted', 0)->update([
                        'application_status_id' => 2
                    ]);

                    DB::table('optcl_application_status_history')->insertGetId([
                        'user_id'           => $user->id,
                        'application_id'    => $request->application_id,
                        'status_id'         => 2,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                        'status'            => 1,
                        'deleted'           => 0
                    ]);

                    $status = 'approved';
                } else {
                    DB::table('optcl_pension_application_form')->where('id', $request->application_id)->where('status', 1)
                    ->where('deleted', 0)->update([
                        'application_status_id' => 3
                    ]);
                    DB::table('optcl_application_status_history')->insertGetId([
                        'user_id'           => $user->id,
                        'application_id'    => $request->application_id,
                        'status_id'         => 3,
                        'remarks'           => $request->remarks,
                        'created_at'        => $this->current_date,
                        'created_by'        => $user->id,
                        'status'            => 1,
                        'deleted'           => 0
                    ]);

                    $status = 'returned';
                }

                DB::commit();
                Session::flash('success','Application has been '. $status .' successfully!');
                return redirect()->route('pension_unit_applications');
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

        try {
            DB::beginTransaction();
            
            $employee_recoveries = $request->add_recovery;

            $user = Auth::user();

            $application = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($employee_recoveries)) {
                foreach ($employee_recoveries as $key => $value) {
                    
                    $recovery = [
                        'application_id' => $request->application_id,
                        'employee_id' => $application->employee_id,
                        'recovery_label' => $value['label'],
                        'recovery_value' => $value['value'],
                        'status' => 1,
                        'created_at' => $this->current_date,
                        'created_by' => $user->id,
                        'deleted' => 0
                    ];

                    DB::table('optcl_employee_add_recovery')->insert($recovery);
                }
            }

            $application_status = [
                'application_status_id' => 12
            ];

            DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)
                ->where('id', $request->application_id)->update($application_status);

            DB::table('optcl_application_status_history')->insertGetId([
                'user_id'           => $user->id,
                'application_id'    => $request->application_id,
                'status_id'         => 12,
                'created_at'        => $this->current_date,
                'created_by'        => $user->id,
                'status'            => 1,
                'deleted'           => 0
            ]);

            DB::commit();
            Session::flash('success','Recovery has been added successfully!');
            return redirect()->route('pension_unit_applications');
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
        // dd($request->all());

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $application_form = DB::table('optcl_pension_application_form')->select('id', 'employee_id', 'application_no')
                ->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->first();

            if(!empty($application_form)) {
                $service_pension_form = [
                    'application_id' => $request->application_id,
                    'employee_id' => $application_form->employee_id,
                    'is_service_period_duly' => !empty($request->form_service_period_duly) ? $request->form_service_period_duly : 0,
                    'service_period_duly_from' => !empty($request->form_service_period_duly_from) ? date('Y-m-d', strtotime($request->form_service_period_duly_from)) : NULL,
                    'service_period_duly_to' => !empty($request->form_service_period_duly_to) ? date('Y-m-d', strtotime($request->form_service_period_duly_to)) : NULL,
                    'is_period_of_absence' => !empty($request->form_service_period_absence) ? $request->form_service_period_absence : 0,
                    'service_period_absence_from' => !empty($request->form_service_period_absence_from) ? date('Y-m-d', strtotime($request->form_service_period_absence_from)) : NULL,
                    'service_period_absence_to' => !empty($request->form_service_period_absence_to) ? date('Y-m-d', strtotime($request->form_service_period_absence_to)) : NULL,
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
                    'net_qualifying_months' => !empty($request->net_qualifying_days) ? $request->net_qualifying_days : 0,
                    'net_qualifying_days' => !empty($request->gross_days) ? $request->gross_days : 0,
                    'non_qualifying_period_from' => !empty($request->non_qualifying_period_from) ? date('Y-m-d', strtotime($request->non_qualifying_period_from)) : NULL,
                    'non_qualifying_period_to' => !empty($request->non_qualifying_period_to) ? date('Y-m-d', strtotime($request->non_qualifying_period_to)) : NULL,
                    'status' => 1,
                    'created_at' => $this->current_date,
                    'created_by' => $user->id,
                    'deleted' => 0
                ];

                $service_pension_form_id = DB::table('optcl_employee_pension_service_form')->insertGetId($service_pension_form);

                $service_form = $request->service_form;

                if(!empty($service_form)) {
                    foreach ($service_form as $key => $value) {
                        $various_off = [
                            'service_pension_form_id' => $service_pension_form_id,
                            'application_id' => $request->application_id,
                            'organisation_name' => !empty($value['form_organisation']) ? $value['form_organisation'] : NULL,
                            'name_of_office' => !empty($value['form_name_of_office']) ? $value['form_name_of_office'] : NULL,
                            'post_held' => !empty($value['form_post_held']) ? $value['form_post_held'] : NULL,
                            'service_period_from' => !empty($value['form_period_from']) ? date('Y-m-d', strtotime($value['form_period_from'])) : NULL,
                            'service_period_to' => !empty($value['form_period_to']) ? date('Y-m-d', strtotime($value['form_period_to'])) : NULL,
                            'total_service_years' => !empty($value['total_service_years']) ? $value['total_service_years'] : 0,
                            'total_service_months' => !empty($value['total_service_months']) ? $value['total_service_months'] : 0,
                            'total_service_days' => !empty($value['total_service_days']) ? $value['total_service_days'] : 0,
                        ];

                        DB::table('optcl_employee_pension_service_offices')->insert($various_off);
                    }
                }

                DB::table('optcl_pension_application_form')->where('status', 1)->where('deleted', 0)->where('id', $request->application_id)->update(['application_status_id' => 13]);

                DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => $user->id,
                    'application_id'    => $request->application_id,
                    'status_id'         => 13,
                    'created_at'        => $this->current_date,
                    'created_by'        => $user->id,
                    'status'            => 1,
                    'deleted'           => 0
                ]);

                DB::commit();
                Session::flash('success','Service Pension Form submitted successfully!');
                return redirect()->route('pension_unit_applications');
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
