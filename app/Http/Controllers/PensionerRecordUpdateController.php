<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BankBranchNameMaster;
use App\Models\BankNameMaster;
use App\Libraries\Util;
use Session;
use Auth; 

use App\Imports\DataImport;
use Maatwebsite\Excel\Facades\Excel;

class PensionerRecordUpdateController extends Controller{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    // update pension record listing page or index page
    public function Index(){
        $user_id = Auth::user()->employee_code;
        $result = DB::table('optcl_employee_personal_details')
                    ->where('employee_code',$user_id)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->first();
        $flag = DB::table('optcl_update_pension_record')
                ->where('employee_code',$user_id)
                ->where('is_approved',0)
                ->where('status',1)
                ->where('deleted',0)
                ->count();

        
        $data   = DB::table('optcl_update_pension_record')
                    ->where('employee_code',$user_id)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->paginate(10);
        //dd($data);
        if(!empty($result)){
            
            return view('user.pension.update_pensioner_record_details')->with(compact('user_id','result','data','flag'));
        } else {
            Session::flash('error', 'Please submit all the pension application.');
            return redirect()->route('view_details');
        }
    }
    // add update pension record form
    public function AddUpdatePensionRecordForm(){
        $user_id = Auth::user()->employee_code;
        $result = DB::table('optcl_employee_personal_details')
                    ->where('employee_code',$user_id)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->first();
        $bank_name = BankNameMaster::where('status',1)->where('deleted',0)->get();
        return view('user.pension.update_pensioner_record')->with(compact('bank_name','user_id','result'));
    }
    // service pensioner view update pension record 
    public function viewUpdatePensionRecord(Request $request,$id){
        $username = Auth::user()->username;
        $result   = DB::table('optcl_update_pension_record')
                    ->where('employee_code',$username)
                    ->where('id',$id)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->first();
        
        return view('user.pension.update_pensioner_record_view',compact('result'));
    
      }
    // fetch bank name
    public function getbankBranch(Request $request){
        $bid = $request->post('bid');
        $branchName = BankBranchNameMaster::where('bank_id',$bid)->get();
        $html='<option value="">Select Branch Name</option>';
        foreach($branchName as $list){
            $html.='<option value="'.$list->id.'">'.$list->branch_name.'</option>';
        }
        echo $html;
    }
    //fetch MICR code 
    public function getIfscMicr(Request $request){
        $bank_id     = $request->post('bankname');
        $branch_name = $request->post('branchname');
        $branchName  = DB::table('optcl_bank_branch_master')
                          ->where('bank_id',$bank_id)
                          ->where('id', $branch_name)
                          ->first();
        return response()->json([
            'ifsc_code' => $branchName->ifsc_code,
            'micr_code' => $branchName->micr_code
        ]);
        
    }
    // insert in update pension record table
    public function UpdatePensionRecordFormSubmit(Request $request){
        $employee_code = $request->employee_code;
        $user_id       = DB::table('optcl_employee_master')
                            ->where('employee_code',$employee_code)
                            ->where('status',1)
                            ->where('deleted',0)
                            ->first();
        $pension_unit_id = DB::table('optcl_employee_personal_details')
                            ->where('employee_code',$employee_code)
                            ->where('status',1)
                            ->where('deleted',0)
                            ->first();
        $employee_id   = $user_id->id;
        $PensionUnitId = $pension_unit_id->pension_unit_id;
        $bank_id       = $request->bank_name;
        $bank_ac_no    = $request->bank_account_no;
        $branch_name   = $request->branch_name;
        $cur_date      = date('Y-m-d H:i:s', time());

        try {
            DB::beginTransaction();
            $branchName = DB::table('optcl_update_pension_record')
                           ->insert([
                            'employee_id'     => $employee_id ,
                            'employee_code'   => $employee_code,
                            'pension_unit_id' => $PensionUnitId,
                            'bank_id'         => $bank_id,
                            'bank_branch_id'  => $branch_name,
                            'bank_ac_no'      => $bank_ac_no,
                            'is_approved'     => 0,
                            'is_latest'       => 1,
                            'status'          => 1,
                            'created_at'      => $cur_date,
                            'deleted'         => 0
                            ]);

                DB::table('optcl_update_pension_record')
                        ->where('employee_id',$employee_id)
                        ->update([
                            'is_latest' => 0,
                         ]);
            DB::commit();

        } catch (Exception $ex) {
            DB::rollback();
        }
        Session::flash('success', 'Pesnsion record added successfully');
        return redirect()->route('update_pension_record');
        

    }
    // edit update pension record 
    public function EditUpdatePensionRecord(Request $request,$id){
        $result = DB::table('optcl_update_pension_record')
                    ->where('id',$id)
                    ->where('is_approved',0)
                    ->where('status',1)
                    ->where('deleted',0)
                    ->first();
        //dd($result);
        $bank_name        = BankNameMaster::where('status',1)->where('deleted',0)->get(); 
        $bank_branch_name = BankBranchNameMaster::where('status',1)->where('deleted',0)->get();           
        return view('user.pension.update_pensioner_record_edit')->with(compact('result','bank_name','bank_branch_name'));
    }
    //update data for update pension record
    public function UpdatePensionRecordData(Request $request){
        $id            = $request->id;
        $bank_id       = $request->bank_name;
        $bank_ac_no    = $request->bank_account_no;
        $branch_name   = $request->branch_name;
        $cur_date      = date('Y-m-d H:i:s', time());
        DB::table('optcl_update_pension_record')
            ->where('id',$id)
            ->update([
            'bank_id'         => $bank_id,
            'bank_branch_id'  => $branch_name,
            'bank_ac_no'      => $bank_ac_no,
            'is_approved'     => 0,
            'is_latest'       => 0,
            'status'          => 1,
            'modified_at'     => $cur_date,
            'deleted'         => 0
            ]);
        Session::flash('success', 'Pension record updated successfully');
        return redirect()->route('update_pension_record');
    }
   
