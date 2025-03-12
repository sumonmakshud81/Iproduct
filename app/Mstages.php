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
  # This is Mstages model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Mstages extends Model
{
    protected $table = "tbl_manufactures_stages";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','productionstage_id','manufacture_id','stage_check','stage_month','stage_day','stage_hours','stage_minute','del_status'
    ];
}
