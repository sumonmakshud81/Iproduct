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
  # This is PaymentSettings Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSettings extends Model
{
    protected $table = "tbl_payment_settings";

    protected $fillable = [
        'status', 'method', 'type', 'client_id', 'secret_key', 'created_by', 'updated_by',
    ];
}
