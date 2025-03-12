<?php
/*
  ##############################################################################
  # iProduction - Production and Manufacture Management Software
  ##############################################################################
  # AUTHOR:		Door Soft
  ##############################################################################
  # EMAIL:		info@doorsoft.co
  ##############################################################################
  # COPYRIGHT:		RESERVED BY Door Soft
  ##############################################################################
  # WEBSITE:		https://www.doorsoft.co
  ##############################################################################
  # This is CustomerPaymentController Controller
  ##############################################################################
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Customer;
use App\CustomerDueReceive;
use App\User;
use App\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use App\AdminSettings;
use App\FinishedProduct;


class CustomerPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = CustomerDueReceive::where('del_status',"Live")->orderBy('id','DESC')->get();
        $title =  __('index.customer_due_receives');

        return view('pages.customer_payment.index',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_customer_receive');

        $total_customerdue = CustomerDueReceive::count();
        $ref_no = str_pad($total_customerdue + 1, 6, '0', STR_PAD_LEFT);

        $company_id = auth()->user()->company_id;

        $customers = Customer::where('del_status',"Live")->get();
        $accountList = Account::where('del_status',"Live")->get();

        return view('pages.customer_payment.create',compact('title', 'ref_no', 'customers', 'accountList'));
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
            'reference_no' => 'required',
            'only_date' => 'required|date',
            'customer_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'account_id' => 'required'
        ],
        [
            'reference_no.required' => __('index.reference_no_required'),
            'only_date.required' => __('index.date_required'),
            'customer_id.required' => __('index.customer_required'),
            'amount.required' => __('index.amount_required'),
            'account_id.required' => __('index.account_required')
        ]
    );

        $obj = new \App\CustomerDueReceive;
        $obj->reference_no = escape_output($request->reference_no);
        $obj->only_date = escape_output($request->only_date);
        $obj->customer_id = escape_output($request->customer_id);
        $obj->amount = escape_output($request->amount);
        $obj->account_id = escape_output($request->account_id);
        $obj->note = escape_output($request->note);
        $obj->user_id = auth()->user()->id;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();

        return redirect('customer-payment')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $customer_payment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer_payment = CustomerDueReceive::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_customer_due_receive');
        $obj = $customer_payment;

        $company_id = auth()->user()->company_id;

        $customers = Customer::where('del_status',"Live")->get();
        $accountList = Account::where('del_status',"Live")->get();

        return view('pages.customer_payment.edit',compact('title','obj', 'customers', 'accountList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $customer_payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerDueReceive $customer_payment)
    {
        request()->validate([
            'reference_no' => 'required',
            'only_date' => 'required|date',
            'customer_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'account_id' => 'required'
        ],
        [
            'reference_no.required' => __('index.reference_no_required'),
            'only_date.required' => __('index.date_required'),
            'customer_id.required' => __('index.customer_required'),
            'amount.required' => __('index.amount_required'),
            'account_id.required' => __('index.account_required')
        ]
    );

        $customer_payment->reference_no = escape_output($request->reference_no);
        $customer_payment->only_date = escape_output($request->only_date);
        $customer_payment->customer_id = escape_output($request->customer_id);
        $customer_payment->amount = escape_output($request->amount);
        $customer_payment->account_id = escape_output($request->account_id);
        $customer_payment->note = escape_output($request->note);
        $customer_payment->save();

        return redirect('customer-payment')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $customer_payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerDueReceive $customer_payment)
    {
        $customer_payment->del_status = "Deleted";
        $customer_payment->save();
        return redirect('customer-payment')->with(deleteMessage());
    }
	
	public function download($id)
    {
        $id = encrypt_decrypt($id, 'decrypt');
        $title = __('index.customer_payment_invoice');

        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        $customers = Customer::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $finishProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $fifoProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->where('stock_method', "fifo")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        $obj = CustomerDueReceive::findOrFail($id);
        
        return view('pages.customer_payment.invoice', compact('title', 'obj', 'customers', 'accounts', 'company'));
    }

    public function print($id)
    {
        $title = __('index.customer_payment_invoice');
        $obj = CustomerDueReceive::findOrFail($id);

        $customers = Customer::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        return view('pages.customer_payment.print_invoice', compact('title', 'obj', 'customers', 'accounts', 'company'));
    }
}
