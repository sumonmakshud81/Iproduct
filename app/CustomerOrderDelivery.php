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
  # This is CustomerOrderDelivery Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderDelivery extends Model
{
    protected $table = "tbl_customer_order_deliveries";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Customer Relationship
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    /**
     * Product Relationship
     */
    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'product_id');
    }
}
