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
use App\Models\AdminUser;
use App\Libraries\Util;
use Session;
use Auth;


class ExistingProposalController extends Controller { 
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function get_category_ti_amount(Request $request){
        $ti_category_id = $request->ti_category_id;
        $basic_amount = $request->basic_amount;
        $additional_pension_amount = $request->additional_pension_amount ? $request->additional_pension_amount : 0;
        $enhanced_pension_amount = $request->enhanced_pension_amount ? $request->enhanced_pension_amount : 0;
        $enhanced_pension_end_date = $request->enhanced_pension_end_date ? date('Y-m-d', strtotime(str_replace("/","-", $request->enhanced_pension_end_date))) : NULL;
        $normal_pension_amount = $request->normal_pension_amount ? $request->normal_pension_amount : 0;
        $normal_pension_effective_date = $request->normal_pension_effective_date ? date('Y-m-d', strtotime(str_replace("/","-", $request->normal_pension_effective_date))) : 0;
        $age_year = $request->age_year ? $request->age_year : 0;
        $age_month = $request->age_month ? $request->age_month : 0;
        $age_days = $request->age_days ? $request->age_days : 0;
        $pensioner_type = $request->pesioner_type;

        $current_date = date('Y-m-d');
        // Enhanced Pensioner year to month
        $enhanced_pension_year = 65*12;
        // Additional Pension Amount Calculation Month
        $additional_pension_year = 80*12;
        // Pensioner age in month
        $age_month = ($age_year*12)+$age_month;

        if($pensioner_type == 2){
            if($age_month < $enhanced_pension_year){
                $calculation_amount = $enhanced_pension_amount;
            }else{
                $calculation_amount = $normal_pension_amount;
            }
        }else{
            $calculation_amount = $basic_amount + $additional_pension_amount;
        }
        

        $categoryDetails = DB::table('optcl_ti_category_master')
                                ->join('optcl_ti_master', 'optcl_ti_master.id', '=', 'optcl_ti_category_master.ti_master_id')
                                ->select('optcl_ti_category_master.*', 'optcl_ti_master.da_rate')
                                ->where('optcl_ti_category_master.id', $ti_category_id)
                                ->where('optcl_ti_category_master.status', 1)
                                ->where('optcl_ti_category_master.deleted', 0)->first();
        //print_r($categoryDetails);
        if($categoryDetails){
            $da_rate = $categoryDetails->da_rate;
            $da_amount = (($calculation_amount)/100)*$da_rate;
            $display_value = $da_amount." (".$da_rate."%)";
        }else{
            $da_rate = 0;
            $display_value = "0.00 (0%)";
        }
        
        echo json_encode(['da_amount' => $da_amount, 'da_percentage' => $da_rate, 'display_value' => $display_value]);
    }
    public function pension_list(Request $request){
        $user = Auth::user();
        //dd($user);
        //DB::enableQueryLog();
        $pension_unit_id = $user->pension_unit_id;
        // Here we get list for service pensioner and family pensioner by left JOIN operation
        $applications = DB::table('optcl_existing_user')
                    ->join('optcl_pension_type_master', 'optcl_pension_type_master.id','=', 'optcl_existing_user.pensioner_type')
                    ->leftJoin('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_existing_user.application_status_id')
                    ->select('optcl_existing_user.*', 'optcl_pension_type_master.pension_type', 'optcl_existing_user.pensioner_type', 'optcl_application_status_master.status_name')
                    ->where('optcl_existing_user.pension_unit_id', $pension_unit_id)
                    ->where('optcl_existing_user.status', 1)
                    //->where('optcl_existing_user.is_taxable_amount_generated', 0)
                    ->where('optcl_existing_user.deleted', 0)
                    ->where(function($queryCond){
                        return $queryCond->orWhere('optcl_existing_user.is_taxable_amount_generated', 1)
                                        ->orWhere('optcl_existing_user.is_taxable_amount_generated', 0);
                    });

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
        }

        $applications = $applications->orderBy('optcl_existing_user.id','DESC');
        $applications = $applications->paginate(10);
        // Status List
        $statuslist = DB::table('optcl_application_status_master')
                            ->whereIn('id', [50,51,52,53,54])
                            ->where('status', 1)
                            ->where('deleted', 0)->get();

