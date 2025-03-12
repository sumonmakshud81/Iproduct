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
# This is AjaxController Controller
##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderDetails;
use App\FinishedProduct;
use App\FPnonitem;
use App\FPproductionstage;
use App\FPrmitem;
use App\Manufacture;
use App\Mrmitem;
use App\RawMaterial;
use App\Stage;
use App\Stock;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{

    public function addSupplierByAjax(Request $request)
    {
        $obj = new \App\Supplier;
        $obj->name = escape_output($request->get('name'));
        $obj->contact_person = escape_output($request->get('contact_person'));
        $obj->phone = escape_output($request->get('phone'));
        $obj->email = escape_output($request->get('emailAddress'));
        $obj->address = escape_output($request->get('supAddress'));
        $obj->credit_limit = escape_output($request->get('credit_limit'));
        $obj->opening_balance = escape_output($request->get('opening_balance'));
        $obj->opening_balance_type = escape_output($request->get('opening_balance_type'));
        $obj->note = escape_output($request->get('note'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        $last_id = $obj->id;
        $html = '';

        $obj = Supplier::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        foreach ($obj as $value) {
            $current_due = currentSupplierDue($value->id);
            $html .= "<option  data-credit_limit='$value->credit_limit'   data-current_due='$current_due'  value='$value->id'>" . $value->name . "(" . $value->phone . ")</option>";
        }
        $return = array();
        $return['supplier_id'] = $last_id;
        $return['html'] = $html;
        echo \GuzzleHttp\json_encode($return);
    }
    public function addCustomerByAjax(Request $request)
    {
        $obj = new \App\Customer;
        $obj->name = escape_output($request->get('name'));
        $obj->phone = escape_output($request->get('phone'));
        $obj->email = escape_output($request->get('emailAddress'));
        $obj->address = escape_output($request->get('supAddress'));
        $obj->note = escape_output($request->get('note'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        $last_id = $obj->id;
        $html = '';

        $obj = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        foreach ($obj as $value) {
            $current_due = currentSupplierDue($value->id);
            $html .= "<option  data-credit_limit='$value->credit_limit'   data-current_due='$current_due'  value='$value->id'>" . $value->name . "(" . $value->phone . ")</option>";
        }
        $return = array();
        $return['customer_id'] = $last_id;
        $return['html'] = $html;
        echo \GuzzleHttp\json_encode($return);
    }

    public function getRMByFinishProduct(Request $request)
    {
        $id = escape_output($request->get('id'));
        $fp_rmaterials = FPrmitem::orderBy('id', 'ASC')->where('finish_product_id', $id)->where('del_status', "Live")->get();
        $fp_nonitems = FPnonitem::orderBy('id', 'ASC')->where('finish_product_id', $id)->where('del_status', "Live")->get();
        foreach ($fp_rmaterials as $key => $value) {
            $fp_rmaterials[$key]->rm_name = getRMName($value->rmaterials_id);
            $fp_rmaterials[$key]->rm_consumption_unit = getPurchaseSaleUnitById($value->rmaterials_id);
            $fp_rmaterials[$key]->currency = getCurrencyOnly();
        }
        foreach ($fp_nonitems as $key => $value) {
            $fp_nonitems[$key]->noninrm_name = getNonInventroyItem($value->noninvemtory_id);
            $fp_nonitems[$key]->currency = getCurrencyOnly();
        }
        $return['rmaterials'] = $fp_rmaterials;
        $return['noninmaterials'] = $fp_nonitems;
        echo \GuzzleHttp\json_encode($return);

    }

    public function sortingPage(Request $request)
    {
        $stages = $request->get('stages');
        $i = 1;
        foreach ($stages as $key => $value) {
            $obj = Stage::find($stages[$key]);
            $obj->arranged_by = $i;
            $obj->save();
            $i++;
        }
        echo \GuzzleHttp\json_encode('success');
    }

    public function checkCreditLimit(Request $request)
    {
        $supplier = $request->get('supplier');
        $due = $request->get('due');
        $return['status'] = false;
        if (checkCreditLimit($supplier, $due)) {
            $return['status'] = true;
        }
        echo \GuzzleHttp\json_encode($return);
    }

    public function getSupplierDue()
    {
        $supplier_id = escape_output($_GET['supplier_id']);
        $getSupplierDue = getSupplierDue($supplier_id);
        $creditLimit = getSupplierCreditLimit($supplier_id);

        $return_array['supplier_total_due'] = $getSupplierDue;
        $return_array['credit_limit'] = $creditLimit;

        echo json_encode($return_array);
    }

    public function getCustomerDue()
    {
        $customer_id = escape_output($_GET['customer_id']);
        $getCustomerDue = getCustomerDue($customer_id);
        $creditLimit = getCustomerCreditLimit($customer_id);

        $return_array['supplier_total_due'] = $getCustomerDue;
        $return_array['credit_limit'] = $creditLimit;

        echo json_encode($return_array);
    }

    public function getSupplierBalance()
    {
        $supplier_id = escape_output($_GET['supplier_id']);
        $getSupplierBalance = companySupplierBalance($supplier_id);
        $creditLimit = getSupplierCreditLimit($supplier_id);
        $getSupplierDue = getSupplierDue($supplier_id);

        $return_array['supplier_balance'] = $getSupplierBalance;
        $return_array['credit_limit'] = $creditLimit;
        $return_array['supplier_due'] = $getSupplierDue;

        echo json_encode($return_array);

    }

    public function getLowRMStock()
    {
        $obj1 = new Stock();
        $inventory = $obj1->getLowRMStock();
        $i = 1;
        $table_row = '';
        $setting = getSettingsInfo();
        if (!empty($inventory) && isset($inventory)) {
            foreach ($inventory as $key => $value) {
                $totalStock = @($value->total_purchase * $value->conversion_rate) - $value->total_rm_waste + $value->opening_stock;
                $last_p_price = getLastPurchasePrice($value->id);
                $table_row .= '<tr class="rowCount" data-id="' . $value->id . '">
                                    <td class="width_1_p ir_txt_center"><p class="set_sn"></p></td>
                                    <td><input type="hidden" value="' . $value->id . '" name="rm_id[]"> <span>' . $value->name . '(' . $value->code . ')' . '</span></td>

                                    <td><div class="input-group"><input type="text" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk unit_price_c cal_row" placeholder="Unit Price" value="' . $last_p_price . '" id="unit_price_1"><span class="input-group-text">  ' . $setting->currency . '</span></div></td>

                                    <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="qty_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk qty_c cal_row" value="1" placeholder="0"><span class="input-group-text">' . getRMUnitById($value->unit) . '</span></div></td>

                                    <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control total_c" value="" placeholder="Total" readonly=""><span class="input-group-text">  ' . $setting->currency . '</span></div></td>
                                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                                </tr>';
                $i++;
            }
        }
        //we skip escape due to html content
        echo ($table_row);
    }

    public function getFinishProductRM(Request $request)
    {
        $fproduct_id = escape_output($request->post('id'));
        $product_quantity = escape_output($request->post('value'));
        $setting = getSettingsInfo();
        $obj2 = new FPrmitem();
        $finishProductRM = $obj2->getFinishProductRM($fproduct_id);
        $html = '';
        foreach ($finishProductRM as $value) {
            $consumption = $value->consumption * $product_quantity;
            $html .= '<tr class="rowCount" data-id="' . $value->finish_product_id . '">
                        <td class="width_1_p text-start"><p class="set_sn"></p></td>
                        <td><input type="hidden" value="' . $value->rmaterials_id . '" name="rm_id[]" class="rm_id"> <span>' . getRMName($value->rmaterials_id) . '</span></td>

                        <td><div class="input-group"><input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="' . $value->unit_price . '" id="unit_price_1"><span class="input-group-text">' . $setting->currency . '</span></div><div class="text-danger unitPriceErr d-none"></div></td>

                        <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="qty_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="' . $consumption . '" placeholder="Consumption"><span class="input-group-text">' . getPurchaseUnitByRMID($value->rmaterials_id) . '</span></div><div class="text-danger quantityErr d-none"></div></td>

                        <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c" value="' . $value->total_cost . '" placeholder="Total" readonly=""><span class="input-group-text">  ' . $setting->currency . '</span></div></td>
                        <td class="text-end"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                    </tr>';
        }
        echo json_encode($html);
    }

    public function getFinishProductRMForManufacture(Request $request)
    {
        $fproduct_id = escape_output($request->post('id'));
        $product_quantity = escape_output($request->post('value'));
        $setting = getSettingsInfo();
        $obj2 = new FPrmitem();
        $finishProductRM = $obj2->getFinishProductRM($fproduct_id);
        $html = '';
        foreach ($finishProductRM as $value) {
            $consumption = $value->consumption * $product_quantity;
            $html .= '<tr class="rowCount" data-id="' . $value->finish_product_id . '">
                        <td class="width_1_p text-start"><p class="set_sn"></p></td>
                        <td><input type="hidden" value="' . $value->rmaterials_id . '" name="rm_id[]" class="rm_id"> <span>' . getRMName($value->rmaterials_id) . '</span></td>

                        <td><div class="input-group"><input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="' . $value->unit_price . '" id="unit_price_1"><span class="input-group-text">' . $setting->currency . '</span></div><div class="text-danger unitPriceErr d-none"></div></td>

                        <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="qty_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="' . $consumption . '" placeholder="Consumption"><span class="input-group-text">' . getManufactureUnitByRMID($value->rmaterials_id) . '</span></div><div class="text-danger quantityErr d-none"></div></td>

                        <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c" value="' . $value->total_cost . '" placeholder="Total" readonly=""><span class="input-group-text">  ' . $setting->currency . '</span></div></td>
                        <td class="text-end"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                    </tr>';
        }
        echo json_encode($html);
    }

    public function getFinishProductNONI(Request $request)
    {
        $fproduct_id = escape_output($request->post('id'));
        $product_quantity = escape_output($request->post('value'));
        $setting = getSettingsInfo();
        $obj2 = new FPnonitem();
        $finishProductNoni = $obj2->getFinishProductNONI($fproduct_id);
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $account_dropdown = '<option value="">Select</option>';
        $htmlnoni = '';

        foreach ($finishProductNoni as $value) {
            $nin_cost = $value->nin_cost * $product_quantity;
            $htmlnoni .= '<tr class="rowCount1 noninventory" data-id="' . $value->finish_product_id . '">
                        <td class="width_1_p text-start"><p class="set_sn1"></p></td>
                        <td><input type="hidden" value="' . $value->noninvemtory_id . '" name="noniitem_id[]"> <span>' . getNonInventroyItem($value->noninvemtory_id) . '</span></td><td></td>

                        <td><div class="input-group"><input type="text" id="total_1" name="total_1[]" class="cal_row noi_cost form-control aligning total_c1" onfocus="select();" value="' . $nin_cost . '" placeholder="Non Inventory Cost"><span class="input-group-text">' . $setting->currency . '</span></div><div class="text-danger nonInventoryCostErr d-none"></div></td>

                        <td width="20%"><select class="form-control account_id_c1" name="account_id[]">';
            $htmlnoni .= $account_dropdown;
            foreach ($accounts as $account) {
                $htmlnoni .= '<option id="account_id" class="account_id" value="' . $account->id . '">' . $account->name . '</option>';
            }
            $htmlnoni .= '</select><div class="text-danger accountErr d-none"></div></td>
                        <td class="text-end"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td></tr>';
        }
        echo json_encode($htmlnoni);
    }

    public function getFinishProductStages(Request $request)
    {
        $fproduct_id = escape_output($request->post('id'));
        $product_quantity = escape_output($request->post('value'));
        $setting = getSettingsInfo();
        $obj2 = new FPproductionstage();
        $finishProductStage = $obj2->getFinishProductStages($fproduct_id);
        $htmlstages = '';
        $total_month = 0;
        $total_day = 0;
        $total_hour = 0;
        $total_mimute = 0;
        $i = 1;
        foreach ($finishProductStage as $key => $value) {
            $total_value = (($value->stage_month * 2592000) + ($value->stage_day * 86400) + ($value->stage_hours * 3600) + ($value->stage_minute * 60)) * $product_quantity;
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
            $htmlstages .= '<tr class="rowCount2 align-baseline" data-id="' . $value->finish_product_id . '">
                            <td class="width_1_p"><p class="set_sn2"></p></td>
                            <td class="width_1_p">
                            <input type="hidden"
                                                                value="' . $value->productionstage_id . '"
                                                                name="producstage_id[]">
                                <input class="form-check-input set_class custom_checkbox" data-stage_name="' . getProductionStage($value->productionstage_id) . '" type="radio" id="checkboxNoLabel" name="stage_check" value="' . $i . '">
                            </td>
                            <td class="stage_name" style="text-align: left;"> <span>' . getProductionStage($value->productionstage_id) . '</span></td>
                            <td>
                                <div class="row">
                                    <div class="col-xl-3 col-md-4"><div class="input-group"><input class="form-control stage_aligning" type="text" id="month_limit" name="stage_month[]" min="1" max="12" value="' . $months . '"  placeholder="Months"><span class="input-group-text">Months</span></div></div>

                                    <div class="col-xl-3 col-md-4"><div class="input-group"><input class="form-control stage_aligning" type="text" id="day_limit" name="stage_day[]" min="1" max="31" value="' . $days . '"  placeholder="Days"><span class="input-group-text">Days</span></div></div>

                                    <div class="col-xl-3 col-md-4"><div class="input-group"><input class="form-control stage_aligning" type="text" id="hours_limit" name="stage_hours[]" min="1" max="24" value="' . $hours . '"  placeholder="Hours"><span class="input-group-text">Hours</span></div></div>

                                    <div class="col-xl-3 col-md-4getLowRMStock"><div class="input-group"><input class="form-control stage_aligning" type="text" id="minute_limit" name="stage_minute[]" min="1" max="60" value="' . $minuts . '"  placeholder="Minutes"><span class="input-group-text">Minutes</span></div></div>
                                </div>
                            </td>
                        </tr>';
            $i++;
        }
        $data_arr['html'] = $htmlstages;
        $data_arr['total_month'] = $total_months;
        $data_arr['total_day'] = $total_days;
        $data_arr['total_hour'] = $total_hours;
        $data_arr['total_minute'] = $total_minutes;
        echo json_encode($data_arr);
    }

    public function getFifoFProduct(Request $request)
    {
        $product_id = escape_output($request->post('id'));
        $unit_price = escape_output($request->post('unit_price'));
        $quantity = escape_output($request->post('quantity'));
        $item_id_modal = escape_output($request->post('item_id_modal'));
        $item_currency_modal = escape_output($request->post('item_currency_modal'));
        $item_unit_modal = escape_output($request->post('item_unit_modal'));

        $obj2 = new Manufacture();

        $finishProductFifo = $obj2->getFinishProductFifo($product_id);

        $html = '';

        $xquantity = 0;

        foreach ($finishProductFifo as $value) {
            $productInfo = FinishedProduct::where('del_status', 'Live')
                ->where('id', $product_id)
                ->first();

            $item_quantity = 0;

            $avaiable_quantity = $value->product_quantity;

            if ($quantity > 0) {
                if ($quantity < $avaiable_quantity) {
                    $item_quantity = $quantity;

                } else {
                    $item_quantity = $avaiable_quantity;

                }

                $html .= '<tr class="rowCount" data-id="' . $item_id_modal . '">
                    <td class="width_1_p text-start"><p class="set_sn">1</p></td>
                    <td><input type="hidden" value="' . $product_id . '" name="selected_product_id[]">
                    <input type="hidden" value="' . $productInfo->current_total_stock . '" class="current_stock" name="current_stock[]">
                    <input type="hidden" value="' . $value->id . '" name="rm_id[]"><span>' . $productInfo->name . '(' . $productInfo->code . ')</span></td>
                    <td><div class="input-group"><input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="' . $unit_price . '"><span class="input-group-text">' . $item_currency_modal . '</span></div><span class="text-danger"></span></td>
                    <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="quantity_amount_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="' . $item_quantity . '" placeholder="Qty/Amount" ><span class="input-group-text">' . $item_unit_modal . '</span></div><span class="text-danger"></span></td>
                    <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c" placeholder="Total" readonly=""><span class="input-group-text">' . $item_currency_modal . '</span></div></td>
                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                </tr>';
            }

            $quantity = $quantity - $item_quantity;

            $item_id_modal = $item_id_modal + 1;
        }
        //we skip escape due to html content
        echo ($html);
    }

    public function getFefoFProduct(Request $request)
    {
        $product_id = escape_output($request->post('id'));
        $unit_price = escape_output($request->post('unit_price'));
        $quantity = escape_output($request->post('quantity'));
        $item_id_modal = escape_output($request->post('item_id_modal'));
        $item_currency_modal = escape_output($request->post('item_currency_modal'));
        $item_unit_modal = escape_output($request->post('item_unit_modal'));

        $setting = getSettingsInfo();

        $obj2 = new Manufacture();
        $finishProductFefo = $obj2->getFinishProductFefo($product_id);

        $html = '';

        foreach ($finishProductFefo as $value) {
            $productInfo = FinishedProduct::where('del_status', 'Live')
                ->where('id', $product_id)
                ->first();

            $item_quantity = 0;

            $avaiable_quantity = $value->product_quantity;

            if ($quantity > 0) {
                if ($quantity < $avaiable_quantity) {
                    $item_quantity = $quantity;

                } else {
                    $item_quantity = $avaiable_quantity;

                }

                $html .= '<tr class="rowCount" data-id="' . $item_id_modal . '">
                    <td class="width_1_p text-start"><p class="set_sn">1</p></td>
                    <td><input type="hidden" value="' . $product_id . '" name="selected_product_id[]">
                    <input type="hidden" value="' . $value->id . '" name="manufacture_id[]">
                    <input type="hidden" value="' . $productInfo->current_total_stock . '" class="current_stock" name="current_stock[]">
                    <input type="hidden" value="' . $value->id . '" name="rm_id[]"><span>' . $productInfo->name . '(' . $productInfo->code . ')</span><br>';
                if ($value->expiry_days !== null) {
                    $html .= 'Expiry Date: ' . getDateFormat(expireDate($value->complete_date, $value->expiry_days));
                }
                $html .= '</td>
                    <td><div class="input-group"><input type="text" tabindex="5" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning unit_price_c cal_row" placeholder="Unit Price" value="' . $unit_price . '"><span class="input-group-text">' . $item_currency_modal . '</span></div><span class="text-danger"></span></td>
                    <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="quantity_amount_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk input_aligning qty_c cal_row" value="' . $item_quantity . '" placeholder="Qty/Amount" ><span class="input-group-text">' . $item_unit_modal . '</span></div><span class="text-danger"></span></td>
                    <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control input_aligning total_c" placeholder="Total" readonly=""><span class="input-group-text">' . $item_currency_modal . '</span></div></td>
                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                </tr>';
            }

            $quantity = $quantity - $item_quantity;

            $item_id_modal = $item_id_modal + 1;
        }

        //we skip escape due to html content
        echo ($html);
    }

    public function getBatchControlProduct(Request $request)
    {
        $product_id = escape_output($request->get('product_id'));
        $productList = Manufacture::where('product_id', $product_id)->where('del_status', "Live")->get()->map(function ($item) {
            $item->expiry_date = expireDate($item->complete_date, $item->expiry_days);
            return $item;
        });
        echo json_encode($productList);
    }

    public function getFinishProductDetails(Request $request)
    {
        $fproduct_id = escape_output($request->post('id'));

        $productList = FinishedProduct::with(['rmaterials', 'rmaterials.rawMaterials.unit', 'nonInventory', 'rmaterials.rawMaterials', 'nonInventory.nonInventoryItem', 'stage'])->where('id', $fproduct_id)->first();

        echo json_encode($productList);
    }

    public function getCustomerOrderList(Request $request)
    {
        $customer_id = escape_output($request->post('id'));
        $customerOrderList = CustomerOrder::where('customer_id', $customer_id)->where('del_status', "Live")->orderBy('id', 'DESC')->get();

        $html = '<label>Customer Order <span class="required_star">*</span></label>
        <select class="form-control customer_order_id_c1 select2" name="customer_order_id" id="customer_order_id">
                <option value="">Select</option>';
        foreach ($customerOrderList as $value) {
            $html .= '<option value="' . $value->id . '">' . $value->reference_no . '</option>';
        }

        $html .= '</select>';

        //we skip escape due to html content
        echo ($html);
    }

    public function getCustomerOrderProducts(Request $request)
    {
        $customer_order_id = escape_output($request->post('id'));
        $from = escape_output($request->post('from'));
        $orderDetails = CustomerOrderDetails::where('customer_order_id', $customer_order_id)->where('del_status', "Live")->get();
        $productId = [];
        $html = '';
        foreach ($orderDetails as $key => $value) {
            $productList = FinishedProduct::where('id', $value->product_id)->where('del_status', "Live")->first();
            if ($request->has('from') && $from == 'purchase') {
                $productId[$key] = $productList->id;
            }

            $html .= '<option value="' . $productList->id . "|" . $productList->stock_method . '">' . $productList->name . '</option>';
        }
        if ($request->has('from') && $from == 'purchase') {
            return $productId;
        } else {
            //we skip escape due to html content
            echo ($html);
        }

    }

    public function getProductionData(Request $request)
    {
        $manufactureId = $request->manufacture_id;
        $rmMaterials = Mrmitem::where('manufacture_id', $manufactureId)->where('del_status', "Live")->get();

        $i = 1;
        $table_row = '';
        $setting = getSettingsInfo();
        if (!empty($rmMaterials) && isset($rmMaterials)) {
            foreach ($rmMaterials as $key => $value) {
                $rawMaterials = RawMaterial::find($value->rmaterials_id);
                $last_p_price = getLastPurchasePrice($rawMaterials->id);
                $table_row .= '<tr class="rowCount" data-id="' . $rawMaterials->id . '">
                                    <td class="width_1_p ir_txt_center"><p class="set_sn"></p></td>
                                    <td><input type="hidden" value="' . $rawMaterials->id . '" name="rm_id[]"> <span>' . $rawMaterials->name . '(' . $rawMaterials->code . ')' . '</span></td>

                                    <td><div class="input-group"><input type="text" name="unit_price[]" onfocus="this.select();" class="check_required form-control integerchk unit_price_c cal_row" placeholder="Unit Price" value="' . $last_p_price . '" id="unit_price_1"><span class="input-group-text">  ' . $setting->currency . '</span></div></td>

                                    <td><div class="input-group"><input type="text" data-countid="1" tabindex="51" id="qty_1" name="quantity_amount[]" onfocus="this.select();" class="check_required form-control integerchk qty_c cal_row" value="1" placeholder="0"><span class="input-group-text">' . getRMUnitById($rawMaterials->unit) . '</span></div></td>

                                    <td><div class="input-group"><input type="text" id="total_1" name="total[]" class="form-control total_c" value="" placeholder="Total" readonly=""><span class="input-group-text">  ' . $setting->currency . '</span></div></td>
                                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                                </tr>';
                $i++;
            }
        }
        //we skip escape due to html content
        echo ($table_row);
    }

    public function getProduct()
    {
        $product = FinishedProduct::orderBy('id', 'DESC')->where('del_status', "Live")->get(['id', 'name', 'code']);
        echo json_encode($product);
    }

}
