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
  # This is ProductionStageController
  ##############################################################################
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductionStage;

class ProductionStageController extends Controller
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
        $obj = ProductionStage::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.production_stage');
        return view('pages.productionstage.productionstages',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_production_stage');
        return view('pages.productionstage.addEditProductionStage',compact('title'));
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

        $obj = new \App\ProductionStage();
        $obj->name = escape_output($request->get('name'));
        $obj->description = escape_output($request->get('description'));
        $obj->save();
        return redirect('productionstages')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productionstage = ProductionStage::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_production_stage');
        $obj = $productionstage;
        return view('pages.productionstage.addEditProductionStage',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductionStage $productionstage)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $productionstage->name = escape_output($request->get('name'));
        $productionstage->description = escape_output($request->get('description'));
        $productionstage->save();
        return redirect('productionstages')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionStage $productionstage)
    {
        $productionstage->del_status = "Deleted";
        $productionstage->save();
        return redirect('productionstages')->with(deleteMessage());
    }
}
