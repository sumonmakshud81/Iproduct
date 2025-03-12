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
  # This is Tax Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = "tbl_taxes";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','tax_id','tax','tax_rate','del_status'
    ];
}
