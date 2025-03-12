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
  # This is RawMaterialPurchase Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RawMaterialPurchase extends Model
{
    protected $table = "tbl_purchase";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','reference_no','supplier_id','invoice_no','date','subtotal','other','grand_total','paid','due','discount','account','file','note','status','user_id','del_status'
    ];

    /**
     * Define Scope for Single Date
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('date', $date);
        }
    }

    /**
     * Relationship with Purchase Raw Materials
     */
    public function purchaseRmaterials()
    {
        return $this->hasMany(RMPurchase_model::class, 'purchase_id');
    }

    /**
     * Relationship with Supplier
     */
    public function getSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier');
    }
}
