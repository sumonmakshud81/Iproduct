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
  # This is Prmitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Prmitem extends Model
{
    protected $table = "tbl_production_rmaterials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','production_id','rm_id','unit_price','consumption','total','del_status'
    ];
}
