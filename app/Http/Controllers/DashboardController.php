<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DashboardController extends Controller {

    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d');
    }

    public function dashboard(){
         return view('user.dashboard');
    }
  
}
