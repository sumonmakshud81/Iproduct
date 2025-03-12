<?php
/*
##############################################################################
# iProduction - Production and Manufacture Management Software
##############################################################################
# AUTHOR:        Door Soft
##############################################################################
# EMAIL:        info@doorsoft.co
##############################################################################
# COPYRIGHT:        RESERVED BY Door Soft
##############################################################################
# WEBSITE:        https://www.doorsoft.co
##############################################################################
# This is StockController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Customer;
use App\FinishedProduct;
use App\FPrmitem;
use App\Manufacture;
use App\Mrmitem;
use App\RawMaterial;
use App\RawMaterialCategory;
use App\Stock;
use App\StockAdjustLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRMStock(Request $request)
    {
        $product_id_array = $request->product_id;
        $quantity = $request->quantity;
        $category_id = escape_output($request->get('category_id'));
        $product_id = escape_output($request->get('finish_p_id'));
        $manufacture_id = escape_output($request->get('manufacture_id'));

        $obj1 = new Stock();
        $obj = $obj1->getRMStock($category_id);
        if ($product_id != '') {
            $rm_id = [];
            $rm = new FPrmitem();
            $rmObj = $rm->getFinishProductRM($product_id);
            foreach ($rmObj as $key => $value) {
                $rm_id[] = $value->rmaterials_id;
            }

            $obj = $obj->filter(function ($v) use ($rm_id) {
                return in_array($v->id, $rm_id);
            });
        }

        if ($manufacture_id != '') {
            $mrm = Mrmitem::where('manufacture_id', $manufacture_id)->pluck('rmaterials_id');
            $obj = $obj->filter(function ($v) use ($mrm) {
                return in_array($v->id, $mrm->toArray());
            });
        }
        $rmCategory = RawMaterialCategory::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $finishProduct = FinishedProduct::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $manufactureProduct = Manufacture::orderBy('id', 'DESC')
            ->where('manufacture_status', 'inProgress')
            ->where('del_status', "Live")
            ->get();

        $title = __('index.rm_stocks');

        if (!empty($product_id_array) && is_array($product_id_array)) {
            $rm_id = [];
            $quantity_array = [];
            $consumption = [];

            foreach ($product_id_array as $key => $value) {
                $rm = new FPrmitem();
                $rmObj = $rm->getFinishProductRM($value);
                $quantity_array[] = $quantity[$key];
                foreach ($rmObj as $rmData) {
                    $rm_id[$value][] = $rmData->rmaterials_id;
                    $consumption[$value][$rmData->rmaterials_id] = $rmData->consumption;
                }
            }

            $result = [];
            foreach ($rm_id as $key => $rm_value) {
                $filtered = $obj->filter(function ($v) use ($rm_value) {
                    return in_array($v->id, $rm_value);
                });
                $result[$key] = $filtered;
            }
            $obj = $result;

            $m = 0;
            $_required = [];
            foreach ($obj as $k => $value) {
                foreach ($value as $key => $v) {
                    $_required[$k][$v->id] = $consumption[$k][$v->id] * $quantity_array[$m];
                }
                $m++;
            }

            return view('pages.stock.stockCheck', compact(
                'title', 'product_id', 'quantity', 'obj', 'rmCategory',
                'finishProduct', 'category_id', 'product_id', 'manufactureProduct',
                'manufacture_id', '_required'
            ));
        }
        return view('pages.stock.stocks', compact(
            'title', 'obj', 'rmCategory', 'finishProduct',
            'category_id', 'product_id', 'manufactureProduct', 'manufacture_id'
        ));
    }

    /**
     * Display Stock Adjust List
     */

    public function stockAdjust()
    {
        $title = __('index.stock_adjustment');
        $obj1 = new Stock();
        $rmaterials = $obj1->getRMStock();
        return view('pages.stock.stockAdjustList', compact('rmaterials', 'title'));
        
    }

    /**
     * Stock Adjust Post
     */

    public function stockAdjustPost(Request $request)
    {
        $request->validate([
            'rmaterial' => 'required',
            'type' => 'required',
            'quantity' => 'required|numeric|min:1',
        ],
            [
                'rmaterial.required' => __('index.raw_material_required'),
                'type.required' => __('index.type_required'),
                'quantity.required' => __('index.quantity_required'),
                'quantity.numeric' => __('index.quantity_numeric'),
                'quantity.min' => __('index.quantity_min'),
            ]);

        $rawMaterialData = explode('|', $request->rmaterial);
        $rm_id = $rawMaterialData[0];
        $currentStock = $rawMaterialData[6];
        $type = $request->type;
        $quantity = $request->quantity;
        if ($type == 'subtraction') {
            if ($quantity > $currentStock) {
                return redirect()->back()->with(dangerMessage(__('index.you_cannot_subtract')));
            }
        }
        $stockAdjustLog = new StockAdjustLog();
        $stockAdjustLog->rm_id = $rm_id;
        $stockAdjustLog->type = $type;
        $stockAdjustLog->quantity = $quantity;
        $stockAdjustLog->save();

        return redirect()->route('stockAdjustList')->with(saveMessage());
    }

    /**
     * Stock Check
     */

    public function rawMaterialStockCheck(Request $request)
    {
        $product_id = escape_output($request->product_id);
        $quantity = escape_output($request->quantity);

        $rm = FPrmitem::where('finish_product_id', $product_id)->where('del_status', 'Live')->get();

        $stock = [];
        foreach ($rm as $r) {
            $consumptionQuantity = $r->rawMaterials->consumption_check == 1 ? $r->consumption / $r->rawMaterials->conversion_rate : $r->consumption;
            $stock[] = [
                'id' => $r->rawMaterials->id,
                'name' => $r->rawMaterials->name,
                'stock' => $r->rawMaterials->current_stock,
                'required' => $consumptionQuantity * $quantity,
                'status' => $r->rawMaterials->current_stock >= $consumptionQuantity * $quantity ? 'in_stock' : 'need_purchase',
            ];
        }

        return response()->json($stock);
    }

    /**
     * Raw Material Stock Check By Material
     */

    public function rawMaterialStockCheckByMaterial(Request $request)
    {
        $rm_id = $request->rm_id;
        $quantity = $request->quantity;

        $stock = [];
        foreach ($rm_id as $key => $value) {
            $rm = RawMaterial::find($value);
            $consumptionQuantity = $rm->consumption_check == 1 ? $quantity[$key] / $rm->conversion_rate : $quantity[$key];
            $stock[] = [
                'id' => $rm->id,
                'name' => $rm->name,
                'stock_final' => $rm->current_stock_final,
                'stock' => $rm->current_stock,
                'required' => $consumptionQuantity,
                'status' => $rm->current_stock >= $consumptionQuantity ? 'in_stock' : 'need_purchase',
                'unit' => getRMUnitById($rm->unit),
            ];
        }

        return response()->json($stock);
    }

    /**
     * Check Single Raw Material Stock
     */

    public function checkSingleMaterialStock(Request $request)
    {
        $rm_id = escape_output($request->material_id);
        $quantity = escape_output($request->quantity);

        $rm = RawMaterial::find($rm_id);
        $consumptionQuantity = $rm->consumption_check == 1 ? $quantity / $rm->conversion_rate : $quantity;
        $stock = [
            'id' => $rm->id,
            'name' => $rm->name,
            'stock' => $rm->current_stock_final,
            'required' => $quantity,
            'consumption' => $consumptionQuantity,
            'status' => $rm->current_stock >= $consumptionQuantity ? 'in_stock' : 'need_purchase',
        ];

        return response()->json($stock);
    }

    /**
     * Download Stock Check
     */
    public function downloadStockCheck(Request $request)
    {
        $reference_no = escape_output($request->reference_no);
        $order_type = escape_output($request->order_type);
        $customer = escape_output($request->customer);
        $productData = $request->productData;

        $customer = Customer::find($customer);
        $date = date('Y-m-d');

        $pdf = Pdf::loadView('pages.stock.stock_download', compact('reference_no', 'order_type', 'customer', 'productData', 'date'));
        return $pdf->download('stock.pdf');
    }

    /**
     * downloadEstimateCost
     */

    public function downloadEstimateCost(Request $request)
    {
        $reference_no = escape_output($request->reference_no);
        $order_type = escape_output($request->order_type);
        $customer = escape_output($request->customer);
        $productData = $request->productData;
        $customer = Customer::find($customer);
        $date = date('Y-m-d');
        $pdf = Pdf::loadView('pages.stock.estimate_cost', compact('reference_no', 'order_type', 'customer', 'productData', 'date'));
        return $pdf->download('estimate_cost.pdf');
        // return urldecode($productData[0]['cost']);
    }

    /**
     * Stock Adjustment List
     */
    public function stockAdjustList()
    {
        $title = __('index.stock_adjustment');
        $obj = StockAdjustLog::orderBy('id', 'DESC')->where('del_status', 'Live')->get();
        return view('pages.stock.stockAdjustmentList', compact('title', 'obj'));
    }

    /**
     * Stock Adjustment Edit
     */

    public function stockAdjustEdit($id)
    {
        $title = __('index.stock_adjustment');
        $obj = StockAdjustLog::find(encrypt_decrypt($id, 'decrypt'));
        $obj1 = new Stock();
        $rmaterials = $obj1->getRMStock();
        return view('pages.stock.stockAdjustList', compact('title', 'obj', 'rmaterials'));
    }

    /**
     * Stock Adjustment Update
     */

    public function stockAdjustUpdate(Request $request, $id)
    {
        $request->validate([
            'rmaterial' => 'required',
            'type' => 'required',
            'quantity' => 'required|numeric|min:1',
        ],
            [
                'rmaterial.required' => __('index.raw_material_required'),
                'type.required' => __('index.type_required'),
                'quantity.required' => __('index.quantity_required'),
                'quantity.numeric' => __('index.quantity_numeric'),
                'quantity.min' => __('index.quantity_min'),
            ]);

        $rawMaterialData = explode('|', $request->rmaterial);
        $rm_id = $rawMaterialData[0];
        $currentStock = $rawMaterialData[6];
        $type = $request->type;
        $quantity = $request->quantity;
        if ($type == 'subtraction') {
            if ($quantity > $currentStock) {
                return redirect()->back()->with(dangerMessage(__('index.you_cannot_subtract')));
            }
        }
        $stockAdjustLog = StockAdjustLog::find(encrypt_decrypt($id, 'decrypt'));
        $stockAdjustLog->rm_id = $rm_id;
        $stockAdjustLog->type = $type;
        $stockAdjustLog->quantity = $quantity;
        $stockAdjustLog->save();

        return redirect()->route('stockAdjustList')->with(updateMessage());
    }

    /**
     * Get Low Stock
     */

    public function getLowStock()
    {
        $title = __('index.low_stock');
        $obj1 = new Stock();
        $obj = $obj1->getLowRMStock();
        return view('pages.stock.lowStock', compact('title', 'obj'));
    }
}
