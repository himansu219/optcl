<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;

class Otp_controller extends Controller
{

    public function index($id){
        //dd($id);
        return view('UserView.otp', compact('id'));
    }

    public function verify_otp(Request $request){
        //dd($id);
         //$res->user_id= $request->user_id;
        $user_id=$request->input('user_id');
        $otp=$request->input('otp');
        $password=$request->input('password');
        $confirm_password=$request->input('confirm_password');
     
        // dd($res);
        
        // $res->password= $request->password;
        // $res->confirm_password= $request->confirm_password;


        $result= DB::table('optcl_user_otp_verifications')->where('user_id',"$user_id")
        ->where('otp',"$otp")
        ->count();
       //$checktime=DB::table('optcl_user_otp_verifications')->select('created_at','expired_at')->where('user_id',"$user_id")->where('otp',"$otp")->get();
        // $created_at=DB::table('optcl_user_otp_verifications')->select('created_at')->where('user_id',"$user_id")->where('otp',"$otp")->get();
        // $expired_at=DB::table('optcl_user_otp_verifications')->select('expired_at')->where('user_id',"$user_id")->where('otp',"$otp")->get();

        // echo "<pre>";
        // print_r($expired_at);
        //dd($checktime);
        // if($created_at < $expired_at)
        // {

        if($result > 0)
        {
        $result= DB::table('optcl_user_otp_verifications')->where('user_id',"$user_id")
        ->where('otp',"$otp")
        ->update(['verified'=> 1]);
        $result= DB::table('optcl_pensioner_users')->where('user_id',"$user_id")->update(['password'=> $password]);
         return view('UserView.login'); 
            }
            else
             {
                $request->session()->flash('msg','Otp Not Verified');
                return view('UserView.login'); 
            //echo 'not verified';
             }
      

        // }else
        //      {
        //     $request->session()->flash('msg','Link Expired');
        //      return redirect('UserView.otp');
        //      }
        
      
       // echo '<pre>';
       // print_r($result);
        //return redirect('UserView.login');
    }
}




// $cur_date = strtotime(date('Y-m-d H:i:s', time()));
//             $ex_time = strtotime($user->otp_ex_time);
                        
//             if ($cur_date < $ex_time) {
                
                
                
//             } else {
                
//                 Session::flash('error', 'Link expired');
//                 return redirect('UserView.forget-password');
                
//             }