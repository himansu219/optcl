<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Util;
use App\Libraries\PensinorCalculation;
use Session;
use Auth;
use DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->current_date = date('Y-m-d H:i:s');
    }

    public function list(){
        $notifications = DB::table('optcl_user_notification')
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->orderBy('id', 'DESC')->paginate(10);
        return view('user.notification.list', compact('notifications'));
    }    

}