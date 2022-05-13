<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Libraries\Util;
use Auth;
use Session;

class NomineeLoginController extends Controller 
{
    // public function index() {
    //     //echo bcrypt('Secret@123');
	// 	if(Auth::check()) {
    //         if(Auth::user()->is_admin == 1) {
    //             return redirect()->route('admin_dashboard');
    //         } else {
    //             return redirect()->route('user_dashboard');
    //         }
    //     }
    //     return view('user.login');
    // }
 
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
             if( Auth::attempt(['aadhaar_no' => $user_name, 'password' => $dryptPwd,'user_type' => 2, 'status' => 1, 'deleted' => 0]) || Auth::attempt(['mobile' => $user_name, 'password' => $dryptPwd,'user_type' => 2, 'status' => 1, 'deleted' => 0])){
               return redirect()->route('user_dashboard');
            }else{
                $validation['loginCheckMessage'][] = "Something went wrong";
                Session::flash('error', 'Invalid username or password');
                return redirect()->route('nominee_login');
            }
            
        }else{
            Session::flash('error', $validation['error'][0]['eValue']);
            return redirect()->route('nominee_login');
        }
        //echo json_encode($validation);
    }

    public function reloadCaptcha(){
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function logout() {

        if(Auth::check()) {
            $user = Auth::user();

            if($user->user_type == 2) {
                Auth::logout();
                Session::flush();
                return redirect()->route('nominee_login');
            } else {
                Auth::logout();
                Session::flush();
                return redirect('admin');
            }
        } else {
            Auth::logout();
            Session::flush();
            return redirect()->route('nominee_login');
        }
    }

}
