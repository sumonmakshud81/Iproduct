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
  # This is Production Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = "tbl_productions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','reference_no','production_stage','production_stage_text','start_date','completion_date','finished_product','quantity','rmcost_total','noninitem_total','total_cost','sale_price','note','added_by','file_paths','del_status'
    ];
}
