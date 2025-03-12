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
  # This is RawMaterialCategory Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RawMaterialCategory extends Model
{
    protected $table = "tbl_rmcategory";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','description','del_status'
    ];
}
