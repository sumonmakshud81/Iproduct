<?php

namespace App\Imports;

use App\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Supplier([
            'name' => $row['name'],
            'contact_person' => $row['contact_person'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'opening_balance' => $row['opening_balance'],
            'opening_balance_type' => $row['opening_balance_type'],
            'credit_limit' => $row['credit_limit'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'contact_person' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:tbl_suppliers,phone',
            'email' => 'required|email|unique:tbl_suppliers,email',
        ];
    }

    public function headingRow(): int
    {
        return 3;
    }
}
