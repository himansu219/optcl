<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUser;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Libraries\Util;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{

    public function __construct(){
        $this->middleware('AdminLoginAuth', ['only' => 'index', 'admin_login_submit']);
    }

    public function index() {
        if(Auth::check()) {
            if(Auth::user()->is_admin == 1) {
                return redirect()->route('admin_dashboard');
            } else {
                return redirect()->route('user_dashboard');
            }
        }
        return view('admin.login');
    }

    public function admin_login_submit(Request $request) {
        try {
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
                $dryptPwd = Util::decrypt_password($request->password);
                $credentials = array(
                    "mobile" => $request->user_id,
                    "password" => $dryptPwd,
                    "status" => 1,
                    "deleted" => 0,
                    "is_verified" => 1,
                );

                if(Auth::attempt($credentials)) {
                    $user = Auth::user();

                    if($user->is_admin == 1 && $user->user_type == 4)  {
                        $request->session()->put('id',$request->user_id);
                        return redirect()->route('admin_dashboard');
                    } elseif ($user->is_admin == 0 && $user->user_type == 4) {
                        return redirect()->route('user_dashboard');
                    } else {
                        Auth::logout();
                        Session::flush();
                        return redirect('admin');
                    }
                }
                Session::flash('error', 'Please enter correct login details');
                return redirect()->back();
            } else {
                Session::flash('error', $validation['error'][0]['eValue']);
                return redirect('admin');
            }
        } catch(Exception $e) {
            return redirect('admin');
        }
    }

    public function dashboardindex() {
        return view('admin.dashboard');
    }

    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect('admin');
    }

    // forgot password index page
    public function ForgotPassword(){
        return view('admin.forgot_password');
    }

    // Verify Mobile no and Aaadhar No form for Service Pensioner
    public function VerifyMobileAadhaar(Request $request){        
        $mobile_aadhaarno = $request->input('mobile_no');
        DB::enableQueryLog();
        $result = AdminUser::select('id','mobile')
                            ->where('deleted', 0)
                            ->where('user_type', 4)
                            ->where(function($query) use ($mobile_aadhaarno){
                                $query->where('mobile',$mobile_aadhaarno);
                                $query->orWhere('aadhaar_no',$mobile_aadhaarno);
                            })
                            ->first();
        //dd(DB::getQueryLog(), $result);
        if(!empty($result)){
            $mobile_no = $result->mobile;
            $user_id=$result->id;
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

    public function setForgotPassword(){
        if(Session::has('temp_user_id')){
            return view('admin.verify_forgot_password');
        }else{
            return redirect()->route('admin_login_form');
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
                    ->where('delete', 0)
                    ->count();
        $checktime = Otp::select('created_at', 'expired_at')
                    ->where('user_id', $user_id)
                    ->where('otp', $otp)
                    ->where('verified', 0)
                    ->where('delete', 0)
                    ->first();            
        $cur_time = date('Y-m-d H:i:s', time());
        if($result > 0) {
            if (strtotime($cur_time) < strtotime($checktime->expired_at)) {
                
                if($dPassword == $dConfirmPassword){
                    Otp::where('user_id', $user_id)
                            ->where('otp', $otp)
                            ->update(['verified'=> 1]);
                    AdminUser::where('id', $user_id)->update([
                        'password'    => Hash::make($dPassword),
                        'is_verified' => 1,
                        'updated_at'  => date('Y-m-d H:i:s')
                    ]);
                    Session::forget('temp_user_id');
                    return response()->json(['success'=>'OTP verified successfully', 'status' => 'true']);
                }else{
                    return response()->json(['error'=>'Password and confirm password not matched', 'status' => 'false']);
                }                
            } else {
                return response()->json(['error'=>'OTP Expired, Please generate new OTP', 'status' => 'false']);
            }
        } else {
            return response()->json(['error'=>'Invalid OTP', 'status' => 'false']);
        }
    }
    // URl link verify set password index page
    public function SetPassword(Request $request,$id){
        $cur_date = date('Y-m-d H:i:s', time());
        $result = DB::table('optcl_users')
                    ->where('status',1)
                    ->where('deleted',0)
                    ->where('verification_code',$id)
                    ->first();
        $expired_time = $result->expired_link_at;
        $created_at = $result->created_at;
        //dd($created_at);
        if($cur_date < $expired_time){
            return view('admin.set_password',compact('id'));
        } else {
            Session::flash('error', 'Link expired');
            return redirect()->route('admin_login_form');
        }
        
    }

    public function SetPasswordVerify(Request $request){
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
        $verification_code = $request->input('verification_code');
        $new_password      = $request->input('new_password');
        $confirm_password  = $request->input('confirm_password');
        $cur_date          = date('Y-m-d H:i:s', time());
        $data = AdminUser::where('verification_code', $verification_code)
                            ->where('status',1)
                            ->where('deleted',0)
                            ->first();
        $user_id = $data->id;
        //dd($user_id);
        $result = AdminUser::where('verification_code', $verification_code)
                                ->update([
                                    'password'   => bcrypt($new_password) ,
                                    'updated_at' => $cur_date ,
                                    'updated_by' => $user_id ,
                                    'is_verified'   => 1
                                    ]);
        if($result == true){
            Session::flash('success', 'Password set successfully');
            return redirect()->route('admin_login_form');
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

}
