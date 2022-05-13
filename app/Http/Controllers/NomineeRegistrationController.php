<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\NomineeRegistration;
use App\Models\AdminUser;
use App\Models\Otp;
use App\Libraries\Util;
use Session;
use Auth;

class NomineeRegistrationController extends Controller{

    public function __construct(){
        //$this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function index() {
        return view('user.nominee.register');
    }

    public function NomineeLogin() {
        return view('user.nominee.login');
    }

    public function RegistrationFormSubmit(Request $request){
        $validation = array();
        $person_name = $request->person_name;
        if($person_name == ""){
            $validation['error'][] = array("id" => "person_name-error","eValue" => "Please enter person name");
        }
        $employee_acc_type = $request->emp_code;
        $employee_code = $request->employee_code;
        if($employee_acc_type == "employee_code"){
            if($employee_code == ""){
                $validation['error'][] = array("id" => "employee_code-error","eValue" => "Please enter employee code");
            }else{
                $checkCount = DB::table('optcl_employee_master')
                            ->where('employee_code', $employee_code)
                            ->where('deleted', 0)
                            ->count();
                if($checkCount < 1){
                    $validation['error'][] = array("id" => "employee_code-error","eValue" => "Invalid employee code given");
                }     
            }
        }
        $ppo_no = $request->ppo_no;
        if($employee_acc_type == "ppo_no"){
            if($ppo_no == ""){
                $validation['error'][] = array("id" => "ppo_no-error","eValue" => "Please enter PPO No");
            }else{
                $checkCount = DB::table('optcl_employee_master')
                            ->where('ppo_no', $ppo_no)
                            ->whereNotNull('ppo_no')
                            ->where('deleted', 0)
                            ->count();
                if($checkCount < 1){
                    $validation['error'][] = array("id" => "ppo_no-error","eValue" => "Invalid PPO code given");
                }     
            }
        }
        $employee_aadhaar_no = $request->employee_aadhaar_no;
        if(!empty($employee_aadhaar_no)){
            $checkCount = DB::table('optcl_employee_master')
                            ->where('employee_code', $employee_code)
                            ->where('aadhaar_no', $employee_aadhaar_no)
                            ->where('deleted', 0)
                            ->count();
            if($checkCount < 1){
                $validation['error'][] = array("id" => "employee_aadhaar_no-error","eValue" => "Wrong Aadhaar No. given");
            }            
        }
        $nominee_aadhaar_no = $request->nominee_aadhaar_no;
        if($nominee_aadhaar_no == ""){
            $validation['error'][] = array("id" => "nominee_aadhaar_no-error","eValue" => "Please enter person Aadhaar No");
        }else{
            $checkCount = DB::table('optcl_employee_master')
                            ->where('aadhaar_no', $nominee_aadhaar_no)
                            ->where('deleted', 0)
                            ->count();
            $checkCount2 = DB::table('optcl_nominee_master')
                            ->where('aadhaar_no', $nominee_aadhaar_no)
                            ->where('deleted', 0)
                            ->count();
            $checkCount3 = DB::table('optcl_employee_nominee_details')
                            ->where('employee_aadhaar_no', $nominee_aadhaar_no)
                            ->where('deleted', 0)
                            ->count();
            $checkCount4 = DB::table('optcl_nominee_nominee_details')
                            ->where('nominee_aadhaar_no', $nominee_aadhaar_no)
                            ->where('deleted', 0)
                            ->count();
            //dd($checkCount, $checkCount2, $checkCount3, $checkCount4);
            if($checkCount > 0 || $checkCount2 > 0 || $checkCount3 > 0 || $checkCount4 > 0){
                $validation['error'][] = array("id" => "nominee_aadhaar_no-error","eValue" => "Aadhaar No. already exists");
            }
        }
        $mobile_no = $request->mobile_no;
        if($mobile_no == ""){
            $validation['error'][] = array("id" => "mobile_no-error","eValue" => "Please enter person Mobile No.");
        }else{
            $checkCount = DB::table('optcl_users')
                            ->where('mobile', $mobile_no)
                            ->where('deleted', 0)
                            ->count();
            $checkCount2 = DB::table('optcl_employee_nominee_details')
                            ->where('mobile_no', $mobile_no)
                            ->where('deleted', 0)
                            ->count();
            $checkCount3 = DB::table('optcl_nominee_nominee_details')
                            ->where('mobile_no', $mobile_no)
                            ->where('deleted', 0)
                            ->count();
            
            if($checkCount > 0 || $checkCount2 > 0 || $checkCount3 > 0){
                $validation['error'][] = array("id" => "mobile_no-error","eValue" => "Mobile No. already exists");
            }
        }     
        $captcha_value = $request->captcha_value;
        if($captcha_value == ""){
            $validation['error'][] = array("id" => "captcha_value-error","eValue" => "Please enter captcha value");
        }else{
            $validator = Validator::make($request->all(), [
                'captcha_value' => 'captcha'
            ]);
            if ($validator->fails()) {
                $validation['error'][] = array("id" => "captcha_value-error","eValue" => "Invalid captcha value");
            }
        }
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $optcl_users_tbl = new AdminUser;
                $optcl_users_tbl->employee_code = $employee_code;
                $optcl_users_tbl->first_name    = $person_name;
                $optcl_users_tbl->user_type     = 2;
                $optcl_users_tbl->username      = $employee_code;
                $optcl_users_tbl->aadhaar_no    = $nominee_aadhaar_no;
                $optcl_users_tbl->mobile        = $mobile_no;
                //$optcl_users_tbl->password      = bcrypt('Secret@123');
                $optcl_users_tbl->status        = 1;
                $optcl_users_tbl->deleted       = 1;
                $optcl_users_tbl->save();

                $user_id =$optcl_users_tbl->id;
                Session::put('tem_user_id', $user_id);
                $otp = Util::otp_value();
                $cur_date = date('Y-m-d H:i:s', time());
                $otp_ex_time = date('Y-m-d H:i:s', strtotime("+30 minutes",strtotime($cur_date)));
                // insert in optcl_user_otp table
                $otp_insert = new Otp;
                $otp_insert->user_id    = $user_id;
                $otp_insert->otp        = $otp;
                $otp_insert->otp_type   = "nominee_register";
                $otp_insert->created_at = $cur_date;
                $otp_insert->expired_at = $otp_ex_time;
                $otp_insert->verified   = 0;
                $otp_insert->save();
                // insert in optcl_users_otp_history table
                DB::table('optcl_users_otp_history')
                            ->insert([
                            "user_id"    => $user_id,
                            "otp_type"   => "nominee_register",
                            "otp_value"  => $otp,
                            "created_at" => $cur_date,
                            ]);
                $validation = ['success'=>'OTP sent successfully to the  Mobile No', 'status' => 'true'];
                DB::commit();
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        return response()->json($validation);
    }

