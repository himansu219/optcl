<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;

class forgot_password extends Controller
{   

    public function index(){

        return view('UserView.forgot_password');
    }
    public function verify_mobile_no(Request $request){

        $request->validate([
            'mobile_no'=>'required|min:10|max:10',           
        ]);

        $mobile_no= $request->input('mobile_no');
        $result = DB::table('optcl_pensioner_users')->select('user_id')->where('mobile_no',$mobile_no)->get();
        
        $otp_result['user_id']=$result[0]->user_id;
        
        $otp = $this->create_otp(6);
        
        //$expired = DB::table('optcl_user_otp_verifications')->get(expired_at('CURRENT_TIMESTAMP', INTERVAL 15 MINUTE));
        $cur_date = date('Y-m-d H:i:s', time());
         $otp_ex_time = date('Y-m-d H:i:s', strtotime("+15 minutes",strtotime($cur_date)));

       

        $otp_check = DB::table('optcl_user_otp_verifications')->select('otp')->where('user_id',$otp_result)->count();
        // echo "<pre>";
        // print_r($otp_check);

        if($otp_check > 0)
        {
            $result= DB::table('optcl_user_otp_verifications')->where('user_id',$otp_result)->update(['otp'=> $otp , 'created_at' => $cur_date ,'expired_at' => $otp_ex_time ,'verified' =>0]);
            //echo "updated";
            return view('UserView.otp/' . $otp_result['user_id']);
        }
            else
            {
                 $otp_insert= new otp;
                 //$uid=$otp_result->user_id;
                 $otp_insert->user_id=$otp_result['user_id']=$result[0]->user_id;
                 $otp_insert->otp= $otp;
                 $otp_insert->created_at=$cur_date;
                 $otp_insert->expired_at=$otp_ex_time;
                 $otp_insert->verified=0;
                 $otp_insert->save();

                // echo "inserted";
                 return view('UserView.otp/' . $otp_result['user_id']);
            }
        //return redirect('otp/' . $result->id);

        // echo "<pre>";
        // print_r($otp);
       // return redirect('otp/' . $result);


    }



       public function create_otp($n) {
        // all numeric digits 
        $generator = "1357902468";
        // Iterate for n-times and pick a single character 
        // from generator and append it to $result
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        // Return result
        return $result;
    }
}
