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
# This is ProductionController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\Customer;
use App\FinishedProduct;
use App\FPproductionstage;
use App\Manufacture;
use App\Mnonitem;
use App\Mrmitem;
use App\Mstages;
use App\NonIItem;
use App\ProductionHistory;
use App\ProductionScheduling;
use App\ProductionStage;
use App\RawMaterial;
use App\Tax;
use App\TaxItems;
use App\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = request()->get('status');
        $product_id = request()->get('finish_p_id');
        $batch_no = request()->get('batch_no');
        $customer = request()->get('customer');

        $obj = Manufacture::orderBy('id', 'DESC')
            ->status($status)
            ->product($product_id)
            ->batchNo($batch_no)
            ->customer($customer)
            ->where('del_status', "Live")
            ->get();
        $title = __('index.manufactures');
        $finishProduct = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        return view('pages.manufacture.manufactures', compact('title', 'obj', 'finishProduct', 'customers', 'status', 'product_id', 'batch_no', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_manufacture');
        $obj_rm = Manufacture::count();
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rm = RawMaterial::all();
        $ref_no = "MP-" . str_pad($obj_rm + 1, 6, '0', STR_PAD_LEFT);
        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        return view('pages.manufacture.addEditManufacture', compact('title', 'ref_no', 'rmaterials', 'p_stages', 'nonitem', 'tax_fields', 'units', 'tax_items', 'manufactures', 'rm', 'accounts', 'customers'));
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
            'manufacture_type' => 'required|max:150',
            'reference_no' => 'required|max:50',
            'manufacture_status' => 'required|max:50',
            'product_id' => 'required|max:50',
            'product_quantity' => 'required|max:50',
            'start_date_m' => 'required',
            'complete_date_m' => 'required_if:manufacture_status,done',
            'file_button.*' => 'max:5120|mimes:jpeg,jpg,png,gif,doc,docx,pdf,txt',
        ]);

        DB::beginTransaction();
        try {
            $product_id = $request->get('product_id');
            $p_id = explode('|', $product_id);
            $obj = new \App\Manufacture();
            $obj->reference_no = null_check(escape_output($request->get('reference_no')));
            $obj->manufacture_type = escape_output($request->get('manufacture_type'));
            $obj->manufacture_status = escape_output($request->get('manufacture_status'));
            $obj->product_id = null_check(escape_output($p_id[0]));
            $obj->product_quantity = null_check(escape_output($request->get('product_quantity')));
            $obj->batch_no = null_check(escape_output($request->get('batch_no')));
            $obj->expiry_days = null_check(escape_output($request->get('expiry_days')));
            $obj->start_date = escape_output($request->get('start_date_m'));
            $obj->complete_date = escape_output($request->get('complete_date_m')) ?? null;
            $obj->mrmcost_total = null_check(escape_output($request->get('mrmcost_total')));
            $obj->mnoninitem_total = null_check(escape_output($request->get('mnoninitem_total')));
            $obj->mtotal_cost = null_check(escape_output($request->get('mtotal_cost')));
            $obj->mprofit_margin = null_check(escape_output($request->get('mprofit_margin')));
            $obj->msale_price = null_check(escape_output($request->get('msale_price')));
            $obj->note = escape_output($request->get('note'));
            $obj->stage_counter = null_check(escape_output($request->get('stage_counter')));
            $obj->stage_name = escape_output($request->get('stage_name'));

            if ($request->get('manufacture_type') == 'fco') {
                $obj->customer_id = null_check(escape_output($request->get('customer_id')));
                $obj->customer_order_id = null_check(escape_output($request->get('customer_order_id')));
            }

            $file = '';
            if ($request->hasFile('file_button')) {
                $files = $request->file('file_button');
                $fileNames = [];
                foreach ($files as $file) {
                    $filename = $file->getClientOriginalName();
                    $fileNames[] = time() . "_" . $filename;
                    $file->move(base_path('uploads/manufacture'), $fileNames[count($fileNames) - 1]);
                }
                $obj->file = implode(',', $fileNames);
            }

            //generate json data for tax value
            $tax_information = array();
            if (!empty($_POST['tax_field_percentage'])) {
                foreach ($_POST['tax_field_percentage'] as $key => $value) {
                    $single_info = array(
                        'tax_field_id' => escape_output($_POST['tax_field_id'][$key]),
                        'tax_field_name' => escape_output($_POST['tax_field_name'][$key]),
                        'tax_field_percentage' => ($_POST['tax_field_percentage'][$key] == "") ? 0 : escape_output($_POST['tax_field_percentage'][$key]),
                    );
                    array_push($tax_information, $single_info);
                }
            }
            $tax_information = json_encode($tax_information);
            //end
            $obj->tax_information = $tax_information;
            $obj->added_by = auth()->user()->id;

            $obj->save();
            $last_id = $obj->id;
            $rm_id = $request->get('rm_id');
            foreach ($rm_id as $row => $value) {
                $rmId = explode('|', $value);
                $obj = new \App\Mrmitem();
                $obj->rmaterials_id = null_check($rmId[0]);
                $obj->unit_price = null_check(escape_output($_POST['unit_price'][$row]));
                $obj->consumption = null_check(escape_output($_POST['quantity_amount'][$row]));
                $obj->total_cost = null_check(escape_output($_POST['total'][$row]));
                $obj->manufacture_id = null_check($last_id);

                $obj->save();
            }
            $noniitem_id = $request->get('noniitem_id');
            if (isset($noniitem_id) && $noniitem_id) {
                foreach ($noniitem_id as $row => $value) {
                    $noiId = explode('|', $value);
                    $obj = new \App\Mnonitem();
                    $obj->noninvemtory_id = null_check($noiId[0]);
                    $obj->nin_cost = null_check(escape_output($_POST['total_1'][$row]));
                    $obj->account_id = null_check(escape_output($_POST['account_id'][$row]));
                    $obj->manufacture_id = null_check($last_id);
                    $obj->save();
                }
            }

            $total_months = $request->t_month;
            $total_days = $request->t_day;
            $total_hours = $request->t_hours;
            $total_minutes = $request->t_minute;
            $producstage_id = $request->get('producstage_id');
            $product_quantity = $request->get('product_quantity');
            if (isset($producstage_id) && $producstage_id) {
                foreach ($producstage_id as $row => $value) {
                    $obj = new \App\Mstages();
                    $obj->productionstage_id = null_check($value);
                    $obj->stage_month = null_check(escape_output($_POST['stage_month'][$row]));
                    $obj->stage_day = null_check(escape_output($_POST['stage_day'][$row]));
                    $obj->stage_hours = null_check(escape_output($_POST['stage_hours'][$row]));
                    $obj->stage_minute = null_check(escape_output($_POST['stage_minute'][$row]));
                    $obj->manufacture_id = null_check($last_id);
                    $obj->save();
                }
            }
            if ($request->productionstage_id != null) {
                foreach ($request->productionstage_id as $key => $value) {
                    $producstage_id = $value;
                    $producstage_id = explode('|', $producstage_id);
                    $productionScheduling = new ProductionScheduling();
                    $productionScheduling->manufacture_id = null_check($last_id);
                    $productionScheduling->production_stage_id = null_check($producstage_id[0]);
                    $productionScheduling->task = $request->task[$key];
                    $productionScheduling->start_date = $request->start_date[$key];
                    $productionScheduling->end_date = $request->complete_date[$key];
                    $productionScheduling->save();
                }
            }

            $str_consumed_time = "Month(s): " . $total_months . " Day(s): " . $total_days . " Hour(s): " . $total_hours . " Min.(s) :" . $total_minutes;

            //update for consumed time
            $obj = Manufacture::find($last_id);
            $obj->consumed_time = $str_consumed_time;
            $obj->save();

            //update finish product stock for done
            if ($obj->manufacture_status == 'done') {
                $finishedProduct = FinishedProduct::findOrFail($obj->product_id);

                $newStock = $finishedProduct->current_total_stock + $obj->product_quantity;

                $finishedProduct->current_total_stock = $newStock;
                $finishedProduct->save();
            }

            DB::commit();

            return redirect('productions')->with(saveMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(dangerMessage($e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manufacture = Manufacture::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.view_details_manufactures');
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $m_rmaterials = Mrmitem::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $m_nonitems = Mnonitem::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $m_stages = Mstages::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $obj = $manufacture;
        $productionScheduling = ProductionScheduling::where('manufacture_id', $manufacture->id)->get();
        return view('pages.manufacture.viewDetails', compact('title', 'obj', 'rmaterials', 'productionScheduling', 'p_stages', 'manufactures', 'nonitem', 'accounts', 'tax_fields', 'm_rmaterials', 'm_nonitems', 'm_stages', 'units', 'tax_items'));
    }

    public function printManufactureDetails($id)
    {
        $title = __('index.view_details_manufactures');
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $m_rmaterials = Mrmitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_nonitems = Mnonitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_stages = Mstages::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $obj = Manufacture::find($id);
        return view('pages.manufacture.print_manufacture_details', compact('title', 'obj', 'rmaterials', 'p_stages', 'manufactures', 'nonitem', 'accounts', 'tax_fields', 'm_rmaterials', 'm_nonitems', 'm_stages', 'units', 'tax_items'));
    }

    public function downloadManufactureDetails($id)
    {
        $id = encrypt_decrypt($id, 'decrypt');
        $title = __('index.view_details_manufactures');
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $m_rmaterials = Mrmitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_nonitems = Mnonitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_stages = Mstages::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $obj = Manufacture::find($id);

        $pdf = Pdf::loadView('pages.manufacture.print_manufacture_details', compact('title', 'obj', 'rmaterials', 'p_stages', 'manufactures', 'nonitem', 'accounts', 'tax_fields', 'm_rmaterials', 'm_nonitems', 'm_stages', 'units', 'tax_items'));
        return $pdf->download($obj->reference_no . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manufacture = Manufacture::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_manufacture');
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $m_rmaterials = Mrmitem::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $m_nonitems = Mnonitem::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $m_stages = Mstages::orderBy('id', 'ASC')->where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        $obj = $manufacture;
        $obj2 = new FPproductionstage();
        $finishProductStage = $obj->getProductStages($manufacture->id);
        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $productionScheduling = ProductionScheduling::where('manufacture_id', $manufacture->id)->where('del_status', "Live")->get();
        return view('pages.manufacture.addEditManufacture', compact('title', 'obj', 'rmaterials', 'productionScheduling', 'p_stages', 'manufactures', 'nonitem', 'accounts', 'tax_fields', 'm_rmaterials', 'm_nonitems', 'm_stages', 'units', 'tax_items', 'finishProductStage', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $manufacture = Manufacture::find($id);
        request()->validate([
            'manufacture_type' => 'required|max:150',
            'reference_no' => 'required|max:50',
            'manufacture_status' => 'required|max:50',
            'product_id' => 'required|max:50',
            'product_quantity' => 'required|max:50',
        ]);
        $manufacture->reference_no = null_check(escape_output($request->get('reference_no')));
        $manufacture->manufacture_type = escape_output($request->get('manufacture_type'));
        $manufacture->manufacture_status = escape_output($request->get('manufacture_status'));
        $manufacture->product_id = null_check(escape_output($request->get('product_id')));
        $manufacture->product_quantity = null_check(escape_output($request->get('product_quantity')));
        $manufacture->batch_no = null_check(escape_output($request->get('batch_no')));
        $manufacture->expiry_days = null_check(escape_output($request->get('expiry_days')));
        $manufacture->start_date = escape_output($request->get('start_date_m'));
        $manufacture->complete_date = escape_output($request->get('complete_date_m')) ?? null;

        $manufacture->mrmcost_total = null_check(escape_output($request->get('mrmcost_total')));
        $manufacture->mnoninitem_total = null_check(escape_output($request->get('mnoninitem_total')));
        $manufacture->mtotal_cost = null_check(escape_output($request->get('mtotal_cost')));
        $manufacture->mprofit_margin = null_check(escape_output($request->get('mprofit_margin')));
        $manufacture->msale_price = null_check(escape_output($request->get('msale_price')));
        $manufacture->note = escape_output($request->get('note'));
        $manufacture->stage_counter = null_check(escape_output($request->get('stage_counter')));
        $manufacture->stage_name = escape_output($request->get('stage_name'));

        //generate json data for tax value
        if ($request->get('manufacture_type') == 'fco') {
            $manufacture->customer_id = null_check(escape_output($request->get('customer_id')));
            $manufacture->customer_order_id = null_check(escape_output($request->get('customer_order_id')));
        }
        $file = $manufacture->file;
        if ($request->hasFile('file_button')) {
            $files = $request->file('file_button');
            $fileNames = [];
            foreach ($files as $file) {
                @unlink(base_path('uploads/manufacture/' . $file));
                $filename = $file->getClientOriginalName();
                $fileNames[] = time() . "_" . $filename;
                $file->move(base_path('uploads/manufacture'), $fileNames[count($fileNames) - 1]);
            }
            $manufacture->file = implode(',', $fileNames);
        }else{
            $manufacture->file = $file;

        }
        
        $tax_information = array();
        if (!empty($_POST['tax_field_percentage'])) {
            foreach ($_POST['tax_field_percentage'] as $key => $value) {
                $single_info = array(
                    'tax_field_id' => escape_output($_POST['tax_field_id'][$key]),
                    'tax_field_name' => escape_output($_POST['tax_field_name'][$key]),
                    'tax_field_percentage' => ($_POST['tax_field_percentage'][$key] == "") ? 0 : escape_output($_POST['tax_field_percentage'][$key]),
                );
                array_push($tax_information, $single_info);
            }
        }
        $tax_information = json_encode($tax_information);

        $manufacture->tax_information = $tax_information;
        $manufacture->save();
        $last_id = $manufacture->id;

        Mrmitem::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);
        Mnonitem::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);
        Mstages::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);
        ProductionScheduling::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);

        $rm_id = $request->get('rm_id');
        foreach ($rm_id as $row => $value) {
            $obj = new \App\Mrmitem();
            $obj->rmaterials_id = null_check($value);
            $obj->unit_price = null_check(escape_output($_POST['unit_price'][$row]));
            $obj->consumption = null_check(escape_output($_POST['quantity_amount'][$row]));
            $obj->total_cost = null_check(escape_output($_POST['total'][$row]));
            $obj->manufacture_id = null_check($last_id);
            $obj->save();
        }

        $noniitem_id = $request->get('noniitem_id');
        if (isset($noniitem_id) && $noniitem_id) {
            foreach ($noniitem_id as $row => $value) {
                $obj = new \App\Mnonitem();
                $obj->noninvemtory_id = null_check($value);
                $obj->nin_cost = null_check(escape_output($_POST['total_1'][$row]));
                $obj->account_id = null_check(escape_output($_POST['account_id'][$row]));
                $obj->manufacture_id = null_check($last_id);
                $obj->save();
            }
        }
        $total_months = $request->t_month;
        $total_days = $request->t_day;
        $total_hours = $request->t_hours;
        $total_minutes = $request->t_minute;
        $producstage_id = $request->get('producstage_id');
        $product_quantity = $request->get('product_quantity');
        if (isset($producstage_id) && $producstage_id) {
            foreach ($producstage_id as $row => $value) {
                $obj = new \App\Mstages();
                $obj->productionstage_id = $value;
                $obj->stage_month = null_check(escape_output($_POST['stage_month'][$row]));
                $obj->stage_day = null_check(escape_output($_POST['stage_day'][$row]));
                $obj->stage_hours = null_check(escape_output($_POST['stage_hours'][$row]));
                $obj->stage_minute = null_check(escape_output($_POST['stage_minute'][$row]));
                $obj->manufacture_id = null_check($last_id);
                $obj->save();
            }
        }

        if ($request->productionstage_id_scheduling != null) {
            foreach ($request->productionstage_id_scheduling as $key => $value) {
                $split = explode('|', $value);
                $productionScheduling = new ProductionScheduling();
                $productionScheduling->manufacture_id = null_check($last_id);
                $productionScheduling->production_stage_id = null_check($split[0]);
                $productionScheduling->task = $request->task[$key];
                $productionScheduling->start_date = $request->start_date[$key];
                $productionScheduling->end_date = $request->complete_date[$key];
                $productionScheduling->save();
            }
        }

        $str_consumed_time = "Month(s): " . $total_months . " Day(s): " . $total_days . " Hour(s): " . $total_hours . " Min.(s) :" . $total_minutes;

        //update for consumed time
        $obj = Manufacture::find($last_id);
        $obj->consumed_time = $str_consumed_time;
        $obj->save();

        $previous_status = $request->previous_status;
        if ($previous_status == 'done') {
            if ($obj->manufacture_status == 'inProgress' || $obj->manufacture_status == 'draft') {
                $finishedProduct = FinishedProduct::findOrFail($obj->product_id);
                $newStock = $finishedProduct->current_total_stock - $obj->product_quantity;
                $finishedProduct->current_total_stock = $newStock;
                $finishedProduct->save();
            }
        }

        //update finish product stock for done
        if ($obj->manufacture_status == 'done') {
            $finishedProduct = FinishedProduct::findOrFail($obj->product_id);

            $newStock = $finishedProduct->current_total_stock + $obj->product_quantity;

            $finishedProduct->current_total_stock = $newStock;
            $finishedProduct->save();
        }

        return redirect('productions')->with(updateMessage());
    }
    public function duplicate($id)
    {
        $id = encrypt_decrypt($id, 'decrypt');
        $title = __('index.duplicate_manufacture');
        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $manufactures = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $rmaterials = RawMaterial::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $nonitem = NonIItem::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $p_stages = ProductionStage::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
        $tax_items = TaxItems::first();
        $m_rmaterials = Mrmitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_nonitems = Mnonitem::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $m_stages = Mstages::orderBy('id', 'ASC')->where('manufacture_id', $id)->where('del_status', "Live")->get();
        $obj = Manufacture::find($id);
        $obj_rm = Manufacture::count();
        $ref_no = "MP-" . str_pad($obj_rm + 1, 6, '0', STR_PAD_LEFT);

        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        return view('pages.manufacture.duplicateManufacture', compact('customers', 'title', 'obj', 'rmaterials', 'p_stages', 'manufactures', 'nonitem', 'accounts', 'tax_fields', 'm_rmaterials', 'm_nonitems', 'm_stages', 'units', 'tax_items', 'ref_no'));
    }

    public function duplicate_store(Request $request)
    {
        request()->validate([
            'manufacture_type' => 'required|max:150',
            'reference_no' => 'required|max:50',
            'manufacture_status' => 'required|max:50',
            'product_id' => 'required|max:50',
            'product_quantity' => 'required|max:50',
        ]);

        $obj = new \App\Manufacture();
        $obj->reference_no = null_check(escape_output($request->get('reference_no')));
        $obj->manufacture_type = escape_output($request->get('manufacture_type'));
        $obj->manufacture_status = escape_output($request->get('manufacture_status'));
        $obj->product_id = null_check(escape_output($request->get('product_id')));
        $obj->product_quantity = null_check(escape_output($request->get('product_quantity')));
        $obj->batch_no = null_check(escape_output($request->get('batch_no')));
        $obj->expiry_days = null_check(escape_output($request->get('expiry_days')));
        $obj->start_date = escape_output($request->get('start_date'));
        $obj->complete_date = escape_output($request->get('complete_date'));
        $obj->mrmcost_total = null_check(escape_output($request->get('mrmcost_total')));
        $obj->mnoninitem_total = null_check(escape_output($request->get('mnoninitem_total')));
        $obj->mtotal_cost = null_check(escape_output($request->get('mtotal_cost')));
        $obj->mprofit_margin = null_check(escape_output($request->get('mprofit_margin')));
        $obj->msale_price = null_check(escape_output($request->get('msale_price')));
        $obj->note = escape_output($request->get('note'));
        $obj->stage_counter = null_check(escape_output($request->get('stage_counter')));
        $file = '';
        if ($request->hasFile('file_button')) {
            if ($request->hasFile('file_button')) {
                $image = $request->file('file_button');
                $filename = $image->getClientOriginalName();
                $file = time() . "_" . $filename;
                $request->file_button->move(base_path('uploads/manufacture'), $file);
            }
        }
        $obj->file = $file;

        //generate json data for tax value
        $tax_information = array();
        if (!empty($_POST['tax_field_percentage'])) {
            foreach ($_POST['tax_field_percentage'] as $key => $value) {
                $single_info = array(
                    'tax_field_id' => escape_output($_POST['tax_field_id'][$key]),
                    'tax_field_name' => escape_output($_POST['tax_field_name'][$key]),
                    'tax_field_percentage' => ($_POST['tax_field_percentage'][$key] == "") ? 0 : escape_output($_POST['tax_field_percentage'][$key]),
                );
                array_push($tax_information, $single_info);
            }
        }
        $tax_information = json_encode($tax_information);

        $obj->tax_information = $tax_information;
        $obj->added_by = auth()->user()->id;
        $obj->save();
        $last_id = $obj->id;

        $rm_id = $request->get('rm_id');
        foreach ($rm_id as $row => $value) {
            $obj = new \App\Mrmitem();
            $obj->rmaterials_id = null_check($value);
            $obj->unit_price = null_check(escape_output($_POST['unit_price'][$row]));
            $obj->consumption = null_check(escape_output($_POST['quantity_amount'][$row]));
            $obj->total_cost = null_check(escape_output($_POST['total'][$row]));
            $obj->manufacture_id = null_check($last_id);
            $obj->save();
        }
        $noniitem_id = $request->get('noniitem_id');
        if (isset($noniitem_id) && $noniitem_id) {
            foreach ($noniitem_id as $row => $value) {
                $obj = new \App\Mnonitem();
                $obj->noninvemtory_id = null_check($value);
                $obj->nin_cost = null_check(escape_output($_POST['total_1'][$row]));
                $obj->account_id = null_check(escape_output($_POST['account_id'][$row]));
                $obj->manufacture_id = null_check($last_id);
                $obj->save();
            }
        }

        $total_month = 0;
        $total_day = 0;
        $total_hour = 0;
        $total_mimute = 0;

        $total_months = 0;
        $total_days = 0;
        $total_hours = 0;
        $total_minutes = 0;
        $checker = false;
        $producstage_id = $request->get('producstage_id');
        $product_quantity = $request->get('product_quantity');
        if (isset($producstage_id) && $producstage_id) {
            foreach ($producstage_id as $row => $value) {
                $stage_check = $request->get('stage_counter');
                $tmp_row = $row + 1;
                if ($checker == false) {
                    $total_value = (($_POST['stage_month'][$row] * 2592000) + ($_POST['stage_day'][$row] * 86400) + ($_POST['stage_hours'][$row] * 3600) + ($_POST['stage_minute'][$row] * 60)) * $product_quantity;
                    $months = floor($total_value / 2592000);
                    $hours = floor(($total_value % 86400) / 3600);
                    $days = floor(($total_value % 2592000) / 86400);
                    $minuts = floor(($total_value % 3600) / 60);

                    $total_month += $months;
                    $total_hour += $hours;
                    $total_day += $days;
                    $total_mimute += $minuts;
                    $total_stages = ($total_month * 2592000) + ($total_hour * 3600) + ($total_day * 86400) + $total_mimute * 60;
                    $total_months = floor($total_stages / 2592000);
                    $total_hours = floor(($total_stages % 86400) / 3600);
                    $total_days = floor(($total_stages % 2592000) / 86400);
                    $total_minutes = floor(($total_stages % 3600) / 60);
                }

                $obj = new \App\Mstages();
                $obj->productionstage_id = $value;
                $obj->stage_month = null_check(escape_output($_POST['stage_month'][$row]));
                $obj->stage_day = null_check(escape_output($_POST['stage_day'][$row]));
                $obj->stage_hours = null_check(escape_output($_POST['stage_hours'][$row]));
                $obj->stage_minute = null_check(escape_output($_POST['stage_minute'][$row]));
                $obj->manufacture_id = null_check($last_id);
                $obj->save();

                if ($stage_check == $tmp_row) {
                    $checker = true;
                }
            }
        }

        $str_consumed_time = "Month(s): " . $total_months . " Day(s): " . $total_days . " Hour(s): " . $total_hours . " Min.(s) :" . $total_minutes;

        //update for consumed time
        $obj = Manufacture::find($last_id);
        $obj->consumed_time = $str_consumed_time;
        $obj->save();
        return redirect('productions')->with(saveMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Manufacture $manufacture)
    {
        //delete previous data before add
        Mrmitem::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);
        Mnonitem::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);
        Mstages::where('manufacture_id', $manufacture->id)->update(['del_status' => "Deleted"]);

        $manufacture->del_status = "Deleted";
        $manufacture->save();
        return redirect('productions')->with(deleteMessage());
    }

    /**
     * Partillay Done the specified resource from storage.
     */

    public function changePartiallyDone(Request $request)
    {
        $manufacture = Manufacture::find($request->manufacture_id);
        $total_quantity = $manufacture->product_quantity;
        $partially_done_quantity = $manufacture->partially_done_quantity;
        $remaining_quantity = $total_quantity - $partially_done_quantity;

        $input_partially_done_quantity = $request->partially_done_quantity;
        if ($input_partially_done_quantity > $remaining_quantity) {
            return redirect('productions')->with(dangerMessage(__('index.partially_done_quantity_cannot_greater')));
        }

        $manufacture->partially_done_quantity += $input_partially_done_quantity;
        $manufacture->save();

        if ($manufacture->product_quantity == $manufacture->partially_done_quantity) {
            $manufacture->manufacture_status = 'done';
            $manufacture->save();
        }

        if ($manufacture->manufacture_status == 'done') {
            $finishedProduct = FinishedProduct::findOrFail($manufacture->product_id);

            $newStock = $finishedProduct->current_total_stock + $input_partially_done_quantity;

            $finishedProduct->current_total_stock = $newStock;
            $finishedProduct->save();
        }

        return redirect('productions')->with(updateMessage());
    }

    /**
     * updateProducedQuantityData
     */

    public function updateProducedQuantityData(Request $request)
    {
        $manufacture = Manufacture::find($request->id);
        $customer_id = $manufacture->customer_order_id;

        $productionHistory = ProductionHistory::where('work_order_id', $customer_id)->get();

        $table = '';
        if (empty($productionHistory)) {
            $table .= '<tr>';
            $table .= '<td colspan="3" class="text-center">' . __('index.no_data_found') . '</td>';
            $table .= '</tr>';
        } else {
            foreach ($productionHistory as $key => $value) {
                $table .= '<tr>';
                $table .= '<td>' . ++$key . '</td>';
                $table .= '<td class="text-center">' . $value->produced_quantity . '</td>';
                $table .= '<td>' . getDateFormat($value->produced_date) . '</td>';
                $table .= '</tr>';
            }
        }

        $totalProducedQuantity = ProductionHistory::where('work_order_id', $customer_id)->sum('produced_quantity');
        $totalQuantity = $manufacture->product_quantity;
        $remainingQuantity = $totalQuantity - $totalProducedQuantity;

        $array = [
            'table' => $table,
            'remainingQuantity' => $remainingQuantity,
        ];

        return response()->json($array);
    }

    public function updateProducedQuantity(Request $request)
    {
        $manufacture = Manufacture::find($request->manufacture_id);
        $customer_id = $manufacture->customer_order_id;

        $productionHistory = new ProductionHistory();
        $productionHistory->work_order_id = $customer_id;
        $productionHistory->produced_quantity = $request->produced_quantity;
        $productionHistory->produced_date = date('Y-m-d');
        $productionHistory->save();

        return redirect('productions')->with(updateMessage());
    }

    public function getProductionScheduling(Request $request)
    {
        $id = $request->id;
        //Production Schedule get with production stage name
        $productionScheduling = ProductionScheduling::where('manufacture_id', $id)->where('del_status', 'Live')->get();
        $productionScheduling = $productionScheduling->map(function ($item) {
            $item->production_stage_name = getProductionStages($item->production_stage_id);
            return $item;
        });

        return response()->json($productionScheduling);
    }
}
