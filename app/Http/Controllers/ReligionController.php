<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ReligionModel;
use Session;

class ReligionController extends Controller
{

    
    public function index(){
        
        return view('admin.religion-add');
    }

    public function religion_submit(Request $request){

    $request->validate([

    
        //'religion_code'=>'required',
        'religion_name'=>'required'
    ]);

    $res= new ReligionModel;
    //$res->religion_code= $request->religion_code;
    $res->religion_name= $request->religion_name;
    $res->save();
    Session::flash('success', 'Religion added successfully');
    return redirect()->route('religion_details');

    }


    public function fetch_religion(){

    $data['result']=ReligionModel::where('deleted',0)->orderBy('id','DESC')->paginate(10);

    return view('admin.religion-details',$data);

    }

    public function edit_religion(Request $request,$id){

    $result=ReligionModel::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('admin.religion-edit')->with(compact('result'));
    }

    public function update_religion(Request $request,$id){

    $request->validate([

        // 'religion_code'=>'required',
        'religion_name'=>'required'
    ]);
        
    $data=array(    
        
        //'religion_code'=>$request->input('religion_code'),
        'religion_name'=>$request->input('religion_name')

        );
        ReligionModel::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Religion updated successfully');
        return redirect()->route('religion_details');


    }

    public function delete_religion(Request $request,$id){

        ReligionModel::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
        Session::flash('success', 'Religion deleted successfully');
        return redirect()->route('religion_details');

    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = ReligionModel::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       } 
}
