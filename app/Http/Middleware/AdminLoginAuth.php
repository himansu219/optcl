<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class AdminLoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        if(Auth::check()) {
            if(Auth::user()->is_admin == 1) {
                return redirect()->route('admin_dashboard');
            }
        }
        return $next($request);
    }
}
