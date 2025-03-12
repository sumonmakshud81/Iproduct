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
  # This is CustomerOrder Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $table = "tbl_customer_orders";

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
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Customer Order Details Relationship
     */
    public function details()
    {
        return $this->hasMany(CustomerOrderDetails::class, 'customer_order_id')->where('del_status', 'Live');
    }
}
