<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\Religion;
use App\Models\OfficeLastServed;
use App\Models\PensionerDesignation;
use App\Models\Pensionform;
use App\Models\PensionDocument;
use App\Models\PersonalDetails;
use App\Libraries\Util;
use Session;
use Auth;
use App\Models\TaxDeclaration;
use Datatables;


class TaxDeclarationController extends Controller { 
    
  public function __construct(){
      $this->middleware('auth');
      $this->current_date = date('Y-m-d H:i:s');
      
  }

  public function tax_declaration(){
    $username = Auth::user()->username;
    //dd($username);
    // dd(Auth::check());
      $checkEmployee = DB::table('optcl_pension_application_form')->where('employee_code',Auth::user()->employee_code)->count();
      
      if($checkEmployee < 1){
        Session::flash('error','Please submit your application before tax declaration');
        return redirect()->route('check_employee');
      }
    $empId = Auth::user()->id;
    $empAadhaar = Auth::user()->aadhaar_no;
    return view('user.pension.tax_declaration', compact('username', 'empId', 'empAadhaar'));
  }

  public function submit_tax_declaration(Request $request){
    $is_income_other_pension = $request->is_income_other_pension;
    $emp_id = $request->emp_id;
    $emp_code = $request->emp_code;
    $emp_aadhaar = $request->emp_aadhaar;

    $income_property = $request->income_property;
    $other_income = $request->other_income;
    $lic = $request->lic;
    $nsc = $request->nsc;
    $ppf = $request->ppf;
    $eighty_d = $request->ety_d;
    $eighty_dd = $request->ety_dd;

    $income_property_file_path = '';
    $other_income_file_path = '';
    $lic_file_path = '';
    $nsc_file_path = '';
    $ppf_file_path = '';
    $eighty_d_file_path = '';
    $eighty_dd_file_path = '';

    $upload_income_property_path = 'uploads/tax_declaration/income_property_file/';
    $upload_other_income_path = 'uploads/tax_declaration/other_income_file/';
    $upload_lic_path = 'uploads/tax_declaration/lic_file/';
    $upload_nsc_path = 'uploads/tax_declaration/nsc_file/';
    $upload_ppf_path = 'uploads/tax_declaration/ppf_file/';
    $upload_eighty_d_path = 'uploads/tax_declaration/ety_d_file/';
    $upload_eighty_dd_path = 'uploads/tax_declaration/ety_dd_file/';

   



    //dd($request->all());
    $validation = array();
    if($is_income_other_pension == 1){

      if($income_property == ""){
        $validation['error'][] = array("id" => "income_property_error","eValue" => "Please enter income property");
      }
      
      if($other_income == ""){
          $validation['error'][] = array("id" => "other_income_error","eValue" => "Please enter other income");
      }
      
      if($lic == ""){
          $validation['error'][] = array("id" => "lic_error","eValue" => "Please enter LIC savings");
      }
      
      if($nsc == ""){
          $validation['error'][] = array("id" => "nsc_error","eValue" => "Please enter NSC savings");
      }
      
      if($ppf == ""){
          $validation['error'][] = array("id" => "ppf_error","eValue" => "Please enter PPF savings");
      }
      
      if($eighty_d == ""){
          $validation['error'][] = array("id" => "ety_d_error","eValue" => "Please enter 80 D savings");
      }
      
      if($eighty_dd == ""){
          $validation['error'][] = array("id" => "ety_dd_error","eValue" => "Please enter 80 DD savings");
      }
    }

   
    if(!isset($validation['error'])){
        DB::beginTransaction();
        try {
          
          // $tax_declarationo_exists = DB::table('optcl_tax_declaration_master')->where('emp_id', $emp_id)->first();
          
          $taxDeclaration = new TaxDeclaration();
          // if(!empty($tax_declarationo_exists)){
           
          //   Alert::error('User already has Tax declaration!');
            
          // }else{
            if($is_income_other_pension == 0){
              
              $taxDeclaration->emp_id = $emp_id;
              $taxDeclaration->emp_code = $emp_code;
              $taxDeclaration->aadhaar_no = $emp_aadhaar;
              $taxDeclaration->is_income_other_pension = $is_income_other_pension;
              $taxDeclaration->created_at = $this->current_date;
              $taxDeclaration->is_approved = 0; // 0 for pending TD form , 1 for Approve TD form
              $taxDeclaration->status = 1;
              $taxDeclaration->deleted = 0;
              $taxDeclaration->save();
              $lastID = $taxDeclaration->id;
              DB::commit();
              // Alert::success('Tax declaration submitted!');
              return response()->json([
                'taxDeclaration' => $taxDeclaration,
                'lastID' => $lastID,
              ]);

            }else if($is_income_other_pension == 1){
              $filename = Util::rand_filename($request->file('income_property_file_path')->getClientOriginalExtension());
              $income_property_file_path = Util::upload_file($request->file('income_property_file_path'), $filename, null, $upload_income_property_path);
          
              $filename = Util::rand_filename($request->file('other_income_file_path')->getClientOriginalExtension());
              $other_income_file_path = Util::upload_file($request->file('other_income_file_path'), $filename, null, $upload_other_income_path);
          
              $filename = Util::rand_filename($request->file('lic_file_path')->getClientOriginalExtension());
              $lic_file_path = Util::upload_file($request->file('lic_file_path'), $filename, null, $upload_lic_path);
          
              $filename = Util::rand_filename($request->file('nsc_file_path')->getClientOriginalExtension());
              $nsc_file_path = Util::upload_file($request->file('nsc_file_path'), $filename, null, $upload_nsc_path);
          
              $filename = Util::rand_filename($request->file('ppf_file_path')->getClientOriginalExtension());
              $ppf_file_path = Util::upload_file($request->file('ppf_file_path'), $filename, null, $upload_ppf_path);
          
              $filename = Util::rand_filename($request->file('ety_d_file_path')->getClientOriginalExtension());
              $eighty_d_file_path = Util::upload_file($request->file('ety_d_file_path'), $filename, null, $upload_eighty_d_path);
          
              $filename = Util::rand_filename($request->file('ety_dd_file_path')->getClientOriginalExtension());
              $eighty_dd_file_path = Util::upload_file($request->file('ety_dd_file_path'), $filename, null, $upload_eighty_dd_path);
              // for get the optcl unit id 
              $optclUnitId = DB::table('optcl_pension_application_form')->join('optcl_employee_master','optcl_employee_master.id','=','optcl_pension_application_form.employee_id')->where('optcl_pension_application_form.employee_code',Auth::user()->username)->value('optcl_employee_master.optcl_unit_id');
              //dd($optclUnitId);
          
              $taxDeclaration->emp_id = $emp_id;
              $taxDeclaration->emp_code = $emp_code;
              $taxDeclaration->aadhaar_no = $emp_aadhaar;
              $taxDeclaration->is_income_other_pension = $is_income_other_pension;
              $taxDeclaration->income_property = $income_property;
              $taxDeclaration->income_property_file = $income_property_file_path;
              $taxDeclaration->other_income = $other_income;
              $taxDeclaration->other_income_file = $other_income_file_path;
              $taxDeclaration->lic = $lic;
              $taxDeclaration->lic_file = $lic_file_path;
              $taxDeclaration->nsc = $nsc;
              $taxDeclaration->nsc_file = $nsc_file_path;
              $taxDeclaration->ppf = $ppf;
              $taxDeclaration->ppf_file = $ppf_file_path;
              $taxDeclaration->eighty_d = $eighty_d;
              $taxDeclaration->eighty_d_file = $eighty_d_file_path;
              $taxDeclaration->eighty_dd = $eighty_dd;
              $taxDeclaration->eighty_dd_file = $eighty_dd_file_path;
              $taxDeclaration->optcl_unit_id = $optclUnitId;
              $taxDeclaration->is_approved = 0; // 0 for pending TD form , 1 for Approve TD form
              $taxDeclaration->status = 1;
              $taxDeclaration->deleted = 0;
              $taxDeclaration->created_at = $this->current_date;
              $taxDeclaration->save();
              $lastID = $taxDeclaration->id;
              // Session::put('step_two', 'true');
              //return redirect()->route('nominee_form');
              DB::commit();
              // Alert::success('Tax declaration submitted!');
              return response()->json([
                'taxDeclaration' => $taxDeclaration,
                'lastID' => $lastID,
              ]);
            }
          // } 
          
        }catch (\Throwable $e){
          DB::rollback();
          throw $e;
          // Alert::error('Server responsing with error!');
        }
        
    }
    echo json_encode($validation);  
  }
  
