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
  # This is DepositController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Deposit::where('del_status', "Live")->orderBy('id', 'DESC')->get();
        $title = __('index.deposit_list');

        return view('pages.deposit.index', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_deposit');

        $company_id = auth()->user()->company_id;

        $accountList = Account::where('del_status', "Live")->get();

        $obj = Deposit::count();

        $ref_no = str_pad($obj + 1, 6, '0', STR_PAD_LEFT);

        return view('pages.deposit.create', compact('ref_no', 'title', 'accountList'));
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
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'type' => 'required',
            'account_id' => 'required',
        ],
            [
                'reference_no.required' => __('index.reference_no_required'),
                'date.required' => __('index.date_required'),
                'date.date' => __('index.date_date'),
                'amount.required' => __('index.amount_required'),
                'amount.numeric' => __('index.amount_numeric'),
                'type.required' => 'The Deposit Or Withdraw field is required',
                'account_id.required' => __('index.account_required'),
            ]
        );

        $obj = new \App\Deposit;
        $obj->reference_no = escape_output($request->reference_no);
        $obj->date = escape_output($request->date);
        $obj->amount = escape_output($request->amount);
        $obj->type = escape_output($request->type);
        $obj->account_id = escape_output($request->account_id);
        $obj->note = escape_output($request->note);
        $obj->user_id = auth()->user()->id;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();
        return redirect('deposit')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deposit = Deposit::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_deposit');
        $obj = $deposit;

        $company_id = auth()->user()->company_id;

        $accountList = Account::where('del_status', "Live")->get();

        return view('pages.deposit.edit', compact('title', 'obj', 'accountList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposit $deposit)
    {
        request()->validate([
            'reference_no' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'type' => 'required',
            'account_id' => 'required',
        ],
            [
                'reference_no.required' => __('index.reference_no_required'),
                'date.required' => __('index.date_required'),
                'date.date' => __('index.date_date'),
                'amount.required' => __('index.amount_required'),
                'amount.numeric' => __('index.amount_numeric'),
                'type.required' => __('index.type_required'),
                'account_id.required' => __('index.account_required'),
            ]
        );

        $deposit->reference_no = escape_output($request->reference_no);
        $deposit->date = escape_output($request->date);
        $deposit->amount = escape_output($request->amount);
        $deposit->type = escape_output($request->type);
        $deposit->account_id = escape_output($request->account_id);
        $deposit->note = escape_output($request->note);
        $deposit->save();

        return redirect('deposit')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deposit $deposit)
    {
        $deposit->del_status = "Deleted";
        $deposit->save();
        return redirect('deposit')->with(deleteMessage());
    }

}
