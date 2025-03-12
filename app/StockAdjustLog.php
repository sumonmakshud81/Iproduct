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
  # This is StockAdjustLog Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_stock_adjust_logs';

    /**
     * Relation with Raw Material
     */
    public function rawMaterials()
    {
        return $this->belongsTo(RawMaterial::class, 'rm_id');
    }
}
