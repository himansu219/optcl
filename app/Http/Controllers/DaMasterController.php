<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DaMaster;
use Session;

class DaMasterController extends Controller
{
    public function index(){
        return view('admin.da-add');
    }
    public function da_submit(Request $request){
    $request->validate([
        'start_date'=>'required',
        'end_date'=>'required',
        'basic_pay'=>'required'
    ]);
    
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    // 'timezone' => 'Asia/Kolkata',
    //  str_replace("/","-",$enddate);
    
    // $dend_date= str_replace("/","-",$end_date);
    
    // dd(date('Y-m-d', strtotime($dend_date)));
    $start_date2 = date('Y-m-d', strtotime(str_replace("/","-",$start_date)));
    $end_date2 = date('Y-m-d', strtotime(str_replace("/","-",$end_date)));
    $damaster = DaMaster::whereDate('start_date', '<=', $start_date2)
                        ->whereDate('end_date','>=', $end_date2)
                        ->where('status',1)->where('deleted',0)->first();
    //dd($damaster);
    
    if($damaster === null){
        $res= new DaMaster;
        $res->start_date = $start_date2;
        $res->end_date= $end_date2;
        $res->percentage_of_basic_pay = $request->basic_pay;
        $res->save();
        Session::flash('success', 'Data added successfully');
        return redirect()->route('da_details');
    }else{
        Session::flash('error', 'Date already exists');
        return redirect()->route('da_add'); 
    }
    
    
   }
    public function fetch_da(Request $request){
       
        //$damaster = DaMaster::where('status',1)->where('deleted',0)->get();
        $start_date =  $request->input('start_date');
        $end_date =  $request->input('end_date');
        $start_date2 = date('Y-m-d', strtotime(str_replace("/","-",$start_date)));
        $end_date2 = date('Y-m-d', strtotime(str_replace("/","-",$end_date)));
        //  print_r($end_date2);
        //  die();

        $result =  DaMaster::where('deleted',0)->orderBy('id','DESC');
        
        //dd($result);

        if($start_date!="") {
            $result = $result->where('start_date', '>=', $start_date2)
                             ->where('end_date','<=', $end_date2);
                    
        }

         $result = $result->paginate(10);

        return view('admin.da-details',compact('result'));

        
   }

   public function edit_da(Request $request,$id){
        $result=DaMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
        return view('admin.da-edit')->with(compact('result'));

   }

   public function update_da(Request $request,$id){

    $request->validate([
        'start_date'=>'required',
        'end_date'=>'required',
        'basic_pay'=>'required'
    ]);
    $start_date =  $request->input('start_date');
    $end_date =  $request->input('end_date');

    $data=array(    
        'start_date'=> date('Y-m-d', strtotime(str_replace("/","-",$start_date))),
        'end_date'=> date('Y-m-d', strtotime(str_replace("/","-",$end_date))),
        'percentage_of_basic_pay'=>$request->input('basic_pay')
        );
        DaMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('da_details');


   }

   public function delete_da(Request $request,$id){

    DaMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('da_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = DaMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }
}
