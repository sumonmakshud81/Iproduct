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
  # This is CustomerDueReceive Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDueReceive extends Model
{
    protected $table = "tbl_customer_due_receives";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Get Customer Name
     */
    public function customerName($value='')
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Scope For Single Date
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('only_date', $date);
        }
    }
}
