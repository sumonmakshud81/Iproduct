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
  # This is SupplierController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
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
        $obj = Supplier::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.suppliers');
        return view('pages.supplier.suppliers',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_supplier');
        return view('pages.supplier.addEditSupplier',compact('title'));
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
            'contact_person' => 'max:50',
            'phone' => 'required',
            'email' => 'email:filter',
            'address' => 'max:250',
            'note' => 'max:250'
        ],
        [
            'name.required' => __('index.name_required'),
            'phone.required' => __('index.phone_required'),
            'email.email' => __('index.email_validation'),
        ]);

        $obj = new \App\Supplier;
        $obj->name = escape_output($request->get('name'));
        $obj->contact_person = escape_output($request->get('contact_person'));
        $obj->phone = escape_output($request->get('phone'));
        $obj->email = escape_output($request->get('email'));
        $obj->address = escape_output($request->get('address'));
        $obj->opening_balance = null_check(escape_output($request->get('opening_balance')));
        $obj->opening_balance_type = escape_output($request->get('opening_balance_type'));
        $obj->note = escape_output($request->get('note'));
        $obj->added_by = auth()->user()->id;
        $obj->company_id = null_check(auth()->user()->company_id);
        $obj->credit_limit = null_check(escape_output($request->get('credit_limit')));
        $obj->save();
        return redirect('suppliers')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_supplier');
        $obj = $supplier;
        return view('pages.supplier.addEditSupplier',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        request()->validate([
            'name' => 'required|max:50',
            'contact_person' => 'max:50',
            'phone' => 'required',
            'email' => 'email:filter',
            'address' => 'max:250',
            'note' => 'max:250'
        ],[
            'name.required' => __('index.name_required'),
            'phone.required' => __('index.phone_required'),
            'email.email' => __('index.email_validation'),
        ]);

        $supplier->name = escape_output($request->get('name'));
        $supplier->contact_person = escape_output($request->get('contact_person'));
        $supplier->phone = escape_output($request->get('phone'));
        $supplier->email = escape_output($request->get('email'));
        $supplier->opening_balance = null_check(escape_output($request->get('opening_balance')));
        $supplier->opening_balance_type = escape_output($request->get('opening_balance_type'));
        $supplier->address = escape_output($request->get('address'));
        $supplier->note = escape_output($request->get('note'));
        $supplier->added_by = auth()->user()->id;
        $supplier->credit_limit = null_check(escape_output($request->get('credit_limit')));

        $supplier->save();
        return redirect('suppliers')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->del_status = "Deleted";
        $supplier->save();
        return redirect('suppliers')->with(deleteMessage());
    }
}
