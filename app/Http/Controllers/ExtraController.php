<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ExtraController extends Controller
{
    public function validate_aadhar_number(Request $request){
        if(!empty($request->nominee_aadhaar_no)){
            $aadhaar_no = $request->nominee_aadhaar_no;
        }else{
            $aadhaar_no = $request->aadhaar_no;
        }        
        //DB::enableQueryLog();
        $count0 = DB::table('optcl_users')
                    ->where('aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            $count0 = $count0->where('employee_code', '!=', Auth::user()->employee_code);
        } 
        $count0 = $count0->count();
        //DB::enableQueryLog();
        $count1 = DB::table('optcl_employee_master')
                    ->where('aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $count1 = $count1->where('employee_code', '!=', Auth::user()->employee_code);
        }        
        if(!empty($request->pension_form_id)){
            $count1 = $count1->where('id', '!=', $request->pension_form_id);
        }            
        $count1 = $count1->count();
        //dd(DB::getQueryLog());
        // DB::enableQueryLog();
        // $coun11 = DB::table('optcl_employee_personal_details')
        //             ->where('addhaar_no', $aadhaar_no)
        //             ->where('deleted', 0);
        // if(Auth::check() && Auth::user()->employee_code != NULL){
        //     $coun11 = $coun11->where('employee_code', '!=', Auth::user()->employee_code);
        // }                    
        // $coun11 = $coun11->count();
        //dd(DB::getQueryLog());
        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('nominee_aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            $count2 = $count2->where('employee_code', '!=', Auth::user()->employee_code);
        }     
        $count2 = $count2->count();
        $count3 = DB::table('optcl_nominee_master')
                    ->where('aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            $count3 = $count3->where('employee_code', '!=', Auth::user()->employee_code);
        }     
        $count3 = $count3->count();
        $count4 = DB::table('optcl_nominee_nominee_details')
                    ->where('nominee_aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            //$count4 = $count4->where('employee_code', '!=', Auth::user()->employee_code);
        }     
        $count4 = $count4->count();
        //dd($count0, $coun11, $count1, $count2, $count3, $count4);
        if($count0 > 0 || $count1 > 0 || $count2 > 0 || $count3 > 0 || $count4 > 0){
            return 'false';    
        }else {
            return 'true';
        }  
    }

    public function validate_account_number(Request $request){
        $pf_acno = $request->pf_acno;
        $count1 = DB::table('optcl_employee_master')
                    ->where('pf_account_no', $pf_acno);
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $count1 = $count1->where('employee_code', '!=', Auth::user()->employee_code);
        }        
        if(!empty($request->pension_form_id)){
            $count1 = $count1->where('id', '!=', $request->pension_form_id);
        }   
        $count1 = $count1->where('deleted', 0)
                    ->count();
        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('savings_bank_account_no', $pf_acno)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        //dd($count1, $count2);
        if($count1 > 0 || $count2 > 0){
            return 'false';    
        }else {
            return 'true';
        }        
    }

    public function validate_doj(Request $request){
        //dd($request);
        //$doj = $request->doj;
        //$dob = $request->dob;
		
		$doj = str_replace("/","-",$request->doj);
        $dob = str_replace("/","-",$request->dob);
		
        if(date('Y-m-d',strtotime($doj)) < date('Y-m-d',strtotime($dob))){
            return 'false';
        }else{
            if($doj == "" || $dob == ""){
                return 'false';  
            }else{
                return 'true';   
            }
        }   
    }
    public function validate_dor(Request $request){
        //dd($request);
        //$doj = $request->doj;
        //$dor = $request->dor;
			
		$doj = str_replace("/","-",$request->doj);
        $dor = str_replace("/","-",$request->dor);
		
        if(date('Y-m-d',strtotime($doj)) > date('Y-m-d',strtotime($dor))){
            return 'false';
        }else{
            if($doj == "" || $dor == ""){
                return 'false';  
            }else{
                return 'true';   
            }
        }   
    }

    public function validate_pan(Request $request){
        //dd($request);
        $pan_no = $request->pan_no;
        DB::enableQueryLog();
        $pan_count = DB::table('optcl_employee_personal_details')
                ->where('pan_no', $pan_no);
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $pan_count = $pan_count->where('employee_code', '!=', Auth::user()->employee_code);
        }
        if(!empty($request->persional_detail_id)){
            $pan_count = $pan_count->where('id', '!=', $request->persional_detail_id);
        }   
        $pan_count = $pan_count->where('deleted', 0)
                ->count();
        //dd(DB::getQueryLog());
        if($pan_count > 0){
            return 'false';  
        }else{
            return 'true';   
        }
    }

    public function validate_account(Request $request){
        $saving_bank_ac_no = $request->saving_bank_ac_no;
        //dd($saving_bank_ac_no );
        $count_value = DB::table('optcl_employee_personal_details')
                ->where('savings_bank_account_no', $saving_bank_ac_no);;
                //->where('employee_code', '!=', Auth::user()->employee_code)
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $count_value = $count_value->where('employee_code', '!=', Auth::user()->employee_code);
        }
        if(!empty($request->persional_detail_id)){
            $count_value = $count_value->where('id', '!=', $request->persional_detail_id);
        }
        $count_value = $count_value->where('deleted', 0)
                ->count();
        $count_value2 = DB::table('optcl_nominee_nominee_details')
                ->where('savings_bank_account_no', $saving_bank_ac_no)
                ->where('deleted', 0)
                ->count();
        //dd($count_value, $count_value2);
        if($count_value > 0 || $count_value2 > 0){
            return 'false';  
        }else{
            return 'true';   
        }
    }

    public function validate_email(Request $request){
        $email = $request->email;
        $count_value = DB::table('optcl_employee_personal_details')
                ->where('email_address', $email)
                ->where('email_address', '!=', '')
                ->where('employee_code', '!=', Auth::user()->employee_code)
                ->where('deleted', 0)
                ->count();
        if($count_value > 0){
            return 'false';  
        }else{
            return 'true';   
        }
    }

    public function validate_mobile_number(Request $request){
        $mobile_no = $request->mobile_no;
        
        $count0 = DB::table('optcl_users')
                    ->where('mobile', $mobile_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            $count0 = $count0->where('id', '!=', Auth::user()->id);
        } 
        $count0 = $count0->count();

        //DB::enableQueryLog();
        $count1 = DB::table('optcl_employee_personal_details')
                    ->where('mobile_no', $mobile_no)
                    ->where('deleted', 0);
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $count1 = $count1->where('employee_code', '!=', Auth::user()->employee_code);
        }
        if(!empty($request->persional_detail_id)){
            $count1 = $count1->where('id', '!=', $request->persional_detail_id);
        }   
        $count1 = $count1->count();
        //dd(DB::getQueryLog());

        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('mobile_no', $mobile_no)
                    ->where('deleted', 0);
        if(Auth::check() && Auth::user()->employee_code != NULL){
            $count2 = $count2->where('employee_code', '!=', Auth::user()->employee_code);
        }
        $count2 = $count2->count();
        
        $count3 = DB::table('optcl_nominee_nominee_details')
                    ->where('mobile_no', $mobile_no)
                    ->where('deleted', 0);
        if(Auth::check()){
            //$count3 = $count3->where('employee_code', '!=', Auth::user()->employee_code);
        }
        $count3 = $count3->count();

        //dd($count1, $count2, $count3, $count0);
        if($count0 > 0 || $count1 > 0 || $count2 > 0 || $count3 > 0){
            return 'false';    
        }else {
            return 'true';
        }        
    }
	
	public function validate_pensinor_nominee_mobile_number(Request $request){
        $mobile_no = $request->mobile_no;
        $count1 = DB::table('optcl_employee_personal_details')
                    ->where('mobile_no', $mobile_no)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();

        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('mobile_no', $mobile_no)
                    ->where('deleted', 0);

        if(!empty($request->nominee_id)) {
            $count2->where('id', '!=', $request->nominee_id);
        }
        
        $count2 = $count2->count();
		
        if($count1 > 0 || $count2 > 0){
            return 'false';
        }else {
            return 'true';
        }        
    }

    public function validate_pensinor_nominee_aadhar_number(Request $request){
        $aadhaar_no = $request->aadhaar_no;
        $count1 = DB::table('optcl_employee_master')
                    ->where('aadhaar_no', $aadhaar_no)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
					
        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('nominee_aadhaar_no', $aadhaar_no)
                    ->where('deleted', 0);
                    
        if(!empty($request->nominee_id)) {
            $count2->where('id', '!=', $request->nominee_id);
        }
        
        $count2 = $count2->count();

        if($count1 > 0 || $count2 > 0){
            return 'false';
        }else {
            return 'true';
        }        
    }

    // Dealing Assistant Add Applicant form  start
    public function validate_da_employee_code(Request $request){
        $employee_code = $request->employee_code;
        $count1 = DB::table('optcl_employee_nominee_details')
                    ->where('employee_code',$request->employee_code)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count2 = DB::table('optcl_users')
                    ->where('employee_code',$request->employee_code);
        if(!empty($request->applicant_id)){
            $count2 = $count2->where('id', '!=',$request->applicant_id);
        }

        $count2 = $count2->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count3 = DB::table('optcl_nominee_master')
                    ->where('employee_code',$request->employee_code)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        //dd($count1, $count2, $count3);
        if($count1 > 0 || $count2 > 0 || $count3 > 0){
            return 'false';    
        }else {
            return 'true';
        }        
    }

    public function validate_da_aadhaar_no(Request $request){
        $aadhaar_no = $request->aadhaar_no;
        $count1 = DB::table('optcl_employee_nominee_details')
                    ->where('employee_aadhaar_no',$request->aadhaar_no)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count2 = DB::table('optcl_employee_nominee_details')
                    ->where('nominee_aadhaar_no',$request->aadhaar_no)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count3 = DB::table('optcl_users')
                    ->where('aadhaar_no',$request->aadhaar_no);
        if(!empty($request->applicant_id)){
            $count3 = $count3->where('id', '!=',$request->applicant_id);
        }
        $count3 = $count3->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count4 = DB::table('optcl_nominee_master')
                    ->where('aadhaar_no',$request->aadhaar_no)
                    ->where('employee_code', '!=', Auth::user()->employee_code)
                    ->where('deleted', 0)
                    ->count();
        $count5 = DB::table('optcl_nominee_nominee_details')
                    ->where('nominee_aadhaar_no',$request->aadhaar_no)
                    ->where('deleted', 0)
                    ->count();
        //dd($count1, $count2, $count3, $count4, $count5);
        if($count1 > 0 || $count2 > 0 || $count3 > 0 || $count4 >0 || $count5>0){
            return 'false';    
        }else {
            return 'true';
        }        
    }

    public function validate_da_mobile_number(Request $request){
        $mobile_no = $request->mobile_no;
        $count1 = DB::table('optcl_employee_nominee_details')
                    ->where('mobile_no',$request->mobile_no)
                    ->where('deleted', 0)
                    ->count();
        $count2 = DB::table('optcl_users')
                    ->where('mobile',$request->mobile_no);
        if(!empty($request->applicant_id)){
            $count2 = $count2->where('id', '!=',$request->applicant_id);
        }
        $count2 = $count2->where('deleted', 0)
                    ->count();
        $count3 = DB::table('optcl_nominee_nominee_details')
                    ->where('mobile_no',$request->mobile_no)
                    ->where('deleted', 0)
                    ->count();
        if($count1 > 0 || $count2 > 0 || $count3 > 0){
            return 'false';    
        }else {
            return 'true';
        }        
    }

    public function convert_days_to_year_month_days(Request $request) {
        $days = $request->days;

        // $start_date = new \DateTime();
        /*$start_date = new \DateTime($request->start_date);
        // dd($start_date);
        $end_date = (new $start_date)->add(new \DateInterval("P{$days}D") );*/
        // dd($end_date);

        if(!empty($request->start_date)) {
            $start_date = \Carbon\Carbon::parse($request->start_date);
            $end_date = \Carbon\Carbon::parse($request->start_date)->addDays($days);
        } else {
            /*$start_date = new \DateTime();
            $end_date = (new $start_date)->add(new \DateInterval("P{$days}D") );*/

            $start_date = \Carbon\Carbon::parse('1970-01-01');
            $end_date = \Carbon\Carbon::parse('1970-01-01')->addDays($days);
        }

        $dd = date_diff($start_date,$end_date);

        $response = ['years' => $dd->y, 
            'months' => $dd->m, 
            'days' => $dd->d
        ];

        return response()->json($response);
    }
    
}
