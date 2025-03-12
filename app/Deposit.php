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
  # This is Deposit Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = "tbl_deposits";
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Account Relationship
     */
    public function accountName($value='')
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }
    /**
     * Get Added By User
     */
    public function addedBy($value='')
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
