<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use App\Models\CommutationMaster;
use Session;
class CommutationMasterController extends Controller
{
    public function fetch_commutation(){

        $data['result']=CommutationMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    
        return view('admin.commutation-details',$data);
    
    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = CommutationMaster::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       }
}
