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
  # This is NonInventoryItemController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\NonIItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonInventoryItemController extends Controller
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
        $obj = NonIItem::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.non_inventory_items');
        return view('pages.non_inventory_item.nonIItems',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_non_inventory_bill');
        return view('pages.non_inventory_item.addEditNonIItem',compact('title'));
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

        $obj = new \App\NonIItem;
        $obj->name = escape_output($request->get('name'));
        $obj->description = escape_output($request->get('description'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        return redirect('noninventoryitems')->with(saveMessage());
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NonIItem  $nonIItem
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $noninventoryitem = NonIItem::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_non_inventory_item');
        $obj = $noninventoryitem;
        return view('pages.non_inventory_item.addEditNonIItem',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NonIItem  $nonIItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NonIItem $noninventoryitem)
    {
        request()->validate([
            'name' => 'required|max:50',
            'description' => 'max:250'
        ]);

        $noninventoryitem->name = escape_output($request->get('name'));
        $noninventoryitem->description = escape_output($request->get('description'));
        $noninventoryitem->added_by = auth()->user()->id;
        $noninventoryitem->save();
        return redirect('noninventoryitems')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NonIItem  $nonIItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(NonIItem $noninventoryitem)
    {
        $noninventoryitem->del_status = "Deleted";
        $noninventoryitem->save();
        return redirect('noninventoryitems')->with(deleteMessage());
    }
}
