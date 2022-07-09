<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Validator;

class BenImportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }
    
    public function index(Request $request){    
        $applications = DB::table('optcl_beneficiaries_import');
        /* if(!empty($request->search_ppo_no)) {
            $search_ppo_no = $request->search_ppo_no;
            //$applications = $applications->where('a.search_ppo_no', 'like', '%' . $request->search_ppo_no . '%');
            $applications = $applications->where(function($query) use($search_ppo_no) {
                $query->orWhere('optcl_existing_user.new_ppo_no', 'like', '%' . $search_ppo_no . '%');
                $query->orWhere('optcl_pension_application_form.ppo_number', 'like', '%' . $search_ppo_no . '%');
            });
        } */
        $applications = $applications->where('optcl_beneficiaries_import.status', 1)
                                    ->where('optcl_beneficiaries_import.deleted', 0)
                                    ->orderBy('optcl_beneficiaries_import.id','DESC')
                                    ->paginate(10);

        return view('user.import_beneficiary.list', compact('applications'));
    }

    public function add(){
        return view('user.import_beneficiary.add');
    }

    public function file_submission(Request $request){
        $validated = $request->validate([
            'beneficiary_file' => 'required|mimes:xlsx,xls',
        ],[
            'beneficiary_file.required' => 'Please upload file',
            'beneficiary_file.mimes' => 'Please upload excel file only',
        ]);
        if($validated->fails()){
            dd($validated->errors());
        }
        dd($validated, "123", $request->file('beneficiary_file'));
    }

}
