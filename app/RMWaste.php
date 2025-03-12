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
  # This is RMWaste Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class RMWaste extends Model
{
    protected $table = "tbl_wastes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','reference_no','responsible_person','added_by','date','total_loss','note','del_status'
    ];
}
