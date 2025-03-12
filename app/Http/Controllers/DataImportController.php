<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use App\Imports\ProductImport;
use App\Imports\RawMaterialImport;
use App\Imports\SupplierImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataImportController extends Controller
{
    public function index()
    {
        $type = [
            'raw_material' => 'Raw Material',
            'product' => 'Product',
            'customer' => 'Customer',
            'supplier' => 'Supplier',
        ];
        $title = 'Data Import';
        return view('pages.data-import.index', compact('title', 'type'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'import_file' => 'required|mimes:xls,xlsx',
        ]);

        try {
            if ($request->type == 'raw_material') {
                $import = new RawMaterialImport();
            } elseif ($request->type == 'product') {
                $import = new ProductImport();
            } elseif ($request->type == 'bom') {
                $import = null;
            } elseif ($request->type == 'customer') {
                $import = new CustomerImport();
            } elseif ($request->type == 'supplier') {
                $import = new SupplierImport();
            }

            Excel::import($import, $request->import_file);
            return back()->with('success', 'Data imported successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function sample()
    {
        return response()->download(storage_path('sample.zip'));
    }
}