    public function PensionUnitUpdatePensionRecord(Request $request){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        $result = DB::table('optcl_change_data_list')
                        ->join('optcl_change_data_master', 'optcl_change_data_master.id', '=', 'optcl_change_data_list.change_data_id')
                        ->join('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_change_data_list.status_id')
                        ->select('optcl_change_data_list.*', 'optcl_change_data_master.change_type', 'optcl_application_status_master.status_name');
        if(!empty($request->change_data_type)) {
            $result = $result->where('optcl_change_data_list.change_data_id', $request->change_data_type);
        }  
        if(!empty($request->cr_number)) {
            $result = $result->where('optcl_change_data_list.cr_number', $request->cr_number);
        }              
        $result = $result->where('optcl_change_data_list.status',1)
                        ->where('optcl_change_data_list.deleted',0)
                        ->orderBy('optcl_change_data_list.id','DESC')
                        ->paginate(10);
        return view('user.pension_unit.update-pension-record-details', compact('result', 'changed_data_list', 'request'));             
    }
    public function update_record(){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $pension_units = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
        return view('user.pension_unit.update_record', compact('changed_data_list', 'banks', 'pension_units'));             
    }
    /*  ------------------ Additional Family Pensioner after Death of SP/FP ------------------  */
    // Listing
    public function update_record_listing(Request $request){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        /* 
            Column- Table Name
            ------------------
            ppo_no, date of death, name of family pensioner, created_at- optcl_change_data_addl_family_pensioner
            status- optcl_application_status_master
        */
        $result = DB::table('optcl_change_data_addl_family_pensioner')
                    ->join('optcl_change_data_list', function($join){
                        $join->on('optcl_change_data_list.change_data_id', '=', DB::raw(1));
                        $join->on('optcl_change_data_list.cr_application_id', '=', 'optcl_change_data_addl_family_pensioner.id');
                    })
                    ->join('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_change_data_list.status_id')
                    ->select('optcl_change_data_addl_family_pensioner.*','optcl_change_data_list.id AS cID', 'optcl_change_data_list.cr_number', 'optcl_application_status_master.status_name');

        if(!empty($request->ppo_no_search)) {
            $result = $result->where('optcl_change_data_addl_family_pensioner.ppo_no', 'like', '%'.$request->ppo_no_search.'%');
        }  
        if(!empty($request->name_family_pensioner_search)) {
            $result = $result->where('optcl_change_data_addl_family_pensioner.name_family_pensioner', 'like', '%'.$request->name_family_pensioner_search.'%');
        }    
        if(!empty($request->saving_acc_no_search)) {
            $result = $result->where('optcl_change_data_addl_family_pensioner.sb_bank_ac_number', $request->saving_acc_no_search);
        }        
        $result = $result->where('optcl_change_data_addl_family_pensioner.status',1)
                        ->where('optcl_change_data_addl_family_pensioner.deleted',0)
                        ->where('optcl_change_data_list.change_data_id',1)
                        ->orderBy('optcl_change_data_addl_family_pensioner.id','DESC')
                        ->paginate(10);
        return view('user.pension_unit.update_pages.addnl_family_pensioner.listing', compact('result', 'changed_data_list', 'request'));
    }
    // Form Page
    public function update_record_additional(){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $pension_units = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
        return view('user.pension_unit.update_pages.addnl_family_pensioner.additional_pension_add', compact('changed_data_list', 'banks', 'pension_units'));             
    }    
    // From Submission
    public function update_record_submission(Request $request){
        $validation = [];
        $ppo_number = $request->ppo_number;
        if($ppo_number == ""){
            $validation['error'][] = array("id" => "ppo_number-error","eValue" => "Please enter PPO no");
        }else{
            $ppo_details = DB::table('optcl_pension_application_form')->where('ppo_number', $ppo_number)->first();
            if($ppo_details){
                $is_dead_status = $ppo_details->is_dead;
                if($is_dead_status == 0){
                    $validation['error'][] = array("id" => "ppo_number-error","eValue" => "Service pensioner status is not updated");
                }
            }else{
                $validation['error'][] = array("id" => "ppo_number-error","eValue" => "PPO no. not found");
            }
        }
        $pension_emp_no = $request->pension_emp_no;
        /* if($pension_emp_no == ""){
            $validation['error'][] = array("id" => "pension_emp_no-error","eValue" => "Please enter employee no");
        } */
        $dod_sp_fp = $request->dod_sp_fp;
        if($dod_sp_fp == ""){
            $validation['error'][] = array("id" => "dod_sp_fp-error","eValue" => "Please enter DOD of SP/ FP");
        }
        $name_family_pensioner = $request->name_family_pensioner;
        if($name_family_pensioner == ""){
            $validation['error'][] = array("id" => "name_family_pensioner-error","eValue" => "Please enter name of family pensioners");
        }
        $eod_enhanced_family_pension = $request->eod_enhanced_family_pension;
        if($eod_enhanced_family_pension == ""){
            $validation['error'][] = array("id" => "eod_enhanced_family_pension-error","eValue" => "Please enter end date of enhanced family pension");
        }
        $bank_name = $request->bank_name;
        if($bank_name == ""){
            $validation['error'][] = array("id" => "bank_name-error","eValue" => "Please slect bank");
        }
        $branch_name_address = $request->branch_name_address;
        if($branch_name_address == ""){
            $validation['error'][] = array("id" => "branch_name_address-error","eValue" => "Please select branch");
        }
        $savings_bank_ac_no = $request->savings_bank_ac_no;
        if($savings_bank_ac_no == ""){
            $validation['error'][] = array("id" => "savings_bank_ac_no-error","eValue" => "Please enter savings bank A/C no");
        }
        $ifsc_code = $request->ifsc_code;
        if($ifsc_code == ""){
            $validation['error'][] = array("id" => "ifsc_code-error","eValue" => "Please enter IFSC code");
        }
        $noc_previous_bank = $request->noc_previous_bank;
        if($noc_previous_bank == ""){
            $validation['error'][] = array("id" => "noc_previous_bank-error","eValue" => "Please select NOC from previous bank");
        }
        $upload_path = 'uploads/add_family_noc_attachment/';
        if($request->hasFile('noc_previous_bank_attachment')) {
            $filename = Util::rand_filename($request->file('noc_previous_bank_attachment')->getClientOriginalExtension());
            $noc_previous_bank_attachment = Util::upload_file($request->file('noc_previous_bank_attachment'), $filename, null, $upload_path);
        } else {
            $validation['error'][] = array("id" => "noc_previous_bank_attachment-error","eValue" => "Please upload NOC document");
        }
        
        // Check PPO number with other details
            /* ---------Code---------- */
        $addl_fam_pen_changed_type_id = $request->addl_fam_pen_changed_type_id;
        if(!isset($validation['error'])){
            try{
                $data = [
                    "ppo_no" => $ppo_number,
                    "pension_emp_no" => $pension_emp_no,
                    "dod_sp_fp" =>  date('Y-m-d', strtotime(str_replace("/", "-", $dod_sp_fp))),
                    "name_family_pensioner" => $name_family_pensioner,
                    "end_date_enhan_fam_pension" => date('Y-m-d', strtotime(str_replace("/", "-", $eod_enhanced_family_pension))),
                    "sb_bank_ac_number" => $savings_bank_ac_no,
                    "bank_branch_id" => $branch_name_address,
                    "noc_from_pre_bank" => $noc_previous_bank,
                    "noc_document" => $upload_path.$filename,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_addl_family_pensioner')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $addl_fam_pen_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $addl_fam_pen_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function update_record_additional_new_pensioner_edit($appID){
        //dd(123);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $pension_units = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $addl_family_pen_details = DB::table('optcl_change_data_addl_family_pensioner')
                                            ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id', '=', 'optcl_change_data_addl_family_pensioner.bank_branch_id')
                                            ->join('optcl_bank_master', 'optcl_bank_master.id', '=', 'optcl_bank_branch_master.bank_id')
                                            ->select('optcl_change_data_addl_family_pensioner.*', 'optcl_bank_branch_master.branch_name', 'optcl_bank_branch_master.ifsc_code', 'optcl_bank_master.bank_name', 'optcl_bank_master.id AS bank_id')
                                            ->where('optcl_change_data_addl_family_pensioner.id', $cr_application_id)
                                            ->where('optcl_change_data_addl_family_pensioner.status', 1)
                                            ->where('optcl_change_data_addl_family_pensioner.deleted', 0)
                                            ->first();
                                            //dd(123);
            if($addl_family_pen_details){
                return view('user.pension_unit.update_pages.addnl_family_pensioner.additional_pension_edit', compact('banks', 'pension_units', 'addl_family_pen_details'));
            }else{
                Session::flash('error', 'Data not found in additional family pensioner');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Form Submission
    public function pension_unit_update_record_edit_submission(Request $request){
        $validation = [];
        $ppo_number = $request->ppo_number;
        if($ppo_number == ""){
            $validation['error'][] = array("id" => "ppo_number-error","eValue" => "Please enter PPO no");
        }
        $pension_emp_no = $request->pension_emp_no;
        /* if($pension_emp_no == ""){
            $validation['error'][] = array("id" => "pension_emp_no-error","eValue" => "Please enter employee no");
        } */
        $dod_sp_fp = $request->dod_sp_fp;
        if($dod_sp_fp == ""){
            $validation['error'][] = array("id" => "dod_sp_fp-error","eValue" => "Please enter DOD of SP/ FP");
        }
        $name_family_pensioner = $request->name_family_pensioner;
        if($name_family_pensioner == ""){
            $validation['error'][] = array("id" => "name_family_pensioner-error","eValue" => "Please enter name of family pensioners");
        }
        $eod_enhanced_family_pension = $request->eod_enhanced_family_pension;
        if($eod_enhanced_family_pension == ""){
            $validation['error'][] = array("id" => "eod_enhanced_family_pension-error","eValue" => "Please enter end date of enhanced family pension");
        }
        $bank_name = $request->bank_name;
        if($bank_name == ""){
            $validation['error'][] = array("id" => "bank_name-error","eValue" => "Please slect bank");
        }
        $branch_name_address = $request->branch_name_address;
        if($branch_name_address == ""){
            $validation['error'][] = array("id" => "branch_name_address-error","eValue" => "Please select branch");
        }
        $savings_bank_ac_no = $request->savings_bank_ac_no;
        if($savings_bank_ac_no == ""){
            $validation['error'][] = array("id" => "savings_bank_ac_no-error","eValue" => "Please enter savings bank A/C no");
        }
        $ifsc_code = $request->ifsc_code;
        if($ifsc_code == ""){
            $validation['error'][] = array("id" => "ifsc_code-error","eValue" => "Please enter IFSC code");
        }
        $noc_previous_bank = $request->noc_previous_bank;
        if($noc_previous_bank == ""){
            $validation['error'][] = array("id" => "noc_previous_bank-error","eValue" => "Please select NOC from previous bank");
        }
        $upload_path = 'uploads/add_family_noc_attachment/';
        if($request->hasFile('noc_previous_bank_attachment')) {
            $filename = Util::rand_filename($request->file('noc_previous_bank_attachment')->getClientOriginalExtension());
            $noc_previous_bank_attachment = Util::upload_file($request->file('noc_previous_bank_attachment'), $filename, null, $upload_path);
            $path_filename = $upload_path.$filename;
        }  else {
            $path_filename = $request->hidden_noc_previous_bank_attachment;
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $addl_fam_pen_changed_type_appl_id = $request->addl_fam_pen_changed_type_appl_id;
        if(!isset($validation['error'])){
            try{
                $data = [
                    "ppo_no" => $ppo_number,
                    "pension_emp_no" => $pension_emp_no,
                    "dod_sp_fp" =>  date('Y-m-d', strtotime(str_replace("/", "-", $dod_sp_fp))),
                    "name_family_pensioner" => $name_family_pensioner,
                    "end_date_enhan_fam_pension" => date('Y-m-d', strtotime(str_replace("/", "-", $eod_enhanced_family_pension))),
                    "sb_bank_ac_number" => $savings_bank_ac_no,
                    "bank_branch_id" => $branch_name_address,
                    "noc_from_pre_bank" => $noc_previous_bank,
                    "noc_document" => $path_filename,
                    "modified_by" => Auth::user()->id,
                    "modified_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_addl_family_pensioner')->where('id', $addl_fam_pen_changed_type_appl_id)->update($data);
                // Store in Changed Data List
                $data_1 = [
                    "modified_by" => Auth::user()->id,
                    "modified_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_list')->where('cr_application_id', $addl_fam_pen_changed_type_appl_id)->update($data_1);

                Session::flash('success','Additional family pensioner data updated successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function update_record_additional_new_pensioner_view($appID){
        //dd(123);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $addl_family_pen_details = DB::table('optcl_change_data_addl_family_pensioner')
                                            ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id', '=', 'optcl_change_data_addl_family_pensioner.bank_branch_id')
                                            ->join('optcl_bank_master', 'optcl_bank_master.id', '=', 'optcl_bank_branch_master.bank_id')
                                            ->select('optcl_change_data_addl_family_pensioner.*', 'optcl_bank_branch_master.branch_name', 'optcl_bank_branch_master.ifsc_code', 'optcl_bank_master.bank_name')
                                            ->where('optcl_change_data_addl_family_pensioner.id', $cr_application_id)
                                            ->where('optcl_change_data_addl_family_pensioner.status', 1)
                                            ->where('optcl_change_data_addl_family_pensioner.deleted', 0)
                                            ->first();
            if($addl_family_pen_details){
                return view('user.pension_unit.update_pages.addnl_family_pensioner.additional_pensioner_view', compact('addl_family_pen_details'));
            }else{
                Session::flash('error', 'Data not found in additional family pensioner');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Additional Pension ------------------ */
    // Listing
    public function additional_pension_listing(Request $request){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        $result = DB::table('optcl_change_data_list')
                        ->join('optcl_change_data_master', 'optcl_change_data_master.id', '=', 'optcl_change_data_list.change_data_id')
                        ->join('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_change_data_list.status_id')
                        ->select('optcl_change_data_list.*', 'optcl_change_data_master.change_type', 'optcl_application_status_master.status_name');
        if(!empty($request->change_data_type)) {
            $result = $result->where('optcl_change_data_list.change_data_id', $request->change_data_type);
        }  
        if(!empty($request->cr_number)) {
            $result = $result->where('optcl_change_data_list.cr_number', $request->cr_number);
        }              
        $result = $result->where('optcl_change_data_list.status',1)
                        ->where('optcl_change_data_list.deleted',0)
                        ->where('optcl_change_data_list.change_data_id',1)
                        ->orderBy('optcl_change_data_list.id','DESC')
                        ->paginate(10);
        return view('user.pension_unit.update_pages.addnl_family_pensioner.listing', compact('result', 'changed_data_list', 'request'));
    }
    // Form Page

    // Form Submission
    public function additional_pension_submission(Request $request){
        $validation = [];
        $ap_ppo_number = $request->ap_ppo_number;
        if($ap_ppo_number == ""){
            $validation['error'][] = array("id" => "ap_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $ap_pension_emp_no = $request->ap_pension_emp_no;
        if($ap_pension_emp_no == ""){
            $validation['error'][] = array("id" => "ap_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $ap_name_family_pensioner = $request->ap_name_family_pensioner;
        if($ap_name_family_pensioner == ""){
            $validation['error'][] = array("id" => "ap_name_family_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $ap_dob = $request->ap_dob;
        if($ap_dob == ""){
            $validation['error'][] = array("id" => "ap_dob-error","eValue" => "Please select date of birth");
        }
        $ap_effective_date = $request->ap_effective_date;
        if($ap_effective_date == ""){
            $validation['error'][] = array("id" => "ap_effective_date-error","eValue" => "Please slect effective date");
        }
        $ap_additional_rate = $request->ap_additional_rate;
        if($ap_additional_rate == ""){
            $validation['error'][] = array("id" => "ap_additional_rate-error","eValue" => "Please enter additional rate");
        }
        
        // Check PPO number with other details
            /* ---------Code---------- */
        $ap_pension_changed_type_id = $request->ap_pension_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $ap_ppo_number,
                    "pensioner_emp_no" => $ap_pension_emp_no,
                    "pensioner_name" =>  $ap_name_family_pensioner,
                    "dob" => date('Y-m-d', strtotime(str_replace("/", "-", $ap_dob))),
                    "effective_date" => date('Y-m-d', strtotime(str_replace("/", "-", $ap_effective_date))),
                    "additional_rate" => $ap_additional_rate,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_additional_pension')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $ap_pension_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $ap_pension_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function update_additional_pension_edit_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_additional_pension')
                                            ->where('id', $cr_application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.additional_pension_edit', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Page Submission
    public function additional_pension_edit_submission(Request $request){
        $validation = [];
        $ap_ppo_number = $request->ap_ppo_number;
        if($ap_ppo_number == ""){
            $validation['error'][] = array("id" => "ap_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $ap_pension_emp_no = $request->ap_pension_emp_no;
        if($ap_pension_emp_no == ""){
            $validation['error'][] = array("id" => "ap_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $ap_name_family_pensioner = $request->ap_name_family_pensioner;
        if($ap_name_family_pensioner == ""){
            $validation['error'][] = array("id" => "ap_name_family_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $ap_dob = $request->ap_dob;
        if($ap_dob == ""){
            $validation['error'][] = array("id" => "ap_dob-error","eValue" => "Please select date of birth");
        }
        $ap_effective_date = $request->ap_effective_date;
        if($ap_effective_date == ""){
            $validation['error'][] = array("id" => "ap_effective_date-error","eValue" => "Please slect effective date");
        }
        $ap_additional_rate = $request->ap_additional_rate;
        if($ap_additional_rate == ""){
            $validation['error'][] = array("id" => "ap_additional_rate-error","eValue" => "Please enter additional rate");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $ap_pension_changed_type_id = $request->ap_pension_changed_type_id;
        $additional_pension_id_value = $request->additional_pension_id_value;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $ap_ppo_number,
                    "pensioner_emp_no" => $ap_pension_emp_no,
                    "pensioner_name" =>  $ap_name_family_pensioner,
                    "dob" => date('Y-m-d', strtotime(str_replace("/", "-", $ap_dob))),
                    "effective_date" => date('Y-m-d', strtotime(str_replace("/", "-", $ap_effective_date))),
                    "additional_rate" => $ap_additional_rate,
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_additional_pension')
                                        ->where('id', $additional_pension_id_value)
                                        ->update($data);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function additional_pension_view_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_additional_pension')
                                            ->where('id', $cr_application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.additional_pension_view', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Revision of Basic Pension ------------------ */
    // listing
    public function revision_basic_pension_listing(Request $request){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        /* 
            Column- Table Name
            ------------------
            ppo_no, date of death, name of family pensioner, created_at- optcl_change_data_addl_family_pensioner
            status- optcl_application_status_master
        */
        $result = DB::table('optcl_change_data_revision_basic_pension')
                    ->join('optcl_change_data_list', function($join){
                        $join->on('optcl_change_data_list.change_data_id', '=', DB::raw(2));
                        $join->on('optcl_change_data_list.cr_application_id', '=', 'optcl_change_data_revision_basic_pension.id');
                    })
                    ->join('optcl_application_status_master', 'optcl_application_status_master.id', '=', 'optcl_change_data_list.status_id')
                    ->select('optcl_change_data_revision_basic_pension.*','optcl_change_data_list.id AS cID', 'optcl_change_data_list.cr_number', 'optcl_application_status_master.status_name');

        if(!empty($request->ppo_no_search)) {
            $result = $result->where('optcl_change_data_revision_basic_pension.ppo_no', 'like', '%'.$request->ppo_no_search.'%');
        }  
        if(!empty($request->name_family_pensioner_search)) {
            $result = $result->where('optcl_change_data_revision_basic_pension.name_family_pensioner', 'like', '%'.$request->name_family_pensioner_search.'%');
        }    
        if(!empty($request->saving_acc_no_search)) {
            $result = $result->where('optcl_change_data_revision_basic_pension.sb_bank_ac_number', $request->saving_acc_no_search);
        }        
        $result = $result->where('optcl_change_data_revision_basic_pension.status',1)
                        ->where('optcl_change_data_revision_basic_pension.deleted',0)
                        ->where('optcl_change_data_list.change_data_id',2)
                        ->orderBy('optcl_change_data_revision_basic_pension.id','DESC')
                        ->paginate(10);
        return view('user.pension_unit.update_pages.revision_basic_pension.list', compact('result', 'changed_data_list', 'request'));
    }
    // Form Page
    public function revision_basic_pension_form_page(){
        $changed_data_list = DB::table('optcl_change_data_master')
                                    ->where('status', 1)
                                    ->where('status', 1)
                                    ->orderBy('change_type', 'ASC')
                                    ->get();
        $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
        $pension_units = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
        return view('user.pension_unit.update_pages.revision_basic_pension.add', compact('changed_data_list', 'banks', 'pension_units'));             
    }
    // Pensioner Details
    public function pensioner_details(Request $request){
        $ppo_no = $request->ppo_no;
        $response = [];
        DB::enableQueryLog();
        $pensioner_data = DB::table('optcl_ppo_no_list AS pnl')
                                ->leftJoin('optcl_pension_application_form AS np', function($join){
                                    $join->on('np.application_type', '=', 'pnl.application_type');
                                    $join->on('np.pension_type_id', '=', 'pnl.pensioner_type');
                                })
                                ->leftJoin('optcl_existing_user AS ep', function($join2){
                                    $join2->on('ep.application_type', '=', 'pnl.application_type');
                                    $join2->on('ep.pensioner_type', '=', 'pnl.pensioner_type');
                                })
                                //->select('optcl_ppo_no_list.*', DB::raw('IF(pnl.application_type = 1, np.basic_amount, ep.basic_amount) AS basic_amount'))
                                ->where('pnl.ppo_no', $ppo_no)
                                ->first();
        //dd(DB::getQueryLog(), $pensioner_data);
        if($pensioner_data){
            $response = [
                "basic_amount" => $pensioner_data->basic_amount,
                "employee_no" => $pensioner_data->employee_code ? $pensioner_data->employee_code : 'NA',
                "pensioner_name" => $pensioner_data->pensioner_name,
                "application_type" => $pensioner_data->application_type,
                "pensioner_type" => $pensioner_data->pensioner_type,
                "application_id" =>  $pensioner_data->application_id,
            ];
        }
        return response()->json($response);

    }
    // Form Submission
    public function revision_basic_pension_submission(Request $request){
        $validation = [];
        $rbp_ppo_number = $request->rbp_ppo_number;
        if($rbp_ppo_number == ""){
            $validation['error'][] = array("id" => "rbp_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $rbp_pension_emp_no = $request->rbp_pension_emp_no;
        /* if($rbp_pension_emp_no == ""){
            $validation['error'][] = array("id" => "rbp_pension_emp_no-error","eValue" => "Please enter employee no");
        } */
        $rbp_name_pensioner = $request->rbp_name_pensioner;
        if($rbp_name_pensioner == ""){
            $validation['error'][] = array("id" => "rbp_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $rbp_basic_amt = $request->rbp_basic_amt;
        if($rbp_basic_amt == ""){
            $validation['error'][] = array("id" => "rbp_basic_amt-error","eValue" => "Please enter basic amount");
        }
        $rbp_oo_no = $request->rbp_oo_no;
        if($rbp_oo_no == ""){
            $validation['error'][] = array("id" => "rbp_oo_no-error","eValue" => "Please enter O.O. no");
        }
        $rbp_oo_no_date = $request->rbp_oo_no_date;
        if($rbp_oo_no_date == ""){
            $validation['error'][] = array("id" => "rbp_oo_no_date-error","eValue" => "Please select O.O. no date");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $revision_basic_pension_changed_type_id = 2;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "application_type" => $rbp_ppo_number,
                    "pensioner_type" => $rbp_pension_emp_no,
                    "application_id" =>  $rbp_name_pensioner,
                    "ppo_no" => $rbp_ppo_number,
                    "pensioner_emp_no" => $rbp_pension_emp_no,
                    "pensioner_name" =>  $rbp_name_pensioner,
                    "pensioner_basic_amount" => $rbp_basic_amt,
                    "oo_no" => $rbp_oo_no,
                    "oo_no_date" => date('Y-m-d', strtotime(str_replace("/", "-", $rbp_oo_no_date))),
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_revision_basic_pension')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $revision_basic_pension_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $revision_basic_pension_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function revision_basic_pension_edit_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_revision_basic_pension')
                                            ->where('id', $cr_application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.revision_basic_pension.edit', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Form Submission
    public function revision_basic_pension_edit_submission(Request $request){
        $validation = [];
        $rbp_ppo_number = $request->rbp_ppo_number;
        if($rbp_ppo_number == ""){
            $validation['error'][] = array("id" => "rbp_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $rbp_pension_emp_no = $request->rbp_pension_emp_no;
        if($rbp_pension_emp_no == ""){
            $validation['error'][] = array("id" => "rbp_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $rbp_name_pensioner = $request->rbp_name_pensioner;
        if($rbp_name_pensioner == ""){
            $validation['error'][] = array("id" => "rbp_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $rbp_basic_amt = $request->rbp_basic_amt;
        if($rbp_basic_amt == ""){
            $validation['error'][] = array("id" => "rbp_basic_amt-error","eValue" => "Please enter basic amount");
        }
        $rbp_oo_no = $request->rbp_oo_no;
        if($rbp_oo_no == ""){
            $validation['error'][] = array("id" => "rbp_oo_no-error","eValue" => "Please enter O.O. no");
        }
        $rbp_oo_no_date = $request->rbp_oo_no_date;
        if($rbp_oo_no_date == ""){
            $validation['error'][] = array("id" => "rbp_oo_no_date-error","eValue" => "Please select O.O. no date");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $revision_basic_pension_changed_type_id = $request->revision_basic_pension_changed_type_id;
        $rbp_application_id = $request->rbp_application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "pensioner_basic_amount" => $rbp_basic_amt,
                    "oo_no" => $rbp_oo_no,
                    "oo_no_date" => date('Y-m-d', strtotime(str_replace("/", "-", $rbp_oo_no_date))),
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_revision_basic_pension')
                                        ->where('id', $rbp_application_id)
                                        ->update($data);               

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Taxable Amount Calculation After Basic Pension Revision
    public function revision_taxable_amount_calculation_page($appID){
        /* 
            - Get all details required in taxable amount calculation page
            - @application_id, @application_type, @pensioner_type
        */
        $request_details = DB::table('optcl_change_data_revision_basic_pension')
                                        ->where('id', $appID)
                                        ->where('status', 1)
                                        ->where('deleted', 0)
                                        ->first();
        if($request_details){
            $application_type = $request_details->application_type;
            $pensioner_type = $request_details->pensioner_type;
            $application_id = $request_details->application_id;
            $revised_basic_amount = $request_details->pensioner_basic_amount;
            $ppo_no = $request_details->ppo_no;
            if($application_type == 2 && ($pensioner_type == 1 || $pensioner_type == 2)){
                // Existing User Details
                $pension_details = DB::table('optcl_existing_user')
                                        ->where('id', $application_id)
                                        ->where('status', 1)
                                        ->where('deleted', 0)
                                        ->first();
                $current_date_value = date('Y-m-d');
                $dataTI = DB::table('optcl_ti_master')
                    ->whereDate('start_date','<=', $current_date_value)
                    ->whereDate('end_date','>=', $current_date_value)
                    //->whereBetween(DB::raw($current_date_value), ['start_date', 'end_date'])
                    ->first();
                //dd($dataTI);
                $ti_percentage = $dataTI->da_rate;
                $ti_amount = ($revised_basic_amount/100)*$ti_percentage;
                $existing_user_id = $pension_details->id;
                // Commutation Amount
                $commutation_amount = DB::table('optcl_existing_user_commutation')
                                        ->select(DB::raw('SUM(commutation_amount) AS commutation_total_amount'))
                                        ->where('existing_user_id', $existing_user_id)
                                        ->where('status', 1)
                                        ->where('deleted', 0)
                                        ->first();
                $commutation_total_amount = $commutation_amount->commutation_total_amount;
                $gross_amount = $revised_basic_amount + $ti_amount;
                $total_income = (($revised_basic_amount + $ti_amount)-$commutation_total_amount)*12;
                /* echo "Revised Pension Amount - ".$revised_basic_amount;
                echo "<br>";
                echo "TI Amount - ".$ti_amount;
                echo "<br>";
                echo "Commutation Amount - ".$commutation_total_amount;
                echo "<br>";
                echo "Total Amount - ". $total_income; */
                $user_type = "existing_user";
            }else{
                // New User Details
                $user_type = "new_user";
            }
            Session::put('application_type', $application_type);
            Session::put('pensioner_type', $pensioner_type);
            Session::put('application_id', $application_id);
            Session::put('gross_amount', $gross_amount);
            Session::put('total_income', $total_income);
            Session::put('ppo_no', $ppo_no);
            Session::put('ti_percentage', $ti_percentage);
            Session::put('ti_amount', $ti_amount);

            // For Revision of Besic Pension Table Data Update
            Session::put('revised_data_id', $request_details->id);
            // Specify User Type
            Session::put('user_type', $user_type);

            return redirect()->route('pension_unit_tds_information_form_page');
        }else{
            Session::flash('error', 'No data found');          
            return redirect()->route('pension_unit_update_pension_record');
        }
    } 
    // View Page    
    public function revision_basic_pension_view_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_revision_basic_pension')
                                            ->where('id', $cr_application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.revision_basic_pension.view', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Bank Change ------------------ */
    // From Submission
    public function bank_change_submission(Request $request){
        $validation = [];
        $bc_ppo_number = $request->bc_ppo_number;
        if($bc_ppo_number == ""){
            $validation['error'][] = array("id" => "bc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $bc_pension_emp_no = $request->bc_pension_emp_no;
        if($bc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "bc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $bc_name_pensioner = $request->bc_name_pensioner;
        if($bc_name_pensioner == ""){
            $validation['error'][] = array("id" => "bc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $bc_savings_bank_ac_no = $request->bc_savings_bank_ac_no;
        if($bc_savings_bank_ac_no == ""){
            $validation['error'][] = array("id" => "bc_savings_bank_ac_no-error","eValue" => "Please enter savings bank A/C no");
        }
        $bc_bank_name = $request->bc_bank_name;
        if($bc_bank_name == ""){
            $validation['error'][] = array("id" => "bc_bank_name-error","eValue" => "Please select bank");
        }
        $bc_branch_name_address = $request->bc_branch_name_address;
        if($bc_branch_name_address == ""){
            $validation['error'][] = array("id" => "bc_branch_name_address-error","eValue" => "Please select branch");
        }
        $bc_ifsc_code = $request->bc_ifsc_code;
        if($bc_ifsc_code == ""){
            $validation['error'][] = array("id" => "bc_ifsc_code-error","eValue" => "Please enter ifsc code");
        }
        $bc_noc_previous_bank = $request->bc_noc_previous_bank;
        if($bc_noc_previous_bank == ""){
            $validation['error'][] = array("id" => "bc_noc_previous_bank-error","eValue" => "Please select NOC from previous bank");
        }
        $upload_path = 'uploads/bank_change_noc_attachment/';
        if($request->hasFile('noc_previous_bank_attachment')) {
            $filename = Util::rand_filename($request->file('noc_previous_bank_attachment')->getClientOriginalExtension());
            $noc_previous_bank_attachment = Util::upload_file($request->file('noc_previous_bank_attachment'), $filename, null, $upload_path);
        } else {
            $validation['error'][] = array("id" => "noc_previous_bank_attachment-error","eValue" => "Please upload NOC document");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->bank_change_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $bc_ppo_number,
                    "pensioner_emp_no" => $bc_pension_emp_no,
                    "pensioner_name" =>  $bc_name_pensioner,
                    "sb_acc_no" => $bc_savings_bank_ac_no,
                    "bank_branch_id" => $bc_branch_name_address,
                    "noc_from_pre_bank" => $bc_noc_previous_bank,
                    "noc_document" => $upload_path.$filename,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_bank_change')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function bank_change_edit_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
            $request_details = DB::table('optcl_change_data_bank_change')
                                        ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id','=', 'optcl_change_data_bank_change.bank_branch_id')
                                        ->select('optcl_change_data_bank_change.*', 'optcl_bank_branch_master.bank_id','optcl_bank_branch_master.branch_name', 'optcl_bank_branch_master.ifsc_code')
                                        ->where('optcl_change_data_bank_change.id', $cr_application_id)
                                        ->where('optcl_change_data_bank_change.status', 1)
                                        ->where('optcl_change_data_bank_change.deleted', 0)
                                        ->first();
            $bank_id = $request_details->bank_id;
            $branchs = DB::table('optcl_bank_branch_master')
                            ->where('bank_id', $bank_id)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->get();
            //dd($branchs);
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.bank_change_edit', compact('request_details','banks','branchs'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Form Submission
    public function bank_change_edit_submission(Request $request){
        $validation = [];
        $bc_ppo_number = $request->bc_ppo_number;
        if($bc_ppo_number == ""){
            $validation['error'][] = array("id" => "bc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $bc_pension_emp_no = $request->bc_pension_emp_no;
        if($bc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "bc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $bc_name_pensioner = $request->bc_name_pensioner;
        if($bc_name_pensioner == ""){
            $validation['error'][] = array("id" => "bc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $bc_savings_bank_ac_no = $request->bc_savings_bank_ac_no;
        if($bc_savings_bank_ac_no == ""){
            $validation['error'][] = array("id" => "bc_savings_bank_ac_no-error","eValue" => "Please enter savings bank A/C no");
        }
        $bc_bank_name = $request->bc_bank_name;
        if($bc_bank_name == ""){
            $validation['error'][] = array("id" => "bc_bank_name-error","eValue" => "Please select bank");
        }
        $bc_branch_name_address = $request->bc_branch_name_address;
        if($bc_branch_name_address == ""){
            $validation['error'][] = array("id" => "bc_branch_name_address-error","eValue" => "Please select branch");
        }
        $bc_ifsc_code = $request->bc_ifsc_code;
        if($bc_ifsc_code == ""){
            $validation['error'][] = array("id" => "bc_ifsc_code-error","eValue" => "Please enter ifsc code");
        }
        $bc_noc_previous_bank = $request->bc_noc_previous_bank;
        if($bc_noc_previous_bank == ""){
            $validation['error'][] = array("id" => "bc_noc_previous_bank-error","eValue" => "Please select NOC from previous bank");
        }
        $upload_path = 'uploads/bank_change_noc_attachment/';
        if($request->hasFile('noc_previous_bank_attachment')) {
            $filename = Util::rand_filename($request->file('noc_previous_bank_attachment')->getClientOriginalExtension());
            $noc_previous_bank_attachment = Util::upload_file($request->file('noc_previous_bank_attachment'), $filename, null, $upload_path);
            $path_filename = $upload_path.$filename;
        }  else {
            $path_filename = $request->hidden_noc_previous_bank_attachment;
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->bank_change_changed_type_id;
        $bc_id_value = $request->bc_id_value;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $bc_ppo_number,
                    "pensioner_emp_no" => $bc_pension_emp_no,
                    "pensioner_name" =>  $bc_name_pensioner,
                    "sb_acc_no" => $bc_savings_bank_ac_no,
                    "bank_branch_id" => $bc_branch_name_address,
                    "noc_from_pre_bank" => $bc_noc_previous_bank,
                    "noc_document" => $path_filename,
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_bank_change')
                                    ->where('id', $bc_id_value)
                                    ->update($data);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function bank_change_view_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $banks = DB::table('optcl_bank_master')->where('status', 1)->where('deleted', 0)->get();
            $request_details = DB::table('optcl_change_data_bank_change')
                                        ->join('optcl_bank_branch_master', 'optcl_bank_branch_master.id','=', 'optcl_change_data_bank_change.bank_branch_id')
                                        ->join('optcl_bank_master', 'optcl_bank_master.id', '=', 'optcl_bank_branch_master.bank_id')
                                        ->select('optcl_change_data_bank_change.*', 'optcl_bank_branch_master.bank_id','optcl_bank_branch_master.branch_name', 'optcl_bank_branch_master.ifsc_code', 'optcl_bank_master.bank_name')
                                        ->where('optcl_change_data_bank_change.id', $cr_application_id)
                                        ->where('optcl_change_data_bank_change.status', 1)
                                        ->where('optcl_change_data_bank_change.deleted', 0)
                                        ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.bank_change_view', compact('request_details','banks'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Dropped Case/Death Case ------------------ */
    // Form Submission
    public function dropped_case_death_case_submission(Request $request){
        $validation = [];
        $dcdc_ppo_number = $request->dcdc_ppo_number;
        if($dcdc_ppo_number == ""){
            $validation['error'][] = array("id" => "dcdc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $dcdc_pension_emp_no = $request->dcdc_pension_emp_no;
        if($dcdc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "dcdc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $dcdc_name_pensioner = $request->dcdc_name_pensioner;
        if($dcdc_name_pensioner == ""){
            $validation['error'][] = array("id" => "dcdc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $dcdc_dod = $request->dcdc_dod;
        if($dcdc_dod == ""){
            $validation['error'][] = array("id" => "dcdc_dod-error","eValue" => "Please select date of death");
        }
        $dcdc_remark = $request->dcdc_remark;
        if($dcdc_remark == ""){
            $validation['error'][] = array("id" => "dcdc_remark-error","eValue" => "Please enter remark");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->dropped_case_death_case_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $dcdc_ppo_number,
                    "pensioner_emp_no" => $dcdc_pension_emp_no,
                    "pensioner_name" =>  $dcdc_name_pensioner,
                    "dod" => date('Y-m-d', strtotime(str_replace("/", "-", $dcdc_dod))),
                    "remark_value" => $dcdc_remark,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_dropped_death_case')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function dropped_case_death_case_edit_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_dropped_death_case')
                                        ->where('optcl_change_data_dropped_death_case.id', $cr_application_id)
                                        ->where('optcl_change_data_dropped_death_case.status', 1)
                                        ->where('optcl_change_data_dropped_death_case.deleted', 0)
                                        ->first();
            //dd($branchs);
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.dropped_case_death_case_edit', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Form Submission
    public function dropped_case_death_case_edit_submission(Request $request){
        $validation = [];
        $dcdc_ppo_number = $request->dcdc_ppo_number;
        if($dcdc_ppo_number == ""){
            $validation['error'][] = array("id" => "dcdc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $dcdc_pension_emp_no = $request->dcdc_pension_emp_no;
        if($dcdc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "dcdc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $dcdc_name_pensioner = $request->dcdc_name_pensioner;
        if($dcdc_name_pensioner == ""){
            $validation['error'][] = array("id" => "dcdc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $dcdc_dod = $request->dcdc_dod;
        if($dcdc_dod == ""){
            $validation['error'][] = array("id" => "dcdc_dod-error","eValue" => "Please select date of death");
        }
        $dcdc_remark = $request->dcdc_remark;
        if($dcdc_remark == ""){
            $validation['error'][] = array("id" => "dcdc_remark-error","eValue" => "Please enter remark");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->dropped_case_death_case_changed_type_id;
        $cr_application_id = $request->cr_application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $dcdc_ppo_number,
                    "pensioner_emp_no" => $dcdc_pension_emp_no,
                    "pensioner_name" =>  $dcdc_name_pensioner,
                    "dod" => date('Y-m-d', strtotime(str_replace("/", "-", $dcdc_dod))),
                    "remark_value" => $dcdc_remark,
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_dropped_death_case')
                                    ->where('id', $cr_application_id)
                                    ->update($data);               

                Session::flash('success','Data updated successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function dropped_case_death_case_view_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_data_dropped_death_case')
                                        ->where('optcl_change_data_dropped_death_case.id', $cr_application_id)
                                        ->where('optcl_change_data_dropped_death_case.status', 1)
                                        ->where('optcl_change_data_dropped_death_case.deleted', 0)
                                        ->first();
            //dd($branchs);
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.dropped_case_death_case_view', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Restoration of Commutation ------------------ */
    // Form Submission
    public function restoration_commutation_submission(Request $request){
        $validation = [];
        $rc_ppo_number = $request->rc_ppo_number;
        if($rc_ppo_number == ""){
            $validation['error'][] = array("id" => "rc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $rc_pension_emp_no = $request->rc_pension_emp_no;
        if($rc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "rc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $rc_name_pensioner = $request->rc_name_pensioner;
        if($rc_name_pensioner == ""){
            $validation['error'][] = array("id" => "rc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $rc_rcv_comm_amt = $request->rc_rcv_comm_amt;
        if($rc_rcv_comm_amt == ""){
            $validation['error'][] = array("id" => "rc_rcv_comm_amt-error","eValue" => "Please enter receive commutation amount");
        }
        $rc_dor = $request->rc_dor;
        if($rc_dor == ""){
            $validation['error'][] = array("id" => "rc_dor-error","eValue" => "Please select date of restoration");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->restoration_commutation_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $rc_ppo_number,
                    "pensioner_emp_no" => $rc_pension_emp_no,
                    "pensioner_name" =>  $rc_name_pensioner,
                    "rev_comm_amount" => $rc_rcv_comm_amt,
                    "date_restoration" => date('Y-m-d', strtotime(str_replace("/", "-", $rc_dor))),
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_changed_data_restoration_commutation')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function restoration_commutation_edit_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_changed_data_restoration_commutation')
                                        ->where('optcl_changed_data_restoration_commutation.id', $cr_application_id)
                                        ->where('optcl_changed_data_restoration_commutation.status', 1)
                                        ->where('optcl_changed_data_restoration_commutation.deleted', 0)
                                        ->first();
            //dd($branchs);
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.restoration_commutation_edit', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Edit Form Submission
    public function restoration_commutation_edit_submission(Request $request){
        $validation = [];
        $rc_ppo_number = $request->rc_ppo_number;
        if($rc_ppo_number == ""){
            $validation['error'][] = array("id" => "rc_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $rc_pension_emp_no = $request->rc_pension_emp_no;
        if($rc_pension_emp_no == ""){
            $validation['error'][] = array("id" => "rc_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $rc_name_pensioner = $request->rc_name_pensioner;
        if($rc_name_pensioner == ""){
            $validation['error'][] = array("id" => "rc_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $rc_rcv_comm_amt = $request->rc_rcv_comm_amt;
        if($rc_rcv_comm_amt == ""){
            $validation['error'][] = array("id" => "rc_rcv_comm_amt-error","eValue" => "Please enter receive commutation amount");
        }
        $rc_dor = $request->rc_dor;
        if($rc_dor == ""){
            $validation['error'][] = array("id" => "rc_dor-error","eValue" => "Please select date of restoration");
        }
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->restoration_commutation_changed_type_id;
        $cr_application_id = $request->cr_application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                $data = [
                    "ppo_no" => $rc_ppo_number,
                    "pensioner_emp_no" => $rc_pension_emp_no,
                    "pensioner_name" =>  $rc_name_pensioner,
                    "rev_comm_amount" => $rc_rcv_comm_amt,
                    "date_restoration" => date('Y-m-d', strtotime(str_replace("/", "-", $rc_dor))),
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_changed_data_restoration_commutation')
                                    ->where('id', $cr_application_id)
                                    ->update($data);            

                Session::flash('success','Data updated successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function restoration_commutation_view_page($appID){
        //dd(1);
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_changed_data_restoration_commutation')
                                        ->where('optcl_changed_data_restoration_commutation.id', $cr_application_id)
                                        ->where('optcl_changed_data_restoration_commutation.status', 1)
                                        ->where('optcl_changed_data_restoration_commutation.deleted', 0)
                                        ->first();
            //dd($branchs);
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.restoration_commutation_view', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ Unit Change for Receiving Unit (Only) ------------------ */
    // Form Submission
    public function unit_change_receiving_unit_only_submission(Request $request){
        $validation = [];
        $ucruo_ppo_number = $request->ucruo_ppo_number;
        if($ucruo_ppo_number == ""){
            $validation['error'][] = array("id" => "ucruo_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $ucruo_pension_emp_no = $request->ucruo_pension_emp_no;
        if($ucruo_pension_emp_no == ""){
            $validation['error'][] = array("id" => "ucruo_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $ucruo_name_pensioner = $request->ucruo_name_pensioner;
        if($ucruo_name_pensioner == ""){
            $validation['error'][] = array("id" => "ucruo_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $urcuo_name_prev_pension_unit = $request->urcuo_name_prev_pension_unit;
        if($urcuo_name_prev_pension_unit == ""){
            $validation['error'][] = array("id" => "urcuo_name_prev_pension_unit-error","eValue" => "Please select name of prev. pension unit");
        }
        $urcuo_name_new_pension_unit = $request->urcuo_name_new_pension_unit;
        if($urcuo_name_new_pension_unit == ""){
            $validation['error'][] = array("id" => "urcuo_name_new_pension_unit-error","eValue" => "Please select name of new pension unit");
        }        
        $upload_path = 'uploads/letter_no_for_above_changes/';
        if($request->hasFile('ucruo_letter_no_above_changes')) {
            //dd($request->file('ucruo_letter_no_above_changes'));
            $filename = Util::rand_filename($request->file('ucruo_letter_no_above_changes')->getClientOriginalExtension());
            $ucruo_letter_no_above_changes = Util::upload_file($request->file('ucruo_letter_no_above_changes'), $filename, null, $upload_path);
        } else {
            $validation['error'][] = array("id" => "ucruo_letter_no_above_changes-error","eValue" => "Please upload letter no for above changes");
        }
        $ucruo_date_for_above_changes = $request->ucruo_date_for_above_changes;
        if($ucruo_date_for_above_changes == ""){
            $validation['error'][] = array("id" => "ucruo_date_for_above_changes-error","eValue" => "Please select date for above changes");
        }
        
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->unit_change_receiving_unit_only_changed_type_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                //dd($filename);
                $data = [
                    "ppo_no" => $ucruo_ppo_number,
                    "pensioner_emp_no" => $ucruo_pension_emp_no,
                    "pensioner_name" =>  $ucruo_name_pensioner,
                    "urcuo_name_prev_pension_unit" => $urcuo_name_prev_pension_unit,
                    "urcuo_name_new_pension_unit" => $urcuo_name_new_pension_unit,
                    "ucruo_letter_no_above_changes" => $upload_path.$filename,
                    "ucruo_date_for_above_changes" => date('Y-m-d', strtotime(str_replace("/", "-", $ucruo_date_for_above_changes))),
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_unit_change_receiving_unit_only')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Page
    public function unit_change_receiving_unit_only_edit_page($appID){
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            DB::enableQueryLog();
            $request_details = DB::table('optcl_change_unit_change_receiving_unit_only')
                                        ->where('optcl_change_unit_change_receiving_unit_only.id', $cr_application_id)
                                        ->where('optcl_change_unit_change_receiving_unit_only.status', 1)
                                        ->where('optcl_change_unit_change_receiving_unit_only.deleted', 0)
                                        ->first();
            //dd(DB::getQueryLog(), $request_details);
            //dd($request_details, $cr_application_id);
            $pension_units = DB::table('optcl_pension_unit_master')->where('status', 1)->where('deleted', 0)->get();
            if($request_details){
                return view('user.pension_unit.update_pages.unit_change_receiving_unit_only_edit', compact('request_details', 'pension_units'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    // Form Submission
    public function unit_change_receiving_unit_only_edit_submission(Request $request){
        $validation = [];
        $ucruo_ppo_number = $request->ucruo_ppo_number;
        if($ucruo_ppo_number == ""){
            $validation['error'][] = array("id" => "ucruo_ppo_number-error","eValue" => "Please enter PPO no");
        }
        $ucruo_pension_emp_no = $request->ucruo_pension_emp_no;
        if($ucruo_pension_emp_no == ""){
            $validation['error'][] = array("id" => "ucruo_pension_emp_no-error","eValue" => "Please enter employee no");
        }
        $ucruo_name_pensioner = $request->ucruo_name_pensioner;
        if($ucruo_name_pensioner == ""){
            $validation['error'][] = array("id" => "ucruo_name_pensioner-error","eValue" => "Please enter pensioner name");
        }
        $urcuo_name_prev_pension_unit = $request->urcuo_name_prev_pension_unit;
        if($urcuo_name_prev_pension_unit == ""){
            $validation['error'][] = array("id" => "urcuo_name_prev_pension_unit-error","eValue" => "Please select name of prev. pension unit");
        }
        $urcuo_name_new_pension_unit = $request->urcuo_name_new_pension_unit;
        if($urcuo_name_new_pension_unit == ""){
            $validation['error'][] = array("id" => "urcuo_name_new_pension_unit-error","eValue" => "Please select name of new pension unit");
        }        
        $upload_path = 'uploads/letter_no_for_above_changes/';
        if($request->hasFile('ucruo_letter_no_above_changes')) {
            //dd($request->file('ucruo_letter_no_above_changes'));
            $filename = Util::rand_filename($request->file('ucruo_letter_no_above_changes')->getClientOriginalExtension());
            $ucruo_letter_no_above_changes = Util::upload_file($request->file('ucruo_letter_no_above_changes'), $filename, null, $upload_path);
            $path_filename = $upload_path.$filename;
        } else {
            $path_filename = $request->hidden_ucruo_letter_no_above_changes;
        }
        $ucruo_date_for_above_changes = $request->ucruo_date_for_above_changes;
        if($ucruo_date_for_above_changes == ""){
            $validation['error'][] = array("id" => "ucruo_date_for_above_changes-error","eValue" => "Please select date for above changes");
        }
        
        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = $request->unit_change_receiving_unit_only_changed_type_id;
        $cr_application_id = $request->cr_application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                //dd($filename);
                $data = [
                    "ppo_no" => $ucruo_ppo_number,
                    "pensioner_emp_no" => $ucruo_pension_emp_no,
                    "pensioner_name" =>  $ucruo_name_pensioner,
                    "urcuo_name_prev_pension_unit" => $urcuo_name_prev_pension_unit,
                    "urcuo_name_new_pension_unit" => $urcuo_name_new_pension_unit,
                    "ucruo_letter_no_above_changes" => $path_filename,
                    "ucruo_date_for_above_changes" => date('Y-m-d', strtotime(str_replace("/", "-", $ucruo_date_for_above_changes))),
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_unit_change_receiving_unit_only')
                                        ->where('id', $cr_application_id)
                                        ->update($data);
                
                Session::flash('success','Data updated successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function unit_change_receiving_unit_only_view_page($appID){
        $cr_data = DB::table('optcl_change_data_list')
                        ->where('id', $appID)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->first();
        if($cr_data){
            $cr_application_id = $cr_data->cr_application_id;
            $request_details = DB::table('optcl_change_unit_change_receiving_unit_only AS a')
                                        ->join('optcl_pension_unit_master AS previous_unit', 'previous_unit.id', '=', 'a.urcuo_name_prev_pension_unit')
                                        ->join('optcl_pension_unit_master AS new_unit', 'new_unit.id', '=', 'a.urcuo_name_new_pension_unit')
                                        ->select('a.*', 'previous_unit.pension_unit_name AS pre_name', 'new_unit.pension_unit_name AS new_name')
                                        ->where('a.id', $cr_application_id)
                                        ->where('a.status', 1)
                                        ->where('a.deleted', 0)
                                        ->first();
            //dd($request_details, $cr_application_id);
            if($request_details){
                return view('user.pension_unit.update_pages.unit_change_receiving_unit_only_view', compact('request_details'));
            }else{
                //dd(2);
                Session::flash('error', 'No data found');          
                return redirect()->route('pension_unit_update_pension_record');
            }
        }else{
            Session::flash('error', 'No data found');            
            return redirect()->route('pension_unit_update_pension_record');
        }
    }
    /* ------------------ TDS Information ------------------ */
    // Listing
    public function tds_information_list_page(){
        // Following sessions are initiated at the time 'Revision of Basic Pension'
        Session::forget(['application_type', 'pensioner_type', 'application_id', 'gross_amount', 'total_income', 'ppo_no', 'ti_percentage', 'ti_amount']);
        $applications = DB::table('optcl_change_data_tds_information AS t')
                        ->join('optcl_change_data_list AS c', function($join)
                        {
                          $join->on('c.cr_application_id', '=', 't.id');
                          $join->on('c.change_data_id', '=', DB::raw(8));                       
                        })
                        ->leftJoin('optcl_existing_user AS e', function($join2)
                        {
                          $join2->on('e.id', '=', 't.application_id');
                          $join2->on('e.application_type', '=', DB::raw(2));                       
                        })
                        ->leftJoin('optcl_pension_application_form AS p', function($join2)
                        {
                          $join2->on('p.id', '=', 't.application_id');
                          $join2->on('e.application_type', '=', DB::raw(1));                       
                        })
                        ->join('optcl_application_status_master AS s', 's.id', '=', 'c.status_id')
                        ->select('t.*', 'c.cr_number', 'e.pensioner_name', 's.status_name',DB::raw('IF(t.application_type = 2, e.new_ppo_no, p.ppo_number) AS my_ppo_no'))
                        ->where('t.status', 1)
                        ->where('t.deleted', 0)
                        ->paginate(10);
        return view('user.pension_unit.update_pages.tds.list', compact('applications'));
    }
    // TDS Form
    public function tds_information_form_page(){
        return view('user.pension_unit.update_pages.tds.add');
    }
    // Get PPO no details
    public function get_data_from_ppo_no(Request $request){
        $ppo_no = $request->input('tds_info_ppo_no');
        //dd($ppo_no);
        $ppo_status = DB::table('optcl_ppo_no_list')
                            ->where('ppo_no', $ppo_no)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->first();
        if($ppo_status){
            $pensioner_type_id = $ppo_status->pensioner_type_id;
            $application_id = $ppo_status->application_id;
            if($pensioner_type_id  == 1 || $pensioner_type_id  == 2){
                // New User - Service Pensioner/ Family Pensioner
                $pernsioner_details = DB::table('optcl_pension_application_form')
                                            ->where('id', $application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
                $response = [
                                'status' => 'success',
                                'message_value' => 'Data fetched successfully',
                                'data' => [
                                    'total_income_amount' => $pernsioner_details->total_income,
                                    'application_id' => $pernsioner_details->id,
                                    'pensioner_type_id' => $pernsioner_details->pensioner_type_id,
                                    'application_type_id' => $pernsioner_details->application_type,
                                    ]
                            ];
            }else{
                // Existing User - Service Pensioner/ Family Pensioner
                $pernsioner_details = DB::table('optcl_existing_user')
                                            ->where('id', $application_id)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->first();
                $response = [
                                'status' => 'success',
                                'message_value' => 'Data fetched successfully',
                                'data' => [
                                    'total_income_amount' => $pernsioner_details->total_income,
                                    'application_id' => $pernsioner_details->id,
                                    'pensioner_type_id' => $pernsioner_details->pensioner_type,
                                    'application_type_id' => $pernsioner_details->application_type,
                                    ]
                            ];
            }
        }else{
            $response = [
                'status' => 'error',
                'message_value' => 'PPO no not found',
                'data' => []
            ];
        }
        return response()->json($response);
    }
    // TDS Form Submission
    public function tds_information_form_submission(Request $request){
        $validation = [];
        $tds_info_ppo_no = $request->tds_info_ppo_no;
        if($tds_info_ppo_no == ""){
            $validation['error'][] = array("id" => "tds_info_ppo_no-error","eValue" => "Please enter PPO no");
        }
        $tds_info_total_income = $request->tds_info_total_income;
        if($tds_info_total_income == ""){
            $validation['error'][] = array("id" => "tds_info_total_income-error","eValue" => "PPO no not found");
        }
        $amount_80c = $request->amount_80c;
        if($amount_80c == ""){
            $validation['error'][] = array("id" => "amount_80c-error","eValue" => "Please enter 80C (LIC/ PPFA/ HB Principal) amount");
        }
        $amount_80d = $request->amount_80d;
        if($amount_80d == ""){
            $validation['error'][] = array("id" => "amount_80d-error","eValue" => "Please select 80D (Health Insurance) amount");
        }
        $amount_8dd = $request->amount_8dd;
        if($amount_8dd == ""){
            $validation['error'][] = array("id" => "amount_8dd-error","eValue" => "Please select 80DD (Dependent Disability) amount");
        }
        $amount_80e = $request->amount_80e;
        if($amount_80e == ""){
            $validation['error'][] = array("id" => "amount_80e-error","eValue" => "Please enter 80E (Higher Education Interest) amount");
        }
        $amount_80u = $request->amount_80u;
        if($amount_80u == ""){
            $validation['error'][] = array("id" => "amount_80u-error","eValue" => "Please select 80U (Self Disability) amount");
        }
        $amount_24b = $request->amount_24b;
        if($amount_24b == ""){
            $validation['error'][] = array("id" => "amount_24b-error","eValue" => "24B (House Building Interest) amount");
        }
        $others_amount = $request->others_amount;
        if($others_amount == ""){
            $validation['error'][] = array("id" => "others_amount-error","eValue" => "Please enter others amount");
        }
        $taxable_amount = $request->taxable_amount;
        if($taxable_amount == ""){
            $validation['error'][] = array("id" => "taxable_amount-error","eValue" => "Please fill all fileds");
        }
        $declaration_status = $request->declaration_status;
        if($declaration_status == ""){
            $validation['error'][] = array("id" => "declaration_status-error","eValue" => "Please check the declaration");
        }
        $pensioner_type_id = $request->pensioner_type_id;
        $application_type_id = $request->application_type_id;

        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = 8;
        $cr_application_id = $request->application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                //dd($filename);
                // TDS table update in of Revision of Besic Pension
                if(Session::has('ppo_no') && Session::has('application_type') && Session::has('pensioner_type') && Session::has('application_id') && Session::has('gross_amount') && Session::has('total_income') && Session::has('revised_data_id')){
                    $revised_data_id = Session::get('revised_data_id');
                    $gross_amount = Session::get('gross_amount');
                    $revised_data_id = Session::get('revised_data_id');
                    $application_type = Session::get('application_type');
                    $pensioner_type = Session::get('pensioner_type');
                    $total_income = Session::get('total_income');
                    $revisedData = DB::table('optcl_change_data_revision_basic_pension')->where('id', $revised_data_id)->where('status', 1)->where('deleted', 0)->first();
                    if($revisedData){
                        $application_id = $revisedData->application_id;
                        // Pensioner Details
                        $pensioner_data = DB::table('optcl_existing_user')->where('id', $application_id)->where('status', 1)->where('deleted', 0)->first();
                        $rID = $revisedData->id;
                        $ti_amount = Session::get('ti_percentage');
                        $ti_percentage = Session::get('ti_amount');
                        $basic_amount = $revisedData->pensioner_basic_amount;
                        $basic_effective_date = $revisedData->oo_no_date;
                        $gross_pension_amount = $gross_amount;
                        $total_amount = $total_income;
                        
                        // Update Revision of Basic Pension Table
                        DB::table('optcl_change_data_revision_basic_pension')->where('id', $rID)->update(['is_taxable_amount_submitted' => 1]);
                        if(Session::get('user_type') == 'existing_user'){
                            // Update Original Data
                            $revised_data = [
                                'ti_amount' => $ti_amount,
                                'ti_percentage' => $ti_percentage,
                                'basic_amount' => $basic_amount,
                                'basic_effective_date' => $basic_effective_date,
                                'gross_pension_amount' => $gross_pension_amount,
                                'total_income' => $total_amount,
                            ];
                            DB::table('optcl_existing_user')
                                ->where('id', $application_id)
                                ->update($revised_data);
                            // Store in Monthly changed Data
                            $monthly_changed_data_revised_pension = [
                                'appliation_type' => $application_type,
                                'pensioner_type' => $pensioner_type,
                                'is_changed_request' => 1,
                                'cr_type_id' => 2,
                                'application_id' => $application_id,
                                'pension_unit_id' => Auth::user()->pension_unit_id,
                                'created_by' => Auth::user()->id,
                                'created_at' => $this->current_date,
                            ];
                            DB::table('optcl_monthly_changed_data')->insert($monthly_changed_data_revised_pension);
                        }else if(Session::has('ppo_no') && Session::get('user_type') == 'new_user'){
                            // New User
                        }

                    }
                }

                $data = [
                    "application_id" => $cr_application_id,
                    "pensioner_type" => $pensioner_type_id,
                    "application_type" =>  $application_type_id,
                    "ppo_no" => $tds_info_ppo_no,
                    "total_income" => $tds_info_total_income,
                    "amount_80c" => $amount_80c,
                    "amount_80d" => $amount_80d,
                    "amount_8dd" => $amount_8dd,
                    "amount_80e" => $amount_80e,
                    "amount_80u" => $amount_80u,
                    "amount_24b" => $amount_24b,
                    "others_amount" => $others_amount,
                    "taxable_amount" => $taxable_amount,
                    "declaration_status" => $declaration_status,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $change_data_id = DB::table('optcl_change_data_tds_information')->insertGetId($data);
                // Store in Changed Data List
                $data_1 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_application_id" => $change_data_id,
                    "status_id" => 59,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                $data_1_id = DB::table('optcl_change_data_list')->insertGetId($data_1);
                // Update Change Request Number
                $cr_number = 'CR'.date('Y').sprintf('%05d',$data_1_id);
                $update_cr_number = ['cr_number' => $cr_number];
                DB::table('optcl_change_data_list')->where('id', $data_1_id)->update($update_cr_number);
                // Status History
                $data_2 = [
                    "change_data_id" => $cr_changed_type_id,
                    "cr_status_id" => 59,
                    "cr_application_id" => $change_data_id,
                    "created_by" => Auth::user()->id,
                    "created_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_status_history')->insertGetId($data_2);

                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // Edit Form
    public function tds_information_edit_form_page($appID){
        $request_details = DB::table('optcl_change_data_tds_information')
                                    ->where('id', $appID)
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        if($request_details){
            return view('user.pension_unit.update_pages.tds.edit', compact('request_details'));
        }else{
            //dd(2);
            Session::flash('error', 'No data found');          
            return redirect()->route('pension_unit_tds_information_list_page');
        }
        //return view('user.pension_unit.update_pages.tds.edit');
    }
    // Edit Form Submission
    public function tds_information_form_edit_submission(Request $request){
        $validation = [];
        $tds_info_ppo_no = $request->tds_info_ppo_no;
        if($tds_info_ppo_no == ""){
            $validation['error'][] = array("id" => "tds_info_ppo_no-error","eValue" => "Please enter PPO no");
        }
        $tds_info_total_income = $request->tds_info_total_income;
        if($tds_info_total_income == ""){
            $validation['error'][] = array("id" => "tds_info_total_income-error","eValue" => "PPO no not found");
        }
        $amount_80c = $request->amount_80c;
        if($amount_80c == ""){
            $validation['error'][] = array("id" => "amount_80c-error","eValue" => "Please enter 80C (LIC/ PPFA/ HB Principal) amount");
        }
        $amount_80d = $request->amount_80d;
        if($amount_80d == ""){
            $validation['error'][] = array("id" => "amount_80d-error","eValue" => "Please select 80D (Health Insurance) amount");
        }
        $amount_8dd = $request->amount_8dd;
        if($amount_8dd == ""){
            $validation['error'][] = array("id" => "amount_8dd-error","eValue" => "Please select 80DD (Dependent Disability) amount");
        }
        $amount_80e = $request->amount_80e;
        if($amount_80e == ""){
            $validation['error'][] = array("id" => "amount_80e-error","eValue" => "Please enter 80E (Higher Education Interest) amount");
        }
        $amount_80u = $request->amount_80u;
        if($amount_80u == ""){
            $validation['error'][] = array("id" => "amount_80u-error","eValue" => "Please select 80U (Self Disability) amount");
        }
        $amount_24b = $request->amount_24b;
        if($amount_24b == ""){
            $validation['error'][] = array("id" => "amount_24b-error","eValue" => "24B (House Building Interest) amount");
        }
        $others_amount = $request->others_amount;
        if($others_amount == ""){
            $validation['error'][] = array("id" => "others_amount-error","eValue" => "Please enter others amount");
        }
        $taxable_amount = $request->taxable_amount;
        if($taxable_amount == ""){
            $validation['error'][] = array("id" => "taxable_amount-error","eValue" => "Please fill all fileds");
        }
        
        $pensioner_type_id = $request->pensioner_type_id;
        $application_type_id = $request->application_type_id;
        $cr_tds_application_id = $request->cr_tds_application_id;

        // Check PPO number with other details
            /* ---------Code---------- */
        $cr_changed_type_id = 8;
        $cr_application_id = $request->application_id;
        if(!isset($validation['error'])){
            try{
                DB::beginTransaction();
                //dd($filename);
                $data = [
                    "application_id" => $cr_application_id,
                    "pensioner_type" => $pensioner_type_id,
                    "application_type" =>  $application_type_id,
                    "ppo_no" => $tds_info_ppo_no,
                    "total_income" => $tds_info_total_income,
                    "amount_80c" => $amount_80c,
                    "amount_80d" => $amount_80d,
                    "amount_8dd" => $amount_8dd,
                    "amount_80e" => $amount_80e,
                    "amount_80u" => $amount_80u,
                    "amount_24b" => $amount_24b,
                    "others_amount" => $others_amount,
                    "taxable_amount" => $taxable_amount,
                    "updated_by" => Auth::user()->id,
                    "updated_at" => $this->current_date,
                ];
                DB::table('optcl_change_data_tds_information')
                                    ->where('id', $cr_tds_application_id)
                                    ->update($data);                    
                Session::flash('success','Data saved successfully');
                DB::commit();         
            }catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            } 
        }
        echo json_encode($validation);
    }
    // View Page
    public function tds_information_view_page($appID){
        $request_details = DB::table('optcl_change_data_tds_information')
                                    ->where('id', $appID)
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        if($request_details){
            return view('user.pension_unit.update_pages.tds.view', compact('request_details'));
        }else{
            //dd(2);
            Session::flash('error', 'No data found');          
            return redirect()->route('pension_unit_tds_information_list_page');
        }
    }
    /* ------------------ Life Certificate ------------------ */
    // Listing
    public function life_certificate_list_page(){    
        $applications = DB::table('optcl_change_data_life_certificate') 
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->paginate(10);    
        return view('user.pension_unit.update_pages.life_certificate.list', compact('applications'));
    }
    // Form Page
    public function life_certificate_form_page(){
        return view('user.pension_unit.update_pages.life_certificate.add');
    }
    // Form Submission
    public function life_certificate_form_submission(Request $request){  

        $file = $request->file('life_certificates_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            //Where uploaded file will be stored on the server 
            $location = 'public/uploads/life_certificate'; //Created an "uploads" folder for that
            // Upload file
            $file->move($location, $filename);

            $location_file = $location.'/'.$filename;
            //$import_file = $request->file('import_file');
            $rows = Excel::toArray(new DataImport, $location_file);
            //dd($rows);
            
            $society = array();
            $dept_map = array();
            
            try {
                
                DB::beginTransaction();
                //dd($rows);
                if (!empty($rows)) {
                    $count_value = 0;
                    $total_imported = 0;
                    $error_value = [];
                    $district_count = 0;
                    $block_count = 0;
                    $department_count = 0;
                    $derectorate_count = 0;
                    $duplicate_society_count = 0;
                    $duplicate_mapping_count = 0;
                    //dd($rows);
                    foreach ($rows[0] as $key => $value) {  
                        //dd($value);
                        
                        $count_value++;
                        $line_no          = $value[0];
                        $ppo_number   = trim($value[1]);
                        $bank_account   = trim($value[2]); // State nodal type
                        $authentication_date      = trim($value[3]);
                        $praman_id         = trim($value[4]);
                        $aadhar_number       = trim($value[5]);
                        $mobile_number       = trim($value[6]);
                        $name       = trim($value[7]);
                        $submit_type       = trim($value[8]);
                        if($line_no == ""){
                            continue;
                        }
                        $data = [
                            "year" => date('Y'),
                            "month" => date('d'),
                            "ppo_number" =>  $ppo_number,
                            "bank_account" => $bank_account,
                            "authentication_date" => date('Y-m-d', strtotime($authentication_date)),
                            "praman_id" => $praman_id,
                            "aadhar_number" => $aadhar_number,
                            "mobile_number" => $mobile_number,
                            "name" => $name,
                            "submit_type" => $submit_type,
                            "created_by" => Auth::user()->id,
                            "created_at" => $this->current_date,
                        ];
                        DB::table('optcl_change_data_life_certificate')->insertGetId($data);                                               
                    }
                }
                
                DB::commit();
                //echo json_encode($upload_details);
                Session::flash('success', 'Data imported successfully');
                //return redirect('/import');
                
            } catch (Exception $ex) {
                DB::rollback();
                dd($ex->getMessage());
            }
        }
    }
    // View Page
    public function life_certificate_view_page($appID){
        $request_details = DB::table('optcl_change_data_life_certificate')
                                    ->where('id', $appID)
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->first();
        if($request_details){
            return view('user.pension_unit.update_pages.life_certificate.view', compact('request_details'));
        }else{
            //dd(2);
            Session::flash('error', 'No data found');          
            return redirect()->route('pension_unit_life_certificate_list_page');
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
                // 
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }

    public function viewPensionUnitUpdatePensionRecord(Request $request,$id){
        $result = DB::table('optcl_update_pension_record')
                    ->where('status',1)
                    ->where('deleted',0)
                    ->where('id',$id)
                    ->first();
        return view('user.pension_unit.update-pension-record-view',compact('result'));
    }
    public function approvePensionUnitUpdatePensionRecord(Request $request,$id){
        //dd($request->all());
        $user_id  = Auth::user()->id;
        $cur_date = date('Y-m-d H:i:s', time());
        $result = DB::table('optcl_update_pension_record')
                    ->where('status',1)
                    ->where('deleted',0)
                    ->where('id',$id)
                    ->update([
                        'user_id'    => $user_id,
                        'is_approved'=> 1,
                        'is_latest'  => 1,
                        'modified_at'=> $cur_date
                        ]);
        $data = DB::table('optcl_update_pension_record')
                    ->where('status',1)
                    ->where('deleted',0)
                    ->where('is_latest',1)
                    ->where('id',$id)
                    ->first();
        //dd($data);
        $employee_id = $data->employee_id;
        $employee_code = $data->employee_code;
        $bank_ac_no  = $data->bank_ac_no;
        $bank_branch_id = $data->bank_branch_id;
        $empdetails = DB::table('optcl_employee_personal_details')
                    ->where('status',1)
                    ->where('deleted',0)
                    ->where('employee_id',$employee_id)
                    ->where('employee_code',$employee_code)
                    ->update([
                        'savings_bank_account_no' => $bank_ac_no,
                        'bank_branch_id'          => $bank_branch_id,
                        'modified_at'             => $cur_date
                        ]);
        
        
        Session::flash('success', 'Applicant update pension record approved successfully');
        return redirect()->route('pension_unit_update_pension_record');
    
    }
}
?>
