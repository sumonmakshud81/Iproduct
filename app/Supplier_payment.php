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
  # This is Supplier_payment Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier_payment extends Model
{
    protected $table = "tbl_supplier_payments";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Relationship with Supplier
     */
    public function supplierName($value='')
    {
        return $this->belongsTo(Supplier::class, 'supplier', 'id');
    }

    /**
     * Define Scope for Single Date
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('date', $date);
        }
    }
}
