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
  # This is FPCategoryController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\FPCategory;
use Illuminate\Http\Request;

class FPCategoryController extends Controller
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
        $obj = FPCategory::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.product_categories');
        return view('pages.fpcategory.fpcategories',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_product_category');
        return view('pages.fpcategory.addEditFPCategory',compact('title'));
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

        $obj = new \App\FPCategory;
        $obj->name = escape_output($request->get('name'));
        $obj->description = escape_output($request->get('description'));
        $obj->save();
        return redirect('fpcategories')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FPCategory  $fpcategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fpcategory = FPCategory::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_product_category');
        $obj = $fpcategory;
        return view('pages.fpcategory.addEditFPCategory',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FPCategory  $fpcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FPCategory $fpcategory)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $fpcategory->name = escape_output($request->get('name'));
        $fpcategory->description = escape_output($request->get('description'));
        $fpcategory->save();
        return redirect('fpcategories')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FPCategory  $fpcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(FPCategory $fpcategory)
    {
        $fpcategory->del_status = "Deleted";
        $fpcategory->save();
        return redirect('fpcategories')->with(deleteMessage());
    }
}
