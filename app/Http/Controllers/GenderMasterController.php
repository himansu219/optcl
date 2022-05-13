<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use App\Models\GenderMaster;
use Session;

class GenderMasterController extends Controller
{
    public function fetch_gender(){

        $data['result']=GenderMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    
        return view('admin.gender-details',$data);
    
    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = GenderMaster::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       }
}
