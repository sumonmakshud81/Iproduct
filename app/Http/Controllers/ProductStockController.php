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
  # This is ProductStockController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\FinishedProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function productStock()
    {
        $title = __('index.product_stocks');
        $obj = FinishedProduct::latest()->where('del_status', 'Live')->get();
        return view('pages.finished_product.product_stock', compact('title', 'obj'));
    }
}
