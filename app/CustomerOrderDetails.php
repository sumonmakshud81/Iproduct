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
  # This is CustomerOrderDetails Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderDetails extends Model
{
    protected $table = "tbl_customer_order_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Customer Order Relationship
     */
    public function customerOrder() {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id', 'id');
    }
    /**
     * Product Relationship
     */
    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id');
    }
}
