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
  # This is RMPurchase_model Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RMPurchase_model extends Model
{
    protected $table = "tbl_purchase_rmaterials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','rmaterials_id','unit_price','quantity_amount','total','purchase_id','del_status'
    ];
    /**
     * Relationship with Raw Material Purchase
     */
    public function purchase()
    {
        return $this->belongsTo(RawMaterialPurchase::class, 'purchase_id');
    }

    /**
     * Relationship with Raw Material
     */
    public function rawmaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rmaterials_id');
    }

    
}
