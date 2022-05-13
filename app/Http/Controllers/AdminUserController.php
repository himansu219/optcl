<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdminUser;
use App\Models\UnitMaster;
use App\Models\PensionUnit;
use App\Models\UserDesignation;
use App\Models\UserRoleMaster;
use Session;

use Illuminate\Support\Facades\Mail;
//use Auth;

class AdminUserController extends Controller
{   

    // public function __construct(){
    //     $this->middleware('auth');
    //     $this->current_date = date('Y-m-d');
    // }

    public function index(){
        
        $designation=UserDesignation::where('status',1)->where('deleted',0)->get();
        $optcl_unit=UnitMaster::where('status',1)->where('deleted',0)->get();
        $pension_unit=PensionUnit::where('status',1)->where('deleted',0)->get();
        $user_role=UserRoleMaster::where('status',1)->where('deleted',0)->get();

        //  $data = DB::table('optcl_users')
        //     ->join('optcl_user_designation_master','optcl_user_designation_master.id','=', 'optcl_users.designation_id')
        //     ->leftJoin('optcl_unit_master','optcl_unit_master.id','=','optcl_users.optcl_unit_id')
        //     ->leftJoin('optcl_pension_unit_master','optcl_pension_unit_master.id','=','optcl_users.pension_unit_id')
        //     ->select('optcl_users.*')
        //     ->orderBy('optcl_users.id','DESC')
        //     ->get();
        // dd($data);
        return view('admin.user-add',compact('designation','optcl_unit','pension_unit','user_role'));
    }

    public function user_submit(Request $request){

        // $request->validate([

        //     'user_name'=>'required',
        //     'employee_id'=>'required',
        //     'aadhaar_no'=>'required',
        //     // 'unit_name'=>'required',
        //     'designation'=>'required',
        //     'user_mobile_no'=>'required'
            
        // ]);

        //$data=AdminUser::where('username',$request->user_name)->where('status',1)->where('deleted',0)->first();
        $data=AdminUser::where('employee_code',$request->employee_id)->where('status',1)->where('deleted',0)->first();
        $checkEmail=AdminUser::where('email_id',$request->email_id)->where('status',1)->where('deleted',0)->first();
        
        $checkMobile = AdminUser::where('mobile', $request->user_mobile_no)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
        //dd($checkMobile);
        if($checkMobile === null){
            if($checkEmail === null){
                //dd($request->all());
                if($data === null){
                    
                    $res= new AdminUser;
                    $res->first_name = $request->user_name;
                    $res->user_type = 4;
                    $res->username = $request->user_name;
                    $res->employee_code = $request->employee_id;
                    $res->aadhaar_no = $request->aadhaar_no;
                    $res->mobile = $request->user_mobile_no;
                    $res->email_id = $request->email_id;
                    $res->optcl_unit_id = $request->unit_name;
                    $res->system_user_role = $request->user_role;
                    $res->pension_unit_id = $request->pension_unit_name;
                    $res->designation_id = $request->designation;
                    //$res->password = bcrypt('Secret@123'); // to create dynamically
                    $res->created_at = date('Y-m-d H:i:s', time());
                    $res->created_by = 1;
                    $res->verification_code = $this->verified_code(10);
                    $res->expired_link_at = date("Y-m-d H:i:s", strtotime("+24 hours"));
                    $res->status = 1;
                    $res->deleted = 0;
                    //$res->is_verified = 1;
                    $res->save();
                    $verification_code = $res->verification_code;
                    //dd($verification_code);
                    // email data
                    $email_data = array(
                        'name'  => $request->user_name,
                        'email' => $request->email_id,
                        'mobile'=> $request->user_mobile_no,
                        'img_src'=> asset('/public/images/logo_1.png'),
                        'verification_url'=> URL('set_password/'.$verification_code),
                        
                        
                    );
                    //dd($email_data);
                    // send email with the template
                    Mail::send('email', $email_data, function ($message) use ($email_data) {
                        $message->to($email_data['email'], $email_data['name'], $email_data['mobile'],$email_data['img_src'])
                            ->subject('OPTCL User Registration')
                            ->from('ntspl.demo5@gmail.com', 'OPTCL Pension Portal');
                    });
                    //return view('email',compact('user_name','mobile'));

                    Session::flash('success', 'User registered successfully');
                    return redirect()->route('user_details');
                } else {
                    Session::flash('error', 'Employee Code already exists');
                    return redirect()->route('user_add');
                }
            } else {
                Session::flash('error', 'Email Id already exists');
                return redirect()->route('user_add');
            }
        } else {
            Session::flash('error', 'Mobile No already exists');
            return redirect()->route('user_add');
        }  
    }

    // for resend email to the user
    public function ResendEmail(Request $request,$id){
        $result = DB::table('optcl_users')
                    ->where('id',$id)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->first();
        //dd($result);
        $cur_date = date('Y-m-d H:i:s', time());
        $email_id = $result->email_id;
        $user_name = $result->username;
        $mobile = $result->mobile;
        $verification_code = $this->verified_code(10);
        $expired_link_at = date("Y-m-d H:i:s", strtotime("+24 hours"));
        $update_user_data = DB::table('optcl_users')
                                ->where('id',$id)
                                ->update([
                                    'verification_code' => $verification_code,
                                    'created_at'        => $cur_date,
                                    'expired_link_at'   => $expired_link_at,
                                    'is_verified'       => 0
                                ]);
        $email_data = array(
            'name'  => $user_name,
            'email' => $email_id,
            'mobile'=> $mobile,
            'img_src'=> asset('/public/images/logo_1.png'),
            'verification_url'=> URL('set_password/'.$verification_code),
            
            
        );
        //dd($email_data);
        // send email with the template
        Mail::send('resend_email', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'], $email_data['mobile'],$email_data['img_src'])
                ->subject('OPTCL User Registration')
                ->from('ntspl.demo5@gmail.com', 'OPTCL Pension Portal');
        });
        
