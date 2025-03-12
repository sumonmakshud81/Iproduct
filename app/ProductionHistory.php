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
  # This is ProductionHistory Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionHistory extends Model
{
    use HasFactory;

    protected $table = 'tbl_production_history';
    /**
     * Relationship with Customer Order
     */
    public function customerOrder()
    {
        return $this->belongsTo('App\CustomerOrder', 'customer_order_id');
    }
}
