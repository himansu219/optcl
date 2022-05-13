<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CountryMaster;
use App\Models\StateMaster;
use Session;

class StateMasterController extends Controller
{
    public function index(){
        
        
        $country=CountryMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.state-add',compact('country'));
    }

   public function state_submit(Request $request){
    $request->validate([

        'country'=>'required',
        'state_name'=>'required'
    ]);

    //dd($request);
    $res= new StateMaster;
    $res->country_id= $request->country;
    $res->state_name= $request->state_name;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('state_details');
   }
    

   public function fetch_state(Request $request){
       // $id=StateMaster::select('country_id')->where('status',1)->where('deleted',0)->get();
       // $country=CountryMaster::select('country_name')->where('country_id',$id)->where('status',1)->where('deleted',0)->get();
       $country = CountryMaster::where('status',1)->where('deleted',0)->get();
       //$data['result']=StateMaster::where('status',1)->where('deleted',0)->paginate(10);
       $search =  $request->input('country');
       $result = StateMaster::where('deleted',0)->orderBy('id','DESC');

        if($search!="") {
            $result = $result->where('country_id', $search);
        }

        $result = $result->paginate(10);

        return view('admin.state-details',compact('country','result','search'));


        //return view('admin.state-details')->with(compact('country', 'result','no', 1));
   }

   public function edit_state(Request $request,$id){
        $country=CountryMaster::where('status',1)->where('deleted',0)->get();
        $result=StateMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
       return view('admin.state-edit')->with(compact('country', 'result'));
        //return redirect()->route('pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_state(Request $request,$id){

    $request->validate([

        'country'=>'required',
        'state_name'=>'required'
    ]);
        
    $data=array(    
        'country_id'=>$request->input('country'),
        'state_name'=>$request->input('state_name')
    
        );
        StateMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('state_details');


   }

   public function delete_state(Request $request,$id){

    StateMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('state_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = StateMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }

}
