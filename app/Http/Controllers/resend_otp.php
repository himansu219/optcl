<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;

class resend_otp extends Controller
{
    public function resend($id){

        
        $otp = $this->create_otp(6);
        $cur_date = date('Y-m-d H:i:s', time());
        $otp_ex_time = date('Y-m-d H:i:s', strtotime("+15 minutes",strtotime($cur_date)));
        $otp_check = DB::table('optcl_user_otp_verifications')->select('otp')->where('user_id',$id)->count();
        if($otp_check > 0)
        {
            $result= DB::table('optcl_user_otp_verifications')->where('user_id',$id)->update(['otp'=> $otp , 'created_at' => $cur_date ,'expired_at' => $otp_ex_time ,'verified' =>0]);
            //echo "updated";
            return view('UserView.otp/' . $id);
        }

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
