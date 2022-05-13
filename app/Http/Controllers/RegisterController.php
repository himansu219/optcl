<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RegisterController extends Controller {

    public function registration_form() {
        return view('user.register');
    }

}
