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
  # This is RMWasteItem_model Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RMWasteItem_model extends Model
{
    protected $table = "tbl_waste_materials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','rmaterials_id','waste_amount','last_purchase_price','loss_amount','waste_id','del_status'
    ];
}
