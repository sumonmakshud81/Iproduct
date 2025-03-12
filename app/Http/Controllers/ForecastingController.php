<?php

namespace App\Http\Controllers;

use App\CustomerOrder;
use App\CustomerOrderDetails;
use App\FinishedProduct;
use App\FPrmitem;
use App\Http\Controllers\Controller;
use App\RawMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ForecastingController extends Controller
{
    public function order(Request $request)
    {
        $orders = CustomerOrder::where('del_status', 'Live')->get();
        $title = 'Forecasting by Order';        
        return view('pages.forecasting.order', compact('orders', 'title'));
    }

    public function orderView(Request $request)
    {
        $title = 'Forecasting by Order';
        $order_id = $request->order_id;      

        $orders = CustomerOrder::with(['details', 'details.product'])->where('id', $order_id)->get();
        session(['order_id' => $order_id]);
        return view('pages.forecasting.order-view', compact('title', 'orders'));
    }

    public function orderPrint()
    {
        $title = 'Forecasting by Order';
        $order_id = session('order_id');      

        $orders = CustomerOrder::with(['details', 'details.product'])->where('id', $order_id)->get();
        
        return view('pages.forecasting.order-print', compact('title', 'orders'));
    }

    public function orderDownload()
    {
        $title = 'Forecasting by Order';
        $order_id = session('order_id');      

        $orders = CustomerOrder::with(['details', 'details.product'])->whereIn('id', $order_id)->get();
        
        $pdf = Pdf::loadView('pages.forecasting.order-print', compact('title', 'orders'));
        $name = 'forecasting_' . time() . '.pdf';
        return $pdf->download($name);
    }

    public function product(Request $request)
    {
        $products = FinishedProduct::where('del_status', 'Live')->get();
        $title = 'Forecasting by Product';        
        return view('pages.forecasting.product', compact('products', 'title'));
    }

    public function productView(Request $request)
    {
        $title = 'Forecasting by Product';
        $product_id = $request->product_id;
        $quantity = $request->quantity;

        $quantity = array_combine($product_id, $quantity);
        $products = FinishedProduct::with(['rmaterials'])->whereIn('id', $product_id)->get()->map(function ($product) use ($quantity) {
            $product->required_quantity = $quantity[$product->id];
            $product->need_to_manufacture = $product->current_total_stock - $quantity[$product->id];            
            return $product;
        });

        session(['product_id' => $product_id, 'quantity' => $quantity]);

        return view('pages.forecasting.product-view', compact('title', 'products'));
    }

    public function productPrint()
    {
        $title = 'Forecasting by Product';
        $product_id = session('product_id');
        $quantity = session('quantity');

        $quantity = array_combine($product_id, $quantity);
        $products = FinishedProduct::with(['rmaterials'])->whereIn('id', $product_id)->get()->map(function ($product) use ($quantity) {
            $product->required_quantity = $quantity[$product->id];
            $product->need_to_manufacture = $product->current_total_stock - $quantity[$product->id];
            return $product;
        });

        return view('pages.forecasting.product-print', compact('title', 'products'));
    }

    public function productDownload()
    {
        $title = 'Forecasting by Product';
        $product_id = session('product_id');
        $quantity = session('quantity');

        $quantity = array_combine($product_id, $quantity);
        $products = FinishedProduct::with(['rmaterials'])->whereIn('id', $product_id)->get()->map(function ($product) use ($quantity) {
            $product->required_quantity = $quantity[$product->id];
            $product->need_to_manufacture = $product->current_total_stock - $quantity[$product->id];
            return $product;
        });

        $pdf = Pdf::loadView('pages.forecasting.product-print', compact('title', 'products'));
        $name = 'forecasting_' . time() . '.pdf';
        return $pdf->download($name);
    }
}
