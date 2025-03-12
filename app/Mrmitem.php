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
  # This is Mrmitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Mrmitem extends Model
{
    protected $table = "tbl_manufactures_rmaterials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','rmaterials_id','manufacture_id','unit_price','consumption','total_cost','del_status'
    ];
    /**
     * Relationship with Raw Material
     */
    public function rawMaterial(){
        return $this->belongsTo('App\RawMaterial', 'rmaterials_id');
    }
}
