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
  # This is Expense Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = "tbl_expenses";
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * Expense Category Relationship
     */
    public function expenseCategory($value='')
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }
    /**
     * Responsible By User
     */
    public function responsibleBy($value='')
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    /**
     * Scope for Single Date
     */
    public function scopeSingleDate($query, $date)
    {
        if($date && $date != '')
        {
            return $query->whereDate('date', $date);
        }
    }
}
