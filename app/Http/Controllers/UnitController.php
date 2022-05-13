<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UnitMaster;
use App\Models\DistrictMaster;
use Session;


class UnitController extends Controller
{
    public function index(){
        
        
        $district=DistrictMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.unit_add',compact('district'));
    }

   public function unit_submit(Request $request){

    $request->validate([

        // 'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);

    $res= new UnitMaster;
    // $res->country_id= $request->country;
    // $res->state_id= $request->state;
    // $res->district_id= $request->district;
    $res->unit_code= $request->unit_code;
    $res->unit_name= $request->unit_name;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('unit_details');
   }
    

   public function fetch_unit(){

    $data['result']=UnitMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
   
    return view('admin.unit_details',$data);

   }

   public function edit_unit(Request $request,$id){
       
    $district=DistrictMaster::where('status',1)->where('deleted',0)->get();


    $result=UnitMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('admin.unit_edit')->with(compact('district', 'result'));
   }

   public function update_unit(Request $request,$id){

    $request->validate([

        // 'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);
        
    $data=array(    
        // 'district_id'=>$request->input('district'),
        'unit_code'=>$request->input('unit_code'),
        'unit_name'=>$request->input('unit_name')
    
        );
        UnitMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('unit_details');


   }

   public function delete_unit(Request $request,$id){

    UnitMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    
    return redirect()->route('unit_details');

   }

   public function changeStatus(Request $request){
    //dd($request->all());
    $user = UnitMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   } 

}
