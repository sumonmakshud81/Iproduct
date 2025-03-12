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
  # This is UnitController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Unit::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.unit');
        return view('pages.unit.units',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_unit');
        return view('pages.unit.addEditUnit',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $obj = new \App\Unit;
        $obj->name = escape_output($request->get('name'));
        $obj->description = escape_output($request->get('description'));
        $obj->save();
        return redirect('units')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_unit');
        $obj = $unit;
        return view('pages.unit.addEditUnit',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $unit->name = escape_output($request->get('name'));
        $unit->description = escape_output($request->get('description'));
        $unit->save();
        return redirect('units')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->del_status = "Deleted";
        $unit->save();
        return redirect('units')->with(deleteMessage());
    }
}
