<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NomineePreference;
use Session;

class NomineePreferenceController extends Controller
{
    public function fetch_nominee(){

        $data['result']=NomineePreference::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    
        return view('admin.nominee-details',$data);
    
    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = NomineePreference::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       }
}
