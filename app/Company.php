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
  # This is Company Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = "tbl_companies";
    public $timestamps = false;

    protected $guarded = ["id"];
}