        return view('user.existing_pension_application.application-list', compact('applications', 'request', 'statuslist'));
    }
    public function pensioner_form(){
        //dd($request->all());
        $user_details = Auth::user();
        // Check all data submitted or not
        /*if(Util::check_submission()){
            return redirect()->route('view_details');
        }*/
        // Check Previous Form Submission
        $preSubmission = DB::table('optcl_existing_application_submission')
                            ->where('user_id', Auth::user()->id)
                            ->where('deleted', 0)
                            ->orderBy('id', 'DESC')
                            ->first();
        if($preSubmission){            
            if($preSubmission->form_one == 1 && $preSubmission->form_two == 0 && $preSubmission->form_three == 0 && $preSubmission->form_four == 0 && $preSubmission->form_declaration == 0){     

                Session::put('application_no', $preSubmission->employee_id);
                return redirect()->route('existing_personal_details'); 
            }else if($preSubmission->form_two == 1 && $preSubmission->form_two == 1 && $preSubmission->form_three == 0 && $preSubmission->form_four == 0 && $preSubmission->form_declaration == 0){    
                // Nominee             
                Session::put('application_no', $preSubmission->employee_id);
                return redirect()->route('existing_nominees');
            }else if($preSubmission->form_three == 1 && $preSubmission->form_two == 1 && $preSubmission->form_three == 1 && $preSubmission->form_four == 0 && $preSubmission->form_declaration == 0){
                // List of Documents
                Session::put('application_no', $preSubmission->employee_id);
                return redirect()->route('existing_pension_documents');
            }else if($preSubmission->form_four == 1 && $preSubmission->form_two == 1 && $preSubmission->form_three == 1 && $preSubmission->form_four == 1 && $preSubmission->form_declaration == 0){
                // Form Declaration
                Session::put('application_no', $preSubmission->employee_id);
                return redirect()->route('existing_pension_documents');
            }else{
                // Do nothing
                //dd($preSubmission);
                /*Session::put('application_no', $preSubmission->employee_id);
                return redirect()->route('existing_pensioner_form_edit');*/
            }
        }                    
        // Check application applied or not. If applied then redirect to edit page with session value
        $checkStatus = Pensionform::where('employee_code', Auth::user()->employee_code)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'DESC')
                            ->limit(1)
                            ->first();
        /*if($checkStatus){
            Session::put('application_no', $checkStatus->id);
            return redirect()->route('edit_pensioner_form');
        }*/
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
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $category_ti = DB::table('optcl_ti_category_master')->where('status', 1)->where('deleted', 0)->get();
        $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();
        $pensioner_designation = PensionerDesignation::where('status', 1)->where('deleted', 0)->get();
        $pension_type = DB::table('optcl_pension_type_master')->where('status', 1)->where('deleted', 0)->get();
        $tax_master_list = DB::table('optcl_tax_master')->where('status', 1)->where('deleted', 0)->get();

        return view('user.existing_pension_application.pensioner_form', compact('religions', 'office_last_served','pensioner_designation','user_details', 'mstatus', 'genders','account_types','employee_master', 'banks', 'category_ti','relations','pensioner_designation', 'pension_type', 'tax_master_list'));
    }

    public function get_relation_type(Request $request){
        $relation_type = $request->relation_type;
        $relations = DB::table('optcl_relation_status_master')
                        ->where('relation_id', $relation_type)
                        ->where('status', 1)->where('deleted', 0)
                        ->get(); ?>
        <option value="">Select Branch</option>
        <?php foreach($relations as $relation){ ?>
            <option value="<?php echo $relation->id; ?>"><?php echo $relation->relation_status_name; ?></option>
        <?php 
        }
    }

    public function save_pensioner_form(Request $request){
        // Check all data submitted or not
        
        $validation = array();
        $pesioner_type = $request->pesioner_type;
        if($pesioner_type == ""){
            $validation['error'][] = array("id" => "pesioner_type-error","eValue" => "Please select pensioner type");
        }
        $old_ppo_no = $request->old_ppo_no;
        if($old_ppo_no == ""){
            $validation['error'][] = array("id" => "old_ppo_no-error","eValue" => "Please enter PPO no");
        }
        /*$hidden_ppo_file = $request->hidden_ppo_file;
        if($hidden_ppo_file == ""){
            $validation['error'][] = array("id" => "attached_ppo_certificate-error","eValue" => "Please upload PPO file");
        }*/
        $pensioner_name = $request->pensioner_name;
        if($pensioner_name == ""){
            $validation['error'][] = array("id" => "pensioner_name-error","eValue" => "Please enter pensioner name");
        }
        $gender = $request->gender;
        if($gender == ""){
            $validation['error'][] = array("id" => "gender-error","eValue" => "Please select gender");
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
        $dor = $request->dor;
        if($dor == ""){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select date of retiremet");
        }else{
            $dor = str_replace("/","-",$dor);
        }
        $date_of_death = $request->date_of_death;
        if($pesioner_type == 2 && $date_of_death == ""){
            $validation['error'][] = array("id" => "dor-error","eValue" => "Please select date of retiremet");
        }else{
            $date_of_death = str_replace("/","-",$date_of_death);
        }
        $enhanced_pension_amount = $request->enhanced_pension_amount;
        if($pesioner_type == 2 && $enhanced_pension_amount == ""){
            $validation['error'][] = array("id" => "enhanced_pension_amount-error","eValue" => "Please enter enhanced pension amount");
        }
        $enhanced_pension_end_date = $request->enhanced_pension_end_date;
        if($pesioner_type == 2 && $enhanced_pension_end_date == ""){
            $validation['error'][] = array("id" => "enhanced_pension_end_date-error","eValue" => "Please enter enhanced pension end date");
        }else{
            $enhanced_pension_end_date = str_replace("/","-",$enhanced_pension_end_date);
        }
        $normal_pension_amount = $request->normal_pension_amount;
        if($pesioner_type == 2 && $normal_pension_amount == ""){
            $validation['error'][] = array("id" => "normal_pension_amount-error","eValue" => "Please enter normal pension amount");
        }
        $normal_pension_effective_date = $request->normal_pension_effective_date;
        if($pesioner_type == 2 && $normal_pension_effective_date == ""){
            $validation['error'][] = array("id" => "normal_pension_effective_date-error","eValue" => "Please enter normal pension effective date");
        }else{
            $normal_pension_effective_date = str_replace("/","-",$normal_pension_effective_date);
        }
        $relation_type = $request->relation_type;
        if($pesioner_type == 2 && $relation_type == ""){
            $validation['error'][] = array("id" => "relation_type-error","eValue" => "Please select relation type");
        }
        $current_status = $request->current_status;
        if($pesioner_type == 2 && $current_status == ""){
            $validation['error'][] = array("id" => "current_status-error","eValue" => "Please select current status");
        }
        $rel_cur_status_end_date = $request->rel_cur_status_end_date;
        if($pesioner_type == 2 && $relation_type == 2 && $current_status == 9 && $rel_cur_status_end_date == ""){
            $validation['error'][] = array("id" => "rel_cur_status_end_date-error","eValue" => "Please select end date");
        }
        $nominee_name = $request->nominee_name;
        if($pesioner_type == 2 && $nominee_name == ""){
            $validation['error'][] = array("id" => "nominee_name-error","eValue" => "Please enter nominee name");
        }
        $nominee_mob_no = $request->nominee_mob_no;
        if($pesioner_type == 2 && $nominee_mob_no == ""){
            $validation['error'][] = array("id" => "nominee_mob_no-error","eValue" => "Please enter mobile number");
        }
        $nominee_aadhar_no = $request->nominee_aadhar_no;
        if($pesioner_type == 2 && $nominee_aadhar_no == ""){
            $validation['error'][] = array("id" => "nominee_aadhar_no-error","eValue" => "Please enter aadhar no");
        }
        $nominee_dob = $request->nominee_dob;
        if($pesioner_type == 2 && $nominee_dob == ""){
            $validation['error'][] = array("id" => "nominee_dob-error","eValue" => "Please select nominee date of birth");
        }else{
            $nominee_dob = str_replace("/","-",$nominee_dob);
        }
        $basic_pension_amount = $request->basic_pension_amount;
        if($basic_pension_amount == ""){
            $validation['error'][] = array("id" => "basic_pension_amount-error","eValue" => "Please enter basic pension amount");
        }
        $basic_pension_effective_date = $request->basic_pension_effective_date;
        if($basic_pension_effective_date == ""){
            $validation['error'][] = array("id" => "basic_pension_effective_date-error","eValue" => "Please select basic pension effective date");
        }else{
            $basic_pension_effective_date = str_replace("/","-",$basic_pension_effective_date);
        }
        $additional_pension_amount = $request->additional_pension_amount;
        if($additional_pension_amount == ""){
            $validation['error'][] = array("id" => "additional_pension_amount-error","eValue" => "Please select date of birth");
        }
        $ti_category_id = $request->ti_category_id;
        if($ti_category_id == ""){
            $validation['error'][] = array("id" => "ti_category_id-error","eValue" => "Please select category");
        }
        $hidden_ti_amount = $request->hidden_ti_amount;
        if($hidden_ti_amount == ""){
            $validation['error'][] = array("id" => "ti_amount-error","eValue" => "Please check category/basic pension amount");
        }
        $hidden_ti_percentage = $request->hidden_ti_percentage;
        if($hidden_ti_percentage == ""){
            $validation['error'][] = array("id" => "ti_amount-error","eValue" => "Please check category/basic pension amount");
        }
        $bank_name = $request->bank_name;
        if($bank_name == ""){
            $validation['error'][] = array("id" => "bank_name-error","eValue" => "Please select bank");
        }
        $branch_name_address = $request->branch_name_address;
        if($branch_name_address == ""){
            $validation['error'][] = array("id" => "branch_name_address-error","eValue" => "Please select branch");
        }
        $ifsc_code = $request->ifsc_code;
        if($ifsc_code == ""){
            $validation['error'][] = array("id" => "ifsc_code-error","eValue" => "Please select bank and branch");
        }
        $micr_code = $request->micr_code;
        if($micr_code == ""){
            $validation['error'][] = array("id" => "micr_code-error","eValue" => "Please select bank and branch");
        }
        $saving_bank_ac_no = $request->saving_bank_ac_no;
        if($saving_bank_ac_no == ""){
            $validation['error'][] = array("id" => "saving_bank_ac_no-error","eValue" => "Please enter saving account no");
        }
        $gross_pension = $request->gross_pension;
        if($gross_pension == ""){
            $validation['error'][] = array("id" => "gross_pension-error","eValue" => "Please enter gross pension");
        }
        $total_income_amount = $request->total_income_amount;
        if($total_income_amount == ""){
            $validation['error'][] = array("id" => "total_income_amount-error","eValue" => "Please enter total income amount");
        }

        $tax_type = $request->tax_type;
        if($pesioner_type == 1 && $tax_type == ""){
            $validation['error'][] = array("id" => "tax_type-error","eValue" => "Please select tax type");
        }else{
            $tax_type = "1";
        }
        $employee_pan = $request->employee_pan;
        if($employee_pan == ""){
            $validation['error'][] = array("id" => "employee_pan-error","eValue" => "Please enter PAN");
        }

        $mobile_number = $request->mobile_number;
        $aadhaar_number = $request->aadhaar_number;
        $employee_code = $request->employee_code;
        $total_income_month = $request->total_income_month;

        $upload_path = 'uploads/documents/';
        $ppo_attachment_path = NULL;
        $ppo_attachment_file = !empty($request->file('attached_ppo_certificate')) ? $request->file('attached_ppo_certificate') : NULL;

        if($request->hasFile('attached_ppo_certificate')) {
            $filename = Util::rand_filename($ppo_attachment_file->getClientOriginalExtension());
            $ppo_attachment_path = Util::upload_file($ppo_attachment_file, $filename, null, $upload_path);
        }else{
            $validation['error'][] = array("id" => "attached_ppo_certificate-error","eValue" => "Please upload PPO file");
        }        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                // Create User
                $pension_unit_id = Auth::user()->pension_unit_id;
                $optcl_users_tbl = new AdminUser;
                $optcl_users_tbl->employee_code = $employee_code;
                $optcl_users_tbl->first_name    = $pensioner_name;
                $optcl_users_tbl->user_type     = $pesioner_type;
                $optcl_users_tbl->username      = $employee_code;
                $optcl_users_tbl->aadhaar_no    = $aadhaar_number;
                $optcl_users_tbl->mobile        = $mobile_number;
                $optcl_users_tbl->pension_unit_id        = $pension_unit_id;
                //$optcl_users_tbl->password      = bcrypt('Secret@123');
                $optcl_users_tbl->status        = 1;
                $optcl_users_tbl->deleted       = 1;
                $optcl_users_tbl->save();
                $user_id = $optcl_users_tbl->id;
                
                $data = [
                    "user_id"                           => $user_id,
                    "pension_unit_id"                   => $pension_unit_id,
                    "tax_type_id"                       => $tax_type,  // Tax Type
                    "old_ppo_no"                        => $old_ppo_no,
                    "old_ppo_attachment"                => 'public/'.$upload_path.$filename,
                    "category_ti_id"                    => $ti_category_id,
                    "ti_amount"                         => $hidden_ti_amount,
                    "ti_percentage"                     => $hidden_ti_percentage,
                    "pensioner_name"                    => $pensioner_name,
                    "aadhar_no"                         => $aadhaar_number,
                    "pan_no"                            => $employee_pan,
                    "mobile_number"                     => $mobile_number,
                    "employee_code"                     => $employee_code,
                    "pensioner_type"                    => $pesioner_type,
                    "bank_branch_id"                    => $branch_name_address,
                    "acc_number"                        => $saving_bank_ac_no,
                    "relation_type"                     => $relation_type,
                    "relation_current_status"           => $current_status,
                    "relation_pension_closing_date"     => !empty($rel_cur_status_end_date) ? date('Y-m-d', strtotime($rel_cur_status_end_date)) : NULL,
                    "nominee_name"                      => $nominee_name,
                    "nominee_mobile"                    => $nominee_mob_no,
                    "nominee_aadhar"                    => $nominee_aadhar_no,
                    "nominee_dob"                       => !empty($nominee_dob) ? date('Y-m-d', strtotime($nominee_dob)) : NULL,
                    "designation_id"                    => !empty($designation) ? $designation : NULL,
                    "gender_id"                         => !empty($gender) ? $gender : NULL,
                    "date_of_birth"                     => !empty($dob) ? date('Y-m-d', strtotime($dob)) : NULL,
                    "date_of_retirement"                => !empty($dor) ? date('Y-m-d', strtotime($dor)) : NULL,
                    "date_of_death"                     => !empty($date_of_death) ? date('Y-m-d', strtotime($date_of_death)) : NULL,
                    "basic_amount"                      => !empty($basic_pension_amount) ? $basic_pension_amount : NULL,
                    "basic_effective_date"              => !empty($basic_pension_effective_date) ? date('Y-m-d', strtotime($basic_pension_effective_date)) : NULL,
                    "additional_pension_amount"         => !empty($additional_pension_amount) ? $additional_pension_amount : NULL,
                    "enhanced_pension_amount"           => !empty($enhanced_pension_amount) ? $enhanced_pension_amount : NULL,
                    "enhanced_pension_end_date"         => !empty($enhanced_pension_end_date) ? date('Y-m-d', strtotime($enhanced_pension_end_date)) : NULL,
                    "normal_pension_amount"             => !empty($normal_pension_amount) ? $normal_pension_amount : NULL,
                    "normal_pension_effective_date"     => !empty($normal_pension_effective_date) ? date('Y-m-d', strtotime($normal_pension_effective_date)) : NULL,
                    "gross_pension_amount"              => $gross_pension,
                    "total_income"                      => $total_income_amount,
                    "total_income_month"                => $total_income_month,
                    "application_status_id"             => 54,
                    "created_by"                        => Auth::user()->id,
                    "created_at"                        => $this->current_date,
                ];                

                //dd($data, $request->all());
                $existingInsertedID = DB::table('optcl_existing_user')->insertGetId($data);
                // Commutation Data
                $commutation_amount = $request->commutation_amount;
                $commutation_amount_end_date = $request->commutation_amount_end_date;
                foreach($commutation_amount as $key => $commutation_amount){
                    
                    $commutation_data = [
                        "existing_user_id"      => $existingInsertedID,
                        "commutation_amount"    => $commutation_amount,
                        "commutation_end_date"  => date('Y-m-d', strtotime(str_replace("/","-", $commutation_amount_end_date[$key]))),
                        "created_by"            => Auth::user()->id,
                        "created_at"            => $this->current_date,
                    ];
                    DB::table('optcl_existing_user_commutation')->insert($commutation_data);
                }
                // Taxable value value calculation in case of Family Pensioner
                if($pesioner_type == 2){
                    $data = [
                        "appliation_type"        => 2,
                        "pensioner_type"         => $pesioner_type,
                        "application_id"         => $existingInsertedID,
                        "total_income"           => $total_income_amount,
                        "standard_deduction"     => 0,
                        "amount_80c"             => 0,
                        "amount_80d"             => 0,
                        "amount_80dd"            => 0,
                        "amount_80u"             => 0,
                        "amount_24b"             => 0,
                        "amount_others"          => 0,   
                        "taxable_amount"         => $total_income_amount,
                        "created_by"             => Auth::user()->id,
                        "created_at"             => $this->current_date,
                    ];                
                    DB::table('optcl_taxable_amount_calculation_details')->insert($data);
                    // Update taxable amount on optcl_existing_user
                    $updateTaxableAmount = ['is_taxable_amount_generated' => 1,'taxable_amount' => $total_income_amount];
                    DB::table('optcl_existing_user')->where('id', $existingInsertedID)->update($updateTaxableAmount);
                }
                // History
                DB::table('optcl_application_status_history')->insert([
                    'is_new'            => 0,
                    'user_id'           => Auth::user()->id,
                    'application_id'    => $existingInsertedID,
                    'status_id'         => 54,
                    'created_at'        => $this->current_date,
                    'created_by'        => Auth::user()->id,
                ]);
                // New PPO Number Generation
                /* $ppo_no = rand(0, 9999);
                $current_month = date('m');
                $current_year = date('Y');
                $generated_ppo_number = $ppo_no . '/' . $current_month . '/' . $current_year; */
                if($pesioner_type == 1){
                    $pensioner_type_id_value = 3;
                }else{
                    $pensioner_type_id_value = 4;
                }
                $ppo_data = [
                    'pensioner_type_id'   => $pensioner_type_id_value,
                    'application_type'   => 2,
                    'pensioner_type'   => $pesioner_type,
                    "application_id"    => $existingInsertedID,
                    "created_by"          => Auth::user()->id,
                    "created_at"          => $this->current_date,
                ];
                $generated_ppo_number = Util::generate_ppo_number($ppo_data);
                DB::table('optcl_existing_user')
                    ->where('id', $existingInsertedID)
                    ->update(['new_ppo_no' => $generated_ppo_number]);
                // Monthly Changed Data
                $monthlyChangedData = [
                    "appliation_type"   => 2, // 2 - Existing
                    "pensioner_type"    => $pesioner_type,
                    "application_id"    => $existingInsertedID,
                    "pension_unit_id"     => Auth::user()->pension_unit_id,
                    "created_by"        => Auth::user()->id,
                    "created_at"        => $this->current_date,
                ];
                $monthly_changed_data_id = DB::table('optcl_monthly_changed_data')->insertGetId($monthlyChangedData);
                // 
                $monthlyChangedMappingData = [
                    "monthly_changed_data_id"   => $monthly_changed_data_id,
                    "application_pensioner_type_id"   => $pesioner_type == 1 ? 3 : 4,
                    "application_id"    => $existingInsertedID,
                    "created_by"        => Auth::user()->id,
                    "created_at"        => $this->current_date,
                ];
                DB::table('optcl_application_monthly_changed_data_mapping')->insert($monthlyChangedMappingData);

                Session::flash('success', 'Pensioner added successfully');
                DB::commit();
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function save_as_draft_pensioner_form(Request $request){
        // Check all data submitted or not
        if(Util::check_submission()){
            return redirect()->route('view_details');
        }        
        try{
            DB::beginTransaction();
            $dob = $request->dob != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->dob))) : NULL;       
            $doj = $request->doj != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->doj))) : NULL;
            $dor = $request->dor != "" ? date('Y-m-d', strtotime(str_replace("/","-",$request->dor))) : NULL;
            $chech_pension_data = Pensionform::where('employee_code', $request->emp_code)->first();
            if(!empty($chech_pension_data)){                
                $pension_column = new Pensionform();
                $pension_column->exists = true;
                $pension_column->id = $chech_pension_data->id;
                $pension_column->employee_code = $request->emp_code;
                $pension_column->aadhaar_no = $request->aadhaar_no;
                $pension_column->employee_name = $request->name;
                $pension_column->designation_id = $request->designation;
                $pension_column->date_of_birth = $dob;
                $pension_column->father_name = $request->father_name;
                $pension_column->gender_id = $request->gender;
                $pension_column->marital_status_id = $request->marital_status;
                $pension_column->husband_name = $request->husband_name ? $request->husband_name : NULL;
                $pension_column->religion_id = $request->religion;
                $pension_column->pf_account_type_id = $request->pf_acc_type;   
                $pension_column->pf_account_no = $request->pf_acno;
                $pension_column->optcl_unit_id = $request->name_of_office_dept;
                $pension_column->date_of_joining = $doj;
                $pension_column->date_of_retirement = $dor;
                $pension_column->modified_by = Auth::user()->id;
                $pension_column->modified_at = $this->current_date;
                $pension_column->save();
                $lastID = $chech_pension_data->id;
            }else{
                $pension_column = new Pensionform();
                $pension_column->employee_code = $request->emp_code;
                $pension_column->aadhaar_no = $request->aadhaar_no;
                $pension_column->employee_name = $request->name;
                $pension_column->designation_id = $request->designation;
                $pension_column->date_of_birth = $dob;
                $pension_column->father_name = $request->father_name;
                $pension_column->gender_id = $request->gender;
                $pension_column->marital_status_id = $request->marital_status;
                $pension_column->husband_name = $request->husband_name;
                $pension_column->religion_id = $request->religion;
                $pension_column->pf_account_type_id = $request->pf_acno_type;   
                $pension_column->pf_account_no = $request->pf_acno;
                $pension_column->optcl_unit_id = $request->name_of_office_dept;
                $pension_column->date_of_joining = $doj;
                $pension_column->date_of_retirement = $dor;
                $pension_column->created_by = Auth::user()->id;
                $pension_column->created_at = $this->current_date;
                $pension_column->save();
                $lastID = $pension_column->id;
                // Update form submission status
                DB::table('optcl_existing_application_submission')->insert([
                    'user_id' => Auth::user()->id,
                    'employee_id' => $lastID,
                    'form_one' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => $this->current_date,
                ]);
            }
            Session::put('application_no', $lastID);

            DB::commit();
            return response()->json( array('status' => 'success'));            
        }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        } 
    }

    public function save_existing_documents(Request $request) {
        $validation = array();

        try {
            DB::beginTransaction();

            $upload_path = 'uploads/documents/';

            if(!isset($validation['error'])) {

                $attached_ppo_certificate = '';

                if($request->hasFile('attached_ppo_certificate')) {
                    $filename = Util::rand_filename($request->file('attached_ppo_certificate')->getClientOriginalExtension());

                    //dd($filename);
                    $attached_ppo_certificate = Util::upload_file($request->file('attached_ppo_certificate'), $filename, null, $upload_path);

                    /*$application_document = DB::table('optcl_nominee_pension_application_document')->where('nominee_master_id', $request->employee_id)->where('document_id', 9)->first();

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
                    }*/
                }

                /*$pension_documents = [
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
                ];*/
                //print_r($pension_documents);

                /*DB::table('optcl_nominee_employee_document_details')->updateOrInsert(
                    ['nominee_master_id' => $request->employee_id],
                    $pension_documents);*/
                echo $attached_ppo_certificate;

                DB::commit();
            }
        } catch(Exception $e) {
            DB::rollback();
            throw $e;       
        }
        //return response()->json($validation);
    }

    public static function get_age_additional_pension(Request $request) {
        $from_date = date('Y-m-d', strtotime(str_replace("/","-",$request->dob)));
        //dd($from_date);
        $to_date = date('Y-m-d',time());
        //dd($from_date, $to_date);
        $date1 = date_create($from_date);
        $date2 = date_create($to_date);

        $diff = date_diff($date1,$date2);
        //print_r($diff);
        $dmonths = $diff->format("%m months");
        $dyears = $diff->format("%y years");
        $ddays = $diff->format("%d days");

        $response = ['years' => explode(' ', $dyears)[0], 
            'months' => explode(' ', $dmonths)[0], 
            'days' => explode(' ', $ddays)[0]
        ];
        echo json_encode($response);
    }

    public function get_additional_pension_amount(Request $request) {
        $basic_amount = $request->basic_amount;
        $year_value = $request->year_value;
        $month_value = $request->month_value;
        $day_value = $request->day_value;
        $increment_percentage = 0;
        if($year_value >= 80 && $year_value < 85){
            $increment_percentage = 20;
        }else if($year_value >= 85 && $year_value < 90){
            $increment_percentage = 30;
        }else if($year_value >= 90 && $year_value < 95){
            $increment_percentage = 40;
        }else if($year_value >= 95 && $year_value < 100){ 
            $increment_percentage = 50;
        }else if($year_value >= 100){ 
            $increment_percentage = 100;
        }else{
            $increment_percentage = 0;
        }
        $increment_value = ($basic_amount/100)*$increment_percentage;
        $response = ['increment_value' => $increment_value, 
            'increment_percentage' => $increment_percentage. "%", 
        ];
        echo json_encode($response);
    }

    public function get_family_pension_pension_amount_details(Request $request){
        $dob = date('Y-m-d', strtotime(str_replace("/","-", $request->dob)));
        $dor = date('Y-m-d', strtotime(str_replace("/","-", $request->dor)));
        $date_of_death = date('Y-m-d', strtotime(str_replace("/","-", $request->date_of_death)));
        $basic_pension_amount = $request->basic_pension_amount;
        $current_date = date('Y-m-d', time());

        $date1 = date_create($dob);
        $date2 = date_create($date_of_death);

        $diff = date_diff($date1,$date2);
        $dmonths = $diff->format("%m months");
        $dyears = $diff->format("%y years");
        $ddays = $diff->format("%d days");

        $years = explode(' ', $dyears)[0];
        $months = explode(' ', $dmonths)[0];
        $days = explode(' ', $ddays)[0];
        $response = [];
        $enhanced_pension_amount = 0;
        $normal_pension_amount = 0;
        // If pensioner death after age 65
        if($years > 65){
            $enhanced_pension_amount = 0;
            $normal_pension_amount = ($basic_pension_amount/100)*30;
        }else if($years <= 65){ // If pensioner death before age 65
            if($current_date > $date_of_death){
                $enhanced_pension_amount = 0;
            }else{
                $enhanced_pension_amount = ($basic_pension_amount/100)*50;
            }            
            $normal_pension_amount = ($basic_pension_amount/100)*30;
        }else{
            // Do Nothing
        }
        $response = [
            "enhanced_pension_amount" => $enhanced_pension_amount,
            "normal_pension_amount" => $normal_pension_amount,
        ];
        echo json_encode($response);

    }

    public function pensioner_details($penID){
        
        $monthly_data = DB::table('optcl_monthly_changed_data')->where('id',$penID)->where('status', 1)->where('deleted', 0)->first();
        //dd($monthly_data);
        if($monthly_data){
            
            // DB::enableQueryLog();
            $pensionerDetails = DB::table('optcl_existing_user')
                                    ->leftJoin('optcl_tax_master', 'optcl_tax_master.id', '=', 'optcl_existing_user.tax_type_id')
                                    ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_existing_user.pensioner_type')
                                    ->join('optcl_pension_unit_master', 'optcl_pension_unit_master.id', '=', 'optcl_existing_user.pension_unit_id')
                                    ->join('optcl_employee_gender_master', 'optcl_employee_gender_master.id', '=', 'optcl_existing_user.gender_id')
                                    ->join('optcl_employee_designation_master', 'optcl_employee_designation_master.id', '=', 'optcl_existing_user.designation_id')
                                    ->join('optcl_ti_category_master', 'optcl_ti_category_master.id', '=', 'optcl_existing_user.category_ti_id')
                                    ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id', '=', 'optcl_existing_user.bank_branch_id')
                                    ->join('optcl_bank_master', 'optcl_bank_master.id', '=', 'optcl_bank_branch_master.bank_id')
                                    ->leftJoin('optcl_relation_master', 'optcl_relation_master.id', '=', 'optcl_existing_user.relation_type')
                                    ->leftJoin('optcl_relation_status_master', 'optcl_relation_status_master.id', '=', 'optcl_existing_user.relation_current_status')
                                    ->select('optcl_existing_user.*', 'optcl_tax_master.type_name', 'optcl_pension_unit_master.pension_unit_name', 'optcl_pension_type_master.pension_type', 'optcl_employee_gender_master.gender_name', 'optcl_employee_designation_master.designation_name', 'optcl_ti_category_master.category_name','optcl_bank_branch_master.branch_name','optcl_bank_branch_master.ifsc_code','optcl_bank_branch_master.micr_code','optcl_bank_master.bank_name', 'optcl_relation_master.relation_name', 'optcl_relation_status_master.relation_status_name')
                                    ->where('optcl_existing_user.id', $monthly_data->application_id)
                                    ->where('optcl_existing_user.status', 1)
                                    ->where('optcl_existing_user.deleted', 0)
                                    ->first();
            //dd(DB::getQueryLog(), $pensionerDetails);
            if($pensionerDetails){
                //dd(123);
                $religions = Religion::where('status', 1)->where('deleted', 0)->get();
                $office_last_served = OfficeLastServed::where('status', 1)->where('deleted', 0)->get();
                $pensioner_designation = PensionerDesignation::where('status', 1)->where('deleted', 0)->get();     
                $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
                $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
                $account_types = DB::table('optcl_pf_account_type_master')->where('status', 1)->where('deleted', 0)->get();
                $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
                $category_ti = DB::table('optcl_ti_category_master')->where('status', 1)->where('deleted', 0)->get();
                $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();
                $commutation_list = DB::table('optcl_existing_user_commutation')->where('existing_user_id', $pensionerDetails->id)->where('status', 1)->where('deleted', 0)->get();
                //dd(123);
                return view('user.existing_pension_application.pensioner_form_view', compact('monthly_data','pensionerDetails', 'office_last_served','pensioner_designation', 'mstatus', 'genders','account_types', 'banks', 'category_ti','relations', 'commutation_list'));
            }else{
                //dd(123);
                return redirect()->route('billing_officer_approval_list_list');
            }
        }else{
            return redirect()->route('billing_officer_approval_list_list');
        }
    }

    public function show_taxable_amount($appID) {
        //dd($appID);
        // Only for Existing User
        $application_id = $appID;
        $applicationDetails = DB::table('optcl_existing_user')
                                ->join('optcl_pension_type_master', 'optcl_pension_type_master.id', '=', 'optcl_existing_user.pensioner_type')
                                ->join('optcl_application_type', 'optcl_application_type.id', '=', 'optcl_existing_user.application_type')
                                ->select('optcl_existing_user.*', 'optcl_pension_type_master.pension_type', 'optcl_application_type.type_name AS application_type_name')
                                ->where('optcl_existing_user.id', '=', $application_id)
                                ->first();
        $response = [];
        $commutations = [];
        if($applicationDetails){
            $basic_amount = $applicationDetails->basic_amount;
            $additional_pension_amount = $applicationDetails->additional_pension_amount == NULL ? 0 : $applicationDetails->additional_pension_amount;
            $ti_amount = $applicationDetails->ti_amount;
            $ti_percentage = $applicationDetails->ti_percentage;
            $gross_pension_amount = $applicationDetails->gross_pension_amount;
            $application_type_name = $applicationDetails->application_type_name;
            $total = $basic_amount + $additional_pension_amount + $ti_amount;
            $response = [
                "pensioner_name" => $applicationDetails->pensioner_name,
                "ppo_number" => $applicationDetails->new_ppo_no,
                "pension_type" => $applicationDetails->pension_type,
                "pension_type_id" => $applicationDetails->pensioner_type,
                "basic_amount" => $basic_amount,
                "additional_pension_amount" => $additional_pension_amount,
                "ti_amount" => $ti_amount,
                "ti_percentage" => $ti_percentage,
                "gross_pension_amount" => $gross_pension_amount,
                "application_type_name" => $application_type_name,
                "total" => $total,
                "total_income" => $applicationDetails->total_income,
            ];
            $commutations = DB::table('optcl_existing_user_commutation')->where('existing_user_id', '=', $application_id)->get();
        }
        $standard_deduction_amount = DB::table('optcl_taxable_details_master')->where('id', 1)->value('deduction_value');
        //dd($response);
        return view('user.existing_pension_application.taxable-amount-details', compact('response', 'commutations', 'standard_deduction_amount', 'application_id'));
    }

    public function taxable_amount_submit(Request $request) {
        //dd($request->all());
        $validation = array();
        $application_id = $request->application_id;
        $total_income = $request->total_income;
        $standard_deduction = $request->standard_deduction;
        $pensioner_type_id = $request->pensioner_type_id;

        $amount_80c = $request->amount_80c;
        if($amount_80c == ""){
            $validation['error'][] = array("id" => "amount_80c-error","eValue" => "Please enter 80C (LIC/ PPFA/ HB Principal)");
        }
        $amount_80d = $request->amount_80d;
        if($amount_80d == ""){
            $validation['error'][] = array("id" => "amount_80d-error","eValue" => "Please enter 80D (Health Insurance)");
        }
        $amount_8dd = $request->amount_8dd;
        if($amount_8dd == ""){
            $validation['error'][] = array("id" => "amount_8dd-error","eValue" => "Please select 80DD (Dependent Disability)");
        }
        $amount_80e = $request->amount_80e;
        if($amount_80e == ""){
            $validation['error'][] = array("id" => "amount_80e-error","eValue" => "Please enter 80E (Higher Education Interest)");
        }
        $amount_80u = $request->amount_80u;
        if($amount_80u == ""){
            $validation['error'][] = array("id" => "amount_80u-error","eValue" => "Please select 80U (Self Disability)");
        }
        $amount_24b = $request->amount_24b;
        if($amount_24b == ""){
            $validation['error'][] = array("id" => "amount_24b-error","eValue" => "Please select 24B (House Building Interest)");
        }
        $others_amount = $request->others_amount;
        if($others_amount == ""){
            $validation['error'][] = array("id" => "others_amount-error","eValue" => "Please enter others amount");
        }
        $taxable_amount = $request->taxable_amount;
        if($taxable_amount == ""){
            $validation['error'][] = array("id" => "taxable_amount-error","eValue" => "Please fill all fileds");
        }
        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $data = [
                    "appliation_type"        => 2,
                    "pensioner_type"         => $pensioner_type_id,
                    "application_id"         => $application_id,
                    "total_income"           => $total_income,
                    "standard_deduction"     => $standard_deduction,
                    "amount_80c"             => $amount_80c,
                    "amount_80d"             => $amount_80d,
                    "amount_80dd"            => $amount_8dd,
                    "amount_80u"             => $amount_80u,
                    "amount_24b"             => $amount_24b,
                    "amount_others"          => $others_amount,   
                    "taxable_amount"         => $taxable_amount,
                    "created_by"             => Auth::user()->id,
                    "created_at"             => $this->current_date,
                ];                
                DB::table('optcl_taxable_amount_calculation_details')->insert($data);
                // Update taxable amount on optcl_existing_user
                $updateTaxableAmount = ['is_taxable_amount_generated' => 1,'taxable_amount' => $taxable_amount];
                DB::table('optcl_existing_user')->where('id', $application_id)->update($updateTaxableAmount);


                Session::flash('success', 'Pensioner added successfully');
                DB::commit();
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

}