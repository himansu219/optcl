<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\admin\PensionUnit;

class PensionUnitController extends Controller
{
    public function index(){
        
        
        $district=DB::table('optcl_district_masters')->get();
        return view('AdminView.pension_unit',compact('district'));
    }

   public function pension_unit_submit(Request $request){

    $request->validate([

        'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);

    $res= new PensionUnit;
    // $res->country_id= $request->country;
    // $res->state_id= $request->state;
    $res->district_id= $request->district;
    $res->unit_code= $request->unit_code;
    $res->unit_name= $request->unit_name;
    $res->save();
    return redirect('pension_unit_details');
   }
    

   public function fetch_pension_unit(){
    $data['result']=DB::table('optcl_pension_unit_masters')->where('status',1)->where('deleted',0)
    ->get();
    return view('AdminView.pension_unit_details',$data);
   }

   public function edit_pension_unit(Request $request,$id){
    $district=DB::table('optcl_district_masters')->get();


    $result=DB::table('optcl_pension_unit_masters')->where('id',$id)->get();
    return view('AdminView.pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_pension_unit(Request $request,$id){

    $request->validate([

        'district'=>'required',
        'unit_code'=>'required',
        'unit_name'=>'required'
    ]);
        
    $data=array(    
        'district_id'=>$request->input('district'),
        'unit_code'=>$request->input('unit_code'),
        'unit_name'=>$request->input('unit_name')
    
        );
        DB::table('optcl_pension_unit_masters')->where('id' , $id)->update($data);
        $request->session()->flash('msg','Data Updated');
        return redirect('pension_unit_details');


   }

   public function delete_pension_unit(Request $request,$id){

    DB::table('optcl_pension_unit_masters')->where('id' , $id)->update(['deleted'=> 1]);
    $request->session()->flash('msg','Data delete');
    return redirect('pension_unit_details');

   }

}
