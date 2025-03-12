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
  # This is Mnonitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Mnonitem extends Model
{
    protected $table = "tbl_manufactures_noninventory";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','noninvemtory_id','manufacture_id','nin_cost','del_status'
    ];

    /**
     * Relationship with Manufacture
     */
    public function manufacture()
    {
        return $this->belongsTo('App\Manufacture', 'manufacture_id');
    }
}
