<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'name' => $row['name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'opening_balance' => $row['opening_balance'],
            'opening_balance_type' => $row['opening_balance_type'],
            'credit_limit' => $row['credit_limit'],
            'customer_type' => $row['customer_type'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:tbl_customers,phone',
            'email' => 'required|email|unique:tbl_customers,email',
        ];
    }

     public function headingRow(): int
    {
        return 3;
    }
}
