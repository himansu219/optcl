<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Otp;

class ArrearsController extends Controller
{

    public function index(){
        return view('user.arrears.list');
    }

    public function add(){
        return view('user.arrears.add');
    }
}
?>