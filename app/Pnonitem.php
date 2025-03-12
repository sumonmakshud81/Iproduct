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
  # This is Pnonitem Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Pnonitem extends Model
{
    protected $table = "tbl_production_noninventory";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','production_id','ni_id','account','total','totalamount','del_status'
    ];

    /**
     * Define Scope for Single Date
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('created_at', $date);
        }
    }
}
