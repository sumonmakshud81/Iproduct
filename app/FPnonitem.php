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
  # This is FPnonitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FPnonitem extends Model
{
    protected $table = "tbl_finished_products_noninventory";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','noninvemtory_id','finish_product_id','nin_cost','del_status'
    ];

    /**
     * Get Finish Product Non Inventory Item
     */
    public function getFinishProductNONI($fproduct_id){
        $result = DB::select("SELECT * FROM tbl_finished_products_noninventory WHERE del_status='Live' AND finish_product_id='$fproduct_id'");
        return $result;
    }

    /**
     * Relationship with Non Inventory Item
     */
    public function nonInventoryItem()
    {
        return $this->belongsTo(NonIItem::class, 'noninvemtory_id');
    }
}
