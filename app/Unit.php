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
  # This is Unit Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "tbl_rmunits";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','description','del_status'
    ];
}
