<?php
/*
  ##############################################################################
  # iProduction - Production and Manufacture Management Software
  ##############################################################################
  # AUTHOR:		Door Soft
  ##############################################################################
  # EMAIL:		info@doorsoft.co
  ##############################################################################
  # COPYRIGHT:		RESERVED BY Door Soft
  ##############################################################################
  # WEBSITE:		https://www.doorsoft.co
  ##############################################################################
  # This is a HasPermission class.
  ##############################################################################
 */

namespace App\Http\Middleware;

use App\MenuActivity;
use App\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasPermission
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
        if (auth()->check() && auth()->user()->role == 2) {
            $request_route = \Request::route()->getName();
            $activity = MenuActivity::where('route_name', $request_route)->first();
            $user_role = Auth::user()->permission_role;
            /*if (isset($activity)) {
                $activity_id = $activity->id;
                $condition = [
                    'role_id' => $user_role,
                    'activity_id' => $activity_id,
                ];
                if (RolePermission::where($condition)->exists()) {
                    return $next($request);
                } else {
                    return redirect()->route('home')->with("error", __('index.no_permission'));
                }
            } else {
                return redirect()->route('home')->with("error", __('index.no_permission'));
            }*/
			return $next($request);
        } else {
            return $next($request);
        }
    }
}
