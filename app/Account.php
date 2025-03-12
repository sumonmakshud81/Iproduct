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
  # This is Account Model
  ##############################################################################
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = "tbl_accounts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','company_id','acc_name','opening_balance','description','del_status'
    ];

    /**
     * Deposit Relationship
     */
    public function deposit()
    {
        return $this->hasMany('App\Deposit');
    }

    /**
     * Sales Relationship
     */

    public function sales()
    {
        return $this->hasMany('App\Sales');
    }

    /**
     * Purchase Relationship
     */

    public function purchase()
    {
        return $this->hasMany('App\RawMaterialPurchase', 'account', 'id');
    }

    /**
     * Customer Due Receive Relationship
     */

    public function customerDueReceive()
    {
        return $this->hasMany('App\CustomerDueReceive');
    }

    /**
     * Supplier Due Pay Relationship
     */

    public function supplierDuePay()
    {
        return $this->hasMany('App\Supplier_payment');
    }

    /**
     * Production Non Inventory Relationship
     */

    public function productionNonInventory()
    {
        return $this->hasMany('App\Pnonitem', 'account', 'id');
    }

    /**
     * Calculate Balance
     */

     public function balance()
     {
        $balance = getTotalCredit($this->id) - getTotalDebit($this->id);

        return getAmtCustom($balance);
     }

     /**
      * Balance Number Format
      */

      public function balanceNumberFormat()
      {
        $balance = getTotalCredit($this->id) - getTotalDebit($this->id);

        return $balance;
      }
}
