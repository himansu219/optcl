<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CountryMaster;
use App\Models\StateMaster;
use App\Models\DistrictMaster;
use Session;

class DistrictMasterController extends Controller {

    public function index() {
        $country = CountryMaster::where('status',1)->where('deleted',0)->get();
        $state = StateMaster::where('status',1)->where('deleted',0)->get();
        return view('admin.district-add',compact('country','state'));
    }

   public function district_submit(Request $request){
        $request->validate([
            'country'=>'required',
            'state'=>'required',
            'district_name'=>'required'
        ]);

    //dd($request);
    $res= new DistrictMaster;
    // $res->country_id= $request->country;
    $res->state_id= $request->state;
    $res->district_name= $request->district_name;
    $res->save();
    Session::flash('success', 'Data added successfully');
    return redirect()->route('district_details');
   }
    

   public function fetch_district(Request $request){
       
        //$data['result']=DistrictMaster::where('status',1)->where('deleted',0)->get();
        $country = CountryMaster::where('status',1)->where('deleted',0)->get();
        $state = StateMaster::where('status',1)->where('deleted',0)->get();
        $search =  $request->input('country');
        $search2 =  $request->input('state');
        
        if($search!=""){
            $data = DistrictMaster::where(function ($query) use ($search2){
                $query->where('state_id', 'like', '%'.$search2.'%')->where('status',1)->where('deleted',0);
                    
            })
            ->paginate(10);
            $data->appends(['state' => $search2]);
        }
        else{
            $data = DistrictMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
        }
        //$data = DistrictMaster::where('status',1)->where('deleted',0)->paginate(10);
        return view('admin.district-details',compact('country','state','data','search','search2'));

      
   }

   public function edit_district(Request $request,$id){
        $country=CountryMaster::where('status',1)->where('deleted',0)->get();
        $state=StateMaster::where('status',1)->where('deleted',0)->get();
        $result=DistrictMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
        return view('admin.district-edit')->with(compact('country','state', 'result'));
        //return redirect()->route('pension_unit_edit')->with(compact('district', 'result'));
   }

   public function update_district(Request $request,$id){

    $request->validate([

        'country'=>'required',
        'state'=>'required',
        'district_name'=>'required'
    ]);
        
    $data=array(    
        // 'country_id'=>$request->input('country'),
        'state_id'=>$request->input('state'),
        'district_name'=>$request->input('district_name')
    
        );
        DistrictMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('district_details');


   }

   public function delete_district(Request $request,$id){

    DistrictMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('district_details');

   }

   public function getState(Request $request){
        $cid=$request->post('cid');
        $state=StateMaster::where('country_id',$cid)->get();
        $html='<option value="">Select State</option>';
        foreach($state as $list){
            $html.='<option value="'.$list->id.'">'.$list->state_name.'</option>';
        }
        echo $html;
    }

    public function changeStatus(Request $request){
        //dd($request->all());
        $user = DistrictMaster::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       }

}
