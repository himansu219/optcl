<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CountryMaster;
use Session;

class CountryMasterController extends Controller
{
    public function index(){
        
        return view('admin.country-add');
    }

   public function country_submit(Request $request){

    $request->validate([
    
        'country_name'=>'required',
        'country_code'=>'required'
        
    ]);

    //dd("1");
    $res= new CountryMaster;
    $res->country_name= $request->country_name;
    $res->country_code= $request->country_code;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('country_details');
   }
    

   public function fetch_country(){
        $data['result']=CountryMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
        return view('admin.country-details',$data);
   }

   public function edit_country(Request $request,$id){
       $result=CountryMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
       return view('admin.country-edit')->with(compact('result'));
       
   }

   public function update_country(Request $request,$id){

    $request->validate([

        'country_name'=>'required',
        'country_code'=>'required'
    ]);
        
    $data=array(    
        'country_name'=>$request->input('country_name'),
        'country_code'=>$request->input('country_code')
    
        );
        CountryMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('country_details');


   }

   public function delete_country(Request $request,$id){

    CountryMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('country_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = CountryMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }
}
