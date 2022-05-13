<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Form16Master;
use App\Libraries\Util;
use Session;

class Form16MasterController extends Controller
{
    public function index(){
       return view('admin.form16-add');
      
    }

   public function form16_submit(Request $request){

    $request->validate([
        'form_16_file_path'=>'required |mimes:pdf',
        'upload_date'=>'required'
    ]);
    $upload_date = $request->upload_date;
    $upload_date2 = date('Y-m-d', strtotime(str_replace("/","-",$upload_date)));

    $form_16_file_path = '';

    $upload_path = 'uploads/form16_file/';
    if($request->hasFile('form_16_file_path')) {
        $filename = Util::rand_filename($request->file('form_16_file_path')->getClientOriginalExtension());
        $form_16_file_path = Util::upload_file($request->file('form_16_file_path'), $filename, null, $upload_path);

        

            DB::table('optcl_form16_master')->insert([
                'form_16_file_path' => $form_16_file_path,
                'upload_date'=> $upload_date2,
                'status'=> 0
               
            ]);
            Session::flash('success', 'File uploaded successfully');
            return redirect()->route('form16_details');
        }
    }

    //$res= new Form16Master;
    // $res->unit_code= $request->form_16_file_path;
    //$res->save();
 
   
    

   public function fetch_form16(){
    $data['result']=Form16Master::where('deleted',0)->orderBy('id','DESC')->paginate(10);
    return view('admin.form16-details',$data);
   
   }
    
     public function fetch_form16_show(){
    $data['result']=Form16Master::where('status',1)->where('deleted',0)->paginate(10);
   
     return view('user.pensioner-form16-details',$data);
   }

   public function edit_form16(Request $request,$id){
    $result=Form16Master::where('status',1)->where('deleted',0)->where('id',$id)->get();
    return view('admin.form16-edit')->with(compact('result'));
     
   }

 public function update_form16(Request $request,$id){

        $request->validate([
            'form_16_file_path'=>'required |mimes:pdf',
            'upload_date'=>'required'
        ]);
            $upload_date =  $request->input('upload_date');

            $form_16_file_path = '';
            $upload_path = 'uploads/form16_file/';
        if($request->hasFile('form_16_file_path')) {
            $filename = Util::rand_filename($request->file('form_16_file_path')->getClientOriginalExtension());
            $form_16_file_path = Util::upload_file($request->file('form_16_file_path'), $filename, null, $upload_path);
            DB::table('optcl_form16_master')->where('id', $id)->update([
                'form_16_file_path' => $form_16_file_path,
                'upload_date'=> date('Y-m-d', strtotime(str_replace("/","-",$upload_date)))
            
            ]);
            Session::flash('success', 'File updated successfully');
         } else{
            Session::flash('error', 'Please select the file correctly');
            return redirect()->route('form16_edit', array($id));
         }      
            return redirect()->route('form16_details');
            

   }

   public function delete_form16(Request $request,$id){

    Form16Master::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
    Session::flash('success', 'File deleted successfully');
    
    return redirect()->route('form16_details');

   }
   public function changeStatus(Request $request){
    //dd($request->all());
    //$user = Form16Master::find($request->id);
    //dd($user);
    $user = Form16Master::where('id', '!=', $request->id)->where('status',1)->where('deleted',0)->count();
     //dd($user);
    if($user > 0){
        Session::flash('error', 'Status already activated');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']);  
    }else{
        $user = Form16Master::find($request->id); 
        $user->status = $request->status;
        $user->save();
        Session::flash('success', 'Status updated successfully');
        return response()->json(['success'=>'Status change successfully.', 'status' => 'true']); 
    }
  }

}
