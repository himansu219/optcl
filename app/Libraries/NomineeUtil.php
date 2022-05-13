<?php  

namespace App\Libraries;

use Illuminate\Support\Str;
use App\Libraries\Encryption;
use App\Models\GeneralSettingsModel;
use DB;
use Auth;
use Session;


class NomineeUtil {    	

    public static function check_field_return_status ($fieldID, $nomineeID = NULL){
        //return true;
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first('id');
        $id = $application_data->id;

        $field_value = DB::table('optcl_nominee_application_form_field_status')
                        ->where('application_id', $id)
                        ->where('field_id', $fieldID);
        if($nomineeID != NULL ){
            $field_value = $field_value->where('nominee_id', $nomineeID);
        }
        $field_value = $field_value->where('status_id', 2)
                        ->count('id');
        //dd($field_value);
        if($field_value > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function check_field_return_remark_show ($fieldID, $nomineeID = NULL){
        //return ["return_status" => false, "return_remark" => 'testing'];
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first('id');
        $id = $application_data->id;

        $field_value = DB::table('optcl_nominee_application_form_field_status')
                        ->where('application_id', $id)
                        ->where('field_id', $fieldID);
        if($nomineeID != NULL ){
            $field_value = $field_value->where('nominee_id', $nomineeID);
        }
        $field_value = $field_value->where('status_id', 2)
                        ->first();
        //dd($field_value);
        $return_remark = [];
        if($field_value){
            $return_remark = ["return_status" => true, "return_remark" => $field_value->remarks];
            return $return_remark;
        }else{
            return $return_remark;
        }
    }
	
	public static function checkApproveRejectStatus($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereNotIn('application_status_id', [1,14])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if(!empty($approve_reject_status)){
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                    $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
                }
            }
            
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            //dd($response);
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            if($field_id ==86){
                  //dd($approve_reject_status, $application_id, $form_id, $field_id);
            }  
            
            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
        }
        //print_r($response);
        return $response;
    }

    public static function checkApproveRejectStatusFE($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [13,17])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if(!empty($approve_reject_status)){
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                    $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
                }
            }
            
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            //dd($response);
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if($field_id ==86){
                  //dd($approve_reject_status, $application_id, $form_id, $field_id);
            }  
            
            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
        }
        //print_r($response);
        return $response;
    }

    public static function checkApproveRejectStatusFE_copy($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [13])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if(!empty($approve_reject_status)){
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                    $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
                }
            }
            
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            //dd($response);
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if($field_id ==86){
                  //dd($approve_reject_status, $application_id, $form_id, $field_id);
            }  
            
            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        }
        //print_r($response);
        return $response;
    }
	
    public static function checkApproveRejectStatusUH($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereNotIn('application_status_id', [19])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if(!empty($approve_reject_status)){
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                    $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
                }
            }            
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            //dd($response);
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);
                            //->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        }
        //print_r($response);
        return $response;
    }
	
	public static function checkApproveRejectStatusHRDealing($application_id, $form_id, $field_id, $nominee_id=null) {
        // return false;        
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    // ->whereIn('application_status_id', [2,3,7,8,10,11,12,13,18,19,21,22,23]) 
                                    ->whereNotIn('application_status_id', [20]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        if(!empty($pension_application_form)) {
            //dd(1);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            $status_id = "";
            if($approve_reject_status){
                $status_id = $approve_reject_status->status_id;
                if(!empty($approve_reject_status->status_id) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [28])){
                    return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
                }
            }

            return ['form_submit' => true, 'status_id' => $status_id, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        } else {
            //dd(2);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();

            return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL];
        }
    }

    public static function checkApproveRejectStatusHRExecutive($application_id, $form_id, $field_id, $nominee_id=null) {
        // return false;        
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    // ->whereIn('application_status_id', [2,3,7,8,10,11,12,13,18,19,21,22,23]) 
                                    ->whereNotIn('application_status_id', [24]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        if(!empty($pension_application_form)) {
            //dd(1);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            $status_id = "";
            if($approve_reject_status){
                $status_id = $approve_reject_status->status_id;
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [31])){
                    return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
                }
            }

            return ['form_submit' => true, 'status_id' => $status_id, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        } else {
            //dd(2);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);

            return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL];
        }
    }

    public static function checkApproveRejectStatusHRSanctionAuthority($application_id, $form_id, $field_id, $nominee_id=null) {
        $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            ->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

        if(!empty($nominee_id)) {
            $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
        }

        $approve_reject_status = $approve_reject_status->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        //dd($approve_reject_status);
        return ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => 1];
        // return false;        
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    // ->whereIn('application_status_id', [2,3,7,8,10,11,12,13,18,19,21,22,23]) 
                                    ->whereNotIn('application_status_id', [14]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        if(!empty($pension_application_form)) {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            ->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            $status_id = "";
            if($approve_reject_status){
                $status_id = $approve_reject_status->status_id;
            }
            return ['form_submit' => true, 'status_id' => $status_id];
        } else {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            ->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();

            return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL];
        }
    }
	
	public static function getAmountInWords(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees : '') . $paise;
    }

    public static function checkApproveRejectStatusForPensionSection($application_id, $form_id, $field_id, $nominee_id=null) {
        // return false;        
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    // ->whereIn('application_status_id', [2,3,7,8,10,11,12,13,18,19,21,22,23]) 
                                    // ->whereNotIn('application_status_id', [24]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        if(!empty($pension_application_form)) {
            //dd(1);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();

            return ['form_submit' => true, 
                'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 
                'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : 0
            ];
        } else {
            //dd(2);
            $approve_reject_status = DB::table('optcl_nominee_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);

            return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL];
        }
    }

    public static function insert_notification($user_id, $application_id, $message) {
        $current_date = date('Y-m-d H:i:s');
        $notificationData = array(
           "user_id"           => $user_id,
           "application_id"    => $application_id,
           'application_type'  => 'family', 
           "status_message"    => $message,
           "created_at"        => $current_date,
        );
        DB::table('optcl_user_notification')->insert($notificationData);
    }

}