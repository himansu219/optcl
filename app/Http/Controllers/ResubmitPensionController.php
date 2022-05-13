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


class ResubmitPensionController extends Controller { 
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function check_employee(){
        $username = Auth::user()->username;
        // Check all data submitted or not
        
        return view('user.resubmit_proposal.check_employee', compact('username'));
    }

    public function edit_pensioner_form(Request $request){
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first();
        $application_no = $application_data->employee_id;
        $aID = $application_data->id;
        $return_fields = DB::table('optcl_application_form_field_status')
                            ->where('application_id', $aID)
                            ->where('form_id', 1)
                            ->where('status_id', 2)
                            ->count();

        if($return_fields < 1){
            //Session::flash('error', 'Return application not found');
            return redirect()->route('personal_resubmit_page');
        }

        $user_details = Auth::user();
        $religions = Religion::where('status', 1)->where('deleted', 0)->get();
        $office_last_served = OfficeLastServed::where('status', 1)->where('deleted', 0)->get();
        $pensioner_designation = PensionerDesignation::where('status', 1)->where('deleted', 0)->get();     
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $account_types = DB::table('optcl_pf_account_type_master')->where('status', 1)->where('deleted', 0)->get();
        $pensionerDetails = DB::table('optcl_employee_master as em')
                                ->select('em.*')
                                ->where('em.id', $application_no)
                                ->first();
        return view('user.resubmit_proposal.edit_pensioner_form', compact('religions', 'office_last_served','pensioner_designation','user_details', 'mstatus', 'genders','account_types', 'pensionerDetails'));
    }

