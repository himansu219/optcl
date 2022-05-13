<?php  

namespace App\Libraries;

use Illuminate\Support\Str;
use App\Libraries\Encryption;
use App\Models\GeneralSettingsModel;
use DB;
use Auth;
use Session;


class Util {
    
    /**
     * Function for uploading attachment
     * take $file,$destination_file,$prefix,$dir as argument. 
     * 
     * @return upload file path.
     */
    public static function generate_ppo_number ($ppo_data){
        $ppo_no = rand(0, 9999);
        $current_month = date('m');
        $current_year = date('Y');
        
        $lastInsertedID = DB::table('optcl_ppo_no_list')->insertGetId($ppo_data);
        $generated_ppo_number = $current_year . '/' . $current_month. '/' .sprintf('%04d',$lastInsertedID);
        DB::table('optcl_ppo_no_list')->where('id', $lastInsertedID)->update(['ppo_no' => $generated_ppo_number]);
        return $generated_ppo_number;
    }
    
    public static function get_nominee_document_details($nominee_master_id, $document_id) {
        return DB::table('optcl_nominee_pension_application_document')
                    ->where('nominee_master_id', $nominee_master_id)
                    ->where('document_id', $document_id)
                    ->where('deleted', 0)->first();
    }

    public static function insert_notification($user_id, $application_id, $message) {
        $current_date = date('Y-m-d H:i:s');
        $notificationData = array(
           "user_id"           => $user_id,
           "application_id"    => $application_id,
           "status_message"    => $message,
           "created_at"        => $current_date,
        );
        DB::table('optcl_user_notification')->insert($notificationData);
    }

