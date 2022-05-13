<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserDesignation;
use Session;

class UserDesignationController extends Controller
{
    public function fetch_user_designation(){

        $data['result']=UserDesignation::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    
        return view('admin.user-designation-details',$data);
    
    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = UserDesignation::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       } 
}
