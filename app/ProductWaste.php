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
  # This is ProductWaste Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductWaste extends Model
{
	protected $table = "tbl_fpwastes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Relation with Supplier
     */
    public function supplierName()
    {
        return $this->belongsTo(Supplier::class, 'supplier', 'id');
    }

    /**
     * Relation with Product Waste Items
     */
    public function productItems()
    {
        return $this->hasMany(ProductWasteItems::class, 'fpwaste_id');
    }
}
