<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\NomineeUtil;
use App\Models\Pensionform;
use App\Models\AdminUser;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class DaAddApplicantController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }
      // Add Applicant
    public function add_applicant(Request $request) {
        
        $designation = DB::table('optcl_employee_designation_master')
                         ->where('status',1)
                         ->where('deleted',0)
                         ->get();
        return view('user.dealing-assistant.add_applicant',compact('designation'));

    }
       // View Applicant
    public function view_applicant(Request $request) {
        $user = Auth::user();
        $optcl_unit_id = $user->optcl_unit_id;
        $data = DB::table('optcl_users')
                         ->where('optcl_unit_id',$optcl_unit_id)
                         ->where('user_type',1)
                         ->where('status',1)
                         ->where('deleted',0)
                         ->orderBy('id', 'DESC')
                         ->paginate(10);
        //dd($data);
        return view('user.dealing-assistant.view_applicant',compact('data'));
    }
    // insert add applicant
    public function add_applicant_submit(Request $request){        
        $validation = array();
        $applicant_name = $request->applicant_name;
        if($applicant_name == ""){
            $validation['error'][] = array("id" => "applicant_name-error","eValue" => "Please enter Applicant Name");
        }
        $employee_code = $request->employee_code;
        if($employee_code == ""){
            $validation['error'][] = array("id" => "employee_code-error","eValue" => "Please enter Employee Code");
        }
        $aadhaar_no = $request->aadhaar_no;
        if($aadhaar_no == ""){
            $validation['error'][] = array("id" => "aadhaar_no-error","eValue" => "Please enter Aadhaar No");
        }
        $mobile_no = $request->mobile_no;
        if($mobile_no == ""){
            $validation['error'][] = array("id" => "mobile_no-error","eValue" => "Please enter Mobile No");
        }
        $designation = $request->designation;
        if($designation == ""){
            $validation['error'][] = array("id" => "designation-error","eValue" => "Please select designation");
        }
        $dob = $request->dob;
        if($dob == ""){
            $validation['error'][] = array("id" => "dob-error","eValue" => "Please select date of birth");
        }else{
            $dob = str_replace("/","-",$dob);
        }
        $doj = $request->doj;
        if($doj == ""){
            $validation['error'][] = array("id" => "doj-error","eValue" => "Please select date of joining service");
        }else{
            $doj = str_replace("/","-",$doj);
        }
        $dor = $request->dor;
        if($dor == ""){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select date of retirement");
        }else{
            $dor = str_replace("/","-",$dor);
        }

        $working_unit = Auth::user()->optcl_unit_id;
        //dd($working_unit);
        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $pension_column = new Pensionform();
                $pension_column->employee_code = $employee_code;
                $pension_column->aadhaar_no = $aadhaar_no;
                $pension_column->employee_name = $applicant_name;
                $pension_column->designation_id = $designation;
                $pension_column->date_of_birth = date('Y-m-d', strtotime($dob));
                $pension_column->date_of_joining = date('Y-m-d', strtotime($doj));
                $pension_column->date_of_retirement = date('Y-m-d', strtotime($dor));
                $pension_column->optcl_unit_id = $working_unit;
                $pension_column->created_by = Auth::user()->id;
                $pension_column->created_at = $this->current_date;
                $pension_column->save();

                $user_tbl = new AdminUser();
                $user_tbl->employee_code = $employee_code;
                $user_tbl->user_type = 1;
                $user_tbl->aadhaar_no = $aadhaar_no;
                $user_tbl->first_name = $applicant_name;
                $user_tbl->username = $employee_code;
                $user_tbl->mobile = $mobile_no;
                $user_tbl->designation_id = $designation;
                $user_tbl->optcl_unit_id = $working_unit;
                $user_tbl->created_by = Auth::user()->id;
                $user_tbl->created_at = $this->current_date;
                $user_tbl->save();
                $applicantID = $user_tbl->id;
                $this->notify_applicant($applicantID);
                DB::commit();
                Session::flash('success', 'Applicant added successfully');
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function filter_applicants(Request $request){
        $user = Auth::user();
        $optcl_unit_id = $user->optcl_unit_id;
        $applicant_name =  $request->input('applicant_name'); 
        $employee_code =  $request->input('employee_code');
        $aadhaar_no =  $request->input('aadhaar_no'); 
        $mobile_no =  $request->input('mobile_no'); 
        DB::enableQueryLog();
        $users = DB::table('optcl_users')
                    ->where('optcl_unit_id',$optcl_unit_id)
                    ->where('deleted',0);
          
        //dd($application_no);
        //dd($pension_application_form);
       
        if(!empty($aadhaar_no)) {
            $data = $users->where('aadhaar_no', $aadhaar_no);
        }
        if(!empty($employee_code)) {
            $data = $users->where('employee_code', $employee_code);
                             
        }
        if(!empty($applicant_name)) {
            $data = $users->where('first_name', $applicant_name);
                             
        }
        if(!empty($mobile_no)) {
            $data = $users->where('mobile', $mobile_no);                             
        }         
        $users = $users->orderBy('id','DESC');
        $data = $users->paginate(10);
        //dd(DB::getQueryLog() );
        return view('user.dealing-assistant.view_applicant',compact('data','request'));
    }
    
    public function edit_applicant($applicantID) {
        $optcl_unit_id = Auth::user()->optcl_unit_id;
        DB::enableQueryLog();
        $data = DB::table('optcl_users')
                    ->join('optcl_employee_master', 'optcl_employee_master.employee_code', '=', 'optcl_users.employee_code')
                    ->select('optcl_users.*', 'optcl_employee_master.date_of_birth', 'optcl_employee_master.date_of_joining', 'optcl_employee_master.date_of_retirement', 'optcl_employee_master.id AS employee_master_id')
                    ->where('optcl_users.optcl_unit_id', $optcl_unit_id)
                    ->where('optcl_users.id', $applicantID)
                    ->where('optcl_users.user_type', 1)
                    ->where('optcl_users.status', 1)
                    ->where('optcl_users.deleted', 0)
                    ->first();
        //dd(DB::getQueryLog(), $data, $optcl_unit_id, Auth::user()->id);
        if($data){
            $designation = DB::table('optcl_employee_designation_master')
                         ->where('status',1)
                         ->where('deleted',0)
                         ->get();
            return view('user.dealing-assistant.edit_applicant',compact('data', 'designation'));
        }else{
            Session::flash('error', 'No data found');
            return redirect()->route('view_applicant');
        }        
    }

    public function update_applicant_details(Request $request){        
        $validation = array();
        $applicant_name = $request->applicant_name;
        if($applicant_name == ""){
            $validation['error'][] = array("id" => "applicant_name-error","eValue" => "Please enter Applicant Name");
        }
        $employee_code = $request->employee_code;
        if($employee_code == ""){
            $validation['error'][] = array("id" => "employee_code-error","eValue" => "Please enter Employee Code");
        }
        $aadhaar_no = $request->aadhaar_no;
        if($aadhaar_no == ""){
            $validation['error'][] = array("id" => "aadhaar_no-error","eValue" => "Please enter Aadhaar No");
        }
        $mobile_no = $request->mobile_no;
        if($mobile_no == ""){
            $validation['error'][] = array("id" => "mobile_no-error","eValue" => "Please enter Mobile No");
        }
        $designation = $request->designation;
        if($designation == ""){
            $validation['error'][] = array("id" => "designation-error","eValue" => "Please select designation");
        }
        $dob = $request->dob;
        if($dob == ""){
            $validation['error'][] = array("id" => "dob-error","eValue" => "Please select date of birth");
        }else{
            $dob = str_replace("/","-",$dob);
        }
        $doj = $request->doj;
        if($doj == ""){
            $validation['error'][] = array("id" => "doj-error","eValue" => "Please select date of joining service");
        }else{
            $doj = str_replace("/","-",$doj);
        }
        $dor = $request->dor;
        if($dor == ""){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select date of retirement");
        }else{
            $dor = str_replace("/","-",$dor);
        }
        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $applicant_id = $request->applicant_id;
                $employee_master_id = $request->employee_master_id;

                $pension_column = new Pensionform();
                $pension_column->exists = true;
                $pension_column->id = $employee_master_id;
                $pension_column->employee_code = $employee_code;
                $pension_column->aadhaar_no = $aadhaar_no;
                $pension_column->employee_name = $applicant_name;
                $pension_column->designation_id = $designation;
                $pension_column->date_of_birth = date('Y-m-d', strtotime($dob));
                $pension_column->date_of_joining = date('Y-m-d', strtotime($doj));
                $pension_column->date_of_retirement = date('Y-m-d', strtotime($dor));
                $pension_column->modified_by = Auth::user()->id;
                $pension_column->modified_at = $this->current_date;
                $pension_column->save();

                $user_tbl = new AdminUser();
                $user_tbl->exists = true;
                $user_tbl->id = $applicant_id;
                $user_tbl->employee_code = $employee_code;
                $user_tbl->aadhaar_no = $aadhaar_no;
                $user_tbl->first_name = $applicant_name;
                $user_tbl->username = $applicant_name;
                $user_tbl->mobile = $mobile_no;
                $user_tbl->designation_id = $designation;
                $user_tbl->updated_by = Auth::user()->id;
                $user_tbl->updated_at = $this->current_date;
                $user_tbl->save();
                DB::commit();
                Session::flash('success', 'Applicant updated successfully');
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function delete_applicant($applicantID) {
        DB::beginTransaction();
        try{
            $optcl_unit_id = Auth::user()->optcl_unit_id;
            DB::enableQueryLog();
            $data = DB::table('optcl_pension_application_form')
                        ->where('user_id', $applicantID)
                        ->where('deleted', 0)
                        ->count();
            //dd(DB::getQueryLog(), $data, $optcl_unit_id, Auth::user()->id);
            if($data < 1){
                DB::table('optcl_users')->where('id', $applicantID)->update(['deleted' => 1]);
                DB::commit();
                Session::flash('success', 'Applicant deleted successfully.' );
                return redirect()->route('view_applicant');
            }else{
                Session::flash('error', 'Application already applied. Can not delete.' );
                DB::rollback();
                return redirect()->route('view_applicant');
            }             
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }               
    }

    public function notify_applicant($applicantID) {
        DB::beginTransaction();
        try{
            $data = DB::table('optcl_users')
                        ->where('id', $applicantID)
                        ->where('deleted', 0)
                        ->first();
            if($data){
                // SMS Area
                $passwordValue = "Secret@123";
                $encryptPasswordValue = bcrypt($passwordValue);
                DB::table('optcl_users')->where('id', $applicantID)->update(['password' => $encryptPasswordValue, 'is_notified' => 1]);
                DB::commit();
                Session::flash('success', 'Notification sent successfully.' );
                return redirect()->route('view_applicant');
            }else{
                Session::flash('error', 'Something went wrong. Please try again.' );
                DB::rollback();
                return redirect()->route('view_applicant');
            }             
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }               
    }

}
