<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckMasAdmin
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
        if(Auth::user()->admin == 2){
            return $next($request);
        }else{
            abort(403);
        }
    }
}
