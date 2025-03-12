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
  # This is ProductWasteItems Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductWasteItems extends Model
{
    protected $table = "tbl_fpwastes_fp";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Relation with Finished Product
     */
    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'finish_product_id');
    }
}
