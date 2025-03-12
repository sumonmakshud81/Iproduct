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
  # This is ProductionScheduling Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionScheduling extends Model
{
    use HasFactory;

    protected $table = 'tbl_production_scheduling';

    /**
     * Relationship with Manufacture Stage
     */
    public function stage()
    {
        return $this->belongsTo('App\Mstages', 'production_stage_id');
    }
}
