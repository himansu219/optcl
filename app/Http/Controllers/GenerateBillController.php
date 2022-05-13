<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Religion;
use App\Models\OfficeLastServed;
use App\Models\PensionerDesignation;
use App\Models\Pensionform;
use App\Models\PensionDocument;
use App\Models\PersonalDetails;
use App\Libraries\Util;
use Session;
use Auth;


class GenerateBillController extends Controller { 
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function generate_bill_sheet(Request $request){
        $user = Auth::user();
        
    }


}