    public function update_pensioner_form(Request $request){
        // Here we can check wheather there is any other field pending in other form or not. As we can save redirect. 

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
        
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $pension_form_id = $request->pension_form_id;
                $pension_column = new Pensionform();
                $pension_column->exists = true;
                $pension_column->id = $pension_form_id;
                $pension_column->employee_code = $emp_code;
                $pension_column->aadhaar_no = $aadhaar_no;
                if(Util::check_field_return_status(3)){
                    $pension_column->employee_name = $name;
                }
                if(Util::check_field_return_status(4)){
                    $pension_column->designation_id = $designation;
                }
                if(Util::check_field_return_status(5)){
                    $pension_column->father_name = $father_name;
                }
                if(Util::check_field_return_status(6)){
                    $pension_column->gender_id = $gender;
                }
                if(Util::check_field_return_status(7)){
                    $pension_column->marital_status_id = $marital_status;
                }
                if(Util::check_field_return_status(80)){
                    $pension_column->husband_name = $husband_name;
                }
                if(Util::check_field_return_status(8)){
                    $pension_column->religion_id = $religion;
                }
                if(Util::check_field_return_status(9)){
                    $pension_column->pf_account_type_id = $pf_acno_type;
                }
                if(Util::check_field_return_status(79)){
                    $pension_column->pf_account_no = $pf_acno;
                }
                if(Util::check_field_return_status(10)){
                    $pension_column->optcl_unit_id = $name_of_office_dept;
                }
                if(Util::check_field_return_status(11)){
                    $pension_column->date_of_birth = date('Y-m-d', strtotime($dob));
                }
                if(Util::check_field_return_status(12)){
                    $pension_column->date_of_joining = date('Y-m-d', strtotime($doj));
                }
                if(Util::check_field_return_status(13)){
                    $pension_column->date_of_retirement = date('Y-m-d', strtotime($dor));
                }
                $pension_column->modified_by = Auth::user()->id;
                $pension_column->modified_at = $this->current_date;
                //dd(Util::check_field_return_status(4));
                $pension_column->save();

                $application_data = DB::table('optcl_pension_application_form')
                    ->where('user_id', Auth::user()->id)
                    ->first();
                $application_no = $application_data->employee_id;
                $a_no = $application_data->application_no;
                $aID = $application_data->id;
                $application_status_id = $application_data->application_status_id;
                // Update optcl_application_form_field_status where status was rejected
                $where2 = [
                    'application_id' => $aID,
                    'form_id' => 1,
                    'status_id' => 2,
                ];
                DB::table('optcl_application_form_field_status')
                    ->where($where2)
                    ->update(['status_id' => NULL,'is_latest' => 0]);
                // Check other form field return value and proceed
                $return_fields = DB::table('optcl_application_form_field_status')
                                    ->where('application_id', $aID)
                                    ->whereIn('form_id', [2,3,4])
                                    ->where('status_id', 2)
                                    ->count();
                //dd($return_fields);
                if($return_fields < 1){
                    if($application_status_id == 3){
                        // 3 - DA Returned
                        // 16 - Application Resubmitted to DA
                        $to_update_status_id = 16;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else if($application_status_id == 8){
                        // 8 - HR Wing (DA) Returned
                        // 28 - Application Resubmitted to HR Wing (DA)
                        $to_update_status_id = 28;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Unit Head
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Wing Dealing Assistant
                        $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                        Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                        // HR Wing Sanctioning Authority
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else if($application_status_id == 30){
                        // 30 - HR Executive Returned
                        // 31 - Application Resubmitted to HR Executive
                        $to_update_status_id = 31;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id'); 
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Unit Head
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Wing Dealing Assistant
                        $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                        Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                        // HR Wing Sanctioning Authority
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else{
                        // 17 - Application Resubmitted to Finanace Executive
                        $to_update_status_id = 17;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }
                    $where = ['id' => $aID];
                    DB::table('optcl_pension_application_form')
                        ->where($where)
                        ->update(['application_status_id' => $to_update_status_id]);
                    
                    // Status History
                    DB::table('optcl_application_status_history')->insertGetId([
                        'user_id'           => Auth::user()->id,
                        'application_id'    => $aID,
                        'status_id'         => $to_update_status_id,
                        'created_at'        => $this->current_date,
                        'created_by'        => Auth::user()->id,
                    ]);
                    $validation = ['fFormStatus' => TRUE];
                }else{
                    $validation = ['fFormStatus' => FALSE];
                }
                
                Session::flash('success', 'Application submitted successfully');

                DB::commit();                
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }

    public function edit_personal_details(){
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first();
        $application_no = $application_data->employee_id;
        $aID = $application_data->id;

        $return_fields = DB::table('optcl_application_form_field_status')
                            ->where('application_id', $aID)
                            ->where('form_id', 2)
                            ->where('status_id', 2)
                            ->count();
        if($return_fields < 1){
            //Session::flash('error', 'Return application not found');
            if(Session::has('success')){
                Session::flash('success', 'Application submitted successfully');
            }
            return redirect()->route('nominee_form_resubmit');
        }
        
        $country = DB::table('optcl_country_master')->where('status', 1)->where('deleted', 0)->get();

        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();
        $last_served = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
        // Get all details
        $personalDetails = DB::table('optcl_employee_personal_details AS pd')
                                ->select('pd.*','s.state_name','d.district_name','s2.state_name as sName','d2.district_name as dName')
                                ->join('optcl_state_master AS s','s.id','=','pd.permanent_addr_state_id')
                                ->join('optcl_district_master AS d','d.id','=','pd.permanent_addr_district_id')
                                ->join('optcl_state_master AS s2','s2.id','=','pd.present_addr_state_id')
                                ->join('optcl_district_master AS d2','d2.id','=','pd.present_addr_district_id')
                                ->where('employee_id', $application_no)->first();

        return view('user.resubmit_proposal.edit_persional_details', compact('country','banks','last_served','personalDetails','relations'));
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

    public function update_personal_details(Request $request){
        
        //dd($request->all());
        $validation = array();
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
        $same_as_above = $request->same_as_above ? 1:0;
        $atpost1 = $request->atpost1;
        if($atpost1 == ""){
            $validation['error'][] = array("id" => "atpost1-error","eValue" => "Please select at");
        }
        $postoffice1 = $request->postoffice1;
        if($postoffice1 == ""){
            $validation['error'][] = array("id" => "postoffice1-error","eValue" => "Please enter post office");
        }
        $pincode1 = $request->pincode1;
        if($pincode1 == ""){
            $validation['error'][] = array("id" => "pincode1-error","eValue" => "Please enter pin code");
        }
        $country1 = $request->country1;
        if($country1 == ""){
            $validation['error'][] = array("id" => "country1-error","eValue" => "Please select country");
        }
        $state1 = $request->state1;
        if($state1 == ""){
            $validation['error'][] = array("id" => "state1-error","eValue" => "Please select state");
        }
        $district1 = $request->district1;
        if($district1 == ""){
            $validation['error'][] = array("id" => "district1-error","eValue" => "Please select district");
        }
        $telephone_no = $request->telephone_no;
        /*if($telephone_no == ""){
            $validation['error'][] = array("id" => "telephone_no-error","eValue" => "Please enter telephone no");
        }*/
        $mobile_no = $request->mobile_no;
        if($mobile_no == ""){
            $validation['error'][] = array("id" => "mobile_no-error","eValue" => "Please enter mobile no");
        }
        $email = $request->email;
        $pan_no = $request->pan_no;
        if($pan_no == ""){
            $validation['error'][] = array("id" => "pan_no-error","eValue" => "Please enter pan no");
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
        $gratuiyty_recieved = $request->gratuiyty_recieved;        
        if($if_yes == 1){
            if($civil_service == ""){
                $validation['error'][] = array("id" => "civil_service-error","eValue" => "Please enter perticular civil service name");
            }
        }else{
            $civil_service = null;
        }
        if($if_yes == 1){
            if($gratuiyty_recieved == ""){
                $validation['error'][] = array("id" => "gratuiyty_recieved-error","eValue" => "Please enter amount and nature of any pension or gratuity received");
            }
        }else{
            $gratuiyty_recieved = null;
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
        $percentage = $request->percentage;
        $percentage_value = $request->percentage_value;
        if($percentage == 1){
            if($percentage_value == ""){
                $validation['error'][] = array("id" => "percentage_value-error","eValue" => "Please enter percentage value");
            }
        }else{
            $percentage_value = null;
        }
        $basic_pay = $request->basic_pay;
        if($basic_pay == ""){
            $validation['error'][] = array("id" => "basic_pay-error","eValue" => "Please enter last basic pay");
        }
        if(!isset($validation['error'])){
            $personalDetailsID = $request->persional_detail_id;
            $personalDetails = new PersonalDetails();
            $personalDetails->exists = true;
            $personalDetails->id = $personalDetailsID;
            if(Util::check_field_return_status(14)){
                $personalDetails->permanent_addr_at = $atpost;
            }
            if(Util::check_field_return_status(15)){
                $personalDetails->permanent_addr_post = $postoffice;
            }
            if(Util::check_field_return_status(16)){
                $personalDetails->permanent_addr_pincode = $pincode;
            }
            if(Util::check_field_return_status(17)){
                $personalDetails->permanent_addr_country_id = $country;
            }
            if(Util::check_field_return_status(18)){
                $personalDetails->permanent_addr_state_id = $state;
            }
            if(Util::check_field_return_status(19)){
                $personalDetails->permanent_addr_district_id = $district;
            }
            /*if(Util::check_field_return_status(6)){
                $personalDetails->same_as_above = $same_as_above;
            }*/
            if(Util::check_field_return_status(20)){
                $personalDetails->present_addr_at = $atpost1;
            }
            if(Util::check_field_return_status(21)){
                $personalDetails->present_addr_post = $postoffice1;
            }
            if(Util::check_field_return_status(22)){
                $personalDetails->present_addr_pincode = $pincode1;
            }
            if(Util::check_field_return_status(23)){
                $personalDetails->present_addr_country_id = $country1;
            }
            if(Util::check_field_return_status(24)){
                $personalDetails->present_addr_state_id = $state1;
            }
            if(Util::check_field_return_status(25)){
                $personalDetails->present_addr_district_id = $district1;
            }
            if(Util::check_field_return_status(26)){
                $personalDetails->telephone_std_code = $telephone_no;
            }
            if(Util::check_field_return_status(27)){
                $personalDetails->mobile_no = $mobile_no;
            }
            if(Util::check_field_return_status(28)){
                $personalDetails->email_address = $email;
            }
            if(Util::check_field_return_status(29)){
                $personalDetails->pan_no = $pan_no;
            }
            if(Util::check_field_return_status(30)){
                $personalDetails->savings_bank_account_no = $saving_bank_ac_no;
            }
            if(Util::check_field_return_status(31)){
                $personalDetails->bank_branch_id = $branch_name_address;
            }
            if(Util::check_field_return_status(34)){
                $personalDetails->basic_pay_amount_at_retirement = $basic_pay;
            }
            if(Util::check_field_return_status(35)){
                $personalDetails->pension_unit_id = $name_of_the_unit;
            }
            if(Util::check_field_return_status(36)){
                $personalDetails->is_civil_service_amount_received = $if_yes;
            }
            if(Util::check_field_return_status(37)){
                $personalDetails->civil_service_name = $civil_service;
            }
            if(Util::check_field_return_status(38)){
                $personalDetails->civil_service_received_amount = $gratuiyty_recieved;
            }
            if(Util::check_field_return_status(39)){
                $personalDetails->is_family_pension_received_by_family_members = $addmissible;
            }
            if(Util::check_field_return_status(40)){
                $personalDetails->admission_source_of_family_pension = $addmissble_value;
            }
            if(Util::check_field_return_status(41)){
                $personalDetails->family_member_relation_id = $addmissible_family;
            }
            if(Util::check_field_return_status(42)){
                $personalDetails->family_member_name = $addmissible_family_name;
            }
            if(Util::check_field_return_status(6)){
                $personalDetails->is_commutation_pension_applied = $percentage;
            }
            if(Util::check_field_return_status(44)){
                $personalDetails->commutation_percentage = $percentage_value;
            }
            $personalDetails->modified_by = Auth::user()->id;
            $personalDetails->modified_at = $this->current_date;
            $personalDetails->save();

            $application_data = DB::table('optcl_pension_application_form')
                ->where('user_id', Auth::user()->id)
                ->first();
            $application_no = $application_data->employee_id;
            $a_no = $application_data->application_no;
            $aID = $application_data->id;
            $application_status_id = $application_data->application_status_id;
            // Update optcl_application_form_field_status where status was rejected
            $where2 = [
                'application_id' => $aID,
                'form_id' => 2,
                'status_id' => 2,
            ];
            DB::table('optcl_application_form_field_status')
                ->where($where2)
                ->update(['status_id' => NULL,'is_latest' => 0]);
            // Check other form field return value and proceed
            $return_fields = DB::table('optcl_application_form_field_status')
                                ->where('application_id', $aID)
                                ->whereIn('form_id', [3,4])
                                ->where('status_id', 2)
                                ->count();
            if($return_fields < 1){
                
                if($application_status_id == 3){
                    // 3 - DA Returned
                    // 16 - Application Resubmitted to DA
                    $to_update_status_id = 16;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else if($application_status_id == 8){
                    // 8 - HR Wing (DA) Returned
                    // 28 - Application Resubmitted to HR Wing (DA)
                    $to_update_status_id = 28;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Unit Head
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Wing Dealing Assistant
                    $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                    Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                    // HR Wing Sanctioning Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else if($application_status_id == 30){
                    // 30 - HR Executive Returned
                    // 31 - Application Resubmitted to HR Executive
                    $to_update_status_id = 31;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id'); 
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Unit Head
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Wing Dealing Assistant
                    $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                    Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                    // HR Wing Sanctioning Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else{
                    // 17 - Application Resubmitted to Finanace Executive
                    $to_update_status_id = 17;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }
                $where = ['id' => $aID];
                DB::table('optcl_pension_application_form')
                    ->where($where)
                    ->update(['application_status_id' => $to_update_status_id]);
                
                // Status History
                 DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => Auth::user()->id,
                    'application_id'    => $aID,
                    'status_id'         => $to_update_status_id,
                    'created_at'        => $this->current_date,
                    'created_by'        => Auth::user()->id,
                ]);
                $validation = ['fFormStatus' => TRUE];
            }else{
                $validation = ['fFormStatus' => FALSE];
            }
            Session::flash('success', 'Application submitted successfully');
        }else{
            //Session::flash('error', '$validation['error'][0]['eValue']');
            //return redirect()->route('login_form');
        }
        echo json_encode($validation);  
    }

    public function nominee_form() {
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first();
        $id = $application_data->employee_id;
        $aID = $application_data->id;
        $return_fields = DB::table('optcl_application_form_field_status')
                            ->where('application_id', $aID)
                            ->where('form_id', 3)
                            ->where('status_id', 2)
                            ->count();
        if($return_fields < 1){
            //Session::flash('error', 'Return application not found');
            if(Session::has('success')){
                Session::flash('success', 'Application submitted successfully');
            }
            return redirect()->route('pension_documents_resubmit');
        }

        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $genders = DB::table('optcl_employee_gender_master')->where('status', 1)->where('deleted', 0)->get();
        $mstatus = DB::table('optcl_marital_status_master')->where('status', 1)->where('deleted', 0)->get();
        $relations = DB::table('optcl_relation_master')->where('status', 1)->where('deleted', 0)->get();
        $nominee_prefences = DB::table('optcl_nominee_preference_master')->where('status', 1)->where('deleted', 0)->get();

        $employee_nominee_details = DB::table('optcl_employee_nominee_details as a')->select('a.id', 'a.nominee_name', 'a.nominee_aadhaar_no', 'b.nominee_prefrence', 'a.nominee_preference_id')
                            ->join('optcl_nominee_preference_master as b', 'a.nominee_preference_id', '=', 'b.id')
                            ->where('a.employee_id', $id)
                            ->where('a.status', 1)
                            ->where('a.deleted', 0)
                            ->get();

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
        $nominee_list = DB::table('optcl_employee_nominee_details')->where('employee_id', $id)->where('status', 1)->where('deleted', 0)->get();


        $nominee_preference_ids = implode(',', $nominee_preference_ids);
        $key = 1;

        return view('user.resubmit_proposal.nominee_form', compact('banks', 'genders', 'mstatus', 'key', 'id', 'relations', 'nominee_prefences', 'employee_nominee_details', 'nominee_list', 'nominee_preference_ids'));
    }

    public function save_nominee_details(Request $request) {
        
        $validation = array();

        try {
            DB::beginTransaction();

            $nominees = $request->nominee;

            // dd($nominees);
            $id = $request->id;
            // $id = 1;

            if(!empty($nominees)) {

                $employee_master = DB::table('optcl_employee_master')->where('id', $id)->first();

                foreach ($nominees as $key => $value) {

                    if(!empty($value['nominee_id'])) {
                        $details = DB::table('optcl_employee_nominee_details')->where('id', $value['nominee_id'])->where('status', 1)->where('deleted', 0)->first();
                    }

                    $dob_attachment_path = NULL;
                    if(!empty($details->dob_attachment_path)) {
                        $dob_attachment_path = $details->dob_attachment_path;
                    }

                    $upload_path = 'uploads/documents/';
                    
                    // $dob_attachment_path_file = !empty($request->file('nominee')[$key]['dob_attachment_path']) ? $request->file('nominee')[$key]['dob_attachment_path'] : NULL;
                    $dob_attachment_path_file = !empty($value['dob_attachment_path_hidden']) ? $value['dob_attachment_path_hidden'] : NULL;

                    if(!empty($dob_attachment_path_file)) {

                        /*$filename = Util::rand_filename($dob_attachment_path_file->getClientOriginalExtension());
                        $dob_attachment_path = Util::upload_file($dob_attachment_path_file, $filename, null, $upload_path);*/

                        list($type, $cropped_image) = explode(';', $dob_attachment_path_file);
                        list(, $cropped_image) = explode(',', $cropped_image);
                        $cropped_image = base64_decode($cropped_image);
                        $image_name = time().'-'.'1'.rand(0,999999999).'.png';
                        $dob_attachment_path = 'uploads/documents/'.$image_name;
                        file_put_contents('public/uploads/documents/'.$image_name, $cropped_image);
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
                            file_put_contents('public/uploads/documents/'.$image_name, $cropped_image);
                            $first_spouse_death_certificate_path = 'uploads/documents/'.$image_name;
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
                            $disability_certificate_path = 'uploads/documents/'.$image_name;
                            file_put_contents('public/uploads/documents/'.$image_name, $cropped_image);

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

                    if(!empty($value['is_minor']) && $value['is_minor'] == 1) {
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
                            file_put_contents('public/uploads/documents/'.$image_name, $cropped_image);
                            $legal_guardian_attachment_path = 'uploads/documents/'.$image_name;
                            /*$filename = Util::rand_filename($legal_guardian_attachment_path_file->getClientOriginalExtension());
                            $legal_guardian_attachment_path = Util::upload_file($legal_guardian_attachment_path_file, $filename, null, $upload_path);*/
                        }

                    }

                    $nominee = [
                        'employee_id' => $id,
                        'employee_code' => $employee_master->employee_code,
                        'employee_aadhaar_no' => $employee_master->aadhaar_no,
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
                        'is_minor' => !empty($value['is_minor']) && $value['is_minor'] ? $value['is_minor']: NULL,
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
                        DB::table('optcl_employee_nominee_details')->where('id', $value['nominee_id'])->update($nominee);
                    } else {
                        DB::table('optcl_employee_nominee_details')->insert($nominee);
                    }
                }
                
                $application_data = DB::table('optcl_pension_application_form')
                    ->where('user_id', Auth::user()->id)
                    ->first();
                $application_no = $application_data->employee_id;
                $a_no = $application_data->application_no;
                $aID = $application_data->id;
                $application_status_id = $application_data->application_status_id;
                // Update optcl_application_form_field_status where status was rejected
                $where2 = [
                    'application_id' => $aID,
                    'form_id' => 3,
                    'status_id' => 2,
                ];
                DB::table('optcl_application_form_field_status')
                    ->where($where2)
                    ->update(['status_id' => NULL,'is_latest' => 0]);
                // Check other form field return value and proceed
                $return_fields = DB::table('optcl_application_form_field_status')
                                    ->where('application_id', $aID)
                                    ->whereIn('form_id', [4])
                                    ->where('status_id', 2)
                                    ->count();
                if($return_fields < 1){
                    if($application_status_id == 3){
                        // 3 - DA Returned
                        // 16 - Application Resubmitted to DA
                        $to_update_status_id = 16;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else if($application_status_id == 8){
                        // 8 - HR Wing (DA) Returned
                        // 28 - Application Resubmitted to HR Wing (DA)
                        $to_update_status_id = 28;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Unit Head
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Wing Dealing Assistant
                        $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                        Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                        // HR Wing Sanctioning Authority
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else if($application_status_id == 30){
                        // 30 - HR Executive Returned
                        // 31 - Application Resubmitted to HR Executive
                        $to_update_status_id = 31;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id'); 
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Unit Head
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Wing Dealing Assistant
                        $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                        Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                        // HR Wing Sanctioning Authority
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // HR Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }else{
                        // 17 - Application Resubmitted to Finanace Executive
                        $to_update_status_id = 17;
                        // Notification Area
                        $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                        // Dealing Assistant
                        $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                        // Finance Executive
                        $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                        Util::insert_notification($n_user_id, $application_data->id, $message);
                    }
                    $where = ['id' => $aID];
                    DB::table('optcl_pension_application_form')
                        ->where($where)
                        ->update(['application_status_id' => $to_update_status_id]);
                    
                    // Status History
                     DB::table('optcl_application_status_history')->insertGetId([
                        'user_id'           => Auth::user()->id,
                        'application_id'    => $aID,
                        'status_id'         => $to_update_status_id,
                        'created_at'        => $this->current_date,
                        'created_by'        => Auth::user()->id,
                    ]);
                    $validation = ['fFormStatus' => TRUE];
                }else{
                    $validation = ['fFormStatus' => FALSE];
                }
                Session::flash('success', 'Application submitted successfully');
                DB::commit();
            }
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        
        echo json_encode($validation);
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

    /**
     * Praveen Code
     */
    public function get_bank_branch(Request $request) {
        $sid = $request->post('sid');
        $bank_branch = DB::table('optcl_bank_branch_master')->where('bank_id', $sid)->get();
        $html = '<option value="">Select Bank Branch</option>';
        foreach($bank_branch as $list){
            $html.='<option value="'.$list->id.'">'.$list->branch_name.'</option>';
        }
        echo $html;
    }

    public function getBranchDetails(Request $request) {
        $sid = $request->post('sid');

        $branch_details = DB::table('optcl_bank_branch_master')->select('id', 'branch_name', 'ifsc_code', 'micr_code')->where('id', $sid)->first();

        return response()->json($branch_details);
    }
    
    public function pension_documents() {
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first();
        $id = $application_data->employee_id;
        $aID = $application_data->id;
        $return_fields = DB::table('optcl_application_form_field_status')
                            ->where('application_id', $aID)
                            ->where('form_id', 4)
                            ->where('status_id', 2)
                            ->count();
        if($return_fields < 1){
            //Session::flash('error', 'Return application not found');
            if(Session::has('success')){
                Session::flash('success', 'Application submitted successfully');
            }
            return redirect()->route('view_details');
        }

        $employee_master = DB::table('optcl_employee_master')->where('id', $id)->first();

        $edit = 0;

        $employee_documents = DB::table('optcl_employee_document_details')->where('employee_id', $id)->first();

        if(!empty($employee_documents)) {
            $edit = 1;
        }
        return view('user.resubmit_proposal.pensioner_document', compact('id', 'employee_master', 'edit', 'employee_documents'));
    }

    public function save_pension_documents(Request $request) {
        $validation = array();

        try {
            DB::beginTransaction();

            $employee_master = DB::table('optcl_employee_master')->where('id', $request->employee_id)->first();
            $employee_documents = DB::table('optcl_employee_document_details')->where('employee_id', $request->employee_id)->first();

            if($request->edit == 0) {
                if($request->attached_recent_passport == ""){
                    $validation['error'][] = array("id" => "attached_recent_passport-error","eValue" => "Please select file");
                }

                if($request->attached_dob_certificate == ""){
                    $validation['error'][] = array("id" => "attached_dob_certificate-error","eValue" => "Please select file");
                }

                if($request->attached_undertaking_declaration == ""){
                    $validation['error'][] = array("id" => "attached_undertaking_declaration-error","eValue" => "Please select file");
                }

                if($request->attached_bank_passbook == ""){
                    $validation['error'][] = array("id" => "attached_bank_passbook-error","eValue" => "Please select file");
                }

                if($request->attached_cancelled_chqeue == ""){
                    $validation['error'][] = array("id" => "attached_cancelled_chqeue-error","eValue" => "Please select file");
                }

                if($employee_master->date_of_joining >= '1991-03-31' && $employee_master->pf_account_type_id == 1) {
                    if($request->attached_indemnity_bond == "") {
                    $validation['error'][] = array("id" => "attached_indemnity_bond-error","eValue" => "Please select file");
                    }
                }

                if($request->attached_descriptive_roll_slips == ""){
                    $validation['error'][] = array("id" => "attached_descriptive_roll_slips-error","eValue" => "Please select file");
                }
            }
            
            if(!isset($validation['error'])) {

                $attached_recent_passport = '';
                $attached_dob_certificate = '';
                $attached_undertaking_declaration = '';
                $attached_bank_passbook = '';
                $attached_cancelled_chqeue = '';
                $attached_indemnity_bond = '';
                $attached_descriptive_roll_slips = '';

                $upload_path = 'uploads/documents/';

                if($request->hasFile('attached_recent_passport')) {
                    $filename = Util::rand_filename($request->file('attached_recent_passport')->getClientOriginalExtension());
                    $attached_recent_passport = Util::upload_file($request->file('attached_recent_passport'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 1)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 1,
                            'document_attachment_path' => $attached_recent_passport,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 1)->update([
                            'document_attachment_path' => $attached_recent_passport,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_recent_passport)) {
                        $attached_recent_passport = $employee_documents->attached_recent_passport;
                    }
                }

                if($request->hasFile('attached_descriptive_roll_slips')) {
                    $filename = Util::rand_filename($request->file('attached_descriptive_roll_slips')->getClientOriginalExtension());
                    $attached_descriptive_roll_slips = Util::upload_file($request->file('attached_descriptive_roll_slips'), $filename, null, $upload_path);
                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 2)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 2,
                            'document_attachment_path' => $attached_descriptive_roll_slips,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 2)->update([
                            'document_attachment_path' => $attached_descriptive_roll_slips,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_descriptive_roll_slips)) {
                        $attached_descriptive_roll_slips = $employee_documents->attached_descriptive_roll_slips;
                    }
                }

                if($request->hasFile('attached_dob_certificate')) {
                    $filename = Util::rand_filename($request->file('attached_dob_certificate')->getClientOriginalExtension());
                    $attached_dob_certificate = Util::upload_file($request->file('attached_dob_certificate'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 3)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 3,
                            'document_attachment_path' => $attached_dob_certificate,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 3)->update([
                            'document_attachment_path' => $attached_dob_certificate,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_dob_certificate)) {
                        $attached_dob_certificate = $employee_documents->attached_dob_certificate;
                    }
                }

                if($request->hasFile('attached_undertaking_declaration')) {
                    $filename = Util::rand_filename($request->file('attached_undertaking_declaration')->getClientOriginalExtension());
                    $attached_undertaking_declaration = Util::upload_file($request->file('attached_undertaking_declaration'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 4)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 4,
                            'document_attachment_path' => $attached_undertaking_declaration,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 4)->update([
                            'document_attachment_path' => $attached_undertaking_declaration,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_undertaking_declaration)) {
                        $attached_undertaking_declaration = $employee_documents->attached_undertaking_declaration;
                    }
                }

                if($request->hasFile('attached_bank_passbook')) {
                    $filename = Util::rand_filename($request->file('attached_bank_passbook')->getClientOriginalExtension());
                    $attached_bank_passbook = Util::upload_file($request->file('attached_bank_passbook'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 5)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 5,
                            'document_attachment_path' => $attached_bank_passbook,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 5)->update([
                            'document_attachment_path' => $attached_bank_passbook,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_bank_passbook)) {
                        $attached_bank_passbook = $employee_documents->attached_bank_passbook;
                    }
                }

                if($request->hasFile('attached_cancelled_chqeue')) {
                    $filename = Util::rand_filename($request->file('attached_cancelled_chqeue')->getClientOriginalExtension());
                    $attached_cancelled_chqeue = Util::upload_file($request->file('attached_cancelled_chqeue'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 6)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 6,
                            'document_attachment_path' => $attached_cancelled_chqeue,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 6)->update([
                            'document_attachment_path' => $attached_cancelled_chqeue,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_cancelled_chqeue)) {
                        $attached_cancelled_chqeue = $employee_documents->attached_cancelled_chqeue;
                    }
                }

                if($request->hasFile('attached_indemnity_bond')) {
                    $filename = Util::rand_filename($request->file('attached_indemnity_bond')->getClientOriginalExtension());
                    $attached_indemnity_bond = Util::upload_file($request->file('attached_indemnity_bond'), $filename, null, $upload_path);

                    $application_document = DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 7)->first();

                    if(empty($application_document)) {
                        DB::table('optcl_pension_application_document')->insert([
                            'employee_id' => $request->employee_id,
                            'document_id' => 7,
                            'document_attachment_path' => $attached_indemnity_bond,
                            'created_at' => $this->current_date,
                            'created_by' => Auth::user()->id,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    } else {
                        DB::table('optcl_pension_application_document')->where('employee_id', $request->employee_id)->where('document_id', 7)->update([
                            'document_attachment_path' => $attached_indemnity_bond,
                            'modified_at' => $this->current_date,
                            'modified_by' => Auth::user()->id
                        ]);
                    }
                } else {
                    if(!empty($employee_documents->attached_indemnity_bond)) {
                        $attached_indemnity_bond = $employee_documents->attached_indemnity_bond;
                    }
                }

                $pension_documents = [
                    'employee_id' => $request->employee_id,
                    'attached_recent_passport' => $attached_recent_passport,
                    'attached_dob_certificate' => $attached_dob_certificate,
                    'attached_undertaking_declaration' => $attached_undertaking_declaration,
                    'attached_bank_passbook' => $attached_bank_passbook,
                    'attached_cancelled_chqeue' => $attached_cancelled_chqeue,
                    'attached_indemnity_bond' => $attached_indemnity_bond,
                    'attached_descriptive_roll_slips' => $attached_descriptive_roll_slips,
                    'created_at' => $this->current_date,
                    'created_by' => Auth::user()->id,
                    'modified_at' => $this->current_date,
                    'modified_by' => Auth::user()->id
                ];

                DB::table('optcl_employee_document_details')->updateOrInsert(
                    ['employee_id' => $request->employee_id],
                    $pension_documents);
                // Update Status

                $application_data = DB::table('optcl_pension_application_form')
                    ->where('user_id', Auth::user()->id)
                    ->first();
                $application_no = $application_data->employee_id;
                $a_no = $application_data->application_no;
                $aID = $application_data->id;
                $application_status_id = $application_data->application_status_id;
                // Update optcl_application_form_field_status where status was rejected
                $where2 = [
                    'application_id' => $aID,
                    'form_id' => 4,
                    'status_id' => 2,
                ];
                DB::table('optcl_application_form_field_status')
                    ->where($where2)
                    ->update(['status_id' => NULL,'is_latest' => 0]);
                // Check other form field return value and proceed
                if($application_status_id == 3){
                    // 3 - DA Returned
                    // 16 - Application Resubmitted to DA
                    $to_update_status_id = 16;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else if($application_status_id == 8){
                    // 8 - HR Wing (DA) Returned
                    // 28 - Application Resubmitted to HR Wing (DA)
                    $to_update_status_id = 28;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Unit Head
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Wing Dealing Assistant
                    $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                    Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                    // HR Wing Sanctioning Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else if($application_status_id == 30){
                    // 30 - HR Executive Returned
                    // 31 - Application Resubmitted to HR Executive
                    $to_update_status_id = 31;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id'); 
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Unit Head
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 4)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Wing Dealing Assistant
                    $dealing_assistant_list = DB::table('optcl_application_user_assignments')->where('application_id', $application_data->id)->value('user_id');
                    Util::insert_notification($dealing_assistant_list, $application_data->id, $message);
                    // HR Wing Sanctioning Authority
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 7)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // HR Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 6)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }else{
                    // 17 - Application Resubmitted to Finanace Executive
                    $to_update_status_id = 17;
                    // Notification Area
                    $message = "Application resubmitted by pensioner with application no ".$a_no.". Please check the application details.";
                    // Dealing Assistant
                    $optcl_unit_id = DB::table('optcl_users')->where('designation_id', 1)->where('id', Auth::user()->id)->value('optcl_unit_id');
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 2)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                    // Finance Executive
                    $n_user_id = DB::table('optcl_users')->where('designation_id', 3)->where('optcl_unit_id', $optcl_unit_id)->value('id');
                    Util::insert_notification($n_user_id, $application_data->id, $message);
                }
                $where = ['id' => $aID];
                DB::table('optcl_pension_application_form')
                    ->where($where)
                    ->update(['application_status_id' => $to_update_status_id]);
                
                // Status History
                 DB::table('optcl_application_status_history')->insertGetId([
                    'user_id'           => Auth::user()->id,
                    'application_id'    => $aID,
                    'status_id'         => $to_update_status_id,
                    'created_at'        => $this->current_date,
                    'created_by'        => Auth::user()->id,
                ]);

                Session::flash('success', 'Application submitted successfully');
                DB::commit();
            }
        } catch(Exception $e) {
            DB::rollback();
            throw $e;       
        }
        return response()->json($validation);
    }
    
    public function delete_nominee_details(Request $request) {
        $validation = array();
        try {
            DB::beginTransaction();

            DB::table('optcl_employee_nominee_details')->where('id', $request->id)->update([
                'deleted' => 1
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return response()->json($validation);
    }
	
	public function check_account_no(Request $request) {
        try {
            $account_no = $request->account_no;
            $nominee_id = $request->nominee_id;
            if(!empty($account_no)) {
                $check_employee = DB::table('optcl_employee_personal_details')->where('savings_bank_account_no', $account_no)->count();

                if($check_employee > 0) {
                    return response()->json(['status' => 'error', 'message' => 'Account No. already exists']);
                }

                $check_nominee = DB::table('optcl_employee_nominee_details')->where('savings_bank_account_no', $account_no);

                if(!empty($nominee_id)) {
                    $check_nominee = $check_nominee->where('id', '!=', $nominee_id);
                }

                $check_nominee = $check_nominee->count();

                if($check_nominee > 0) {
                    return response()->json(['status' => 'error', 'message' => 'Account No. already exists']);
                }

            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid account no']);   
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }

}
