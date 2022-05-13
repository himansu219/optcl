<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RelationMaster;
use Session;

class RelationMasterController extends Controller
{
    public function index(){
        
        return view('admin.relation-add');
    }

    public function relation_submit(Request $request){

    $request->validate([

        'relation_name'=>'required'
    ]);

    $res= new RelationMaster;
    $res->relation_name= $request->relation_name;
    $res->save();
    Session::flash('success', 'Relation added successfully');
    return redirect()->route('relation_details');

    }


    public function fetch_relation(){

    $data['result']=RelationMaster::where('deleted',0)->orderBy('id','DESC')->paginate(10);

    return view('admin.relation-details',$data);

    }

    public function edit_relation(Request $request,$id){

    $result=RelationMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('admin.relation-edit')->with(compact('result'));
    }

    public function update_relation(Request $request,$id){

    $request->validate([
        'relation_name'=>'required'
    ]);
        
    $data=array(    
        
        'relation_name'=>$request->input('relation_name')

        );
        RelationMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Relation updated successfully');
        return redirect()->route('relation_details');


    }

    public function delete_relation(Request $request,$id){

        RelationMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
        Session::flash('success', 'Relation deleted successfully');

    return redirect()->route('relation_details');

    }
    public function changeStatus(Request $request){
        //dd($request->all());
        $user = RelationMaster::find($request->id);
        //dd($user);
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);
    
       }
}
