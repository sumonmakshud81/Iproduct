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
  # This is Attendance Model
  ##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = "tbl_attendance";
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    public $timestamps = false;

    protected $guarded = ["id"];

    /**
     * User Relationship
     */
    public function user($value='')
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    /**
     * Added By Relation with User
     */
    public function addedBy($value='')
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