  // public function fetchTaxDeclaration(){
  //   $taxDetails = TaxDeclaration::get()->where('status', 1)->where('deleted', 0);
  //   // $taxDetails = $taxDetails->all();
  //   return response()->json([
  //     'taxDetails' => $taxDetails,
  //   ]);
  // }

  public function fetchTaxDeclaration(){
    $username = Auth::user()->username;
    //dd($username);
    $result = TaxDeclaration::where('emp_code',$username)->where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
    //$result = $taxDetails->paginate(10);
    //dd($result);
    return view('user.pension.tax-declaration-details',compact('result'));

  }  
  public function viewTaxDeclaration(Request $request,$id){
    $username = Auth::user()->username;
    $result = TaxDeclaration::where('emp_code',$username)->where('status',1)->where('deleted',0)->where('id',$id)->get();
    //$result = $taxDetails->paginate(10);
    //dd($result);
    return view('user.pension.tax-declaration-view',compact('result'));

  } 
  public function editTaxDeclaration(Request $request,$id){
    $username = Auth::user()->username;
    $result = TaxDeclaration::where('emp_code',$username)->where('status',1)->where('deleted',0)->where('id',$id)->get();
    //$result = $taxDetails->paginate(10);
    //dd($result);
    return view('user.pension.tax-declaration-edit',compact('result'));

  } 

