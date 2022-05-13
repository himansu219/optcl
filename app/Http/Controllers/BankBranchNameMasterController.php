<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BankBranchNameMaster;
use App\Models\BankNameMaster;
use Session;

class BankBranchNameMasterController extends Controller
{
    public function index(){
        $bank_name=BankNameMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.bank-branch-add')->with(compact('bank_name'));
    }

   public function bank_branch_submit(Request $request){
    $request->validate([
        'bank_name'=>'required',
        'branch_name'=>'required',
        'ifsc_code'=>'required',
        'micr_code'=>'required',
        'address'=>'required'
    ]);

    $data=BankBranchNameMaster::where('ifsc_code',$request->ifsc_code)->where('status',1)->where('deleted',0)->first();
    if($data === null){

    //dd($request);
    $res= new BankBranchNameMaster;
    $res->bank_id= $request->bank_name;
    $res->branch_name= $request->branch_name;
    $res->ifsc_code= $request->ifsc_code;
    $res->micr_code= $request->micr_code;
    $res->address= $request->address;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('bank_branch_details');
    }

    else{
    Session::flash('error', 'IFSC Code already exists');
    return redirect()->route('bank_branch_add');
        }
   }
    

   public function fetch_bank_branch(Request $request){
       
        $bank_name = BankNameMaster::where('status',1)->where('deleted',0)->get();
        //dd($bank_name);
       
        $search =  $request->input('bank_name');

        // if($search!=""){
        //     $result = BankBranchNameMaster::where(function ($query) use ($search){
        //         $query->where('bank_id',  '%'.$search.'%')->where('status',1)->where('deleted',0);
                    
        //     })
        //     ->paginate(10);
        //     $result->appends(['bank_name' => $search]);
        // }
        // else{
        //     $result = BankBranchNameMaster::where('status',1)->where('deleted',0)->paginate(10);
        // }

        $result = BankBranchNameMaster::where('deleted',0)->orderBy('id','DESC');

        if($search!="") {
            $result = $result->where('bank_id', $search);
        }

        $result = $result->paginate(10);

        return view('admin.bank-branch-details',compact('bank_name','result', 'search'));

        // $data['result']=BankBranchNameMaster::where('status',1)->where('deleted',0)->paginate(10);
        
        // return view('admin.bank-branch-details',$data);
        //return view('admin.state-details')->with(compact('country', 'result','no', 1));
   }

   public function edit_bank_branch(Request $request,$id){
        $bank_name= BankNameMaster::where('status',1)->where('deleted',0)->get();
        $result=BankBranchNameMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
       return view('admin.bank-branch-edit')->with(compact('bank_name', 'result'));
        //return redirect()->route('pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_bank_branch(Request $request,$id){

    $request->validate([
        'bank_name'=>'required',
        'branch_name'=>'required',
        'ifsc_code'=>'required',
        'micr_code'=>'required',
        'address'=>'required'
    ]);
        
    $data=array(    
        'bank_id'=>$request->input('bank_name'),
        'branch_name'=>$request->input('branch_name'),
        'ifsc_code'=>$request->input('ifsc_code'),
        'micr_code'=>$request->input('micr_code'),
        'address'=>$request->input('address'),

    
        );
        BankBranchNameMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('bank_branch_details');


   }

   public function delete_bank_branch(Request $request,$id){

    BankBranchNameMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('bank_branch_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = BankBranchNameMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }

}
