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
  # This is AccountController Controller
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
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
        $obj = Account::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $title = __('index.accounts');
        return view('pages.account.accounts', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_account');
        return view('pages.account.addEditAccount', compact('title'));
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
            'name' => 'required|max:100',
            'opening_balance' => 'required|numeric',
            'description' => 'max:250',
        ],
            [
                'name.required' => __('index.name_required'),
                'opening_balance.required' => __('index.opening_balance_required'),
                'opening_balance.numeric' => __('index.opening_balance_numeric'),
            ]
        );
        $obj = new \App\Account;
        $obj->name = escape_output($request->get('name'));
        $obj->opening_balance = $request->get('opening_balance');
        $obj->description = escape_output($request->get('description'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        return redirect('accounts')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_account');
        $obj = $account;
        return view('pages.account.addEditAccount', compact('title', 'obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, account $account)
    {

        request()->validate([
            'name' => 'required|max:100',
            'opening_balance' => 'required|numeric',
            'description' => 'max:250',
        ],
            [
                'name.required' => __('index.name_required'),
                'opening_balance.required' => __('index.opening_balance_required'),
                'opening_balance.numeric' => __('index.opening_balance_numeric'),
                'description.required' => __('index.description_required'),
            ]

        );

        $account->name = escape_output($request->get('name'));
        $account->opening_balance = $request->get('opening_balance');
        $account->description = escape_output($request->get('description'));
        $account->added_by = auth()->user()->id;
        $account->save();
        return redirect('accounts')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(account $account)
    {
        $account->del_status = "Deleted";
        $account->save();
        return redirect('accounts')->with(deleteMessage());
    }
}
