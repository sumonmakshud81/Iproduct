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
  # This is ExpenseCategory Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{ 
	protected $table = "tbl_expense_items";
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Get Added By User
     */
    public function addedBy($value='')
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
