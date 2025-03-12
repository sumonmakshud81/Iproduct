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
# This is Stock Model
##############################################################################
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    /**
     * Get Stock of Finished Products
     */
    public function getRMStock($category_id = '', $product_id = '')
    {
        $where = null;
        if ($category_id != '' && $product_id == '') {
            $where = [
                'category' => $category_id,
                'product_id' => $product_id
            ];
        }

        if ($category_id != '') {
            $where = [
                'category' => $category_id
            ];
        }

        if ($product_id != '') {
            $where = [
                'product_id' => $product_id
            ];
        }

        $result = RawMaterial::where('del_status', 'Live')
            ->where($where)
            ->orderBy('id', 'DESC')
            ->get();
        return $result;
    }
    /**
     * Get Low Stock of Raw Materials
     */
    public function getLowRMStock()
    {
        $lowStock = [];
        $result = RawMaterial::where('del_status', 'Live')
            ->orderBy('name', 'ASC')
            ->get();

        foreach ($result as $key => $value) {           

            if ($value->current_stock < $value->alert_level) {
                //Total stock attribute added to the object
                $value->total_stock = $value->current_stock;
                $lowStock[] = $value;
            }
        }
        return $lowStock;
    }
}