    public function registrationVerifyOTP(){
       return view('user.nominee.register_otp');
    }

    public function verifySubmitNomineeRegistration(Request $request){
        $validation = array();
        $otp = $request->otp;
        if($otp == ""){
            $validation['error'][] = array("id" => "otp-error","eValue" => "Please enter OTP");
        }
        $password = $request->password;
        if($password == ""){
            $validation['error'][] = array("id" => "password-error","eValue" => "Please enter password");
        }
        $confirm_password = $request->confirm_password;
        if($confirm_password == ""){
            $validation['error'][] = array("id" => "confirm_password-error","eValue" => "Please enter confirm password");
        }   
        $captcha_value = $request->captcha_value;
        if($captcha_value == ""){
            $validation['error'][] = array("id" => "captcha_value-error","eValue" => "Please enter captcha value");
        }else{
            $validator = Validator::make($request->all(), [
                'captcha_value' => 'captcha'
            ]);
            if ($validator->fails()) {
                $validation['error'][] = array("id" => "captcha_value-error","eValue" => "Invalid captcha value");
            }
        }
        if(!isset($validation['error'])){
            DB::beginTransaction();
            try{
                $user_id = Session::get('tem_user_id');      
                //dd($user_id);
                //$user_id = $request->input('user_id');
                $otp = $request->input('otp');
                $password = Util::decrypt_password($request->password);
                $confirm_password = Util::decrypt_password($request->confirm_password);
                $result = Otp::where('user_id', $user_id)
                            ->where('otp', $otp)
                            ->where('verified', 0)
                            ->count();
                $checktime = Otp::select('created_at', 'expired_at')
                            ->where('user_id', $user_id)
                            ->where('otp', $otp)
                            ->first();
                 
                $cur_time = date('Y-m-d H:i:s', time());        
                if($result > 0) {

                    if (strtotime($cur_time) < strtotime($checktime->expired_at)) {
                        Otp::where('user_id', $user_id)
                                    ->where('otp', $otp)
                                    ->update(['verified'=> 1]);
                        DB::table('optcl_users')
                                ->where('id', $user_id)->update([
                                        'password'=> bcrypt($password),
                                        'updated_at'=> date('Y-m-d H:i:s'),
                                        'deleted' => 0
                                    ]);
                        //dd(1);
                        Session::flash('success', 'Password updated successfully');
                        $validation = ['success'=>'Password updated successfully'];
                        DB::commit();
                    } else {
                        Session::flash('error', 'OTP expired. Please try again.');
                        //$response = ['error'=>'OTP expired. Please try again.', 'status' => 'false'];
                        $validation['error'][] = array("id" => "otp-error","eValue" => "OTP expired. Please try again.");
                    }
                } else {
                    Session::flash('error', 'Invalid OTP');
                    //$response = ['error'=>'Invalid OTP', 'status' => 'false'];
                    $validation['error'][] = array("id" => "otp-error","eValue" => "Invalid OTP");
                }
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        return response()->json($validation);
    }

    public function ForgotPassword(){
       return view('user.nominee.forgot_password.forgot_password');
    }
    // Verify Mobile no and Aadhar No form for Service Pensioner
    public function VerifyMobileAadhaar(Request $request){
        
        $mobile_aadhaarno = $request->input('mobile_no');
        $result = AdminUser::select('id','mobile')
                            ->where('mobile',$mobile_aadhaarno)
                            ->orWhere('aadhaar_no',$mobile_aadhaarno)
                            ->where('user_type', 2)
                            ->first();
        $nominee_result = DB::table('optcl_employee_nominee_details')
                            ->select('id','mobile_no')
                            ->where('mobile_no',$mobile_aadhaarno)
                            ->orWhere('nominee_aadhaar_no',$mobile_aadhaarno)
                            ->first();
        if(!empty($result)){
            // Check the mobile number exists with other mobile number
           /* $userCount = DB::table('optcl_users')
                            ->where('mobile',$mobile_aadhaarno)
                            ->orWhere('aadhaar_no',$mobile_aadhaarno)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->count();
            if($userCount > 0){
                return response()->json(['success'=>'OTP sent successfully to the Mobile No', 'status' => 'true']);
            }*/

            $mobile_no = $result->mobile;
            $user_id=$result->id;
            $otp = Util::otp_value();
            //$expired = DB::table('optcl_user_otp_verifications')->get(expired_at('CURRENT_TIMESTAMP', INTERVAL 30 MINUTE));
            $cur_date = date('Y-m-d H:i:s', time());
            $otp_ex_time = date('Y-m-d H:i:s', strtotime("+30 minutes",strtotime($cur_date)));
            $otp_check = Otp::select('otp')->where('user_id',$user_id)->count();    
            if($otp_check > 0){
                Session::put('tem_user_id', $user_id);
                $result= Otp::where('user_id', $user_id)
                            ->update([
                                'otp'=> $otp ,
                                'created_at' => $cur_date ,
                                'expired_at' => $otp_ex_time ,
                                'verified'   => 0
                                ]);
                DB::table('optcl_users_otp_history')
                    ->insert([
                    "user_id"    => $user_id,
                    "otp_type"   => "forgot_password",
                    "otp_value"  => $otp,
                    "created_at" => $cur_date,
                    ]);
                $response = ['success'=>'OTP sent to your registered mobile no', 'status' => 'true', 'user_id' => $user_id];                  
            } else {                        
                $otp_insert= new Otp;
                //$uid=$otp_result->user_id;
                $otp_insert->user_id=$user_id;
                $otp_insert->otp= $otp;
                $otp_insert->created_at=$cur_date;
                $otp_insert->expired_at=$otp_ex_time;
                $otp_insert->verified=0;
                $otp_insert->save();
                DB::table('optcl_users_otp_history')
                    ->insert([
                    "user_id"    => $user_id,
                    "otp_type"   => "forgot_password",
                    "otp_value"  => $otp,
                    "created_at" => $cur_date,
                    ]);
                $response = ['success'=>'OTP sent successfully to the Mobile No', 'status' => 'true'];
            }
            DB::table('optcl_users_otp_history')
                ->insert([
                "user_id"    => $user_id,
                "otp_type"   => "forgot_password",
                "otp_value"  => $otp,
                "created_at" => $cur_date,
            ]);
            Session::flash('success', 'OTP sent to your registered mobile no');
            $response = ['success'=>'OTP sent to your registered mobile no', 'status' => 'true'];
        }else{
            Session::flash('error', 'Aadhaar No/Mobile No does not exists');
            $response = ['error'=>'Aadhaar No/Mobile No does not exists', 'status' => 'false'];
        }
        return response()->json($response);  
    }

    public function verify_otp(){
       return view('user.nominee.forgot_password.verify_otp');
    }

    // Resend OTP  for Service Pensioner
    public function ReSendOtp(){
        $user_id = Session::get('tem_user_id');
        //dd($user_id);
        //dd($user_id);
        $otp = Util::otp_value();
        $cur_date = date('Y-m-d H:i:s', time());
        $endTime =  date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $condLogin = array(
                    "user_id"           => $user_id,
                    "created_at"        => $endTime,
                    "created_at"        => $cur_date,
                    "otp_type"          => "nominee_register",
                    ); 
        
        $start_time = date("Y-m-d H:i:s", strtotime('-15 minutes'));
        $end_time = date("Y-m-d H:i:s", strtotime('+15 minutes'));       
        $totOTPSend = DB::table('optcl_users_otp_history')
                        ->where('user_id','=', $user_id)
                        ->where('created_at', '>=',$start_time)
                        ->where('created_at','<', $end_time)
                        ->count();
        //dd(DB::getQueryLog());
        //echo $totOTPSend;         
        if($totOTPSend <= 2){
            $result= Otp::where('user_id',$user_id)
                        ->update([
                            'otp'        => $otp ,
                            'otp_type'   => "nominee_register",
                            'created_at' => $cur_date ,
                            'expired_at' => $endTime ,
                            'verified'   => 0
                            ]);
            // insert in otp_history table
            DB::table('optcl_users_otp_history')
                ->insert([
                    "user_id"    => $user_id,
                    "otp_type"   => "nominee_register",
                    "otp_value"  => $otp,
                    "created_at" => $cur_date,
                    ]);
            //return response()->json(['success'=>'OTP resend successfully', 'status' => 'true']);
            Session::flash('success', 'OTP resend successfully');
            return redirect()->route('nominee_registration_verify_otp');
        } else {
            Session::flash('error', 'Your limit was exceeded. Please try after 30 minutes.');
            return redirect()->route('nominee_registration_verify_otp');
            //return response()->json(['error'=>'Your limit was exceeded. Please try after 30 minutes.', 'status' => 'false']);
        }

    }

    // Verify OTP form for Nominee

    public function VerifyOtp(Request $request){  
        $user_id = Session::get('tem_user_id');      
        //$user_id = $request->input('user_id');
        $otp = $request->input('otp');
        $password = Util::decrypt_password($request->password);
        $confirm_password = Util::decrypt_password($request->confirm_password);
        $result = Otp::where('user_id', $user_id)
                    ->where('otp', $otp)
                    ->where('verified', 0)
                    ->count();
        $checktime = Otp::select('created_at', 'expired_at')
                    ->where('user_id', $user_id)
                    ->where('otp', $otp)
                    ->first();
         
        $cur_time = date('Y-m-d H:i:s', time());        
        if($result > 0) {
            if (strtotime($cur_time) < strtotime($checktime->expired_at)) {
                Otp::where('user_id', $user_id)
                            ->where('otp', $otp)
                            ->update(['verified'=> 1]);
                AdminUser::where('id', $user_id)->update([
                                'password'=> bcrypt($password),
                                'updated_at'=> date('Y-m-d H:i:s')
                            ]);
                Session::flash('success', 'Password updated successfully');
                $response = ['success'=>'Password updated successfully', 'status' => 'true'];
            } else {
                Session::flash('error', 'OTP expired. Please try again.');
                $response = ['error'=>'OTP expired. Please try again.', 'status' => 'false'];
            }
        } else {
            Session::flash('error', 'Invalid OTP');
            $response = ['error'=>'Invalid OTP', 'status' => 'false'];
        }
        return response()->json($response);
    }

    public function OtpIndex($id){
        return view('user.nominee.otp', compact('id'));
    }
}
?>
