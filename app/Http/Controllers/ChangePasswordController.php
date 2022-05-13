<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Auth;
//use Hash;
use Session;

class ChangePasswordController extends Controller{
    
    public function __construct(){
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function index(){
        return view('admin.admin_change_password');
    }
    public function changePassword(Request $request)
    {       
        $user = Auth::user();
        //$newPassword = $request->new_password;
        $userPassword = $user->password;
        //dd($userPassword);
        if (!Hash::check($request->current_password, $userPassword)) {
            Session::flash('error','Please enter the correct current password');
            return redirect()->route('change_password');
        }
        //dd($newPassword);
        $user->password = Hash::make($request->new_password);
        $user->save();
        Session::flash('success','Password changed successfully');
        return redirect()->route('change_password');
        
    }
    // for users
    public function indexUsers(){
        return view('user.user_change_password');
    }
    public function changePasswordUser(Request $request)
    {       
        $user = Auth::user();
        //$newPassword = $request->new_password;
        $userPassword = $user->password;
        //dd($userPassword);
        if (!Hash::check($request->current_password, $userPassword)) {
            Session::flash('error','Invalid current password. Please try again');
            return redirect()->route('user_change_password');
        }
        //dd($newPassword);
        $user->password = Hash::make($request->new_password);
        $user->save();
        Session::flash('success','Password changed successfully');
        return redirect()->route('user_change_password');
        
    }
}
?>
