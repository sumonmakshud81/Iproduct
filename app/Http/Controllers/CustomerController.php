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
# This is CustomerController Controller
##############################################################################
 */

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $title = __('index.customer');
        return view('pages.customer.customers', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_customer');
        return view('pages.customer.addEditCustomer', compact('title'));
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
            'name' => 'required|max:50',
            'phone' => 'required',
            'email' => 'email:filter',
            'opening_balance' => 'numeric',
            'credit_limit' => 'numeric',
            'date_of_birth' => 'date',
            'date_of_anniversary' => 'date',
            'address' => 'max:250',
            'note' => 'max:250',
        ],
            [
                'name.required' => __('index.name_required'),
                'phone.required' => __('index.phone_required'),
                'email.email' => __('index.email_validation'),
                'opening_balance.numeric' => __('index.opening_balance_numeric'),
            ]

        );

        $obj = new \App\Customer;
        $obj->name = escape_output($request->get('name'));
        $obj->phone = escape_output($request->get('phone'));
        $obj->email = escape_output($request->get('email'));
        $obj->date_of_birth = string_date_null_check(escape_output($request->get('date_of_birth')));
        $obj->date_of_anniversary = string_date_null_check(escape_output($request->get('date_of_anniversary')));
        $obj->opening_balance = null_check(escape_output($request->get('opening_balance')));
        $obj->opening_balance_type = escape_output($request->get('opening_balance_type'));
        $obj->credit_limit = null_check(escape_output($request->get('credit_limit')));
        $obj->discount = null_check(escape_output($request->get('discount')));
        $obj->customer_type = escape_output($request->get('customer_type'));
        $obj->address = escape_output($request->get('address'));
        $obj->note = escape_output($request->get('note'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        return redirect('customers')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_customer');
        $obj = $customer;
        return view('pages.customer.addEditCustomer', compact('title', 'obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        request()->validate([
            'name' => 'required|max:50',
            'phone' => 'required',
            'email' => 'email:filter',
            'opening_balance' => 'numeric',
            'credit_limit' => 'numeric',
            'date_of_birth' => 'date',
            'date_of_anniversary' => 'date',
            'address' => 'max:250',
            'note' => 'max:250',
        ],
            [
                'name.required' => __('index.name_required'),
                'phone.required' => __('index.phone_required'),
                'email.email' => __('index.email_validation'),
                'opening_balance.numeric' => __('index.opening_balance_numeric'),
            ]
        );

        $customer->name = escape_output($request->get('name'));
        $customer->phone = escape_output($request->get('phone'));
        $customer->email = escape_output($request->get('email'));
        $customer->date_of_birth = string_date_null_check(escape_output($request->get('date_of_birth')));
        $customer->date_of_anniversary = string_date_null_check(escape_output($request->get('date_of_anniversary')));
        $customer->opening_balance = null_check(escape_output($request->get('opening_balance')));
        $customer->opening_balance_type = escape_output($request->get('opening_balance_type'));
        $customer->credit_limit = null_check(escape_output($request->get('credit_limit')));
        $customer->discount = null_check(escape_output($request->get('discount')));
        $customer->customer_type = escape_output($request->get('customer_type'));
        $customer->address = escape_output($request->get('address'));
        $customer->note = escape_output($request->get('note'));
        $customer->added_by = auth()->user()->id;
        $customer->save();
        return redirect('customers')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->del_status = "Deleted";
        $customer->save();
        return redirect('customers')->with(deleteMessage());
    }
}
