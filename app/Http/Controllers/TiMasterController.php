<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TiMaster;
use Session;

class TiMasterController extends Controller
{
    public function index(){
        return view('admin.ti-add');
    }
    public function ti_submit(Request $request){
    $request->validate([
        'start_date'=>'required',
        'end_date'=>'required',
        'da_rate'=>'required'
    ]);
    
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    // 'timezone' => 'Asia/Kolkata',
    //  str_replace("/","-",$enddate);
    
    // $dend_date= str_replace("/","-",$end_date);
    
    // dd(date('Y-m-d', strtotime($dend_date)));
    $start_date2 = date('Y-m-d', strtotime(str_replace("/","-",$start_date)));
    $end_date2 = date('Y-m-d', strtotime(str_replace("/","-",$end_date)));
    $timaster = TiMaster::where('start_date', '<=', $start_date2)
                        ->where('end_date','>=', $end_date2)
                        ->where('status',1)->where('deleted',0)->first();
    //dd($TiMaster);
    
    if($timaster === null){
        $res= new TiMaster;
        $res->start_date = $start_date2;
        $res->end_date= $end_date2;
        $res->da_rate = $request->da_rate;
        $res->save();
        Session::flash('success', 'Data added successfully');
        return redirect()->route('ti_details');
    }else{
        Session::flash('error', 'Date already exists');
        return redirect()->route('ti_add'); 
    }
    
    
   }
    public function fetch_ti(Request $request){
       
        //$timaster = TiMaster::where('status',1)->where('deleted',0)->get();
        $start_date =  $request->input('start_date');
        $end_date =  $request->input('end_date');
        $start_date2 = date('Y-m-d', strtotime(str_replace("/","-",$start_date)));
        $end_date2 = date('Y-m-d', strtotime(str_replace("/","-",$end_date)));
        //  print_r($end_date2);
        //  die();

        $result =  TiMaster::where('deleted',0)->orderBy('id','DESC');
        
        //dd($result);

        if($start_date!="") {
            $result = $result->where('start_date', '>=', $start_date2)
                             ->where('end_date','<=', $start_date2);
                    
        }

        if($end_date!="") {
            $result = $result->where('start_date', '>=', $end_date2)
                             ->where('end_date','<=', $end_date2);
                    
        }

         $result = $result->paginate(10);

        return view('admin.ti-details',compact('result'));

        
   }

   public function edit_ti(Request $request,$id){
        $result=TiMaster::where('status',1)->where('deleted',0)->where('id',$id)->get();
        return view('admin.ti-edit')->with(compact('result'));

   }

   public function update_ti(Request $request,$id){

    $request->validate([
        'start_date'=>'required',
        'end_date'=>'required',
        'da_rate'=>'required'
    ]);
    $start_date =  $request->input('start_date');
    $end_date =  $request->input('end_date');

    $data=array(    
        'start_date'=> date('Y-m-d', strtotime(str_replace("/","-",$start_date))),
        'end_date'=> date('Y-m-d', strtotime(str_replace("/","-",$end_date))),
        'da_rate'=>$request->input('da_rate')
        );
        TiMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update($data);
        Session::flash('success', 'Data updated successfully');
        return redirect()->route('ti_details');


   }

   public function delete_ti(Request $request,$id){

    TiMaster::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'Data deleted successfully');
    return redirect()->route('ti_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    $user = TiMaster::find($request->id);
    //dd($user);
    $user->status = $request->status;
    $user->save();
    Session::flash('success', 'Status updated successfully');
    return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);

   }
}
