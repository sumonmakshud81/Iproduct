<?php

namespace App\Imports;

use App\FinishedProduct;
use App\NonIItem;
use App\ProductionStage;
use App\RawMaterial;
use App\RawMaterialCategory;
use App\Tax;
use App\TaxItems;
use App\Unit;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;


class ProductImport implements ToCollection, WithHeadingRow, WithValidation
{
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $category_id = RawMaterialCategory::where('name', $row['category'])->first();
            $unit_id = Unit::where('name', $row['unit'])->first();
            if ($category_id) {
                $category_id = $category_id->id;
            } else {
                $category_id = RawMaterialCategory::create(['name' => $row['category']]);
            }
            if ($unit_id) {
                $unit_id = $unit_id->id;
            } else {
                $unit_id = Unit::create(['name' => $row['unit']]);
            }
            $raw_materials = explode(',', $row['raw_materials']);
            $raw_material_ids = [];
            foreach ($raw_materials as $raw_material) {
                preg_match('/([A-Za-z0-9-]+)\((\d+)\)/', $raw_material, $matches);
                $raw_material_id = RawMaterial::where('code', $matches[1])->first()->id;
                if ($raw_material_id) {
                    $quantity = $matches[2];
                    $raw_material_ids[] = ['raw_material_id' => $raw_material_id, 'quantity' => $quantity];
                } else {
                    return redirect()->back()->with('error', 'Raw material not found: ' . $raw_material);
                }
            }
            $non_inventory_item = explode(',', $row['non_inventory_item']);
            $non_inventory_item_ids = [];
            foreach ($non_inventory_item as $item) {
                preg_match('/([A-Za-z0-9-]+)\((\d+)\)/', $item, $matches);
                $non_inventory_item_id = NonIItem::where('name', $matches[1])->first();
                $cost = $matches[2];
                if ($non_inventory_item_id) {
                    $non_inventory_item_ids[] = ['non_inventory_item_id' => $non_inventory_item_id->id, 'cost' => $cost];
                } else {
                    $non_inventory_item_id = NonIItem::create(['name' => $matches[1]]);
                    $non_inventory_item_ids[] = ['non_inventory_item_id' => $non_inventory_item_id->id, 'cost' => $cost];
                }
            }

            $production_stage = explode(', ', $row['production_stage']);
            $production_stage_ids = [];
            foreach ($production_stage as $stage) {
                preg_match('/([A-Za-z\s]+)\(([\d,]+)\)/', $stage, $matches);
                $production_stage_id = ProductionStage::where('name', $matches[1])->first();
                $estimated_time = $matches[2];

                $estimate_time_split = explode(',', $estimated_time);
                $month = $estimate_time_split[0];
                $day = $estimate_time_split[1];
                $hour = $estimate_time_split[2];
                $minute = $estimate_time_split[3];
                if ($production_stage_id) {
                    $production_stage_ids[] = ['production_stage_id' => $production_stage_id->id, 'month' => $month, 'day' => $day, 'hour' => $hour, 'minute' => $minute];
                } else {
                    $production_stage_id = ProductionStage::create(['name' => $matches[1]]);
                    $production_stage_ids[] = ['production_stage_id' => $production_stage_id->id, 'month' => $month, 'day' => $day, 'hour' => $hour, 'minute' => $minute];
                }
            }
            $tax_fields = Tax::orderBy('id', 'ASC')->where('del_status', "Live")->get();
            $tax_items = TaxItems::first();
            $tax_information = [];
            $totalTaxRate = 0;
            foreach ($tax_fields as $tax_field) {
                $tax_information[] = [
                    'tax_field_id' => $tax_field->id,
                    'tax_field_name' => $tax_field->tax,
                    'tax_field_percentage' => $tax_field->tax_rate,
                ];
                $totalTaxRate += $tax_field->tax_rate;
            }
            $tax_info = json_encode($tax_information);

            $obj_rm = FinishedProduct::count();
            $ref_no = "FP-" . str_pad($obj_rm + 1, 6, '0', STR_PAD_LEFT);
            DB::beginTransaction();
            try {
                $finishedProduct = FinishedProduct::create([
                    'code' => $ref_no,
                    'name' => $row['name'],
                    'category' => $category_id,
                    'unit' => $unit_id,
                    'stock_method' => $row['stock_method'],
                    'rmcost_total' => 0,
                    'noninitem_total' => 0,
                    'total_cost' => $row['total_cost'],
                    'profit_margin' => $row['profit_margin'],
                    'sale_price' => $row['total_cost'] + ($row['total_cost'] * $totalTaxRate / 100) + ($row['total_cost'] * $row['profit_margin'] / 100),
                    'tax_information' => $tax_info,
                    'added_by' => auth()->user()->id,
                    'company_id' => auth()->user()->company_id,
                ]);
                $total_rm_cost = 0;
                foreach ($raw_material_ids as $raw_material) {
                    $raw_material = RawMaterial::find($raw_material['raw_material_id']);
                    $obj = new \App\FPrmitem;
                    $obj->rmaterials_id = $raw_material->id;
                    $obj->unit_price = $raw_material->rate_per_unit;
                    $obj->consumption = $raw_material['quantity'];
                    $obj->total_cost = (float) $raw_material['quantity'] * $raw_material->rate_per_unit;
                    $obj->finish_product_id = $finishedProduct->id;
                    $obj->company_id = auth()->user()->company_id;
                    $obj->save();
                    $total_rm_cost += $obj->total_cost;
                }
                $total_non_inventory_cost = 0;
                foreach ($non_inventory_item_ids as $row => $value) {
                    $non_inventory_item = NonIItem::find($value['non_inventory_item_id']);
                    $obj = new \App\FPnonitem;
                    $obj->noninvemtory_id = $non_inventory_item->id;
                    $obj->nin_cost = $value['cost'];
                    $obj->finish_product_id = $finishedProduct->id;
                    $obj->company_id = auth()->user()->company_id;
                    $obj->save();
                    $total_non_inventory_cost += $obj->nin_cost;
                }
                foreach ($production_stage_ids as $row => $value) {
                    $production_stage = ProductionStage::find($value['production_stage_id']);
                    $obj = new \App\FPproductionstage();
                    $obj->productionstage_id = $production_stage->id;
                    $obj->stage_month = $value['month'];
                    $obj->stage_day = $value['day'];
                    $obj->stage_hours = $value['hour'];
                    $obj->stage_minute = $value['minute'];
                    $obj->finish_product_id = $finishedProduct->id;
                    $obj->company_id = auth()->user()->company_id;
                    $obj->save();
                }

                $finishedProduct->rmcost_total = $total_rm_cost;
                $finishedProduct->noninitem_total = $total_non_inventory_cost;
                $finishedProduct->save();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }
    public function headingRow(): int
    {
        return 3;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'stock_method' => 'required|string|in:none,fifo,batchcontrol,fefo',
            'raw_materials' => 'required|string',
            'non_inventory_item' => 'required|string',
            'total_cost' => 'required|numeric|min:0',
        ];
    }
}
