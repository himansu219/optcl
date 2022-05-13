<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Login extends Controller
{
    function index() {   
        //echo 'loginpage';
        return view('admin.login');
    }

    public function admin_login_submit(Request $request){
        
        $request->validate([

            'user_id'=>'required|min:5|max:12',
            'password'=>'required|min:5|max:15',
     ]);

        $name= $request->input('user_id');
        $password= $request->input('password');

        // $result = DB::table('optcl_user_masters')->orWhere('name',$name)
        // ->orWhere('mobile',$name)->where('password',$password)->count();
      
        $result = DB::table('optcl_user_masters')->where(function ($query) use($name) {
            $query->orwhere('mobile',$name)
            ->orWhere('name',$name); 
            });
            $result=$result->where('password',$password)
            ->count();

        if($result > 0)
        {
            //echo "login";
            $request->session()->put('id',$name);
            return redirect('admin.dashboard');
        }
        else{

            $request->session()->flash('msg','Please Enter Correct Login Details');
            return view('admin.login');
           
        }
      // return view('register');

    }


    function dashboardindex() {   
        //echo 'loginpage';
        return view('admin.dashboard');
    }

}
