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
  # This is OrderStatusController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\CustomerOrder;
use App\CustomerOrderDetails;
use App\Http\Controllers\Controller;
use App\Manufacture;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function customerOrderStatus()
    {
        $title = __('index.customer_order_status');
        $order_quotation = CustomerOrder::where('order_status', 'Quotation')->where('del_status', 'Live')->get();
        $excludedProductIds = Manufacture::where('del_status', 'Live')->pluck('product_id');
        $details = CustomerOrderDetails::whereNotIn('product_id', $excludedProductIds)->where('del_status', 'Live')->pluck('customer_order_id');
        $waiting_for_confirmation = CustomerOrder::whereIn('id', $details)->where('del_status', 'Live')->get();
        
        $excludedProductIds = Manufacture::where('manufacture_status', 'draft')->where('del_status', 'Live')->pluck('product_id');
        $details = CustomerOrderDetails::whereNotIn('product_id', $excludedProductIds)->where('del_status', 'Live')->pluck('customer_order_id');
        $waiting_for_production = CustomerOrder::with('details')->whereIn('id', $details)->where('del_status', 'Live')->get();

        $excludedProductIds = Manufacture::where('manufacture_status', 'inProgress')->where('del_status', 'Live')->pluck('product_id');
        $details = CustomerOrderDetails::whereNotIn('product_id', $excludedProductIds)->where('del_status', 'Live')->pluck('customer_order_id');
        $in_production = CustomerOrder::whereIn('id', $details)->where('del_status', 'Live')->get();

        $excludedProductIds = Manufacture::where('manufacture_status', 'done')->where('del_status', 'Live')->pluck('product_id');
        $details = CustomerOrderDetails::whereNotIn('product_id', $excludedProductIds)->where('del_status', 'Live')->pluck('customer_order_id');
        $ready_for_shipment = CustomerOrder::whereIn('id', $details)->where('del_status', 'Live')->get();


        return view('pages.customer_order.order-status', compact('title', 'order_quotation', 'waiting_for_confirmation', 'waiting_for_production', 'in_production', 'ready_for_shipment'));
    }
}