        //return view('email',compact('user_name','mobile'));

        Session::flash('success', 'Mail resend to the user successfully');
        return redirect()->route('user_details');
    }

    public function fetch_district(Request $request){
       
        //$data['result']=DistrictMaster::where('status',1)->where('deleted',0)->get();
        $user_type = UserRoleMaster::where('status',1)
                                    ->where('deleted',0)
                                    ->get();
        $designation = UserDesignation::where('status',1)
                                      ->where('deleted',0)
                                      ->get();
        $search =  $request->input('designation');
        if($search!=""){
            $data = AdminUser::where(function ($query) use ($search){
                $query->where('designation_id', 'like', '%'.$search.'%')->where('status',1)->where('deleted',0);
                    
            })
            ->paginate(10);
            $data->appends(['designation' => $search]);
        } else {
            $data = AdminUser::where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
        }
        //$data = DistrictMaster::where('status',1)->where('deleted',0)->paginate(10);
        return view('admin.admin.user-details',compact('user_type','designation','data','search'));
    }
    

    public function fetch_user(Request $request){           
        $user_type = UserRoleMaster::where('status',1)
                                   ->where('deleted',0)
                                   ->get();
        $designation = UserDesignation::where('status',1)
                                      ->where('deleted',0)
                                      ->get();
        $search =  $request->input('user_role');
        $search2 =  $request->input('designation');
        $data = AdminUser::where('is_admin',0)->where('user_type',4)->where('deleted',0)->orderBy('id','DESC');
        if($search!="") {
            $data = $data->where('system_user_role', $search)
                            ->where('designation_id', $search2);
        }
        $data = $data->paginate(10);
        return view('admin.user-details',compact('user_type','designation','data','search','search2'));
    }

    public function edit_user(Request $request,$id){
        $designation = UserDesignation::where('status',1)
                                      ->where('deleted',0)
                                      ->get();
        $optcl_unit = UnitMaster::where('status',1)
                                ->where('deleted',0)
                                ->get();
        $user_role = UserRoleMaster::where('status',1)
                                   ->where('deleted',0)
                                   ->get();
        $pension_unit = PensionUnit::where('status',1)
                                    ->where('deleted',0)
                                    ->get();
        $result = AdminUser::where('status',1)
                            ->where('deleted',0)
                            ->where('id',$id)
                            ->first();
        return view('admin.user-edit')->with(compact('designation','optcl_unit', 'user_role','pension_unit', 'result'));       
    }

    public function update_user(Request $request){
        // $request->validate([

        //     'user_name'=>'required',
        //     'employee_id'=>'required',
        //     'aadhaar_no'=>'required',
        //     // 'unit_name'=>'required',
        //     'designation'=>'required',
        //     'user_mobile_no'=>'required'
        // ]);
        $user_id = $request->input('user_hidden_id');
        if(in_array($request->input('designation'), [2,3,4])){
            $optcl_unit_id = $request->input('unit_name');
        } else {
            $optcl_unit_id =  NULL; 
        }
        if(in_array($request->input('designation'), [12])){
            $pension_unit_id = $request->input('pension_unit_name');
        } else {
            $pension_unit_id = NULL;
        }
        $cur_date = date('Y-m-d H:i:s', time());
        $data = array(    
            'username'          => $request->input('user_name'),
            'employee_code'     => $request->input('employee_id'),
            'aadhaar_no'        => $request->input('aadhaar_no'),
            'email_id'          => $request->input('email_id'),
            'mobile'            => $request->input('user_mobile_no'),
            'system_user_role'  => $request->input('user_role'),
            'designation_id'    => $request->input('designation'),
            'updated_at'        => $cur_date,
            'optcl_unit_id'     => $optcl_unit_id, 
            'pension_unit_id'   => $pension_unit_id,   
        );
        AdminUser::where('status',1)
                 ->where('deleted',0)
                 ->where('id', $user_id)
                 ->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('user_details');
    }

    public function delete_user(Request $request,$id){

            AdminUser::where('status',1)
                    ->where('deleted',0)
                    ->where('id' , $id)
                    ->update(['deleted'=> 1]);
            Session::flash('success', 'Data deleted successfully');
            return redirect()->route('user_details');
    }

    public function getDesignation(Request $request){
            $uid=$request->post('uid');
            $userDesg=UserDesignation::where('user_role_id',$uid)->get();
            $html='<option value="">Select User Designation</option>';
            foreach($userDesg as $list){
                $html.='<option value="'.$list->id.'">'.$list->designation_name.'</option>';
            }
            echo $html;
    }

    public function changeStatus(Request $request){
            //dd($request->all());
            $user = AdminUser::find($request->id);
            $user->status = $request->status;
            $user->save();
            Session::flash('success', 'Status updated successfully');
            return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    }

    public function verified_code($n) {
        // all numeric digits 
        $generator = "1357902468abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
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