    public static function check_submission(){
        $applicationDetails = DB::table('optcl_pension_application_form')
                                    ->where('user_id', Auth::user()->id)
                                    //->whereIn('application_status_id', [1,2,6,10,12,13,14])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        if($applicationDetails){
            Session::flash('error','You have already submitted the apllication. Please wait for application approval/ return. ');
            return true;
        }else{
            return false;
        }
    }

    public static function check_nominee_submission(){
        //return false;
        $applicationDetails = DB::table('optcl_pension_application_form')
                                    ->where('user_id', Auth::user()->id)
                                    //->whereIn('application_status_id', [1,2,6,10,12,13,14])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        if($applicationDetails){
            Session::flash('error','You have already submitted the apllication. Please wait for application approval/ return. ');
            return true;
        }else{
            return false;
        }
    }

    public static function check_field_return_status ($fieldID, $nomineeID = NULL){
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first('id');
        $id = $application_data->id;

        $field_value = DB::table('optcl_application_form_field_status')
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
        $application_data = DB::table('optcl_pension_application_form')
            ->where('user_id', Auth::user()->id)
            ->first('id');
        $id = $application_data->id;

        $field_value = DB::table('optcl_application_form_field_status')
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
	
	public static function get_years_months_days($from, $to) {
        $date1 = date_create($from);
        $date2 = date_create($to);

        $diff = date_diff($date1,$date2);
        $dmonths = $diff->format("%m months");
        $dyears = $diff->format("%y years");
        $ddays = $diff->format("%d days");

        $response = ['years' => explode(' ', $dyears)[0], 
            'months' => explode(' ', $dmonths)[0], 
            'days' => explode(' ', $ddays)[0]
        ];

        return $response;
    }

    public static function otp_value(){
        //return rand(111111,999999);
        return 987654;
    }

    public static function date_format($dateValue, $format = NULL){
        if($format == 'datetime'){
            return date('Y-m-d H:i:s', strtotime($dateValue));
        }else{
            return date('Y-m-d', strtotime($dateValue));
        }
    }

    public static function date_format_show($dateValue, $format = NULL, $dateFormat = 'd-m-Y h:i A'){
        if($format == 'datetime'){
            return date($dateFormat, strtotime($dateValue));
        }else{
            return date('d-m-Y', strtotime($dateValue));
        }
    }
   
    
    /**
     * function for generating unique file name based on timestamp.
     * @param $ext fileextension.
     * 
     * @return string filename.
     */
    
    public static function rand_filename($ext=null)
    {
        if($ext)
        {
            return time().'-'.'1'.rand(0,999999999).'.'.$ext;
        }
        else
        {
            return time().'-'.'1'.rand(0,999999999);
        }
    }
    
    /**
     * Create unique slug based on the page title
     * @param string $str string for which slug would be generated
     * @param  string $model_name model for which slug would be generated
     * @param string $slug_field Column name for slug.
     * 
     * 
     * @return string
     */
    
    public static function uniqueSlug($str,$model_name,$slug_field='slug')
    {        
        $slug = Str::slug($str);
        
        $slug_incrementor = 1;
        
        // If slug already exixts
        if( $model_name::whereRaw("$slug_field = ?", array(Str::slug($str)))->count())
        {
           /* Loop through slug incrementor to check if the combination of
            * slug and slug incrementor is unique
            * 
            * i.e
            * 
            * If existing-title-slug exists then we check if 
            *   existing-title-slug-1, existing-title-slug-2 etc. are unique.
            * First unique combination of slug and slug incrementor will be returned.
            */
            
            while ($model_name::whereRaw("$slug_field = ?", array($slug))->count())
            {
                $slug = Str::slug($str).'-'.$slug_incrementor;
                $slug_incrementor++;
            }
            
            return $slug;
        }  
            
        return $slug;       
    }

    public static function random_strings($length_of_string) 
    { 
      
        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
          
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    }

    /*public static function decrypt($inputs) {
        $Encryption = new Encryption();
        $nonce_value = 'e17e6cb6267d111285cdbe218bd200eb';

        $data = array();
        foreach ($inputs as $key => $value) {
            $data[$Encryption->decrypt($key, $nonce_value)] = $Encryption->decrypt($value, $nonce_value);
        }

        return $data;
    }*/

    public static function decrypt_password($password) {
        $Encryption = new Encryption();
        $nonce_value = 'e17e6cb6267d111285cdbe218bd200eb';
        return $Encryption->decrypt($password, $nonce_value);
    }

    public static function generate_password() {
        $one_letter = 'abcdefghijklmnopqrstuvwxyz';
        $captial_letter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $one_number = '0123456789';

        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $password = substr(str_shuffle($one_letter), 0, 1);
        $password = $password . substr(str_shuffle($captial_letter), 0, 1);
        $password = $password . substr(str_shuffle($one_number), 0, 1);
        $password = $password . substr(str_shuffle($str_result), 0, 3);

        return str_shuffle($password);
    }

    public static function sendSms($mobilenumber, $message, $messageType = NULL) {
        $apikey = "SM32w26Q5kec1lgSRorRbQ";
        $apisender = "NACKCD";
        $msg =$message;
        $num = '91'.$mobilenumber; // MULTIPLE NUMBER VARIABLE PUT HERE...!
        $ms = rawurlencode($msg); //This for encode your message content
        $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$apikey.'&senderid='.$apisender.'&channel=2&DCS=0&flashsms=0&number='.$num.'&text='.$ms.'&route=1';
        //echo $url;
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,2);
        $data = curl_exec($ch);
        return true;
    }

    public static function send_mail($template, $data, $email){
        \Mail::send($template, $data, function ($m) use ($email) {
            $m->from($email, 'Kuchinda');
            $m->to($email)->subject('Booking Details');
        });
    }

    /*
        * CCAvenue Library
    */
    ////////////////////////////////////////////////////////////////////////
    /*
    * @param1 : Plain String
    * @param2 : Working key provided by CCAvenue
    * @return : Decrypted String
    */
    public static function encrypt_details($plainText,$key){
        $key = Util::hextobin_details(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    /*
    * @param1 : Encrypted String
    * @param2 : Working key provided by CCAvenue
    * @return : Plain String
    */
    public static function decrypt_details($encryptedText,$key){
        $key = Util::hextobin_details(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = Util::hextobin_details($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    public static function hextobin_details($hexString){ 
        $length = strlen($hexString); 
        $binString="";   
        $count=0; 
        while($count<$length) 
        {       
            $subString =substr($hexString,$count,2);           
            $packedString = pack("H*",$subString); 
            if ($count==0)
            {
                $binString=$packedString;
            } 
            
            else 
            {
                $binString.=$packedString;
            } 
            
            $count+=2; 
        } 
            return $binString; 
    } 
    ////////////////////////////////////////////////////////////////
    
    public static function get_nice_datetime($date = null) {
        return strftime('%d %b %Y, %I:%M %p', strtotime($date));
    }
    
    public static function check_for_election_period () {
        
        $data = GeneralSettingsModel::get_settings();
        
        $cur_date = date('Y-m-d');
        $from_date = $data->election_period_from;
        $to_date = $data->election_period_to;
        
        if ((strtotime($cur_date) >= strtotime($from_date)) && (strtotime($cur_date) <= strtotime($to_date))) {
            return 1;
        } else {
            return 0;
        }
    }
	
	/**
     * Function for uploading attachment
     * take $file,$destination_file,$prefix,$dir as argument. 
     * 
     * @return upload file path.
     */
    
    public static function upload_file($file,$destination_file,$prefix=null,$dir=null)
    {
        // define empty path.
        $path = '';
        
        // check if $file exists.
        if($file)
        {
            // check dir is not empty.
            if(!empty($dir))
            {   
                //define $dir path into $path.
                $path = $dir;
            }
            else
            {
               //define path.
               $path = 'upload'.DIRECTORY_SEPARATOR.date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR.date('d');
            }
            
            //check $prefix is not empty.
            if(!empty($prefix))
            {
                //define path with prefix.
               $path = $prefix.DIRECTORY_SEPARATOR.$path;
            }
            
            // Upload file in a public path folder
            $upload_success = $file->move(public_path().DIRECTORY_SEPARATOR.$path,$destination_file);
            $upload_success = str_replace(public_path().DIRECTORY_SEPARATOR,'',$upload_success);
            
            // return upload file path.
            return str_replace('\\', '/', $upload_success);
        }
    }
	
    public static function checkApproveRejectStatusDA($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [1,16])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id)
                            ->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();

            //dd($approve_reject_status);
            if(!empty($approve_reject_status) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            }
            $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id)
                            ->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
                            //dd($approve_reject_status);

            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
        }
        //dd($response);
        return $response;
    }
    
	public static function checkApproveRejectStatus($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereNotIn('application_status_id', [1,17,14])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);         
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id)
                            ->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
            //dd($approve_reject_status);
            if(!empty($approve_reject_status) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [16])){
                $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            }else{
                $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            }            
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id)
                            ->where('created_by', Auth::user()->id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->first();

            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        }
        //dd($response);
        return $response;
    }
	
	public static function checkApproveRejectStatusForEmp($application_id, $form_id, $field_id, $nominee_id=null) {
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereNotIn('application_status_id', [1])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            ->select('id', 'status_id', 'remarks')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'desc')
                            ->first();

            return ['form_submit' => true, 
                'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 
                'remarks' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL
            ];
        } else {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            ->select('id', 'status_id', 'remarks')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'desc')
                            ->first();

            return ['form_submit' => true, 
                'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 
                'remarks' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL
            ];
        }
    }

    public static function checkApproveRejectStatusFE($application_id, $form_id, $field_id, $nominee_id=null) {
        /* 
            * Check Application Status.
            * 13- [DA Part - II Submitted & Forwarded to FE (L1)]. Show all radio fields.
            * 17- [Application Resubmitted to Finanace Executive (L1)]. Show only the fields which are resubmitted.
        */
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [13,17])
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        
        if(!empty($pension_application_form)) {   
            //dd(1);  
            /*
                * If Application ID found.
                * Check the field status in field status table [optcl_application_form_field_status]
                * Field Resubmitted / Field Returned
            */       
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            if(!empty($approve_reject_status) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [17])){
                $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            }else{
                $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];    
            }
            
        } else {
            //dd(2);         
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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

            $response = ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
        }
        //dd($response);
        return $response;
    }

    public static function checkApproveRejectStatusFE_copy($application_id, $form_id, $field_id, $nominee_id=null) {
        // return false;   
        //DB::enableQueryLog();
        /* 
            * Check Application Status.
            * 13- [DA Part - II Submitted & Forwarded to FE (L1)]. Show all radio fields.
            * 17- [Application Resubmitted to Finanace Executive (L1)]. Show only the fields which are resubmitted.
        */
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [13,17]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        //dd(DB::getQueryLog(), $pension_application_form);
        $status_id = 0;
        if(!empty($pension_application_form)) {            
            //dd(1);
            /*
                * If Application ID found.
                * Check the field status in field status table [optcl_application_form_field_status]
                * Field Resubmitted / Field Returned
            */
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            //dd($approve_reject_status);
            /*
                * 
            */
            if($pension_application_form->application_status_id == 17 && $approve_reject_status->status_id == NULL && $approve_reject_status->is_latest == 0){
                $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            }else{
                $response = ['form_submit' => true, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            }
            
            /*if($approve_reject_status){
                $status_id = $approve_reject_status->status_id;                
                if($approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [17])){
                    return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
                }
            }
            return ['form_submit' => true, 'status_id' => $status_id, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];*/
        } else {
            //dd(2);
            $paf_status = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
            if(in_array($paf_status->application_status_id, [20,21,22,23,24,25,26,27]) ){
                if($paf_status->application_status_id == 27){
                    return ['form_submit' => true, 'status_id' => 1, 'is_latest' => 1, 'remark_value' => NULL];
                }else if($paf_status->application_status_id == 22){
                    //dd(3);
                    return ['form_submit' => true, 'status_id' => 1, 'is_latest' => 1, 'remark_value' => NULL];
                }else{
                    return ['form_submit' => true, 'status_id' => 1, 'is_latest' => NULL, 'remark_value' => NULL];    
                }
            }else{
                $approve_reject_status = DB::table('optcl_application_form_field_status')
                                ->select('id', 'status_id','remarks')
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
                return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => NULL, 'remark_value' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL];
            }            
        }
        return $response;
    }

    public static function checkApproveRejectStatusUH($application_id, $form_id, $field_id, $nominee_id=null) {
        // return false;        
        $pension_application_form = DB::table('optcl_pension_application_form')
                                    ->where('id', $application_id)
                                    ->whereIn('application_status_id', [1,2,3,6,10,11,12,14,15,16,17,18,19,27]) 
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        $status_id = 0;
        if(!empty($pension_application_form)) {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id', 'remarks')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'desc')
                            ->first();

            return ['form_submit' => true, 
                'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 
                'remarks' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL
            ];
        } else {
            $approve_reject_status = DB::table('optcl_application_form_field_status')
                            //->select('id', 'status_id', 'remarks')
                            ->where('application_id', $application_id)
                            ->where('form_id', $form_id)
                            ->where('field_id', $field_id);

            if(!empty($nominee_id)) {
                $approve_reject_status = $approve_reject_status->where('nominee_id', $nominee_id);
            }

            $approve_reject_status = $approve_reject_status->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'desc')
                            ->first();

            return ['form_submit' => true, 
                'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 
                'remarks' => !empty($approve_reject_status->remarks) ? $approve_reject_status->remarks : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL
            ];
        }
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
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            }
            if(!empty($approve_reject_status) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [28])){
                return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            }

            return ['form_submit' => true, 'status_id' => $status_id, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        } else {
            //dd(2);
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            }
            if(!empty($approve_reject_status) && $approve_reject_status->status_id == NULL && in_array($pension_application_form->application_status_id, [31])){
                return ['form_submit' => false, 'status_id' => !empty($approve_reject_status->status_id) ? $approve_reject_status->status_id : NULL, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
            }

            return ['form_submit' => true, 'status_id' => $status_id, 'is_latest' => !empty($approve_reject_status->is_latest) ? $approve_reject_status->is_latest : NULL];
        } else {
            //dd(2);
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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

    public static function checkApproveRejectStatusHRSanctionAuthority($application_id, $form_id, $field_id, $nominee_id=null) {
        //return ['form_submit' => true, 'status_id' => 1];
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
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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
            $approve_reject_status = DB::table('optcl_application_form_field_status')
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

    

}