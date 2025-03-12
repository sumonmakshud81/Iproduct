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
  # This is FPproductionstage Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FPproductionstage extends Model
{
    protected $table = "tbl_finished_products_productionstage";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','productionstage_id','finish_product_id','stage_month','stage_day','stage_hours','stage_minute','del_status'
    ];
    
    /**
     * Get Finish Product Stages
     */
    public function getFinishProductStages($fproduct_id){
        $result = DB::select("SELECT * FROM tbl_finished_products_productionstage WHERE del_status='Live' AND finish_product_id='$fproduct_id'");
        return $result;
    }
}
