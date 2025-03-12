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
  # This is RawMaterialController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\RawMaterial;
use App\RawMaterialCategory;
use App\RMPurchase_model;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialController extends Controller
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
        $obj = RawMaterial::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $title = __('index.raw_materials');
        return view('pages.rawmaterial.rawmaterials', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_raw_material');
        $categories = RawMaterialCategory::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $obj_rm = RawMaterial::count();

        //generate code
        $code = "RM-" . str_pad($obj_rm + 1, 3, '0', STR_PAD_LEFT);

        return view('pages.rawmaterial.addEditRawMaterial', compact('title', 'units', 'categories', 'code'));
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
            'name' => 'required|max:150',
            'code' => 'required|max:20',
            'category' => 'required|max:50',
            'consumption_unit' => 'max:50',
            'unit' => 'required|max:50',
            'rate_per_unit' => 'required|max:100',
            'consumption_check' => 'max:100',
            'conversion_rate' => 'max:100',
            'rate_per_consumption_unit' => 'max:100',
        ]);

        $obj = new \App\RawMaterial();
        $obj->name = escape_output($request->get('name'));
        $obj->code = escape_output($request->get('code'));
        $obj->category = null_check(escape_output($request->get('category')));
        $obj->consumption_unit = null_check(escape_output($request->get('consumption_unit')));
        $obj->unit = null_check(escape_output($request->get('unit')));
        $obj->rate_per_unit = null_check(escape_output($request->get('rate_per_unit')));
        $obj->consumption_check = null_check(escape_output($request->get('consumption_check')));
        $obj->conversion_rate = null_check(escape_output($request->get('conversion_rate')));
        $obj->rate_per_consumption_unit = null_check(escape_output($request->get('rate_per_consumption_unit')));
        $obj->opening_stock = null_check(escape_output($request->get('opening_stock')));
        $obj->alert_level = null_check(escape_output($request->get('alert_level')));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        return redirect('rawmaterials')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RawMaterial  $rawmaterial
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rawmaterial = RawMaterial::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_raw_material');
        $categories = RawMaterialCategory::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $obj = $rawmaterial;
        return view('pages.rawmaterial.addEditRawMaterial', compact('title', 'obj', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RawMaterial  $rawmaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RawMaterial $rawmaterial)
    {
        request()->validate([
            'name' => 'required|max:150',
            'code' => 'required|max:20',
            'category' => 'required|max:50',
            'consumption_unit' => 'max:50',
            'unit' => 'required|max:50',
            'rate_per_unit' => 'required|max:100',
            'consumption_check' => 'max:100',
            'conversion_rate' => 'max:100',
            'rate_per_consumption_unit' => 'max:100',
            'opening_stock' => 'required|max:100',
            'alert_level' => 'required|max:50',
        ]);

        $rawmaterial->name = escape_output($request->get('name'));
        $rawmaterial->code = escape_output($request->get('code'));
        $rawmaterial->category = null_check(escape_output($request->get('category')));
        $rawmaterial->consumption_unit = null_check(escape_output($request->get('consumption_unit')));
        $rawmaterial->unit = null_check(escape_output($request->get('unit')));
        $rawmaterial->rate_per_unit = null_check(escape_output($request->get('rate_per_unit')));
        $rawmaterial->consumption_check = null_check(escape_output($request->get('consumption_check')));
        $rawmaterial->conversion_rate = null_check(escape_output($request->get('conversion_rate')));
        $rawmaterial->rate_per_consumption_unit = null_check(escape_output($request->get('rate_per_consumption_unit')));
        $rawmaterial->opening_stock = null_check(escape_output($request->get('opening_stock')));
        $rawmaterial->alert_level = null_check(escape_output($request->get('alert_level')));
        $rawmaterial->added_by = auth()->user()->id;
        $rawmaterial->save();
        return redirect('rawmaterials')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RawMaterial  $rawmaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(RawMaterial $rawmaterial)
    {
        $rawmaterial->del_status = "Deleted";
        $rawmaterial->save();
        return redirect('rawmaterials')->with(deleteMessage());
    }

    /**
     * Price History
     */
    public function priceHistory(Request $request)
    {
        $raw_material = encrypt_decrypt($request->get('raw_material'), 'decrypt');
        $rawMaterials = RawMaterial::where('del_status', "Live")->get();
        if($raw_material)
        {
            $obj = RawMaterial::whereHas('purchase')->orderBy('name', 'ASC')->where('del_status', "Live")->where('id', $raw_material)->get();
        }else{
            $obj = null;
        }
        $title = __('index.price_history');
        return view('pages.rawmaterial.priceHistory', compact('title', 'obj', 'rawMaterials'));

    }
}
