<?php
/*
##############################################################################
# iProduction - Production and Manufacture Management
##############################################################################
# AUTHOR:        Door Soft
##############################################################################
# EMAIL:        info@doorsoft.co
##############################################################################
# COPYRIGHT:        RESERVED BY Door Soft
##############################################################################
# WEBSITE:        https://www.doorsoft.co
##############################################################################
# This is WhiteLabelSettings Model
##############################################################################
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhiteLabelSettings extends Model
{
    protected $table = "tbl_white_label_settings";

    public $timestamps = false;

    protected $guarded = ["id"];
}

