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
  # This is Sales Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = "tbl_sales";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    protected $appends = ['cost_of_goods', 'cost_of_transferred', 'total_tax'];

    /**
     * Relationship with Customer
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    /**
     * Relationship with Sale Details
     */
    public function details()
    {
        return $this->hasMany('App\SaleDetail', 'sale_id');
    }

    /**
     * Define scope for date filter
     */
    public function scopeDateFilter($query, $from, $to)
    {
        if($from && $to && $from != '' && $to != '')
        {
            return $query->whereBetween('created_at', [$from, $to]);
        }
    }
    /**
     * Define scope for single date filter
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('sale_date', $date);
        }
    }

    /**
     * Get Cost of Goods
     */
    public function getCostOfGoodsAttribute()
    {
        return $this->getCost();
    }
    /**
     * Get Cost of Transferred
     */
    public function getCostOfTransferredAttribute()
    {
        return $this->costOfTransferred();
    }
    /**
     * Get Total Tax
     */
    public function getTotalTaxAttribute()
    {
        $total = 0;
        foreach($this->details as $detail)
        {
            $total += $detail->product->tax_amount;
        }
        return $total;
    }
    /**
     * Get Cost of Goods
     */
    public function getCost()
    {
        $total = 0;
        foreach($this->details as $detail)
        {
            $total += $detail->product->rmcost_total;
        }
        return $total;
    }

    /**
     * Get Cost of Transferred
     */
    public function costOfTransferred()
    {
        $total = 0;
        foreach($this->details as $detail)
        {
            $total += $detail->product->noninitem_total;
        }
        return $total;
    }
}
