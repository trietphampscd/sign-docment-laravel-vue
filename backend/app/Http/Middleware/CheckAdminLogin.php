<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->admin == 1 || Auth::user()->admin == 2){
                return $next($request);
            }else{
                Auth::logout();
                return redirect('/admin/login');
            }
        } else{
            return redirect('/admin/login');
        }
    }
}
