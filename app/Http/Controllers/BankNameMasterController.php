<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BankNameMaster;
use App\Models\BankType;
use Session;


class BankNameMasterController extends Controller
{
    public function index(){
        
        $bank_type= BankType::where('status',1)->where('deleted',0)->get();
         
        $bank_name=BankNameMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.bank-name-add')->with(compact('bank_type', 'bank_name'));
    }

   public function bank_name_submit(Request $request){
    $request->validate([

        'bank_type'=>'required',
        'bank_name'=>'required'
        // 'bank_code'=>'required'
    ]);

    //dd($request);
    $res= new BankNameMaster;
    $res->bank_type_id= $request->bank_type;
    $res->bank_name= $request->bank_name;
    // $res->bank_code= $request->bank_code;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('bank_name_details');
   }
    

   public function fetch_bank_name(){
       
        $data['result']=BankNameMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
        
        return view('admin.bank-name-details',$data);
        //return view('admin.state-details')->with(compact('country', 'result','no', 1));
   }

   public function edit_bank_name(Request $request,$id){
        $bank_type= BankType::where('status',1)->where('deleted',0)->get();
        $result=BankNameMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
       return view('admin.bank-name-edit')->with(compact('bank_type', 'result'));
        //return redirect()->route('pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_bank_name(Request $request,$id){

    $request->validate([

        'bank_type'=>'required',
        'bank_name'=>'required'
        // 'bank_code'=>'required'
    ]);
        
    $data=array(    
        'bank_type_id'=>$request->input('bank_type'),
        'bank_name'=>$request->input('bank_name')
        // 'bank_code'=>$request->input('bank_code')
    
        );
        BankNameMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('bank_name_details');


   }

   public function delete_bank_name(Request $request,$id){

    BankNameMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('bank_name_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = BankNameMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }



}
