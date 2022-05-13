<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DesignationModel;
use Session;

class DesignationController extends Controller
{
    public function index(){
        return view('admin.designation-add');
    }

   public function designation_submit(Request $request){

    $request->validate([
        'designation_name'=>'required'
    ]);
    $res= new DesignationModel;
    $res->designation_name= $request->designation_name;
    $res->save();
    Session::flash('success', 'Designation added successfully');
    return redirect()->route('designation_details');

   }
    public function fetch_designation(){
    $data['result']=DesignationModel::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    return view('admin.designation-details',$data);
   }

    public function edit_designation(Request $request,$id){
    $result=DesignationModel::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('admin.designation-edit')->with(compact('result'));
   }

   public function update_designation(Request $request,$id){
      $request->validate([
        'designation_name'=>'required'
     ]);
        $data=array(    
        'designation_name'=>$request->input('designation_name')
        );
        DesignationModel::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Designation updated successfully');
        return redirect()->route('designation_details');
    }

   public function delete_designation(Request $request,$id){
        DesignationModel::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
        Session::flash('success', 'Designation deleted successfully');
        return redirect()->route('designation_details');
     }

   public function changeStatus(Request $request){
    //dd($request->all());
    $user = DesignationModel::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   } 
}
