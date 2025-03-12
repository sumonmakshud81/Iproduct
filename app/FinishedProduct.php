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
  # This is FinishedProduct Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class FinishedProduct extends Model
{
    protected $table = "tbl_finish_products";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['tax_amount', 'required_time'];

    

    /**
     * Get Tax Amount
     */
    public function getTaxAmountAttribute()
    {
        $json_decode = json_decode($this->tax_information);
        $tax = 0;
        if ($json_decode) {
            foreach ($json_decode as $tax_info) {
                $tax += (float)$tax_info->tax_field_percentage;
            }
        }

        $total_cost = $this->total_cost;
        $tax_amount = ($total_cost * $tax) / 100;

        return $tax_amount;
    }

    /**
     * Relation with SaleDetail
     */
    public function sales()
    {
        return $this->hasMany(SaleDetail::class, 'product_id');
    }
    /**
     * Relation with RawMaterial
     */
    public function rmaterials()
    {
        return $this->hasMany(FPrmitem::class, 'finish_product_id')->where('del_status', 'Live');
    }

    /**
     * Relation with NonInventory
     */
    public function nonInventory()
    {
        return $this->hasMany(FPnonitem::class, 'finish_product_id')->where('del_status', 'Live');
    }
    /**
     * Relation with Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit')->where('del_status', 'Live');
    }
    /**
     * Relation with Stage
     */
    public function stage()
    {
        return $this->hasMany(FPproductionstage::class, 'finish_product_id')->where('del_status', 'Live');
    }
    /**
     * Get Required Time
     */
    public function getRequiredTimeAttribute()
    {
        // calculate total time required for production
        $total_time = 0;
        $stage_month = 0;
        $stage_day = 0;
        $stage_hours = 0;
        $stage_minutes = 0;
        foreach ($this->stage as $stage) {
            $stage_month += $stage->stage_month;
            $stage_day += $stage->stage_day;
            $stage_hours += $stage->stage_hours;
            $stage_minutes += $stage->stage_minute;
        }

        $total_time += $stage_month * 30 * 24 * 60;

        $total_time += $stage_day * 24 * 60;

        $total_time += $stage_hours * 60;

        $total_time += $stage_minutes;

        //return total time in like that 2 month 3 days 4 hours 5 minutes
        $month = floor($total_time / (30 * 24 * 60));
        $total_time = $total_time % (30 * 24 * 60);
        $day = floor($total_time / (24 * 60));
        $total_time = $total_time % (24 * 60);
        $hour = floor($total_time / 60);
        $minute = $total_time % 60;

        return $month . ' month ' . $day . ' days ' . $hour . ' hours ' . $minute . ' minutes';
    }

    public function manufacture()
    {
        return $this->hasMany(Manufacture::class, 'product_id')->where('del_status', 'Live');
    }
}
