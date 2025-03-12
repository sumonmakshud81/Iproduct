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
  # This is ProductionLossController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Manufacture;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionLossController extends Controller
{
    public function index()
    {
        $title = __('index.add_production_loss');

        $users = User::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = Manufacture::orderBy('id', 'ASC')->where('manufacture_status', 'done')->where('del_status', "Live")->get();
        return view('pages.production_loss.index', compact('title', 'users', 'manufactures'));
    }

    public function productionData(Request $request)
    {
        $manufacture_id = $request->manufacture;

        $manufacture = Manufacture::find($manufacture_id);

        $product = $manufacture->product;

        $product_table = "<tr class='rowCount'>";
        $k = 1;
        //sn
        $product_table .= "<td class='text-start'>" . $k++ . "</td>";
        $product_table .= "<td>" . $product->name . "(" . $product->code . ")</td>";
        //manufacture qty
        $product_table .= "<td><strong>" . $manufacture->product_quantity . "" . getRMUnitById($product->unit) . "</strong><input type='hidden' name='manufacture_qty[]' id='pmanufacture_qty_1' class='form-control cal_row manufacture_qty_c' value='" . $manufacture->product_quantity . "'></td>";
        //input field
        $product_table .= "<td>
            <input type='hidden' name='product_id[]' value='" . $product->id . "'>
            <input type='hidden' tabindex='5' name='punit_price[]' class='form-control integerchk unit_price_c cal_row' id='punit_price_1' value='" . $product->total_cost . "'>
            <div class='input-group'>
                <input type='number' id='pqty_1' name='quantity[]' class='form-control cal_row qty_c' value='1'>
                <div class='input-group-append'>
                    <span class='input-group-text'>" . getRMUnitById($product->unit) . "</span>
                </div>
            </div>
            <div class='text-danger loss_qty_error'></div>
        </td>";
        //loss amount field
        $product_table .= "<td>
            <div class='input-group'>
                <input type='text' id='ptotal_1' name='loss_amount[]' class='form-control total_c cal_total' value=''>
                <div class='input-group-append'>
                    <span class='input-group-text'>" . getSettingsInfo()->currency . "</span>
                </div>
            </div>
            <div class='text-danger p_loss_total_error'></div>
        </td>";
        //delete action
        $product_table .= "<td class='ir_txt_center'><button type='button' class='btn btn-xs del_row dlt_button'><iconify-icon icon='solar:trash-bin-minimalistic-broken'></iconify-icon></button></td>";
        $product_table .= "</tr>";

        $rawMaterials = $manufacture->rawMaterials;

        $materialsTable = "";
        $i = 1;
        $j = 1;
        foreach ($rawMaterials as $rawMaterial) {            
            $material = $rawMaterial->rawMaterial;
            $materialsTable .= "<tr class='rowCount'>";
            //sn
            $materialsTable .= "<td class='text-start'>" . $i . "</td>";
            $materialsTable .= "<td>" . $material->name . "(" . $material->code . ")</td>";
            //manufacture qty
            $materialsTable .= "<td><strong>" . $rawMaterial->consumption . "" . getManufactureUnitByRMID($material->id) . "</strong><input type='hidden' name='manufacture_qty[]' id='rmanufacture_qty_" . $j . "' class='form-control cal_row manufacture_qty_c' value='" . $rawMaterial->consumption . "'></td>";
            //input field
            $materialsTable .= "<td>
                <input type='hidden' name='material_id[]' value='" . $material->id . "'>
                <input type='hidden' tabindex='5' name='unit_price[]' class='form-control integerchk unit_price_c cal_row' id='runit_price_" . $j . "' value='" . $material->rate_per_unit . "'>
                <div class='input-group'>
                    <input type='number' name='material_quantity[]' id='rqty_" . $j . "' class='form-control cal_row qty_c rmqty' value='1'>
                    <div class='input-group-append'>
                        <span class='input-group-text'>" . getManufactureUnitByRMID($material->id) . "</span>
                    </div>
                </div>
                <div class='text-danger rm_loss_qty_error'></div>
            </td>";
            //loss amount field
            $materialsTable .= "<td>
                <div class='input-group'>
                    <input type='text' id='rtotal_" . $j . "' name='material_loss_amount[]' class='form-control total_c cal_total rmtotal' value=''>
                    <div class='input-group-append'>
                        <span class='input-group-text'>" . getSettingsInfo()->currency . "</span>
                    </div>
                </div>
                <div class='text-danger rm_loss_total_error'></div>
            </td>";
            //delete action
            $materialsTable .= "<td class='ir_txt_center'><button type='button' class='btn btn-xs del_row dlt_button'><iconify-icon icon='solar:trash-bin-minimalistic-broken'></iconify-icon></button></td>";

            $materialsTable .= "</tr>";
            $i++;
            $j++;

        }

        $return = [
            'product_table' => $product_table,
            'materials_table' => $materialsTable,
        ];

        return response()->json($return);
    }

    public function store(Request $request)
    {    
        $request->validate([
            'date' => 'required',
            'manufacture' => 'required',
            'responsible_person' => 'required',
        ]);
        $total_product_loss = 0;
        DB::beginTransaction();
        $obj1 = new \App\ProductWaste;
        $ref_no = "PW-" . str_pad($obj1->count() + 1, 6, '0', STR_PAD_LEFT);
        $obj1->reference_no = $ref_no;
        $obj1->date = $request->get('date');
        $obj1->responsible_person = $request->get('responsible_person');
        $obj1->total_loss = $total_product_loss;
        $obj1->note = $request->get('note');
        $obj1->user_id = auth()->user()->id;
        $obj1->company_id = auth()->user()->company_id;
        $obj1->manufacture_id = $request->manufacture;
        $obj1->save();
        foreach ($request->product_id as $key => $value) {
            $total_product_loss += $request->loss_amount[$key];            

            $last_id = $obj1->id;

            // Waste Items
            $obj = new \App\ProductWasteItems;
            $obj->finish_product_id = $value;
            $obj->last_purchase_price = $request->punit_price[$key];
            $obj->fp_waste_amount = $request->quantity[$key];
            $obj->loss_amount = $request->loss_amount[$key];
            $obj->fpwaste_id = $last_id;
            $obj->company_id = auth()->user()->company_id;
            $obj->save();
        }        

        $totalRmWaste = 0;
        $rmobj = new \App\RMWaste;
        $ref_no = "RMW-" . str_pad($rmobj->count() + 1, 6, '0', STR_PAD_LEFT);
        $rmobj->reference_no = $ref_no;
        $rmobj->date = $request->get('date');
        $rmobj->responsible_person = $request->get('responsible_person');
        $rmobj->total_loss = $totalRmWaste;
        $rmobj->note = $request->get('note');
        $rmobj->added_by = auth()->user()->id;
        $rmobj->save();
        $last_id = $rmobj->id;
        foreach ($request->material_id as $key => $value) {
            $totalRmWaste += $request->material_loss_amount[$key];
            //Rm ID
            $obj = new \App\RMWasteItem_model;
            $obj->rmaterials_id = $value;
            $obj->last_purchase_price = $request->unit_price[$key];
            $obj->waste_amount = $request->material_quantity[$key];
            $obj->loss_amount = $request->material_loss_amount[$key];
            $obj->waste_id = $last_id;
            $obj->manufacture_id = $request->manufacture;
            $obj->save();

        }

        $rmobj->total_loss = $totalRmWaste;
        $rmobj->save();

        $manufacture = Manufacture::find($request->manufacture);
        $manufacture->production_loss = $total_product_loss + $totalRmWaste;
        $manufacture->save();
        DB::commit();
        return redirect()->route('production-loss-report')->with(saveMessage());

    }

    public function productionLossReport()
    {
        $title = __('index.production_loss_report');
        
        //get manufacture where has raw material waste and product waste
        $obj = Manufacture::with(['materialWaste', 'productWaste', 'productWaste.productItems'])->whereHas('materialWaste')->whereHas('productWaste')->orderBy('id', 'DESC')->where('del_status', "Live")->get();
        return view('pages.production_loss.production_loss_report', compact('title', 'obj'));
    }
}