  public function UnitHeadTaxDeclaration(){
  $result = TaxDeclaration::where('is_income_other_pension',1)->where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
  return view('user.unit_head.tax-declaration-details',compact('result'));
  } 
  public function viewUnitHeadTaxDeclaration(Request $request,$id){
    $result = TaxDeclaration::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('user.unit_head.tax-declaration-view',compact('result'));
  }
    public function approveUnitHeadTaxDeclaration(Request $request,$id){

      TaxDeclaration::where('status',1)->where('deleted',0)->where('id' , $id)->update(['is_approved'=> 1]);
      Session::flash('success', 'Applicant tax declaration approved successfully');
      return redirect()->route('unit_head_tax_declaration');
  
     }  
    public function FinanceExecutiveTaxDeclaration(){
      $result = TaxDeclaration::where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
      return view('user.finance_executive.tax-declaration-details',compact('result'));
    }
    public function DealingAssistantTaxDeclaration(){
      $DaUsername = Auth::user()->optcl_unit_id;
      //$getOptclUnitId = DB::('optcl_users')->select('optcl_unit_id')->where('mobile',$DaUsername)->get();
      //dd($DaUsername);
      $result = TaxDeclaration::where('is_income_other_pension',1)->where('optcl_unit_id',$DaUsername)->where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
      return view('user.dealing-assistant.tax-declaration-details',compact('result'));
    }
    public function viewDealingAssistantTaxDeclaration(Request $request,$id){
      $result = TaxDeclaration::where('status',1)->where('deleted',0)->where('id',$id)->get();
      return view('user.dealing-assistant.tax-declaration-view',compact('result'));
    }

