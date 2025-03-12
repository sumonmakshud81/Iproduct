<?php
/*
##############################################################################
# iProduction - Production and Manufacture Management Software
##############################################################################
# AUTHOR:        Door Soft
##############################################################################
# EMAIL:        info@doorsoft.co
##############################################################################
# COPYRIGHT:        RESERVED BY Door Soft
##############################################################################
# WEBSITE:        https://www.doorsoft.co
##############################################################################
# This is SupplierPaymentController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\AdminSettings;
use App\FinishedProduct;
use App\Supplier;
use App\Supplier_payment;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Supplier_payment::where('del_status', "Live")->orderBy('id', 'DESC')->get();
        $title = __('index.supplier_due_payment');

        return view('pages.supplier_payment.index', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_supplier_due_payment');

        $company_id = auth()->user()->company_id;

        $suppliers = Supplier::where('company_id', $company_id)->where('del_status', "Live")->get();
        $accountList = Account::where('del_status', "Live")->get();

        return view('pages.supplier_payment.create', compact('title', 'suppliers', 'accountList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'date' => 'required|date',
            'supplier' => 'required',
            'amount' => 'required|numeric',
            'account_id' => 'required',
        ],
            [
                'date.required' => __('index.date_required'),
                'amount.required' => __('index.amount_required'),
                'account_id.required' => __('index.account_required'),
            ]);

        $obj = new \App\Supplier_payment;
        $obj->date = escape_output($request->date);
        $obj->supplier = escape_output($request->supplier);
        $obj->amount = escape_output($request->amount);
        $obj->account_id = escape_output($request->account_id);
        $obj->note = escape_output($request->note);
        $obj->added_by = auth()->user()->id;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();

        return redirect('supplier-payment')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $supplier_payment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier_payment = Supplier_payment::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_supplier_due_payment');
        $obj = $supplier_payment;

        $company_id = auth()->user()->company_id;

        $suppliers = Supplier::where('company_id', $company_id)->where('del_status', "Live")->get();
        $accountList = Account::where('del_status', "Live")->get();

        return view('pages.supplier_payment.edit', compact('title', 'obj', 'suppliers', 'accountList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $supplier_payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier_payment $supplier_payment)
    {
        request()->validate([
            'date' => 'required|date',
            'supplier' => 'required',
            'amount' => 'required|numeric',
            'account_id' => 'required',
        ], [
            'date.required' => __('index.date_required'),
            'amount.required' => __('index.amount_required'),
            'account_id.required' => __('index.account_required'),
        ]);

        $supplier_payment->date = escape_output($request->date);
        $supplier_payment->supplier = escape_output($request->supplier);
        $supplier_payment->amount = escape_output($request->amount);
        $supplier_payment->account_id = escape_output($request->account_id);
        $supplier_payment->note = escape_output($request->note);
        $supplier_payment->save();
        return redirect('supplier-payment')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $supplier_payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier_payment $supplier_payment)
    {
        $supplier_payment->del_status = "Deleted";
        $supplier_payment->save();
        return redirect('supplier-payment')->with(deleteMessage());
    }

    public function download($id)
    {
        $id = encrypt_decrypt($id, 'decrypt');
        $title = __('index.supplier_payment_invoice');

        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        $suppliers = Supplier::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $finishProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $fifoProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->where('stock_method', "fifo")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        $obj = Supplier_payment::findOrFail($id);
        
        return view('pages.supplier_payment.invoice', compact('title', 'obj', 'suppliers', 'accounts', 'company'));
    }

    public function print($id)
    {
        $title = __('index.supplier_payment_invoice');

        $obj = Supplier_payment::findOrFail($id);
        $suppliers = Supplier::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        return view('pages.supplier_payment.print_invoice', compact('title', 'obj', 'suppliers', 'accounts', 'company'));
    }
}
