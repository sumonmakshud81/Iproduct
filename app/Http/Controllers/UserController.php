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
  # This is UserController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = User::query()->where('del_status', 'Live')->where('role', 2);
        $obj->orderBy('id', 'DESC');
        $obj = $obj->get();
        $title = __('index.list_user');
        return view('pages.user.index', compact('obj', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('title')->get();
        $title = __('index.add_user');
        return view('pages.user.addEdit', compact('roles', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'role' => 'required',
                'name' => 'required|max:50',
                'designation' => 'required|max:50',
                'phone_number' => 'required|max:50',
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
                'status' => 'required',
                'password' => 'required|min:6',
            ],
            [
                'role.required' => __('index.role_required'),
                'name.required' => __('index.name_required'),
                'name.max' => __('index.name_max'),
                'designation.required' => __('index.designation_required'),
                'designation.max' => __('index.designation_max'),
                'phone_number.required' => __('index.phone_required'),
                'phone_number.max' => __('index.phone_max'),
                'email.required' => __('index.email_required'),
                'email.regex' => __('index.email_validation'),
                'status.required' => __('index.status_required'),
                'password.required' => __('index.password_required'),
                'password.min' => __('index.password_min'),
            ]
        );

        $row = new User();
        $row->company_id = auth()->user()->company_id;
        $row->role = 2;
        $row->type = 'User';
        $row->designation = $request->designation;
        $row->name = $request->name;
        $row->phone_number = $request->phone_number;
        $row->email = $request->email;
        $row->password = Hash::make($request->password);
        $row->status = $request->status;
        $row->permission_role = $request->role;
        $row->salary = null_check($request->salary);
        $row->is_first_login = 1;
        $row->del_status = 'Live';
        $row->company_id = 1;
        $row->save();

        return redirect()->route('user.index')->with(saveMessage());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $obj = User::find(encrypt_decrypt($id, 'decrypt'));
        $roles = Role::orderBy('title')->get();
        $title = __('index.edit_user');
        return view('pages.user.addEdit', compact('obj','title','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'role' => 'required',
                'name' => 'required|max:50',
                'designation' => 'required|max:50',
                'phone_number' => 'required|max:50',
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
                'status' => 'required',
                'password' => 'min:6',
            ],
            [
                'role.required' => __('index.role_required'),
                'name.required' => __('index.name_required'),
                'name.max' => __('index.name_max'),
                'designation.required' => __('index.designation_required'),
                'designation.max' => __('index.designation_max'),
                'phone_number.required' => __('index.phone_required'),
                'phone_number.max' => __('index.phone_max'),
                'email.required' => __('index.email_required'),
                'email.regex' => __('index.email_validation'),
                'status.required' => __('index.status_required'),
                'password.min' => __('index.password_min'),
            ]
        );

        $row = User::find($id);
        $row->company_id = auth()->user()->company_id;
        $row->role = 2;
        $row->type = 'User';
        $row->designation = $request->designation;
        $row->name = $request->name;
        $row->phone_number = $request->phone_number;
        $row->email = $request->email;
        $row->status = $request->status;
        $row->permission_role = $request->role;
        $row->salary = null_check($request->salary);
        $row->is_first_login = 1;
        $row->company_id = 1;
        $row->save();

        if($request->password != null){
            
            $row->password = Hash::make($request->password);
        }
        $row->save();

        return redirect()->route('user.index')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = User::find($id);
        $obj->del_status = "Deleted";
        $obj->email = $obj->email.'-deleted';
        $obj->save();
        return redirect()->route('user.index')->with(deleteMessage());
    }
}
