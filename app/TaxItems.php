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
  # This is TaxItems Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxItems extends Model
{
    protected $table = "tbl_tax_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
