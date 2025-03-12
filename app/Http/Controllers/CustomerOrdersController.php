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
# This is CustomerOrdersController Controller
##############################################################################
 */

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerOrder;
use App\CustomerOrderDelivery;
use App\CustomerOrderDetails;
use App\CustomerOrderInvoice;
use App\FinishedProduct;
use App\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrdersController extends Controller
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
        $obj = CustomerOrder::where('del_status', "Live")->orderBy('id', 'DESC')->get();
        $title = __('index.customer_order');

        return view('pages.customer_order.index', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_customer_order');

        $total_customer = CustomerOrder::count();
        $ref_no = "CO-" . str_pad($total_customer + 1, 6, '0', STR_PAD_LEFT);

        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get()
            ->mapWithKeys(function ($customer) {
                return [$customer->id => $customer->name . ' (' . $customer->phone . ')'];
            });

        $orderTypes = ['Quotation' => 'Quotation', 'Work Order' => 'Work Order'];

        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        $productList = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $product = $productList->pluck('name', 'id');
        $customerOrder = array();

        return view('pages.customer_order.create', compact('title', 'customerOrder', 'ref_no', 'customers', 'orderTypes', 'units', 'productList', 'product'));
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
            'reference_no' => 'required|max:50',
            'customer_id' => 'required',
            'order_type' => 'required',
            'delivery_date' => 'required_if:order_type,Quotation',
        ]);
        try {
            $productList = $request->get('product');

            $customerOrder = new \App\CustomerOrder();
            $customerOrder->reference_no = null_check(escape_output($request->get('reference_no')));
            $customerOrder->customer_id = null_check(escape_output($request->get('customer_id')));
            $customerOrder->order_type = escape_output($request->get('order_type'));
            $customerOrder->delivery_date = string_date_null_check(escape_output($request->get('delivery_date')));
            $customerOrder->delivery_address = escape_output($request->get('delivery_address'));
            $customerOrder->total_product = null_check(sizeof($productList));
            $customerOrder->total_amount = null_check(escape_output($request->get('total_subtotal')));
            $customerOrder->total_cost = null_check(escape_output($request->get('total_cost')));
            $customerOrder->total_profit = null_check(escape_output($request->get('total_profit')));
            $customerOrder->quotation_note = escape_output($request->get('quotation_note'));
            $customerOrder->internal_note = escape_output($request->get('internal_note'));
            $customerOrder->order_status = '1';
            $customerOrder->created_by = auth()->user()->id;
            $customerOrder->save();

            foreach ($productList as $row => $value) {
                $quantity = null_check(escape_output($_POST['quantity'][$row]));
                $cost_per_unit = null_check(escape_output($_POST['cost'][$row]));

                $obj = new \App\CustomerOrderDetails();
                $obj->customer_order_id = $customerOrder->id;
                $obj->product_id = null_check(escape_output($_POST['product'][$row]));
                $obj->quantity = $quantity;
                $obj->unit_price = null_check(escape_output($_POST['unit_price'][$row]));
                $obj->discount_percent = null_check(escape_output($_POST['discount_percent'][$row]));
                $obj->sub_total = null_check(escape_output($_POST['sub_total'][$row]));
                $obj->total_cost = $cost_per_unit * $quantity;
                $obj->profit = null_check(escape_output($_POST['profit'][$row]));
                $obj->delivery_date = string_date_null_check(escape_output($_POST['delivery_date_product'][$row]));
                $obj->production_status = 0;
                $obj->delivered_qty = 0;
                $obj->save();
            }
            if (!empty($request->invoice_type)) {
                foreach ($request->invoice_type as $key => $value) {
                    $inv_obj = new \App\CustomerOrderInvoice();
                    $inv_obj->customer_order_id = null_check($customerOrder->id);
                    $inv_obj->invoice_type = ($request->invoice_type[$key]);
                    $inv_obj->amount = null_check($request->invoice_amount[$key]);
                    $inv_obj->invoice_date = ($request->invoice_date[$key]);
                    $inv_obj->paid_amount = null_check($request->invoice_paid[$key]);
                    $inv_obj->due_amount = null_check($request->invoice_due[$key]);
                    $inv_obj->order_due_amount = null_check($request->invoice_order_due[$key]);
                    $inv_obj->save();
                }
            }

            if (!empty($request->delivaries_date)) {
                foreach ($request->delivaries_date as $key => $value) {
                    $del_obj = new \App\CustomerOrderDelivery();
                    $del_obj->customer_order_id = null_check($customerOrder->id);
                    $del_obj->product_id = null_check($request->delivaries_product[$key]);
                    $del_obj->quantity = null_check($request->delivaries_quantity[$key]);
                    $del_obj->delivery_date = string_date_null_check(escape_output($request->delivaries_date[$key]));
                    $del_obj->delivery_note = ($request->delivaries_note[$key]) ?? null;
                    $del_obj->delivery_status = ($request->delivaries_status[$key]) ?? null;
                    $del_obj->save();
                }
            }

            return redirect('customer-orders')->with(saveMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->all())->with(dangerMessage($e->getMessage()));
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
        $id = encrypt_decrypt($id, 'decrypt');
        $customerOrder = CustomerOrder::find($id);
        $title = __('index.customer_order_details');

        $orderDetails = CustomerOrderDetails::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->get();
        $orderInvoice = CustomerOrderInvoice::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();
        $orderDeliveries = CustomerOrderDelivery::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();
        $obj = $customerOrder;
        return view('pages.customer_order.view', compact('title', 'obj', 'orderDetails', 'orderInvoice', 'orderDeliveries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customerOrder = CustomerOrder::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_customer_order');

        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->pluck('name', 'id');

        $orderTypes = ['Quotation' => 'Quotation', 'Work Order' => 'Work Order'];

        $units = Unit::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        $productList = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $product = $productList->pluck('name', 'id');
        $orderDetails = CustomerOrderDetails::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->get();
        $orderInvoice = CustomerOrderInvoice::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->get();
        $orderDeliveries = CustomerOrderDelivery::where('customer_order_id', $customerOrder->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();
        return view('pages.customer_order.edit', compact('title', 'product', 'customerOrder', 'customers', 'orderTypes', 'units', 'productList', 'orderDetails', 'orderInvoice', 'orderDeliveries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrder $customerOrder)
    {
        request()->validate([
            'reference_no' => 'required|max:50',
            'customer_id' => 'required',
            'order_type' => 'required',
            'delivery_date' => 'required',
            'delivery_address' => 'required',
        ]);
        $productList = $request->get('product');

        $customerOrder->reference_no = null_check(escape_output($request->get('reference_no')));
        $customerOrder->customer_id = null_check(escape_output($request->get('customer_id')));
        $customerOrder->order_type = escape_output($request->get('order_type'));
        $customerOrder->delivery_date = string_date_null_check(escape_output($request->get('delivery_date')));
        $customerOrder->delivery_address = escape_output($request->get('delivery_address'));
        $customerOrder->total_product = null_check(sizeof($productList));
        $customerOrder->total_amount = null_check(escape_output($request->get('total_subtotal')));
        $customerOrder->total_cost = escape_output($request->get('total_cost'));
        $customerOrder->total_profit = escape_output($request->get('total_profit'));
        $customerOrder->quotation_note = escape_output($request->get('quotation_note'));
        $customerOrder->internal_note = escape_output($request->get('internal_note'));
        $customerOrder->save();

        $last_id = $customerOrder->id;

        //delete previous data before add
        CustomerOrderDetails::where('customer_order_id', $last_id)->update(['del_status' => "Deleted"]);
        CustomerOrderInvoice::where('customer_order_id', $last_id)->update(['del_status' => "Deleted"]);
        CustomerOrderDelivery::where('customer_order_id', $last_id)->update(['del_status' => "Deleted"]);

        foreach ($productList as $row => $value) {
            $quantity = null_check(escape_output($_POST['quantity'][$row]));
            $cost_per_unit = null_check(escape_output($_POST['cost'][$row]));

            $obj = new \App\CustomerOrderDetails();
            $obj->customer_order_id = $last_id;
            $obj->product_id = null_check(escape_output($_POST['product'][$row]));
            $obj->quantity = $quantity;
            $obj->unit_price = null_check(escape_output($_POST['unit_price'][$row]));
            $obj->discount_percent = null_check(escape_output($_POST['discount_percent'][$row]));
            $obj->sub_total = null_check(escape_output($_POST['sub_total'][$row]));
            $obj->total_cost = $cost_per_unit * $quantity;
            $obj->profit = null_check(escape_output($_POST['profit'][$row]));
            $obj->delivery_date = string_date_null_check(escape_output($_POST['delivery_date_product'][$row]));
            $obj->production_status = escape_output($_POST['status'][$row]);
            $obj->delivered_qty = null_check(escape_output($_POST['delivered_qty'][$row]));

            $obj->save();
        }
        if (!empty($request->invoice_type)) {
            foreach ($request->invoice_type as $key => $value) {
                $inv_obj = new \App\CustomerOrderInvoice();
                $inv_obj->customer_order_id = null_check($last_id);
                $inv_obj->invoice_type = escape_output($request->invoice_type[$key]);
                $inv_obj->amount = null_check($request->invoice_amount[$key]);
                $inv_obj->invoice_date = escape_output($request->invoice_date[$key]);
                $inv_obj->paid_amount = null_check($request->invoice_paid[$key]);
                $inv_obj->due_amount = null_check($request->invoice_due[$key]);
                $inv_obj->order_due_amount = null_check($request->invoice_order_due[$key]);
                $inv_obj->save();
            }
        }
        if (!empty($request->delivaries_date)) {
            foreach ($request->delivaries_date as $key => $value) {
                $del_obj = new \App\CustomerOrderDelivery();
                $del_obj->customer_order_id = null_check($last_id);
                $del_obj->product_id = null_check($request->delivaries_product[$key]);
                $del_obj->quantity = null_check($request->delivaries_quantity[$key]);
                $del_obj->delivery_date = escape_output($request->delivaries_date[$key]) ?? null;
                $del_obj->delivery_note = escape_output($request->delivaries_note[$key]) ?? null;
                $del_obj->delivery_status = escape_output($request->delivaries_status[$key]) ?? null;
                $del_obj->save();
            }
        }

        return redirect('customer-orders')->with(updateMessage());
    }

    /**
     * Store/Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdateInvoice(Request $request)
    {
        request()->validate([
            'invoice_type' => 'required|max:150',
            'paid_amount' => 'required',
            'order_due_amount' => 'required',
            'customer_order_id' => 'required',
        ]);

        $orderInvoice = new CustomerOrderInvoice;
        $orderInvoice->customer_order_id = $request->customer_order_id;
        $orderInvoice->invoice_type = $request->invoice_type;
        $orderInvoice->paid_amount = $request->paid_amount;
        $orderInvoice->due_amount = $request->due_amount;
        $orderInvoice->order_due_amount = $request->order_due_amount;
        $orderInvoice->invoice_date = date('Y-m-d');
        $orderInvoice->save();

        return redirect('customer-orders/' . $request->customer_order_id . '/edit')->with(updateMessage());
    }

    /**
     * Store/Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdateDelivery(Request $request)
    {
        request()->validate([
            'product_id' => 'required',
            'quantity' => 'required',
            'delivery_date' => 'required',
            'delivery_note' => 'required',
            'delivery_status' => 'required',
            'customer_order_id' => 'required',
        ]);

        $orderDelivery = new CustomerOrderDelivery;
        $orderDelivery->customer_order_id = $request->customer_order_id;
        $orderDelivery->product_id = $request->product_id;
        $orderDelivery->quantity = null_check($request->quantity);
        $orderDelivery->delivery_date = string_date_null_check(escape_output($request->delivery_date));
        $orderDelivery->delivery_note = escape_output($request->delivery_note) ?? null;
        $orderDelivery->delivery_status = escape_output($request->delivery_status) ?? null;
        $orderDelivery->save();

        return redirect('customer-orders/' . $request->customer_order_id . '/edit')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RawMaterialPurchase  $rawmaterialpurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerOrder $customerOrder)
    {
        //delete previous data before add
        CustomerOrderDetails::where('customer_order_id', $customerOrder->id)->update(['del_status' => "Deleted"]);
        CustomerOrderInvoice::where('customer_order_id', $customerOrder->id)->update(['del_status' => "Deleted"]);
        CustomerOrderDelivery::where('customer_order_id', $customerOrder->id)->update(['del_status' => "Deleted"]);

        $customerOrder->del_status = "Deleted";
        $customerOrder->save();
        return redirect('customer-orders')->with(deleteMessage());
    }

    /**
     * Download customer order invoice
     */

    public function downloadInvoice($id)
    {
        $obj = CustomerOrder::find(encrypt_decrypt($id, 'decrypt'));
        $orderDetails = CustomerOrderDetails::where('customer_order_id', $obj->id)->where('del_status', "Live")->get();
        $orderInvoice = CustomerOrderInvoice::where('customer_order_id', $obj->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();
        $orderDeliveries = CustomerOrderDelivery::where('customer_order_id', $obj->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();

        $pdf = PDF::loadView('pages.customer_order.invoice', compact('obj', 'orderDetails', 'orderInvoice', 'orderDeliveries'));
        return $pdf->download($obj->reference_no . '.pdf');
    }

    /**
     * Print
     */

    public function print($id)
    {
        $obj = CustomerOrder::find($id);
        $orderDetails = CustomerOrderDetails::where('customer_order_id', $obj->id)->where('del_status', "Live")->get();
        $orderInvoice = CustomerOrderInvoice::where('customer_order_id', $obj->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();
        $orderDeliveries = CustomerOrderDelivery::where('customer_order_id', $obj->id)->where('del_status', "Live")->orderBy('id', 'desc')->get();

        return view('pages.customer_order.invoice', compact('obj', 'orderDetails', 'orderInvoice', 'orderDeliveries'));
    }
}
