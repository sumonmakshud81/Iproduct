<?php

namespace App\Imports;

use App\RawMaterial;
use App\RawMaterialCategory;
use App\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RawMaterialImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $obj_rm = RawMaterial::count();
        //generate code
        $code = "RM-" . str_pad($obj_rm + 1, 3, '0', STR_PAD_LEFT);
        $category = RawMaterialCategory::where('name', $row['category'])->first();
        if (!$category) {
            $cat_name = $row['category'];
            $category = RawMaterialCategory::create(['name' => $cat_name]);
            $category_id = $category->id;
        } else {
            $category_id = $category->id;
        }

        $consumption_unit = Unit::where('name', $row['consumption_unit'])->first();
        if (!$consumption_unit) {
            $con_unit_name = $row['consumption_unit'];
            $consumption_unit = Unit::create(['name' => $con_unit_name]);
            $consumption_unit_id = $consumption_unit->id;
        } else {
            $consumption_unit_id = $consumption_unit->id;
        }

        $unit = Unit::where('name', $row['unit'])->first();
        if (!$unit) {
            $unit_name = $row['unit'];
            $unit = Unit::create(['name' => $unit_name]);
            $unit_id = $unit->id;
        } else {
            $unit_id = $unit->id;
        }
        try {
            return new RawMaterial([
                'code' => $code,
                'name' => $row['name'],
                'category' => $category_id,
                'consumption_unit' => $consumption_unit_id,
                'unit' => $unit_id,
                'rate_per_unit' => $row['rate_per_unit'],
                'consumption_check' => $row['has_consumption'],
                'conversion_rate' => $row['conversion_rate'],
                'rate_per_consumption_unit' => $row['rate_per_consumption_unit'],
                'opening_stock' => $row['opening_stock'],
                'alert_level' => $row['alert_level'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function headingRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'category' => 'required',
            'consumption_unit' => 'required',
            'unit' => 'required',
            'rate_per_unit' => 'required',
            'has_consumption' => 'required',
            'conversion_rate' => 'required',
            'rate_per_consumption_unit' => 'required',
            'opening_stock' => 'required',
            'alert_level' => 'required',
        ];
    }
}
