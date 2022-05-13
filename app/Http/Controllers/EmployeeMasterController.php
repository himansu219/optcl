<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pensionform;
use App\Libraries\Util;
use App\Imports\BulkImport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class EmployeeMasterController extends Controller
{
    public function index(){
        return view('admin.employee-add');
       
     }
 
    public function employee_submit(Request $request){
 
     $request->validate([
         'import_file'=>'required |mimes:xlsx'
     ]);
     $import_file = '';

     $upload_path = 'uploads/import_file/';
     if($request->hasFile('import_file')) {
         $filename = Util::rand_filename($request->file('import_file')->getClientOriginalExtension());
         $import_file = Util::upload_file($request->file('import_file'), $filename, null, $upload_path);
         //dd(request()->file());
         Excel::import(new BulkImport,$request->file('import_file'));
 
            
             Session::flash('success', 'File uploaded successfully');
             return redirect()->route('employee_details');
         }
     }
 
    public function fetch_employee(){
     $result=Pensionform::where('status',1)->where('deleted',0)->orderBy('id','DESC')->paginate(10);
     
     return view('admin.employee-details')->with(compact('result'));
    
    }
 
    public function edit_employee(Request $request,$id){
     $result=Pensionform::where('status',1)->where('deleted',0)->where('id',$id)->get();
     return view('admin.employee-edit')->with(compact('result'));
      
    }
 
  public function update_employee(Request $request,$id){
 
         $request->validate([
             'import_file'=>'required |mimes:xlsx'
         ]);
            
           
 
    }
 
    public function delete_employee(Request $request,$id){
 
     Pensionform::where('status',1)->where('deleted',0)->where('id' , $id)->update(['deleted'=> 1]);
     Session::flash('success', 'File deleted successfully');
     
     return redirect()->route('employee_details');
 
    }
}
