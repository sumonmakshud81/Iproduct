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
  # This is ChangePasswordController Controller
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	/**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function changePassword()
    {
    	$title = __('index.change_password');

        return view('pages.change-password', compact('title'));
    }
	/**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function updatePassword(Request $request) {
    	
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ],
        [
            'current_password.required' => __('index.current_password_required'),
            'password.required' => __('index.new_password_required'),
            'password.min' => __('index.password_min'),
            'password.confirmed' => __('index.password_confirmed'),
            'password_confirmation.required' => __('index.password_confirmation_required'),
        ]);

        $user = auth()->user();
        
        if (Hash::check($request->current_password, $user->password)) {
            if (Hash::check($request->password, $user->password)){
                return redirect()->back()->with(dangerMessage(__('index.password_not_match')));
            }            
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->back()->with(saveMessage());
        } else {
            return redirect()->back()->with(dangerMessage(__('index.current_password_not_match')));
        }
    }
}