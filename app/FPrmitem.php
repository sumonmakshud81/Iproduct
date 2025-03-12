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
  # This is FPrmitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FPrmitem extends Model
{
    protected $table = "tbl_finished_products_rmaterials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get Finish Product Raw Material
     */

    public function getFinishProductRM($fproduct_id){
        $result = DB::select("SELECT * FROM tbl_finished_products_rmaterials WHERE del_status='Live' AND finish_product_id='$fproduct_id' ORDER BY id DESC");
        return $result;
    }

    /**
     * Relationship with Raw Material
     */
    public function rawMaterials()
    {
        return $this->belongsTo(RawMaterial::class, 'rmaterials_id');
    }

}
