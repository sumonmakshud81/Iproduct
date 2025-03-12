<?php
/*
  ##############################################################################
  # iProduction - Production and Manufacture Management
  ##############################################################################
  # AUTHOR:		Door Soft
  ##############################################################################
  # EMAIL:		info@doorsoft.co
  ##############################################################################
  # COPYRIGHT:		RESERVED BY Door Soft
  ##############################################################################
  # WEBSITE:		https://www.doorsoft.co
  ##############################################################################
  # This is Manufacture Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manufacture extends Model
{
    protected $table = "tbl_manufactures";

    /**
     * Relationship with Customer
     */
    public function customer(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    /**
     * Relationship with Finished Product
     */
    public function product(){
        return $this->belongsTo('App\FinishedProduct', 'product_id');
    }
    /**
     * Relationship with Raw Materials
     */
    public function rawMaterials(){
        return $this->hasMany('App\Mrmitem', 'manufacture_id')->where('del_status', 'Live');
    }
        
    /**
     * Get Finish Product Fifo
     */
    public function getFinishProductFifo($product_id){

        $result = Manufacture::where('del_status', 'Live')
            ->where('product_id', $product_id)
            ->where('manufacture_status', 'done')
            ->where('product_quantity', '>', 0)
            ->orderBy('id', 'asc')
            ->get();

        return $result;
    }

    /**
     * Get Finish Product Fefo
     */
    public function getFinishProductFefo($product_id){

        $result = Manufacture::where('del_status', 'Live')
            ->where('product_id', $product_id)
            ->where('manufacture_status', 'done')
            ->where('product_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
        return $result;
    }

    /**
     * Relationship with Raw Material Waste
     */
    public function materialWaste()
    {
        return $this->hasMany('App\RMWasteItem_model', 'manufacture_id')->where('del_status', 'Live');
    }
    /**
     * Relationship with Product Waste
     */
    public function productWaste()
    {
        return $this->hasMany('App\ProductWaste', 'manufacture_id')->where('del_status', 'Live');
    }
    /**
     * Get Waste Percentage
     */
    public function getWastePercentage()
    {
        $rawMaterialWaste = $this->materialWaste->sum('waste_amount');
        $productWaste = $this->productWaste->map(function($item){
            return $item->productItems->sum('fp_waste_amount');
        })->sum();

        $totalRawMaterial = $this->rawMaterials->sum('consumption');
        $totalProduct = $this->product_quantity;
        if($totalRawMaterial == 0){
            $totalRawMaterial = 1;
        }

        if($totalProduct == 0){
            $totalProduct = 1;
        }

        $rawMaterialPercentage = $rawMaterialWaste / $totalRawMaterial * 100;
        $productPercentage = $productWaste / $totalProduct * 100;

        return [
            'raw_material' => number_format($rawMaterialPercentage, 2) . '%',
            'product' => number_format($productPercentage, 2) . '%'
        ];
    }

    /**
     * Scope Status
     */

    public function scopeStatus($query, $status)
    {
        if($status == null){
            return $query;
        }

        return $query->where('manufacture_status', $status);
    }

    /**
     * Scope Product
     */
    public function scopeProduct($query, $productId)
    {
        if($productId == null){
            return $query;
        }

        return $query->where('product_id', $productId);
    }

    /**
     * Scope Batch No
     */
    public function scopeBatchNo($query, $batch_no)
    {
        if($batch_no == null){
            return $query;
        }

        return $query->where('batch_no', $batch_no);
    }

    /**
     * Scope Customer
     */
    public function scopeCustomer($query, $customer)
    {
        if($customer == null){
            return $query;
        }

        return $query->where('customer_id', $customer);
    }

     /**
     * Get  Product Stages
     */
    public function getProductStages($fproduct_id){
        $result = DB::select("SELECT * FROM tbl_manufactures_stages WHERE del_status='Live' AND manufacture_id='$fproduct_id'");
        return $result;
    }

}
