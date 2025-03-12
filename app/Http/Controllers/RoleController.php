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
  # This is RoleController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Menu;
use App\MenuActivity;
use App\Role;
use App\RolePermission;
use App\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __('index.list_role');
        $results = Role::latest()->get();
        return view('pages.role.index', compact('title', 'results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_role');
        $menus = Menu::with([
            'activities' => function ($query) {
                $query->where('is_dependant', "No");
            },
        ])->get();
        return view('pages.role.addEdit', compact('title', 'menus'));
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
                'title' => 'required|max:191',
            ],
            [
                'title.required' => __('index.role_name_required'),
                'title.max' => __('index.role_name_max'),
            ]
        );
        $role = new Role();
        $role->title = $request->title;
        $role->save();
        $activity_ids = $request->activity_id;

        if (isset($activity_ids)) {
            foreach ($activity_ids as $activity_id) {
                $menu_id = MenuActivity::find($activity_id)->menu_id;
                $request_activity = [
                    'role_id' => $role->id,
                    'menu_id' => $menu_id,
                    'activity_id' => $activity_id,
                ];
                RolePermission::updateOrInsert($request_activity, $request_activity);
            }

            foreach (MenuActivity::where('is_dependant', "Yes")->get() as $activity) {
                $menu_id = MenuActivity::find($activity->id)->menu_id;
                $dependant_activity = [
                    'role_id' => $role->id,
                    'menu_id' => $menu_id,
                    'activity_id' => $activity->id,
                ];
                RolePermission::updateOrInsert($dependant_activity, $dependant_activity);
            }
        }
        return redirect()->route('role.index')->with(saveMessage());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_role');
        $data = $role;
        $menus = Menu::with([
            'activities' => function($query) {
                $query->where('is_dependant',"No");
            }
        ])->get();
        return view('pages.role.addEdit',compact('title','data','menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request,
        [
            'title'=>'required|max:191'
        ],
        [
            'title.required'=>__('index.role_name_required'),
            'title.max'=>__('index.role_name_max')
        ]);
        $role->title = $request->title;
        $role->save();
        $activity_ids = $request->activity_id;
        if(isset($activity_ids)) {
            RolePermission::whereIn('role_id',array($role->id))->delete();
            foreach ($activity_ids as $activity_id) {
                $menu_id = MenuActivity::find($activity_id)->menu_id;
                $request_activity = [
                    'role_id' => $role->id,
                    'menu_id' => $menu_id,
                    'activity_id' => $activity_id
                ];
                RolePermission::updateOrInsert($request_activity,$request_activity);
            }

           foreach (MenuActivity::where('is_dependant',"Yes")->get() as $activity) {
               $menu_id = MenuActivity::find($activity->id)->menu_id;
               $dependant_activity = [
                   'role_id' => $role->id,
                   'menu_id' => $menu_id,
                   'activity_id' => $activity->id
               ];
               RolePermission::updateOrInsert($dependant_activity,$dependant_activity);
           }
        }
        return redirect()->route('role.index')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if(User::where('permission_role',$role->id)->where('del_status','!=','Deleted')->exists()) {
            return redirect()->route('role.index')->with(deleteMessage($role->title." has been assigned."));
        }else {
            RolePermission::whereIn('role_id',array($role->id))->delete();
            $role->delete();
            return redirect()->route('role.index')->with(deleteMessage());
        }
    }
}
