<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Pensioner_form1;
use Illuminate\Http\Request;


class ForgotPasswordController extends Controller {

    public function __construct(){
        $this->current_date = date('Y-m-d');
    }

    public function forgot_password(){
         return view('user.forgot_password');
    }
  
}
