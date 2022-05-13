<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CalculationRuleMaster;
use App\Models\PensionTypeMaster;
use App\Models\CalculationTypeMaster;
use Session;

class CalculationRuleMasterController extends Controller
{
    public function index(){
        $pension_type=PensionTypeMaster::where('status',1)->where('deleted',0)->get();
        $calculation_type=CalculationTypeMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.rule-add')->with(compact('pension_type','calculation_type'));
    }

   public function rule_submit(Request $request){
    $request->validate([
        'pension_type'=>'required',
        'calculation_type'=>'required',
        'rule_name'=>'required',
        'rule_description'=>'required'
    ]);

    // $data=BankBranchNameMaster::where('ifsc_code',$request->ifsc_code)->where('status',1)->where('deleted',0)->first();
    // if($data === null){

    //dd($request);
    $res= new CalculationRuleMaster;
    $res->pension_type_id= $request->pension_type;
    $res->calculation_type_id= $request->calculation_type;
    $res->rule_name= $request->rule_name;
    $res->rule_description= $request->rule_description;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('rule_details');
    //}else{
    // Session::flash('error', 'IFSC Code already exists');
    // return redirect()->route('bank_branch_add');
    //     }
   }
    

   public function fetch_rule(Request $request){
       
        $pension_type=PensionTypeMaster::where('status',1)->where('deleted',0)->get();
        $calculation_type=CalculationTypeMaster::where('status',1)->where('deleted',0)->get();
        $search =  $request->input('pension_type');
        $search2 =  $request->input('calculation_type');
        $result = CalculationRuleMaster::where('deleted',0)->orderBy('id','DESC');

        if($search2!="") {
            $result = $result->where('pension_type_id', $search)
                             ->where('calculation_type_id', $search2);
        }
        if($search!="") {
            $result = $result->where('pension_type_id', $search);
                             
        }

        $result = $result->paginate(10);

        return view('admin.rule-details',compact('pension_type','calculation_type','result','search','search2'));

   }

   public function edit_rule(Request $request,$id){
        $pension_type=PensionTypeMaster::where('status',1)->where('deleted',0)->get();
        $calculation_type=CalculationTypeMaster::where('status',1)->where('deleted',0)->get();
        $result=CalculationRuleMaster::where('status',1)->where('deleted',0)->where('id',$id)->first();
        return view('admin.rule-edit')->with(compact('pension_type','calculation_type', 'result'));
       
   }

   public function update_rule(Request $request){

    $request->validate([
        'pension_type'=>'required',
        'calculation_type'=>'required',
        'rule_name'=>'required',
        'rule_description'=>'required'
    ]);
    $id = $request->input('rule_hidden_id');
        
    $data=array(    
        'pension_type_id'=>$request->input('pension_type'),
        'calculation_type_id'=>$request->input('calculation_type'),
        'rule_name'=>$request->input('rule_name'),
        'rule_description'=>$request->input('rule_description'),
         );
        CalculationRuleMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('rule_details');


   }

   public function delete_rule(Request $request,$id){

    CalculationRuleMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('rule_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = CalculationRuleMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }
}
