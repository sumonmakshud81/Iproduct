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
  # This is AdminSettings Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSettings extends Model
{
    protected $table = "tbl_admin_settings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_company_name', 'contact_person', 'phone', 'email', 'address', 'currency', 'time_zone', 'web_site', 'date_format', 'logo', 'address', 'user_name', 'password', 'status', 'approval_status'
    ];
}