  public function delTaxDeclaration(Request $request){
    
    // status = 0, deleted = 1 (update)
    
    $id = $request->id;

    $taxDetails = TaxDeclaration::find($id);
    $taxDetails->status = 0;
    $taxDetails->deleted = 1;
    $taxDetails->save();

    return response()->json(['success' => 'Record has been deleted']);
    // Alert::success('Tax declaration deleted');
  }

  // public function editTaxDeclaration(Request $request){
  //   $id = $request->id;
  //   // dd($id);
  //   $taxDeclaration = TaxDeclaration::find($id);

  //   $income_property = $request->edit_income_property;
  //   $other_income = $request->edit_other_income;
  //   $lic = $request->edit_lic;
  //   $nsc = $request->edit_nsc;
  //   $ppf = $request->edit_ppf;
  //   $eighty_d = $request->edit_ety_d;
  //   $eighty_dd = $request->edit_ety_dd;
  //   dd($income_property, $other_income, $lic, $nsc, $ppf, $eighty_d, $eighty_dd);

  //   if($income_property == "" && $other_income == "" && $lic == "" && $nsc == "" && $ppf == "" && $eighty_d == "" && $eighty_dd = ""){
  //     $is_income_other_pension = 0;
  //   }else{
  //     $is_income_other_pension = 1;
  //   }
  //   $taxDeclaration->is_income_other_pension = $is_income_other_pension;
  //   $taxDeclaration->income_property = $income_property;
  //   $taxDeclaration->other_income = $other_income;
  //   $taxDeclaration->lic = $lic;
  //   $taxDeclaration->nsc = $nsc;
  //   $taxDeclaration->ppf = $ppf;
  //   $taxDeclaration->eighty_d = $eighty_d;
  //   $taxDeclaration->eighty_dd = $eighty_dd;
  //   $taxDeclaration->status = 1;
  //   $taxDeclaration->deleted = 0;
  //   $taxDeclaration->created_at = $this->current_date;

  //   $taxDeclaration->save();
  // }

//   public function updateTaxDeclaration(Request $request,$id){

//     $income_property = $request->income_property;
//     $other_income = $request->other_income;
//     $lic = $request->lic;
//     $nsc = $request->nsc;
//     $ppf = $request->ppf;
//     $eighty_d = $request->ety_d;
//     $eighty_dd = $request->ety_dd;

//     $income_property_file_path = '';
//     $other_income_file_path = '';
//     $lic_file_path = '';
//     $nsc_file_path = '';
//     $ppf_file_path = '';
//     $eighty_d_file_path = '';
//     $eighty_dd_file_path = '';

//     $upload_income_property_path = 'uploads/tax_declaration/income_property_file/';
//     $upload_other_income_path = 'uploads/tax_declaration/other_income_file/';
//     $upload_lic_path = 'uploads/tax_declaration/lic_file/';
//     $upload_nsc_path = 'uploads/tax_declaration/nsc_file/';
//     $upload_ppf_path = 'uploads/tax_declaration/ppf_file/';
//     $upload_eighty_d_path = 'uploads/tax_declaration/ety_d_file/';
//     $upload_eighty_dd_path = 'uploads/tax_declaration/ety_dd_file/';

//         $form_16_file_path = '';
//         $upload_path = 'uploads/form16_file/';
//     if($request->hasFile('form_16_file_path')) {
//         $filename = Util::rand_filename($request->file('form_16_file_path')->getClientOriginalExtension());
//         $form_16_file_path = Util::upload_file($request->file('form_16_file_path'), $filename, null, $upload_path);
//         DB::table('optcl_form16_master')->where('id', $id)->update([
//             'form_16_file_path' => $form_16_file_path,
//             'upload_date'=> date('Y-m-d', strtotime(str_replace("/","-",$upload_date)))
        
//         ]);
//         Session::flash('success', 'File updated successfully');
//      } else{
//         Session::flash('error', 'Please select the file correctly');
//         return redirect()->route('form16_edit', array($id));
//      }      
//         return redirect()->route('form16_details');
        

// }
}