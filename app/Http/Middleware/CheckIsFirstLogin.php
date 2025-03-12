<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsFirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = request()->segment(1);
        if(Auth::check() && Auth::user()->is_first_login) {   
            if($url!="security-question" && $url!="update-security-question") {
                return redirect()->route('set-security-question')->with('error', 'Set security question to continue');                
            }
        }
        return $next($request);
    }
}
