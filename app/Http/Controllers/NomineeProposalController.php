<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Religion;
use App\Models\OfficeLastServed;
use App\Models\PensionerDesignation;
use App\Models\Pensionform;
use App\Models\NomineeApplicationForm;
use App\Models\NomineeFamilyPensionerForm;
use App\Models\PensionDocument;
use App\Models\PersonalDetails;
use App\Models\RelationMaster;
use App\Libraries\Util;
use Session;
use Auth;

class NomineeProposalController extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function nominee_application_form(Request $request){
        //dd($request->all());
        $user_details = Auth::user();
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
           return redirect()->route('nominee_application_view_details');
        }
        /*$nominee_id = DB::table('optcl_nominee_nominee_details')
                        ->where('employee_code',Auth::user()->employee_code)
                        ->where('mobile_no',Auth::user()->mobile)
                        ->first();*/
        // Check application applied or not. If applied then redirect to edit page with session value
        $checkStatus = DB::table('optcl_nominee_master')
                        ->where('employee_code', Auth::user()->employee_code)
                        //->where('nominee_id',$nominee_id->id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->orderBy('id', 'DESC')
                        ->limit(1)
                        ->first();
        //dd($checkStatus->id);   
        if(!empty($checkStatus)){
            Session::put('application_no', $checkStatus->id);
            return redirect()->route('edit_nominee_application_form');
        }

        //dd($checkStatus, Auth::user()->username);
        $religions = Religion::where('status', 1)->where('deleted', 0)->get();
        $office_last_served = OfficeLastServed::where('status', 1)->where('deleted', 0)->get();
        $pensioner_designation = PensionerDesignation::where('status', 1)->where('deleted', 0)->get();     
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $account_types = DB::table('optcl_pf_account_type_master')->where('status', 1)->where('deleted', 0)->get();
        // Get Employee details if exists
        $employee_master = DB::table('optcl_employee_master')
                                ->where('employee_code', Auth::user()->employee_code)
                                ->first();
             //dd($employee_master);
        return view('user.nominee.nominee_application_form', compact('religions', 'office_last_served','pensioner_designation','user_details', 'mstatus', 'genders','account_types','employee_master'));
    }
    // save nominee application form 1
    public function save_nominee_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $validation = array();
        $emp_code = $request->emp_code;
        if($emp_code == ""){
         $validation['error'][] = array("id" => "emp_code-error","eValue" => "Please enter employee code");
        }
        $aadhaar_no = $request->aadhaar_no;
        if($aadhaar_no == ""){
         $validation['error'][] = array("id" => "aadhaar_no-error","eValue" => "Please enter aadhaar no");
        }
        $name = $request->name;
        if($name == ""){
         $validation['error'][] = array("id" => "name-error","eValue" => "Please enter name");
        }
        $designation = $request->designation;
        if($designation == ""){
         $validation['error'][] = array("id" => "designation-error","eValue" => "Please select designation");
        }
        $father_name = $request->father_name;
        if($father_name == ""){
         $validation['error'][] = array("id" => "father_name-error","eValue" => "Please father name");
        }
        $gender = $request->gender;
        if($gender == ""){
         $validation['error'][] = array("id" => "gender-error","eValue" => "Please select gender");
        }
        $marital_status = $request->marital_status;
        if($marital_status == ""){
         $validation['error'][] = array("id" => "marital_status-error","eValue" => "Please select marital status");
        }
        $husband_name = $request->husband_name;
        if($gender == 2 && $marital_status == 1 && $husband_name == ""){
            $validation['error'][] = array("id" => "husband_name-error","eValue" => "Please enter husband name");
        }
        $religion = $request->religion;
        if($religion == ""){
         $validation['error'][] = array("id" => "religion-error","eValue" => "Please select religion");
        }
        $pf_acno_type = $request->pf_acc_type;
        if($pf_acno_type == ""){
         $validation['error'][] = array("id" => "pf_acno_type-error","eValue" => "Please select PF a/c type");
        }
        $pf_acno = $request->pf_acno;
        if($pf_acno == ""){
         $validation['error'][] = array("id" => "pf_acno-error","eValue" => "Please enter PF a/c no.");
        }
        $name_of_office_dept = $request->name_of_office_dept;
        if($name_of_office_dept == ""){
         $validation['error'][] = array("id" => "name_of_office_dept-error","eValue" => "Please select office/ dept served");
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

        if(date('Y-m-d',strtotime($doj)) < date('Y-m-d',strtotime($dob))) {
            $validation['error'][] = array("id" => "doj-error","eValue" => "Please select valid date of joining in service");
        }

        if(date('Y-m-d',strtotime($doj)) > date('Y-m-d',strtotime($dor))){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select valid date of retirement");
        }

        $pan_no = DB::table('optcl_employee_master')
                ->where('employee_code', '!=', Auth::user()->employee_code)
                ->where('pf_account_no', $request->pf_acno)
                ->where('pf_account_type_id', $request->pf_acc_type)
                ->first();

        if(!empty($pan_no)) {
            $validation['error'][] = array("id" => "pf_acno-error","eValue" => "PF a/c no. already exists with different employeer");
        }

        $nominee_id = DB::table('optcl_employee_nominee_details')
        ->where('employee_code',Auth::user()->employee_code)
        ->where('mobile_no',Auth::user()->mobile)
        ->first();
        //dd($nominee_id);

        if(!isset($validation['error'])){
         DB::beginTransaction();
         try{
            $pension_column = new NomineeApplicationForm();
            //$pension_column->nominee_id = $nominee_id->id;
            $pension_column->employee_code = $emp_code;
            $pension_column->aadhaar_no = $aadhaar_no;
            $pension_column->employee_name = $name;
            $pension_column->designation_id = $designation;
            $pension_column->date_of_birth = date('Y-m-d', strtotime($dob));
            $pension_column->father_name = $father_name;
            $pension_column->gender_id = $gender;
            $pension_column->marital_status_id = $marital_status;
            $pension_column->husband_name = $husband_name;
            $pension_column->religion_id = $religion;
            $pension_column->pf_account_type_id = $pf_acno_type;   
            $pension_column->pf_account_no = $pf_acno;
            $pension_column->optcl_unit_id = $name_of_office_dept;
            $pension_column->date_of_joining = date('Y-m-d', strtotime($doj));
            $pension_column->date_of_retirement = date('Y-m-d', strtotime($dor));
            $pension_column->created_by = Auth::user()->id;
            $pension_column->created_at = $this->current_date;
            $pension_column->save();
            $lastID = $pension_column->id;
            $proposal_no = "OPTCL-".sprintf('%05d',$lastID)."-".date('Y');
            NomineeApplicationForm::where('id', $lastID)
                         ->update(['application_no' => $proposal_no]);
            // Update Unit id in user table
            DB::table('optcl_users')->where('id', Auth::user()->id)->update(['optcl_unit_id' => $name_of_office_dept]);

            Session::put('application_no', $lastID);
            //Session::put('step_one', 'true');             

            DB::commit();
         }catch (\Throwable $e) {
             DB::rollback();
             throw $e;
         } 
        }
        echo json_encode($validation);
    }
    // nominee application form 1 edit
    public function edit_nominee_application_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $application_no = Session::get('application_no');
        //dd($application_no);
        $user_details = Auth::user();
        $religions = Religion::where('status', 1)->where('deleted', 0)->get();
        $office_last_served = OfficeLastServed::where('status', 1)->where('deleted', 0)->get();
        $pensioner_designation = PensionerDesignation::where('status', 1)->where('deleted', 0)->get();     
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $account_types = DB::table('optcl_pf_account_type_master')->where('status', 1)->where('deleted', 0)->get();
        $pensionerDetails = DB::table('optcl_nominee_master')
                             ->where('employee_code', Auth::user()->employee_code)
                             ->first();
        //dd($pensionerDetails);
        return view('user.nominee.edit_nominee_application_form', compact('religions', 'office_last_served','pensioner_designation','user_details', 'mstatus', 'genders','account_types', 'pensionerDetails'));
    }
     
    public function update_nominee_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }

        $validation = array();
        $emp_code = $request->emp_code;
        if($emp_code == ""){
         $validation['error'][] = array("id" => "emp_code-error","eValue" => "Please enter employee code");
        }
        $aadhaar_no = $request->aadhaar_no;
        if($aadhaar_no == ""){
         $validation['error'][] = array("id" => "aadhaar_no-error","eValue" => "Please enter aadhaar no");
        }
        $name = $request->name;
        if($name == ""){
         $validation['error'][] = array("id" => "name-error","eValue" => "Please enter name");
        }
        $designation = $request->designation;
        if($designation == ""){
         $validation['error'][] = array("id" => "designation-error","eValue" => "Please select designation");
        }
        $father_name = $request->father_name;
        if($father_name == ""){
         $validation['error'][] = array("id" => "father_name-error","eValue" => "Please father name");
        }
        $gender = $request->gender;
        if($gender == ""){
         $validation['error'][] = array("id" => "gender-error","eValue" => "Please select gender");
        }
        $marital_status = $request->marital_status;
        if($marital_status == ""){
         $validation['error'][] = array("id" => "marital_status-error","eValue" => "Please select marital status");
        }
        $husband_name = $request->husband_name;
        if($gender == 2 && $marital_status == 1 && $husband_name == ""){
            $validation['error'][] = array("id" => "husband_name-error","eValue" => "Please enter husband name");
        }
        $religion = $request->religion;
        if($religion == ""){
         $validation['error'][] = array("id" => "religion-error","eValue" => "Please select religion");
        }
        $pf_acno_type = $request->pf_acc_type;
        if($pf_acno_type == ""){
         $validation['error'][] = array("id" => "pf_acno_type-error","eValue" => "Please select PF a/c type");
        }
        $pf_acno = $request->pf_acno;
        if($pf_acno == ""){
            $validation['error'][] = array("id" => "pf_acno-error","eValue" => "Please enter PF a/c no.");
        }
        $name_of_office_dept = $request->name_of_office_dept;
        if($name_of_office_dept == ""){
         $validation['error'][] = array("id" => "name_of_office_dept-error","eValue" => "Please select office/ dept served");
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

        if(date('Y-m-d',strtotime($doj)) < date('Y-m-d',strtotime($dob))) {
            $validation['error'][] = array("id" => "doj-error","eValue" => "Please select valid date of joining in service");
        }

        if(date('Y-m-d',strtotime($doj)) > date('Y-m-d',strtotime($dor))){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select valid date of retirement");
        }

        $pan_no = DB::table('optcl_employee_master')
                ->where('employee_code', '!=', Auth::user()->employee_code)
                ->where('pf_account_no', $request->pf_acno)
                ->where('pf_account_type_id', $request->pf_acc_type)
                ->first();

        if(!empty($pan_no)) {
            $validation['error'][] = array("id" => "pf_acno-error","eValue" => "PF a/c no. already exists with different employeer");
        }

        $nominee_id = DB::table('optcl_employee_nominee_details')
                ->where('employee_code',Auth::user()->employee_code)
                ->where('mobile_no',Auth::user()->mobile)
                ->first();
        //dd($nominee_id);

        if(!isset($validation['error'])){
         DB::beginTransaction();
         try{
            $pension_form_id = $request->pension_form_id;
            $pension_column = new NomineeApplicationForm();
            $pension_column->exists = true;
            $pension_column->id = $pension_form_id;
            // $pension_column->nominee_id = $nominee_id->id;
            $pension_column->employee_code = $emp_code;
            $pension_column->aadhaar_no = $aadhaar_no;
            $pension_column->employee_name = $name;
            $pension_column->designation_id = $designation;
            $pension_column->date_of_birth = date('Y-m-d', strtotime($dob));
            $pension_column->father_name = $father_name;
            $pension_column->gender_id = $gender;
            $pension_column->marital_status_id = $marital_status;
            $pension_column->husband_name = $husband_name;
            $pension_column->religion_id = $religion;
            $pension_column->pf_account_type_id = $pf_acno_type;   
            $pension_column->pf_account_no = $pf_acno;
            $pension_column->optcl_unit_id = $name_of_office_dept;
            $pension_column->date_of_joining = date('Y-m-d', strtotime($doj));
            $pension_column->date_of_retirement = date('Y-m-d', strtotime($dor));
            $pension_column->modified_by = Auth::user()->id;
            $pension_column->modified_at = $this->current_date;

            //dd($pension_column);
            $pension_column->save();
            // Update Unit id in user table
            DB::table('optcl_users')->where('id', Auth::user()->id)->update(['optcl_unit_id' => $name_of_office_dept]);
            /*$application_no = Session::get('application_no');
            dd($application_no);*/

            DB::commit();
         }catch (\Throwable $e) {
             DB::rollback();
             throw $e;
         } 
        }
        echo json_encode($validation);
    }
    // save as draft nominee form     
    public function save_as_draft_nominee_form(Request $request){
         // Check all data submitted or not
         if(Util::check_nominee_submission()){
             return redirect()->route('nominee_application_view_details');
         }  
         //dd($request->all());      
         try{
             DB::beginTransaction();
             $dob = $request->dob != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->dob))) : NULL;       
             $doj = $request->doj != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->doj))) : NULL;
             $dor = $request->dor != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->dor))) : NULL;
             //dd($dob, $doj, $dor);
             $chech_pension_data = NomineeApplicationForm::where('employee_code', $request->emp_code)->first();
             //dd($chech_pension_data);
             if(!empty($chech_pension_data)){                
                 $pension_column = new NomineeApplicationForm();
                 $pension_column->exists = true;
                 $pension_column->id = $chech_pension_data->id;
                 $pension_column->employee_code = $request->emp_code;
                 $pension_column->aadhaar_no = $request->aadhaar_no;
                 $pension_column->employee_name = $request->name;
                 $pension_column->designation_id = $request->designation;
                 $pension_column->father_name = $request->father_name;
                 $pension_column->gender_id = $request->gender;
                 $pension_column->marital_status_id = $request->marital_status;
                 $pension_column->husband_name = $request->husband_name ? $request->husband_name : NULL;
                 $pension_column->religion_id = $request->religion;
                 $pension_column->pf_account_type_id = $request->pf_acc_type;   
                 $pension_column->pf_account_no = $request->pf_acno;
                 $pension_column->optcl_unit_id = $request->name_of_office_dept;
                 $pension_column->date_of_birth = $dob;
                 $pension_column->date_of_joining = $doj;
                 $pension_column->date_of_retirement = $dor;
                 $pension_column->modified_by = Auth::user()->id;
                 $pension_column->modified_at = $this->current_date;
                 $pension_column->save();
                 $lastID = $chech_pension_data->id;
             }else{
                 $pension_column = new NomineeApplicationForm();
                 $pension_column->employee_code = $request->emp_code;
                 $pension_column->aadhaar_no = $request->aadhaar_no;
                 $pension_column->employee_name = $request->name;
                 $pension_column->designation_id = $request->designation;
                 $pension_column->father_name = $request->father_name;
                 $pension_column->gender_id = $request->gender;
                 $pension_column->marital_status_id = $request->marital_status;
                 $pension_column->husband_name = $request->husband_name;
                 $pension_column->religion_id = $request->religion;
                 $pension_column->pf_account_type_id = $request->pf_acno_type;   
                 $pension_column->pf_account_no = $request->pf_acno;
                 $pension_column->optcl_unit_id = $request->name_of_office_dept;
                 $pension_column->date_of_birth = $dob;
                 $pension_column->date_of_joining = $doj;
                 $pension_column->date_of_retirement = $dor;
                 $pension_column->created_by = Auth::user()->id;
                 $pension_column->created_at = $this->current_date;
                 $pension_column->save();
                 $lastID = $pension_column->id;
             }
             Session::put('application_no', $lastID);
 
             DB::commit();
             return response()->json( array('status' => 'success'));            
         }catch (\Throwable $e) {
             DB::rollback();
             throw $e;
         } 
    }  
    // nominee 2nd form or family pensioner form
    public function nominee_family_pensioner_form(Request $request){
        $user_details = Auth::user();
        $emp_code = Auth::user()->employee_code;
        /*$nominee_id = DB::table('optcl_employee_nominee_details')
                        ->where('employee_code',$emp_code)
                        ->where('mobile_no',Auth::user()->mobile)
                        ->first();*/
        //dd($nominee_id);
        //dd($emp_code);
        //DB::enableQueryLog();
        $checkStatus = DB::table('optcl_nominee_family_pensioner_form')
                            ->where('employee_code', $emp_code)
                            //->where('nominee_id',$nominee_id->id)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'DESC')
                            ->limit(1)
                            ->first();
        //dd($checkStatus->id);
        //dd(DB::getQueryLog());
        //$application_no = Session::get('application_no');
        //dd($application_no);
        if(!empty($checkStatus)){
            Session::put('application_no', $checkStatus->nominee_master_id);
            return redirect()->route('edit_nominee_family_pensioner_form');
        }
        //echo Session::get('application_no');
        // fetch data for country table 
        $country   = DB::table('optcl_country_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
         // fetch data for bank master table 
        $banks     = DB::table('optcl_bank_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
        // fetch data for relation master table 
        $relations = DB::table('optcl_relation_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
        // fetch data for pension unit table 
        $last_served = DB::table('optcl_pension_unit_master')
                         ->where('status', 1)
                         ->where('deleted', 0)
                         ->get();
        //Get Nominee details 
        $nominee_details = DB::table('optcl_employee_nominee_details')
                                ->where('mobile_no', Auth::user()->mobile)
                                ->first();
        //dd($nominee_details);
        // fetch employee details 
        $employee_master = DB::table('optcl_employee_master')
                            ->where('employee_code', $emp_code)
                            ->first();
        $employee_personal_details = DB::table('optcl_employee_personal_details')
                                        ->where('employee_code', $emp_code)
                                        ->first();
        $nomineeFamilyPensioner = DB::table('optcl_nominee_family_pensioner_form')
                                    ->where('employee_code', $emp_code)
                                    //->where('nominee_id', $nominee_details->id)
                                    ->first();
        
        
        if(empty($nomineeFamilyPensioner)){ 
            $nomineeFamilyPensioner = array();
        }
        
        return view('user.nominee.family_pensioner_form', compact('country','banks','last_served','relations','employee_master','nominee_details','employee_personal_details','nomineeFamilyPensioner'));
    }

    public function get_state(Request $request){
        $cid=$request->post('cid');
        $state=DB::table('optcl_state_master')->where('country_id',$cid)->get();
        $html='<option value="">Select State</option>';
        foreach($state as $list){
            $html.='<option value="'.$list->id.'">'.$list->state_name.'</option>';
        }
        echo $html;
    }
    
    public function get_district(Request $request){
        $sid=$request->post('sid');
        $district=DB::table('optcl_district_master')->where('state_id',$sid)->get();
        $html='<option value="">Select District</option>';
        foreach($district as $list){
            $html.='<option value="'.$list->id.'">'.$list->district_name.'</option>';
        }
        echo $html;
    }

    public function get_branch(Request $request){
        $bankID = $request->bank_id;
        $branches = DB::table('optcl_bank_branch_master')->where('bank_id', $bankID)->where('status', 1)->where('deleted', 0)->get(); ?>
        <option value="">Select Branch</option>
        <?php foreach($branches as $branch){ ?>
            <option value="<?php echo $branch->id; ?>"><?php echo $branch->branch_name; ?></option>
        <?php 
        }
    }

    public function get_branch_details(Request $request){
        $bank_branch_id = $request->bank_branch_id;
        $branches = DB::table('optcl_bank_branch_master')->where('id', $bank_branch_id)->where('status', 1)->where('deleted', 0)->first();
        $branch_details = [
            "ifsc_code" => $branches->ifsc_code,
            "micr_code" => $branches->micr_code,
        ];
        echo json_encode($branch_details);
    }

    // save nominee application form 2 or nominee family pensioner form
    public function save_nominee_family_pensioner_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        // dd($request->all());
        $validation = array();

        $full_name = $request->full_name;
        //dd($full_name);
        if($full_name == ""){
            $validation['error'][] = array("id" => "full_name-error","eValue" => "Please enter full name");
        }
        $ppo_no = $request->ppo_no;
        if($ppo_no == ""){
            $validation['error'][] = array("id" => "ppo_no-error","eValue" => "Please enter PPO No");
        }
        //dd($ppo_no);
        $dod = $request->dod;
        if($dod == ""){
            $validation['error'][] = array("id" => "dod-error","eValue" => "Please select date of death");
        }else{
            $dod = str_replace("/","-",$dod);
        }

        // $death_certificate = $request->death_certificate;
        // if($death_certificate == ""){
        //     $validation['error'][] = array("id" => "death_certificate-error","eValue" => "Please upload death certificate");
        // }
        $applicant_name = $request->applicant_name;
        if($applicant_name == ""){
            $validation['error'][] = array("id" => "applicant_name-error","eValue" => "Please enter applicant name");
        }
        
        $relationship = $request->relationship;
        if($relationship == ""){
            $validation['error'][] = array("id" => "relationship-error","eValue" => "Please select relationship");
        }
        $employment_status = $request->employment_status;
        $particular_of_employment = $request->particular_of_employment;       
        if($employment_status == 1){
            if($particular_of_employment == ""){
                $validation['error'][] = array("id" => "particular_of_employment-error","eValue" => "Please enter particular of employment");
            }
        }else{
            $particular_of_pension = null;
        }

        $pension_status = $request->pension_status;
        $particular_of_pension = $request->particular_of_pension;       
        if($pension_status == 1){
            if($particular_of_pension == ""){
                $validation['error'][] = array("id" => "particular_of_pension-error","eValue" => "Please enter particular of pension");
            }
        }else{
            $particular_of_pension = null;
        }

        $atpost = $request->atpost;
        if($atpost == ""){
            $validation['error'][] = array("id" => "atpost-error","eValue" => "Please enter at");
        }
        $postoffice = $request->postoffice;
        if($postoffice == ""){
            $validation['error'][] = array("id" => "postoffice-error","eValue" => "Please enter post office");
        }
        $pincode = $request->pincode;
        if($pincode == ""){
            $validation['error'][] = array("id" => "pincode-error","eValue" => "Please enter pin code");
        }
        $country = $request->country;
        if($country == ""){
            $validation['error'][] = array("id" => "country-error","eValue" => "Please select country");
        }
        $state = $request->state;
        if($state == ""){
            $validation['error'][] = array("id" => "state-error","eValue" => "Please select state");
        }
        $district = $request->district;
        if($district == ""){
            $validation['error'][] = array("id" => "district-error","eValue" => "Please select district");
        }
       
        $saving_bank_ac_no = $request->saving_bank_ac_no;
        if($saving_bank_ac_no == ""){
            $validation['error'][] = array("id" => "saving_bank_ac_no-error","eValue" => "Please enter bank account no");
        }
        $bank_name = $request->bank_name;
        if($bank_name == ""){
            $validation['error'][] = array("id" => "bank_name-error","eValue" => "Please select bank ");
        }
        $branch_name_address = $request->branch_name_address;
        if($branch_name_address == ""){
            $validation['error'][] = array("id" => "branch_name_address-error","eValue" => "Please select branch");
        }
        $ifsc_code = $request->ifsc_code;
        if($ifsc_code == ""){
            $validation['error'][] = array("id" => "ifsc_code-error","eValue" => "Please enter ifsc code");
        }
        $micr_code = $request->micr_code;
        
        $name_of_the_unit = $request->name_of_the_unit;
        if($name_of_the_unit == ""){
            $validation['error'][] = array("id" => "name_of_the_unit-error","eValue" => "Please select unit");
        }
        $if_yes = $request->if_yes;
        $civil_service = $request->civil_service;
        $gratuity_recieved = $request->gratuity_recieved;        
        if($if_yes == 1){
            if($civil_service == ""){
                $validation['error'][] = array("id" => "civil_service-error","eValue" => "Please enter particular civil service name");
            }
        }else{
            $civil_service = null;
        }
        if($if_yes == 1){
            if($gratuity_recieved == ""){
                $validation['error'][] = array("id" => "gratuity_recieved-error","eValue" => "Please enter amount and nature of any pension or gratuity received");
            }
        }else{
            $gratuity_recieved = null;
        }
        $addmissible = $request->addmissible;
        $addmissble_value = $request->addmissble_value;
        $addmissible_family = $request->addmissible_family;
        $addmissible_family_name = $request->addmissible_family_name;
        if($addmissible == 1){
            if($addmissble_value == ""){
                $validation['error'][] = array("id" => "addmissble_value-error","eValue" => "Please enter addmissble value");
            }
        }else{
            $addmissble_value = null;
        }
        if($addmissible == 1){
            if($addmissible_family == ""){
                $validation['error'][] = array("id" => "addmissible_family-error","eValue" => "Please select member");
            }
        }else{
            $addmissible_family = null;
        }
        if($addmissible == 1){
            if($addmissible_family_name == ""){
                $validation['error'][] = array("id" => "addmissible_family_name-error","eValue" => "Please enter name of member");
            }
        }else{
            $addmissible_family_name = null;
        }
        
        // $basic_pay = $request->basic_pay;
        // if($basic_pay == ""){
        //     $validation['error'][] = array("id" => "basic_pay-error","eValue" => "Please enter last basic pay");
        // }
        // $death_certificate = '';
        // $upload_death_certificate = 'uploads/pensioner_death_certificate';
        // $filename = Util::rand_filename($request->file('death_certificate')->getClientOriginalExtension());
        // $deathCertificate = Util::upload_file($request->file('death_certificate'), $filename, null, $upload_death_certificate);

        // $death_certificate_path_file = !empty($request->death_certificate_path_hidden) ? $request->death_certificate_path_hidden: NULL;
        // $deathCertificate='';
        //     if(!empty($death_certificate_path_file)) {

        //         list($type, $cropped_image) = explode(';', $death_certificate_path_file);
        //         list(, $cropped_image) = explode(',', $cropped_image);
        //         $cropped_image = base64_decode($cropped_image);
        //         $image_name = time().'-'.'1'.rand(0,999999999).'.png';
        //         file_put_contents('public/uploads/pensioner_death_certificate/'.$image_name, $cropped_image);
        //         $deathCertificate = 'uploads/pensioner_death_certificate/'.$image_name;
                
        //     }
        //Get Nominee details 
        /*$nominee_details = DB::table('optcl_employee_nominee_details')
                                ->where('mobile_no', Auth::user()->mobile)
                                ->first();
        $nominee_id = $nominee_details->id;*/
        $checkbankacno = DB::table('optcl_employee_personal_details')->where('savings_bank_account_no',$saving_bank_ac_no)->where('status',1)->where('deleted',0)->first();

        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $chech_nominee_data = NomineeFamilyPensionerForm::where('employee_code', Auth::user()->employee_code)
                //->where('nominee_id',$nominee_id)
                ->first();
                $death_certificate_path_file = !empty($request->death_certificate_path_hidden) ? $request->death_certificate_path_hidden: NULL;
                //dd($death_certificate_path_file);
                $deathCertificate='';
                if(!empty($death_certificate_path_file)) {
                    list($type, $cropped_image) = explode(';', $death_certificate_path_file);
                    list(, $cropped_image) = explode(',', $cropped_image);
                    $cropped_image = base64_decode($cropped_image);
                    $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                    file_put_contents('public/uploads/pensioner_death_certificate/'.$image_name, $cropped_image);
                    $deathCertificate = 'uploads/pensioner_death_certificate/'.$image_name;
                } else {
                    $deathCertificate = !empty($chech_nominee_data->death_certificate) ? $chech_nominee_data->death_certificate : NULL;
                }
                //dd($nominee_id);
                $nominee_master_id = Session::get('application_no');
                if(empty($checkbankacno)){
                    $nominee_pensioner = new NomineeFamilyPensionerForm();
                    $nominee_pensioner->nominee_master_id = $nominee_master_id;
                    //$nominee_pensioner->nominee_id = $nominee_id;
                    $nominee_pensioner->employee_code = Auth::user()->employee_code;
                    $nominee_pensioner->full_name = $full_name;
                    $nominee_pensioner->ppo_no = $ppo_no;
                    $nominee_pensioner->dod = date('Y-m-d', strtotime($dod));
                    $nominee_pensioner->death_certificate = $deathCertificate;
                    $nominee_pensioner->applicant_name = $applicant_name;
                    $nominee_pensioner->relationship_id = $relationship;
                    $nominee_pensioner->is_employment_status = $employment_status;
                    $nominee_pensioner->particular_of_employment = $particular_of_employment;
                    $nominee_pensioner->is_pension_status = $pension_status;
                    $nominee_pensioner->particular_of_pension = $particular_of_pension;
                    $nominee_pensioner->postal_addr_at = $atpost;
                    $nominee_pensioner->postal_addr_post = $postoffice;
                    $nominee_pensioner->postal_addr_pincode = $pincode;
                    $nominee_pensioner->postal_addr_country_id = $country;
                    $nominee_pensioner->postal_addr_state_id = $state;
                    $nominee_pensioner->postal_addr_district_id = $district;
                    $nominee_pensioner->saving_bank_ac_no = $saving_bank_ac_no;
                    $nominee_pensioner->bank_id = $bank_name;
                    $nominee_pensioner->bank_branch_id = $branch_name_address;
                    // $nominee_pensioner->basic_pay_of_pensioner_time_of_retirement = $basic_pay;
                    $nominee_pensioner->pension_unit_id = $name_of_the_unit;
                    $nominee_pensioner->is_civil_service_amount_received = $if_yes;
                    $nominee_pensioner->civil_service_name = $civil_service;
                    $nominee_pensioner->pension_gratuity_received_amount = $gratuity_recieved;
                    $nominee_pensioner->is_family_pension_received_by_family_members = $addmissible;
                    $nominee_pensioner->admissible_form_any_other_source_to_the_retired_employee = $addmissble_value;
                    $nominee_pensioner->family_member_relation_id = $addmissible_family;
                    $nominee_pensioner->family_member_name = $addmissible_family_name;
                    $nominee_pensioner->status = 1;
                    $nominee_pensioner->deleted = 0;
                    $nominee_pensioner->created_by = Auth::user()->id;
                    $nominee_pensioner->created_at = $this->current_date;
                    $nominee_pensioner->save();
                    $lastID = $nominee_pensioner->id;
                    Session::put('step_two', 'true');
                    // Update Pension Unit ID in user table
                    DB::table('optcl_users')->where('id', Auth::user()->id)->update(['pension_unit_id' => $name_of_the_unit]);
                    /*DB::table('optcl_employee_nominee_details')
                        ->where('id',$nominee_id)
                        ->where('employee_code',$nominee_details->employee_code)
                        ->update([
                            'savings_bank_account_no' => $saving_bank_ac_no,
                            'bank_id'                 => $bank_name,
                            'bank_branch_id'          => $branch_name_address,
                            'modified_by'             => $nominee_id,
                            'modified_at'             => $this->current_date
                        ]); */
                    //return redirect()->route('edit_nominee_application_form');
                    DB::commit();
                } else {
                    //dd(1);
                    /*Session::flash('error', 'Bank account no already exists');
                    return redirect()->route('nominee_family_pensioner_form');*/

                    $validation['error'][] = array("id" => "saving_bank_ac_no-error","eValue" => "Bank account no already exists");
                    // return response()->json( array('status' => 'error'));
                }
                    
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            }
        }
        echo json_encode($validation);  
    }

    public function edit_nominee_family_pensioner_form(){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $application_no = Session::get('application_no');
        //dd($application_no);

        $user_details = Auth::user();
        $emp_code = Auth::user()->employee_code;
        $mobile   = Auth::user()->mobile; 
        //dd($emp_code);
        // fetch data for country table 
        $country   = DB::table('optcl_country_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
         // fetch data for bank master table 
        $banks     = DB::table('optcl_bank_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
        // fetch data for relation master table 
        $relations = DB::table('optcl_relation_master')
                     ->where('status', 1)
                     ->where('deleted', 0)
                     ->get();
        // fetch data for pension unit table 
        $last_served = DB::table('optcl_pension_unit_master')
                         ->where('status', 1)
                         ->where('deleted', 0)
                         ->get();
        //Get Nominee details 
        $nominee_details = DB::table('optcl_employee_nominee_details')
                                ->where('mobile_no', Auth::user()->mobile)
                                ->first();
        // fetch employee details 
        $employee_master = DB::table('optcl_employee_master')
                            ->where('employee_code', $emp_code)
                            ->first();
        $employee_personal_details = DB::table('optcl_employee_personal_details')
                                        ->where('employee_code', $emp_code)
                                        ->first();
        //Get all details
        $nomineeFamilyPensioner = DB::table('optcl_nominee_family_pensioner_form')
                                ->where('employee_code', $emp_code)
                                ->first();
        //dd($nomineeFamilyPensioner);        

        return view('user.nominee.edit_family_pensioner_form', compact('country','banks','last_served','nomineeFamilyPensioner','relations','employee_master'));
    }

    public function update_nominee_family_pensioner_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        //dd($request->all());
        $validation = array();

        $full_name = $request->full_name;
        //dd($full_name);
        if($full_name == ""){
            $validation['error'][] = array("id" => "full_name-error","eValue" => "Please enter full name");
        }
        $ppo_no = $request->ppo_no;
        if($ppo_no == ""){
            $validation['error'][] = array("id" => "ppo_no-error","eValue" => "Please enter PPO No");
        }
        //dd($ppo_no);
        $dod = $request->dod;
        if($dod == ""){
            $validation['error'][] = array("id" => "dod-error","eValue" => "Please select date of death");
        }else{
            $dod = str_replace("/","-",$dod);
        }

        // $death_certificate = $request->death_certificate;
        // if($death_certificate == ""){
        //     $validation['error'][] = array("id" => "death_certificate-error","eValue" => "Please upload death certificate");
        // }
        $applicant_name = $request->applicant_name;
        if($applicant_name == ""){
            $validation['error'][] = array("id" => "applicant_name-error","eValue" => "Please enter applicant name");
        }
        
        $relationship = $request->relationship;
        if($relationship == ""){
            $validation['error'][] = array("id" => "relationship-error","eValue" => "Please select relationship");
        }
        $employment_status = $request->employment_status;
        $particular_of_employment = $request->particular_of_employment;       
        if($employment_status == 1){
            if($particular_of_employment == ""){
                $validation['error'][] = array("id" => "particular_of_employment-error","eValue" => "Please enter particular of employment");
            }
        }else{
            $particular_of_pension = null;
        }

        $pension_status = $request->pension_status;
        $particular_of_pension = $request->particular_of_pension;       
        if($pension_status == 1){
            if($particular_of_pension == ""){
                $validation['error'][] = array("id" => "particular_of_pension-error","eValue" => "Please enter particular of pension");
            }
        }else{
            $particular_of_pension = null;
        }

        $atpost = $request->atpost;
        if($atpost == ""){
            $validation['error'][] = array("id" => "atpost-error","eValue" => "Please enter at");
        }
        $postoffice = $request->postoffice;
        if($postoffice == ""){
            $validation['error'][] = array("id" => "postoffice-error","eValue" => "Please enter post office");
        }
        $pincode = $request->pincode;
        if($pincode == ""){
            $validation['error'][] = array("id" => "pincode-error","eValue" => "Please enter pin code");
        }
        $country = $request->country;
        if($country == ""){
            $validation['error'][] = array("id" => "country-error","eValue" => "Please select country");
        }
        $state = $request->state;
        if($state == ""){
            $validation['error'][] = array("id" => "state-error","eValue" => "Please select state");
        }
        $district = $request->district;
        if($district == ""){
            $validation['error'][] = array("id" => "district-error","eValue" => "Please select district");
        }
       
        $saving_bank_ac_no = $request->saving_bank_ac_no;
        if($saving_bank_ac_no == ""){
            $validation['error'][] = array("id" => "saving_bank_ac_no-error","eValue" => "Please enter bank account no");
        }
        $bank_name = $request->bank_name;
        if($bank_name == ""){
            $validation['error'][] = array("id" => "bank_name-error","eValue" => "Please select bank ");
        }
        $branch_name_address = $request->branch_name_address;
        if($branch_name_address == ""){
            $validation['error'][] = array("id" => "branch_name_address-error","eValue" => "Please select branch");
        }
        $ifsc_code = $request->ifsc_code;
        if($ifsc_code == ""){
            $validation['error'][] = array("id" => "ifsc_code-error","eValue" => "Please enter ifsc code");
        }
        $micr_code = $request->micr_code;
        
        $name_of_the_unit = $request->name_of_the_unit;
        if($name_of_the_unit == ""){
            $validation['error'][] = array("id" => "name_of_the_unit-error","eValue" => "Please select unit");
        }
        $if_yes = $request->if_yes;
        $civil_service = $request->civil_service;
        $gratuity_recieved = $request->gratuity_recieved;        
        if($if_yes == 1){
            if($civil_service == ""){
                $validation['error'][] = array("id" => "civil_service-error","eValue" => "Please enter particular civil service name");
            }
        }else{
            $civil_service = null;
        }
        if($if_yes == 1){
            if($gratuity_recieved == ""){
                $validation['error'][] = array("id" => "gratuity_recieved-error","eValue" => "Please enter amount and nature of any pension or gratuity received");
            }
        }else{
            $gratuity_recieved = null;
        }
        $addmissible = $request->addmissible;
        $addmissble_value = $request->addmissble_value;
        $addmissible_family = $request->addmissible_family;
        $addmissible_family_name = $request->addmissible_family_name;
        if($addmissible == 1){
            if($addmissble_value == ""){
                $validation['error'][] = array("id" => "addmissble_value-error","eValue" => "Please enter addmissble value");
            }
        }else{
            $addmissble_value = null;
        }
        if($addmissible == 1){
            if($addmissible_family == ""){
                $validation['error'][] = array("id" => "addmissible_family-error","eValue" => "Please select member");
            }
        }else{
            $addmissible_family = null;
        }
        if($addmissible == 1){
            if($addmissible_family_name == ""){
                $validation['error'][] = array("id" => "addmissible_family_name-error","eValue" => "Please enter name of member");
            }
        }else{
            $addmissible_family_name = null;
        }
        
        //Get Nominee details 
        /*$nominee_details = DB::table('optcl_employee_nominee_details')
                                ->where('mobile_no', Auth::user()->mobile)
                                ->first();
        $nominee_id = $nominee_details->id;*/

        $application_no = Session::get('application_no');

        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $chech_nominee_data = NomineeFamilyPensionerForm::where('employee_code', Auth::user()->employee_code)->first();
                $death_certificate_path_file = !empty($request->death_certificate_path_hidden) ? $request->death_certificate_path_hidden: NULL;
                //dd($death_certificate_path_file);
                $deathCertificate='';
                if(!empty($death_certificate_path_file)) {
                    list($type, $cropped_image) = explode(';', $death_certificate_path_file);
                    list(, $cropped_image) = explode(',', $cropped_image);
                    $cropped_image = base64_decode($cropped_image);
                    $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                    file_put_contents('public/uploads/pensioner_death_certificate/'.$image_name, $cropped_image);
                    $deathCertificate = 'uploads/pensioner_death_certificate/'.$image_name;
                } else {
                    $deathCertificate = !empty($chech_nominee_data->death_certificate) ? $chech_nominee_data->death_certificate : NULL;
                }
                $family_pensioner_form_id = $request->family_pensioner_form_id;
                $nominee_pensioner = new NomineeFamilyPensionerForm();
                $nominee_pensioner->exists = true;
                $nominee_pensioner->id = $family_pensioner_form_id;
                $nominee_pensioner->nominee_master_id = $application_no;
                //$nominee_pensioner->nominee_id = $nominee_id;
                $nominee_pensioner->employee_code = Auth::user()->employee_code;
                $nominee_pensioner->full_name = $full_name;
                $nominee_pensioner->ppo_no = $ppo_no;
                $nominee_pensioner->dod = date('Y-m-d', strtotime($dod));
                $nominee_pensioner->death_certificate = $deathCertificate;
                //$nominee_pensioner->death_certificate = $request->death_certificate;
                $nominee_pensioner->applicant_name = $applicant_name;
                $nominee_pensioner->relationship_id = $relationship;
                $nominee_pensioner->is_employment_status = $employment_status;
                $nominee_pensioner->particular_of_employment = $particular_of_employment;
                $nominee_pensioner->is_pension_status = $pension_status;
                $nominee_pensioner->particular_of_pension = $particular_of_pension;
                $nominee_pensioner->postal_addr_at = $atpost;
                $nominee_pensioner->postal_addr_post = $postoffice;
                $nominee_pensioner->postal_addr_pincode = $pincode;
                $nominee_pensioner->postal_addr_country_id = $country;
                $nominee_pensioner->postal_addr_state_id = $state;
                $nominee_pensioner->postal_addr_district_id = $district;
                $nominee_pensioner->saving_bank_ac_no = $saving_bank_ac_no;
                $nominee_pensioner->bank_id = $bank_name;
                $nominee_pensioner->bank_branch_id = $branch_name_address;
                // $nominee_pensioner->basic_pay_of_pensioner_time_of_retirement = $basic_pay;
                $nominee_pensioner->pension_unit_id = $name_of_the_unit;
                $nominee_pensioner->is_civil_service_amount_received = $if_yes;
                $nominee_pensioner->civil_service_name = $civil_service;
                $nominee_pensioner->pension_gratuity_received_amount = $gratuity_recieved;
                $nominee_pensioner->is_family_pension_received_by_family_members = $addmissible;
                $nominee_pensioner->admissible_form_any_other_source_to_the_retired_employee = $addmissble_value;
                $nominee_pensioner->family_member_relation_id = $addmissible_family;
                $nominee_pensioner->family_member_name = $addmissible_family_name;
                $nominee_pensioner->status = 1;
                $nominee_pensioner->deleted = 0;
                $nominee_pensioner->created_by = Auth::user()->id;
                $nominee_pensioner->created_at = $this->current_date;
                $nominee_pensioner->save();
                $lastID = $nominee_pensioner->id;
                Session::put('step_two', 'true');
                // Update Pension Unit ID in user table
                DB::table('optcl_users')->where('id', Auth::user()->id)->update(['pension_unit_id' => $name_of_the_unit]);
                /*DB::table('optcl_employee_nominee_details')
                    ->where('id',$nominee_id)
                    ->where('employee_code',$nominee_details->employee_code)
                    ->update([
                        'savings_bank_account_no' => $saving_bank_ac_no,
                        'bank_id'                 => $bank_name,
                        'bank_branch_id'          => $branch_name_address,
                        'modified_by'             => $nominee_id,
                        'modified_at'             => $this->current_date
                ]); */
                //return redirect()->route('edit_nominee_application_form');
                DB::commit();
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
              }
        }
        echo json_encode($validation);
    }
    
    public function save_as_draft_nominee_family_pensioner_form(Request $request){
        // Check all data submitted or not
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }  
        //dd($request->all());  
        // $death_certificate = '';
        // $upload_death_certificate = 'uploads/pensioner_death_certificate';
        // $filename = Util::rand_filename($request->file('death_certificate')->getClientOriginalExtension());
        // $deathCertificate = Util::upload_file($request->file('death_certificate'), $filename, null, $upload_death_certificate);
        //dd($filename);

       
        try{
            DB::beginTransaction();
            $dod = $request->dod != "" ? date('Y-m-d', strtotime($request->dod)) : NULL;       
            $nominee_data = DB::table('optcl_employee_nominee_details')->where('mobile_no',Auth::user()->mobile)->first();
            $chech_nominee_data = NomineeFamilyPensionerForm::where('employee_code', Auth::user()->employee_code)->first();
            //dd($nominee_data);
            //dd($chech_nominee_data);
            //$dod = $request->dod;
            //dd($chech_nominee_data);


            $death_certificate_path_file = !empty($request->death_certificate_path_hidden) ? $request->death_certificate_path_hidden: NULL;
            //dd($death_certificate_path_file);
            $deathCertificate='';
    
            if(!empty($death_certificate_path_file)) {
    
                list($type, $cropped_image) = explode(';', $death_certificate_path_file);
                list(, $cropped_image) = explode(',', $cropped_image);
                $cropped_image = base64_decode($cropped_image);
                $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                file_put_contents('public/uploads/pensioner_death_certificate/'.$image_name, $cropped_image);
                $deathCertificate = 'uploads/pensioner_death_certificate/'.$image_name;
            } else {
                $deathCertificate = !empty($chech_nominee_data->death_certificate) ? $chech_nominee_data->death_certificate : NULL;
            }
            // dd($deathCertificate, $chech_nominee_data);
            if(!empty($chech_nominee_data)){                
                $nomineeFamilyPensioner = new NomineeFamilyPensionerForm();
                $nomineeFamilyPensioner->exists = true;
                $nomineeFamilyPensioner->id = $chech_nominee_data->id;
                $nomineeFamilyPensioner->dod = $dod;
                $nomineeFamilyPensioner->death_certificate = $deathCertificate;
                $nomineeFamilyPensioner->applicant_name = $request->applicant_name;
                $nomineeFamilyPensioner->relationship_id = $request->relationship;
                $nomineeFamilyPensioner->is_employment_status = $request->employment_status;
                $nomineeFamilyPensioner->particular_of_employment = $request->particular_of_employment;
                $nomineeFamilyPensioner->is_pension_status = $request->pension_status;
                $nomineeFamilyPensioner->particular_of_pension = $request->particular_of_pension;
                $nomineeFamilyPensioner->postal_addr_at = $request->atpost;
                $nomineeFamilyPensioner->postal_addr_post = $request->postoffice;
                $nomineeFamilyPensioner->postal_addr_pincode = $request->pincode;   
                $nomineeFamilyPensioner->postal_addr_country_id = $request->country;
                $nomineeFamilyPensioner->postal_addr_state_id = $request->state;
                $nomineeFamilyPensioner->postal_addr_district_id = $request->district;
                $nomineeFamilyPensioner->saving_bank_ac_no = $request->saving_bank_ac_no;
                $nomineeFamilyPensioner->bank_id = $request->bank_name;
                $nomineeFamilyPensioner->bank_branch_id = $request->branch_name_address;   
                // $nomineeFamilyPensioner->basic_pay_of_pensioner_time_of_retirement = $request->basic_pay;
                $nomineeFamilyPensioner->pension_unit_id = $request->name_of_the_unit;
                $nomineeFamilyPensioner->is_civil_service_amount_received = $request->if_yes;
                $nomineeFamilyPensioner->civil_service_name = $request->civil_service;
                $nomineeFamilyPensioner->pension_gratuity_received_amount = $request->gratuity_recieved;
                $nomineeFamilyPensioner->is_family_pension_received_by_family_members = $request->addmissible;   
                $nomineeFamilyPensioner->admissible_form_any_other_source_to_the_retired_employee = $request->addmissble_value;
                $nomineeFamilyPensioner->family_member_relation_id = $request->addmissible_family;
                $nomineeFamilyPensioner->family_member_name = $request->addmissible_family_name;
                $nomineeFamilyPensioner->modified_by = Auth::user()->id;
                $nomineeFamilyPensioner->modified_at = $this->current_date;
                $nomineeFamilyPensioner->save();
                
            }else{
                //dd(1);
                $nomineeFamily = new NomineeFamilyPensionerForm();
                $nomineeFamily->nominee_id = $nominee_data->id;
                $nomineeFamily->employee_code = $nominee_data->employee_code;
                $nomineeFamily->full_name = $request->full_name;
                $nomineeFamily->ppo_no = $request->ppo_no;
                $nomineeFamily->dod = $dod;
                $nomineeFamily->death_certificate = $deathCertificate;
                $nomineeFamily->applicant_name = $request->applicant_name;
                $nomineeFamily->relationship_id = $request->relationship;
                $nomineeFamily->is_employment_status = $request->employment_status;
                $nomineeFamily->particular_of_employment = $request->particular_of_employment;
                $nomineeFamily->is_pension_status = $request->pension_status;
                $nomineeFamily->particular_of_pension = $request->particular_of_pension;
                $nomineeFamily->postal_addr_at = $request->atpost;
                $nomineeFamily->postal_addr_post = $request->postoffice;
                $nomineeFamily->postal_addr_pincode = $request->pincode;   
                $nomineeFamily->postal_addr_country_id = $request->country;
                $nomineeFamily->postal_addr_state_id = $request->state;
                $nomineeFamily->postal_addr_district_id = $request->district;
                $nomineeFamily->saving_bank_ac_no = $request->saving_bank_ac_no;
                $nomineeFamily->bank_id = $request->bank_name;
                $nomineeFamily->bank_branch_id = $request->branch_name_address;   
                // $nomineeFamily->basic_pay_of_pensioner_time_of_retirement = $request->basic_pay;
                $nomineeFamily->pension_unit_id = $request->name_of_the_unit;
                $nomineeFamily->is_civil_service_amount_received = $request->if_yes;
                $nomineeFamily->civil_service_name = $request->civil_service;
                $nomineeFamily->pension_gratuity_received_amount = $request->gratuity_recieved;
                $nomineeFamily->is_family_pension_received_by_family_members = $request->addmissible;   
                $nomineeFamily->admissible_form_any_other_source_to_the_retired_employee = $request->addmissble_value;
                $nomineeFamily->family_member_relation_id = $request->addmissible_family;
                $nomineeFamily->family_member_name = $request->addmissible_family_name;
                $nomineeFamily->modified_by = Auth::user()->id;
                $nomineeFamily->modified_at = $this->current_date;
                $nomineeFamily->save();
                
            }
            
            DB::commit();
            return response()->json( array('status' => 'success'));            
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        } 
    }

    public function NomineeForm(){

        $id = Session::get('application_no');
        //dd($id);
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();
        $nominee_prefences = DB::table('optcl_nominee_preference_master')->where('status', 1)->where('deleted', 0)->get();

        $employee_nominee_details = DB::table('optcl_nominee_nominee_details as a')->select('a.id', 'a.nominee_name', 'a.nominee_aadhaar_no', 'b.nominee_prefrence', 'a.nominee_preference_id')
                            ->join('optcl_nominee_preference_master as b', 'a.nominee_preference_id', '=', 'b.id')
                            ->where('a.nominee_master_id', $id)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();
                            //->toSql();
        //dd($employee_nominee_details, $id);

        $nominee_details = array();
        $nominee_preference_ids = array();
        foreach ($employee_nominee_details as $key => $value) {
            $nominee_details[] = [
                'id' => $value->id,
                'nominee_name' => $value->nominee_name,
                'nominee_aadhaar_no' => $value->nominee_aadhaar_no,
                'nominee_preference_id' => $value->nominee_preference_id
            ];

            $nominee_preference_ids[] = $value->nominee_preference_id;
        }

        $nominee_details = json_encode($nominee_details);

        $nominee_preference_ids = implode(',', $nominee_preference_ids);
        return view('user.nominee.nominee_form', compact('banks', 'genders', 'mstatus', 'id', 'relations', 'nominee_prefences', 'employee_nominee_details', 'nominee_details', 'nominee_preference_ids'));
    }

    public function add_new_nominee_data(Request $request) {

        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();

        $nominee_prefences = DB::table('optcl_nominee_preference_master')->where('status', 1)->where('deleted', 0);
        if(!empty($request->nominee_preference_ids)) {
            $nominee_preference_ids = explode(',', $request->nominee_preference_ids);
            $nominee_prefences = $nominee_prefences->whereNotIn('id', $nominee_preference_ids);
        }

        $nominee_prefences = $nominee_prefences->get();

        $key = $request->key;

        $nominee_details = DB::table('optcl_nominee_nominee_details')->where('id', $request->nominee_id)->where('status', 1)->where('deleted', 0)->first();

        if(!empty($nominee_details)) {
            $nominee_details->spouse_death_certificate_path = $nominee_details->{'1st_spouse_death_certificate_path'};
        }

        $returnHTML = view('user.nominee.append.new_nominee', compact('banks', 'genders', 'mstatus', 'key', 'relations', 'nominee_prefences', 'nominee_details'))->render();

        return response()->json( array('html' => $returnHTML, 'nominee_details' => $nominee_details) );
    }

    public function save_nominee_details(Request $request) {
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $validation = array();
        try {
            DB::beginTransaction();

            $nominees = $request->nominee;

            //dd($nominees);
            $id = $request->id;
            // $id = 1;

            if(!empty($nominees)) {

                $nominee_master = DB::table('optcl_nominee_master')->where('id', $id)->first();

                foreach ($nominees as $key => $value) {

                    if(!empty($value['nominee_id'])) {
                        $details = DB::table('optcl_nominee_nominee_details')->where('id', $value['nominee_id'])->where('status', 1)->where('deleted', 0)->first();
                    }

                    $dob_attachment_path = NULL;
                    if(!empty($details->dob_attachment_path)) {
                        $dob_attachment_path = $details->dob_attachment_path;
                    }

                    $upload_path = 'uploads/nominee_documents/';
                    
                    // $dob_attachment_path_file = !empty($request->file('nominee')[$key]['dob_attachment_path']) ? $request->file('nominee')[$key]['dob_attachment_path'] : NULL;
                    $dob_attachment_path_file = !empty($value['dob_attachment_path_hidden']) ? $value['dob_attachment_path_hidden'] : NULL;

                    if(!empty($dob_attachment_path_file)) {

                        /*$filename = Util::rand_filename($dob_attachment_path_file->getClientOriginalExtension());
                        $dob_attachment_path = Util::upload_file($dob_attachment_path_file, $filename, null, $upload_path);*/

                        list($type, $cropped_image) = explode(';', $dob_attachment_path_file);
                        list(, $cropped_image) = explode(',', $cropped_image);
                        $cropped_image = base64_decode($cropped_image);
                        $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                        $dob_attachment_path = 'uploads/nominee_documents/'.$image_name;
                        file_put_contents('public/uploads/nominee_documents/'.$image_name, $cropped_image);
                    }

                    $is_2nd_spouse = 0;
                    $first_spouse_death_date = NULL;
                    $first_spouse_death_certificate_path = NULL;

                    if(!empty($details->{'1st_spouse_death_certificate_path'})) {
                        $first_spouse_death_certificate_path = $details->{'1st_spouse_death_certificate_path'};
                    }

                    if($value['is_2nd_spouse'] == 2) { 
                        $is_2nd_spouse = 1;
                        $first_spouse_death_date = date('Y-m-d', strtotime($value['1st_spouse_death_date']));

                        // $first_spouse_death_certificate_path_file = !empty($request->file('nominee')[$key]['1st_spouse_death_certificate_path']) ? $request->file('nominee')[$key]['1st_spouse_death_certificate_path'] : NULL;

                        $first_spouse_death_certificate_path_file = !empty($value['1st_spouse_death_certificate_path_hidden']) ? $value['1st_spouse_death_certificate_path_hidden'] : NULL;

                        if(!empty($first_spouse_death_certificate_path_file)) {

                            list($type, $cropped_image) = explode(';', $first_spouse_death_certificate_path_file);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                            file_put_contents('public/uploads/nominee_documents/'.$image_name, $cropped_image);
                            $first_spouse_death_certificate_path = 'uploads/nominee_documents/'.$image_name;
                            /*$filename = Util::rand_filename($first_spouse_death_certificate_path_file->getClientOriginalExtension());
                            $first_spouse_death_certificate_path = Util::upload_file($first_spouse_death_certificate_path_file, $filename, null, $upload_path);*/
                        }
                    }

                    $disability_certificate_path = NULL;
                    $disability_percentage = NULL;

                    if(!empty($details->disability_certificate_path)) {
                        $disability_certificate_path = $details->disability_certificate_path;
                    }

                    if($value['is_physically_handicapped'] == 1) {
                        $disability_percentage = $value['disability_percentage'];

                        // $disability_certificate_path_file = !empty($request->file('nominee')[$key]['disability_certificate_path']) ? $request->file('nominee')[$key]['disability_certificate_path'] : NULL;
                        $disability_certificate_path_file = !empty($value['disability_certificate_path_hidden']) ? $value['disability_certificate_path_hidden'] : NULL;

                        if(!empty($disability_certificate_path_file)) {

                            list($type, $cropped_image) = explode(';', $disability_certificate_path_file);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                            $disability_certificate_path = 'uploads/nominee_documents/'.$image_name;
                            file_put_contents('public/uploads/nominee_documents/'.$image_name, $cropped_image);

                            /*$filename = Util::rand_filename($disability_certificate_path_file->getClientOriginalExtension());
                            $disability_certificate_path = Util::upload_file($disability_certificate_path_file, $filename, null, $upload_path);*/
                        }
                    }

                    $legal_guardian_name = NULL;
                    $legal_guardian_age = NULL;
                    $legal_guardian_addr = NULL;
                    $legal_guardian_attachment_path = NULL;

                    if(!empty($details->legal_guardian_attachment_path)) {
                        $legal_guardian_attachment_path = $details->legal_guardian_attachment_path;
                    }

                    if($value['is_minor'] == 1) {
                        $legal_guardian_name = $value['legal_guardian_name'];
                        $legal_guardian_age = $value['legal_guardian_age'];
                        $legal_guardian_addr = $value['legal_guardian_addr'];
                        
                        // $legal_guardian_attachment_path_file = !empty($request->file('nominee')[$key]['legal_guardian_attachment_path']) ? $request->file('nominee')[$key]['legal_guardian_attachment_path'] : NULL;
                        $legal_guardian_attachment_path_file = !empty($value['legal_guardian_attachment_path_hidden']) ? $value['legal_guardian_attachment_path_hidden'] : NULL;

                        if(!empty($legal_guardian_attachment_path_file)) {

                            list($type, $cropped_image) = explode(';', $legal_guardian_attachment_path_file);
                            list(, $cropped_image) = explode(',', $cropped_image);
                            $cropped_image = base64_decode($cropped_image);
                            $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                            file_put_contents('public/uploads/nominee_documents/'.$image_name, $cropped_image);
                            $legal_guardian_attachment_path = 'uploads/nominee_documents/'.$image_name;
                            /*$filename = Util::rand_filename($legal_guardian_attachment_path_file->getClientOriginalExtension());
                            $legal_guardian_attachment_path = Util::upload_file($legal_guardian_attachment_path_file, $filename, null, $upload_path);*/
                        }

                    }
                    /*$nominee_details = DB::table('optcl_employee_nominee_details')
                                ->where('mobile_no', Auth::user()->mobile)
                                ->first();
                    $nominee_id = $nominee_details->id;*/
                    

                    $nominee = [
                        'nominee_master_id' => Session::get('application_no'),
                        //'nominee_id' => $nominee_id,
                        'nominee_name' => $value['name'],
                        'date_of_birth' => date('Y-m-d', strtotime($value['date_of_birth'])),
                        'dob_attachment_path' => $dob_attachment_path,
                        'gender_id' => $value['gender'],
                        'nominee_preference_id' => $value['nominee_preference_id'],
                        'relationship_id' => $value['relation_with_pensioner'],
                        'is_spouse' => $value['is_spouse'],
                        'is_2nd_spouse' => $is_2nd_spouse,
                        '1st_spouse_death_date' => $first_spouse_death_date,
                        '1st_spouse_death_certificate_path' => $first_spouse_death_certificate_path,
                        'marital_status_id' => $value['marital_status'],
                        'nominee_aadhaar_no' => $value['aadhaar_no'],
                        'mobile_no' => $value['mobile_no'],
                        'savings_bank_account_no' => $value['savings_bank_account_no'],
                        'bank_id' => $value['bank'],
                        'bank_branch_id' => $value['branch'],
                        'employement_status' => $value['employement_status'],
                        'total_income_per_annum' => $value['total_income_per_annum'],
                        'is_physically_handicapped' => $value['is_physically_handicapped'],
                        'disability_certificate_path' => $disability_certificate_path,
                        'disability_percentage' => $disability_percentage,
                        'pension_amount_share_percentage' => $value['pension_amount_share_percentage'],
                        'is_minor' => $value['is_minor'],
                        'legal_guardian_name' => $legal_guardian_name,
                        'legal_guardian_age' => $legal_guardian_age,
                        'legal_guardian_addr' => $legal_guardian_addr,
                        'legal_guardian_attachment_path' => $legal_guardian_attachment_path,
                        'created_at' => $this->current_date,
                        'created_by' => Auth::user()->id,
                        'modified_at' => $this->current_date,
                        'modified_by' => Auth::user()->id
                    ];

                    if(!empty($value['nominee_id'])) {
                        DB::table('optcl_nominee_nominee_details')->where('id', $value['nominee_id'])->update($nominee);
                    } else {
                        DB::table('optcl_nominee_nominee_details')->insert($nominee);
                    }
                }

                DB::commit();
            }
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        
        echo json_encode($validation);
    }

    public function getBranchDetails(Request $request) {
        $sid = $request->post('sid');

        $branch_details = DB::table('optcl_bank_branch_master')->select('id', 'branch_name', 'ifsc_code', 'micr_code')->where('id', $sid)->first();

        return response()->json($branch_details);
    }

    public function get_bank_branch(Request $request) {
        $sid = $request->post('sid');
        $bank_branch = DB::table('optcl_bank_branch_master')->where('bank_id', $sid)->get();
        $html = '<option value="">Select Bank Branch</option>';
        foreach($bank_branch as $list){
            $html.='<option value="'.$list->id.'">'.$list->branch_name.'</option>';
        }
        echo $html;
    }

    public function delete_nominee_details(Request $request) {
        $validation = array();
        try {
            DB::beginTransaction();

            DB::table('optcl_nominee_nominee_details')->where('id', $request->id)->update([
                'deleted' => 1
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return response()->json($validation);
    }

    public function pension_documents(Request $request) {
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $id = Session::get('application_no');

        $nomineeCount = DB::table('optcl_nominee_nominee_details')->where('nominee_master_id', $id)->where('deleted', 0)->count();
        if($nomineeCount < 1){
            Session::flash('error', 'Please add at least one nominee');
            return redirect()->route('nominee_nominee_form');
        }

        $employee_master = DB::table('optcl_nominee_master')->where('id', $id)->first();

        $edit = 0;

        $employee_documents = DB::table('optcl_nominee_employee_document_details')->where('nominee_master_id', $id)->first();

        if(!empty($employee_documents)) {
            $edit = 1;
        }
        return view('user.nominee.pensioner_document', compact('id', 'employee_master', 'edit', 'employee_documents'));
    }

    public function save_pension_documents(Request $request) {
        if(Util::check_nominee_submission()){
            return redirect()->route('nominee_application_view_details');
        }
        $validation = array();

        //dd($request->file());

        try {
            DB::beginTransaction();

            $employee_master = DB::table('optcl_nominee_master')->where('id', $request->employee_id)->first();
            $employee_documents = DB::table('optcl_nominee_employee_document_details')->where('nominee_master_id', $request->employee_id)->first();
            //print_r($employee_documents);

            if(!isset($validation['error'])) {

                $attested_copy_death_certificate = '';
                $identification_document_applicatnt = '';
                $undertaking_for_recovery = '';
                $attested_copy_pension_payment_order = '';
                $power_of_attorney = '';
                $attested_copy_legal_heir_certificate = '';
                $attached_descriptive_roll_slips = '';

                $upload_path = 'uploads/documents/nominee/';

                if($request->hasFile('attached_recent_passport')) {
                    $filename = Util::rand_filename($request->file('attached_recent_passport')->getClientOriginalExtension());

                    //dd($filename);
                    $attested_copy_death_certificate = Util::upload_file($request->file('attached_recent_passport'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 9)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 9,
                            'document_attachment_path' => $attested_copy_death_certificate,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 9)->update([
                            'document_attachment_path' => $attested_copy_death_certificate,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attested_copy_death_certificate)) {
                        $attested_copy_death_certificate = $employee_documents->attested_copy_death_certificate;
                    }
                }

                if($request->hasFile('attached_dob_certificate')) {
                    $filename = Util::rand_filename($request->file('attached_dob_certificate')->getClientOriginalExtension());
                    $identification_document_applicatnt = Util::upload_file($request->file('attached_dob_certificate'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 10)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 10,
                            'document_attachment_path' => $identification_document_applicatnt,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 10)->update([
                            'document_attachment_path' => $identification_document_applicatnt,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->identification_document_applicatnt)) {
                        $identification_document_applicatnt = $employee_documents->identification_document_applicatnt;
                    }
                }

                if($request->hasFile('attached_undertaking_declaration')) {
                    $filename = Util::rand_filename($request->file('attached_undertaking_declaration')->getClientOriginalExtension());
                    $undertaking_for_recovery = Util::upload_file($request->file('attached_undertaking_declaration'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 11)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 11,
                            'document_attachment_path' => $undertaking_for_recovery,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 11)->update([
                            'document_attachment_path' => $undertaking_for_recovery,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->undertaking_for_recovery)) {
                        $undertaking_for_recovery = $employee_documents->undertaking_for_recovery;
                    }
                }

                if($request->hasFile('attached_bank_passbook')) {
                    $filename = Util::rand_filename($request->file('attached_bank_passbook')->getClientOriginalExtension());
                    $attested_copy_pension_payment_order = Util::upload_file($request->file('attached_bank_passbook'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 12)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 12,
                            'document_attachment_path' => $attested_copy_pension_payment_order,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 12)->update([
                            'document_attachment_path' => $attested_copy_pension_payment_order,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attested_copy_pension_payment_order)) {
                        $attested_copy_pension_payment_order = $employee_documents->attested_copy_pension_payment_order;
                    }
                }

                if($request->hasFile('attached_cancelled_chqeue')) {
                    $filename = Util::rand_filename($request->file('attached_cancelled_chqeue')->getClientOriginalExtension());
                    $power_of_attorney = Util::upload_file($request->file('attached_cancelled_chqeue'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 13)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 13,
                            'document_attachment_path' => $power_of_attorney,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 13)->update([
                            'document_attachment_path' => $power_of_attorney,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->power_of_attorney)) {
                        $power_of_attorney = $employee_documents->power_of_attorney;
                    }
                }

                if($request->hasFile('attached_descriptive_roll_slips')) {
                    $filename = Util::rand_filename($request->file('attached_descriptive_roll_slips')->getClientOriginalExtension());
                    $attested_copy_legal_heir_certificate = Util::upload_file($request->file('attached_descriptive_roll_slips'), $filename, null, $upload_path);
                    $application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 14)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_nominee_pension_application_document')->insert([
                            'nominee_master_id' => $request->employee_id,
                            'document_id' => 14,
                            'document_attachment_path' => $attested_copy_legal_heir_certificate,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 14)->update([
                            'document_attachment_path' => $attested_copy_legal_heir_certificate,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attested_copy_legal_heir_certificate)) {
                        $attested_copy_legal_heir_certificate = $employee_documents->attested_copy_legal_heir_certificate;
                    }
                }

                $pension_documents = [
                    'nominee_master_id' => $request->employee_id,
                    'attested_copy_death_certificate' => $attested_copy_death_certificate,
                    'identification_document_applicatnt' => $identification_document_applicatnt,
                    'undertaking_for_recovery' => $undertaking_for_recovery,
                    'attested_copy_pension_payment_order' => $attested_copy_pension_payment_order,
                    'power_of_attorney' => $power_of_attorney,
                    'attested_copy_legal_heir_certificate' => $attested_copy_legal_heir_certificate,
                    'created_at' => $this->current_date,
                    'created_by' => Auth::user()->id,
                    'modified_at' => $this->current_date,
                    'modified_by' => Auth::user()->id
                ];
                //print_r($pension_documents);

                DB::table('optcl_nominee_employee_document_details')->updateOrInsert(
                    ['nominee_master_id' => $request->employee_id],
                    $pension_documents);

                DB::commit();
            }
        } catch(Exception $e) {
            DB::rollback();
            throw $e;       
        }

        return response()->json($validation);
    }
    
    public function application_preview(){
        //$application_no = Session::get('application_no');
        $application_no = DB::table('optcl_nominee_master')
                            ->where('employee_code', Auth::user()->employee_code)
                            ->value('id');
        //dd($application_no);
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
                            ->leftJoin('optcl_relation_master AS rm2','rm2.id','=','pd.family_member_relation_id')
                            ->join('optcl_pension_unit_master AS pu','pu.id','=','pd.pension_unit_id')
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','rm2.relation_name AS relationName','pu.pension_unit_name')
                            ->where('em.id', $application_no)
                            ->first();
        //dd($proposal, $application_no);  
        
        $employee_documents = DB::table('optcl_nominee_pension_application_document as a')
                            ->select('a.nominee_master_id', 'a.document_id', 'a.document_attachment_path', 'b.document_name')
                            ->join('optcl_pension_document_master as b', 'a.document_id', '=', 'b.id')
                            ->where('a.nominee_master_id', $application_no)
                            ->where('b.pension_type_id', 2)
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
                            ->where('a.nominee_master_id', $application_no)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();           
        // Show status history
        $statusHistory = DB::table('optcl_application_status_history AS sh')
                                ->join('optcl_application_status_master AS sm','sm.id','=','sh.status_id')
                                ->select('sm.status_name','sh.created_at','sh.remarks')
                                ->where('sh.user_id', Auth::user()->id)
                                ->where('sh.status', 1)
                                ->where('sh.deleted', 0)
                                ->where('sm.status', 1)
                                ->where('sm.deleted', 0)
                                ->get();
        //dd($proposal);

        return view('user.nominee.application_declaration',  compact('proposal', 'employee_documents', 'employee_nominees','statusHistory'));
    }

    public function application_submit(Request $request){
        $validation = array();
        $declaration_status = $request->declaration_status;
        if($declaration_status == ""){
            $validation['error'][] = array("id" => "declaration_status-error","eValue" => "Please accept the declaration");
        }
        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $application_no = Session::get('application_no');
                
                $pension_application_id = DB::table('optcl_pension_application_form')->insertGetId([
                    'user_id' => Auth::user()->id,
                    'pension_type_id' => 2,
                    'employee_id' => $application_no,
                    'employee_code' => Auth::user()->employee_code,
                    'employee_aadhaar_no' => Auth::user()->aadhaar_no,
                    'application_status_id' => 1,
                    'created_at' => $this->current_date,
                    'created_by' => Auth::user()->id,
                ]);
                $proposal_no = date('Y').sprintf('%05d',$pension_application_id);
                DB::table('optcl_pension_application_form')->where('id', $pension_application_id)
                                ->update(['application_no' => $proposal_no]);
                // Store Status History
                DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => Auth::user()->id,
                    'application_id'    => $pension_application_id,
                    'status_id'         => 1,
                    'created_at'        => $this->current_date,
                    'created_by'        => Auth::user()->id,
                ]);
                // Notification Area
                $message = "One application for family has been submitted with application no ".$proposal_no.". Please check the family pension application details.";
                // Get user details
                $userDetails = DB::table('optcl_users')
                    ->where('designation_id', 2)
                    ->where('optcl_unit_id', Auth::user()->optcl_unit_id)
                    ->first();
                $user_id = $userDetails->id;         
                Util::insert_notification($user_id, $pension_application_id, $message);

                Session::flash('success', 'Application submitted successfully');

                DB::commit();
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function application_details(){
        //$application_no = Session::get('application_no');
        $application_no = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->value('employee_id');
            //dd($application_no);
        if(empty($application_no)){
            Session::flash('error','Please submit all the application form details.');
            return redirect()->route('nominee_application_form');
        }   
        //dd($application_no);
        $application = DB::table('optcl_pension_application_form')->where('user_id', Auth::user()->id)->first();
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
                            ->leftJoin('optcl_relation_master AS rm2','rm2.id','=','pd.family_member_relation_id')
                            ->join('optcl_pension_unit_master AS pu','pu.id','=','pd.pension_unit_id')
                            ->select('em.*','ud.designation_name','g.gender_name','ms.marital_status_name','r.religion_name','a.account_type','o.unit_name','pd.*','s.state_name','d.district_name','c1.country_name as cName','rm.relation_name','rm2.relation_name AS relationName','pu.pension_unit_name')
                            ->where('em.id', $application_no)
                            ->first();
        //dd($proposal, $application_no);  
        
        $employee_documents = DB::table('optcl_nominee_pension_application_document as a')
                            ->select('a.nominee_master_id', 'a.document_id', 'a.document_attachment_path', 'b.document_name')
                            ->join('optcl_pension_document_master as b', 'a.document_id', '=', 'b.id')
                            ->where('a.nominee_master_id', $application_no)
                            ->where('b.pension_type_id', 2)
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
                            ->where('a.nominee_master_id', $application_no)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();           
        // Show status history
        $statusHistory = DB::table('optcl_application_status_history AS sh')
                                    ->join('optcl_application_status_master AS sm','sm.id','=','sh.status_id')
                                    ->select('sm.status_name','sh.created_at','sh.remarks')
                                    ->where('sh.application_id', $application->id)
                                    ->where('sh.status', 1)
                                    ->where('sh.deleted', 0)
                                    ->where('sm.status', 1)
                                    ->where('sm.deleted', 0)
                                    ->get();
        //DB::enableQueryLog();
        $add_recovery = DB::table('optcl_nominee_add_recovery')->where('status', 1)->where('deleted', 0)->where('application_id', $application->id)->get();
        //dd(DB::getQueryLog());
        $service_form = DB::table('optcl_nominee_pension_service_form')->where('status', 1)->where('deleted', 0)->where('application_id', $application->id)->first();
        if(!empty($service_form)){
            $organisation_details = DB::table('optcl_nominee_pension_service_offices')->where('status', 1)->where('deleted', 0)->where('service_pension_form_id', $service_form->id)->get();
        }else{
            $organisation_details = array();    
        }

        return view('user.nominee.application_details',  compact('proposal', 'employee_documents', 'employee_nominees','statusHistory','add_recovery', 'application', 'service_form', 'organisation_details'));
    }

}
?>