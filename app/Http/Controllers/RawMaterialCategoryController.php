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
  # This is RawMaterialCategoryController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\RawMaterialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = RawMaterialCategory::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.raw_material_categories');
        return view('pages.rmcategory.rmcategories',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_raw_material_category');
        return view('pages.rmcategory.addEditRMCategory',compact('title'));
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

        $obj = new \App\RawMaterialCategory;
        $obj->name = escape_output($request->get('name'));
        $obj->description = escape_output($request->get('description'));
        $obj->save();
        return redirect('rmcategories')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RawMaterialCategory  $rmcategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rmcategory = RawMaterialCategory::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_raw_material_category');
        $obj = $rmcategory;
        return view('pages.rmcategory.addEditRMCategory',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RawMaterialCategory  $rmcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RawMaterialCategory $rmcategory)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $rmcategory->name = escape_output($request->get('name'));
        $rmcategory->description = escape_output($request->get('description'));
        $rmcategory->save();
        return redirect('rmcategories')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RawMaterialCategory  $rmcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(RawMaterialCategory $rmcategory)
    {
        $rmcategory->del_status = "Deleted";
        $rmcategory->save();
        return redirect('rmcategories')->with(deleteMessage());
    }
}
