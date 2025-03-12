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
  # This is SaleDetail Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'tbl_sale_details';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * Relation with Finished Product
     */
    public function product()
    {
        return $this->belongsTo('App\FinishedProduct', 'product_id');
    }
    /**
     * Relation with Sale
     */
    public function sale()
    {
        return $this->belongsTo('App\Sales', 'sale_id');
    }
}
