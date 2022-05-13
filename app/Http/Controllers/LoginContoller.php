<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUser;
use App\Models\Otp;
use App\Libraries\Util;
use Auth;
use Session;

class LoginContoller extends Controller
{  

    public function index() {
        //echo bcrypt('Secret@123');
		if(Auth::check()) {
            if(Auth::user()->is_admin == 1) {
                return redirect()->route('admin_dashboard');
            } else {
                return redirect()->route('user_dashboard');
            }
        }
        return view('user.login');
    }
 
    public function login_submit(Request $request){
        $validation = array();
        $user_name = $request->user_name;
        if($user_name == ""){
            $validation['error'][] = array("id" => "user_name-error","eValue" => "Please enter username");
        }
        $user_password = $request->user_password;
        if($user_password == ""){
            $validation['error'][] = array("id" => "user_password-error","eValue" => "Please enter password");
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
        //dd($request);
        //print_r($validation['error']);
        if(!isset($validation['error'])){
            $dryptPwd = Util::decrypt_password($user_password);
            // $credentials = array(
            //     "aadhaar_no"    => $user_name,
            //     // "mobile"     => $user_name,
            //     "password"      => $dryptPwd,//$dryptPwd,
            //     "status"        => 1,
            //     "deleted"       => 0,
            //     "is_verified"   => 1,
            // );
            
            // $credentials = array(
                
            //     "mobile"        => $user_name,
            //     "password"      => $dryptPwd,//$dryptPwd,
            //     "status"        => 1,
            //     "deleted"       => 0,
            //     "is_verified"   => 1,
            // );

            //dd($credentials);
            //if(Auth::attempt($credentials)){
                if( Auth::attempt(['aadhaar_no' => $user_name, 'password' => $dryptPwd,'user_type' => 1, 'status' => 1, 'deleted' => 0]) || Auth::attempt(['mobile' => $user_name, 'password' => $dryptPwd,'user_type' => 1, 'status' => 1, 'deleted' => 0]) || Auth::attempt(['employee_code' => $user_name, 'password' => $dryptPwd,'user_type' => 1, 'status' => 1, 'deleted' => 0])){
                // Activity Log
                // Check Pending booking for previous Date and Update
                
                //Session::flash('success', 'Valid credentials');
                return redirect()->route('user_dashboard');
            }else{
                $validation['loginCheckMessage'][] = "Something went wrong";
                Session::flash('error', 'Invalid username or password');
                return redirect()->route('login_form');
            }  
        }else{
            Session::flash('error', $validation['error'][0]['eValue']);
            return redirect()->route('login_form');
        }
        //echo json_encode($validation);
    }

    public function reloadCaptcha(){
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function logout() {

        if(Auth::check()) {
            $user = Auth::user();

            if($user->user_type == 1 || $user->user_type == 3) {
                Auth::logout();
                Session::flush();
                return redirect()->route('login_form');
            } else if($user->user_type == 2) {
                Auth::logout();
                Session::flush();
                return redirect()->route('nominee_login');
            }else {
                Auth::logout();
                Session::flush();
                return redirect('admin');
            }
        } else {
            Auth::logout();
            Session::flush();
            return redirect()->route('login_form');
        }
    }
    // forgot password index page
    public function ForgotPassword(){
        return view('user.forgot_password.forgot_password');
    }
    // Verify Mobile no and Aaadhar No form for Service Pensioner
    public function VerifyMobileAadhaar(Request $request){        
        $mobile_aadhaarno = $request->input('mobile_no');
        $result = AdminUser::select('id','mobile')
                           ->where('user_type', 1)
                           ->where('deleted', 0)
                           ->where(function($query) use ($mobile_aadhaarno){
                                $query->where('mobile',$mobile_aadhaarno);
                                $query->orWhere('aadhaar_no',$mobile_aadhaarno);
                           })
                           ->first();
        if(!empty($result)){
            $mobile_no = $result->mobile;
            $user_id = $result->id;
            $otp = Util::otp_value();
            //$expired = DB::table('optcl_user_otp_verifications')->get(expired_at('CURRENT_TIMESTAMP', INTERVAL 30 MINUTE));
            $cur_date = date('Y-m-d H:i:s', time());
            $otp_ex_time = date('Y-m-d H:i:s', strtotime("+30 minutes",strtotime($cur_date)));
            $otp_check = Otp::select('otp')
                            ->where('user_id',$user_id)
                            ->where('verified', 0)
                            ->where('delete', 0)
                            ->count(); 
            // Temporary user ID
            Session::put('temp_user_id', $user_id);     
            if($otp_check > 0){                    
                $result= Otp::where('user_id', $user_id)
                            ->update([
                                'otp'        => $otp ,
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
                return response()->json(['success'=>'OTP sent successfully to the  Mobile No', 'status' => 'true', 'user_id' => $user_id]);                   
            } else { 
                    $otp_insert= new Otp;
                    $otp_insert->user_id     = $user_id;
                    $otp_insert->otp         = $otp;
                    $otp_insert->otp_type    = "forgot_password";
                    $otp_insert->created_at  = $cur_date;
                    $otp_insert->expired_at  = $otp_ex_time;
                    $otp_insert->verified    = 0;
                    $otp_insert->save();
                    DB::table('optcl_users_otp_history')
                        ->insert([
                        "user_id"    => $user_id,
                        "otp_type"   => "forgot_password",
                        "otp_value"  => $otp,
                        "created_at" => $cur_date,
                        ]);
                    return response()->json(['success'=>'OTP sent successfully to the Mobile No', 'status' => 'true']);
            }
            
            DB::table('optcl_users_otp_history')
                ->insert([
                "user_id"    => $user_id,
                "otp_type"   => "forgot_password",
                "otp_value"  => $otp,
                "created_at" => $cur_date,
            ]);
            // dd($otp_history_insert);
            return response()->json(['success'=>'OTP sent successfully to the Mobile No', 'status' => 'true']); 
        }else{
           return response()->json(['error'=>'Aadhaar/Mobile No. does not exists', 'status' => 'false']);  
        }
    }
    public function VerifyOtpPage(){
        if(Session::has('temp_user_id')){
            return view('user.forgot_password.verify_otp');
        }else{
            return redirect()->route('login_form');
        }
    }
    // Resend OTP  for Service Pensioner
    public function ReSendOtp(Request $request){
        $user_id = $request->user_id;
        
        //dd($user_id);
        $otp = Util::otp_value();
        $cur_date = date('Y-m-d H:i:s', time());
        $endTime =  date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $condLogin = array(
                    "user_id"           => $user_id,
                    "created_at"        => $endTime,
                    "created_at"        => $cur_date,
                    "otp_type"          => "forgot_password",
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
                            'otp_type'   => "forgot_password",
                            'created_at' => $cur_date ,
                            'expired_at' => $endTime ,
                            'verified'   => 0
                            ]);
            // insert in otp_history table
            DB::table('optcl_users_otp_history')
                ->insert([
                    "user_id"    => $user_id,
                    "otp_type"   => "forgot_password",
                    "otp_value"  => $otp,
                    "created_at" => $cur_date,
                    ]);
            return response()->json(['success'=>'OTP resend successfully', 'status' => 'true']);
        } else {
            return response()->json(['error'=>'Your limit was exceeded. Please try after 30 minutes.', 'status' => 'false']);
        }

    }


    // Verify OTP form for Service Pensioner

    public function VerifyOtp(Request $request){
        $user_id = $request->input('user_id');
        $otp = $request->input('otp');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');    
        $dPassword = Util::decrypt_password($password);
        $dConfirmPassword = Util::decrypt_password($confirm_password);    
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
            if($dPassword == $dConfirmPassword){
                if (strtotime($cur_time) < strtotime($checktime->expired_at)) {
                    Otp::where('user_id', $user_id)
                                ->where('otp', $otp)
                                ->update(['verified'=> 1]);
                    AdminUser::where('id', $user_id)->update([
                                    'password'=> bcrypt($dPassword),
                                    'updated_at'=> date('Y-m-d H:i:s')
                                ]);    
                    Session::forget('temp_user_id');
                    return response()->json(['success'=>'OTP verified successfully', 'status' => 'true']);
                } else {
                    return response()->json(['error'=>'OTP Expired, Please generate new OTP', 'status' => 'false']);
                }
            }else{
                return response()->json(['error'=>'Password and confirm password not matched', 'status' => 'false']);
            }
        } else {
            return response()->json(['error'=>'Invalid OTP', 'status' => 'false']);
        }
        
    }
    
}

?>