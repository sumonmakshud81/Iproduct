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
  # This is Role Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'tbl_roles';

    /**
     * Append extra field
     */
    protected $appends = array('menu_ids','activity_ids');

    /**
     * Get menu ids
     */
    public function getMenuIdsAttribute() {
        return RolePermission::where('role_id',$this->id)->pluck('menu_id')->toArray();
    }

    /**
     * Get menu ids
     */
    public function getActivityIdsAttribute() {
        return RolePermission::where('role_id',$this->id)->pluck('activity_id')->toArray();
    }
}
