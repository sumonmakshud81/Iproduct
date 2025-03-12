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
  # This is ExpenseController
  ##############################################################################
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Expense;
use App\ExpenseCategory;
use App\User;
use App\Account;


class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Expense::where('del_status',"Live")->orderBy('id','DESC')->get();
        $title =  __('index.expense_list');

        return view('pages.expense.index',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_expense');

        $company_id = auth()->user()->company_id;

        $expenseCategories = ExpenseCategory::where('company_id', $company_id)->where('del_status',"Live")->get();
        $employees = User::where('company_id', $company_id)->where('del_status',"Live")->get();
        $accountList = Account::where('del_status',"Live")->get();

        return view('pages.expense.create',compact('title', 'expenseCategories', 'employees', 'accountList'));
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
            'amount' => 'required|numeric',
            'category_id' => 'required|numeric',
            'account_id' => 'required',
            'employee_id' => 'required'
        ],
        [
            'date.required' => __('index.date_required'),
            'amount.required' => __('index.amount_required'),
            'category_id.required' => __('index.category_required'),
            'account_id.required' => __('index.account_required'),
            'employee_id.required' => __('index.responsible_required')
        ]);

        $obj = new \App\Expense;
        $obj->date = escape_output($request->date);
        $obj->amount = escape_output($request->amount);
        $obj->category_id = escape_output($request->category_id);
        $obj->employee_id = escape_output($request->employee_id);
        $obj->account_id = escape_output($request->account_id);
        $obj->note = escape_output($request->note);
        $obj->user_id = auth()->user()->id;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();
        return redirect('expense')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_expense');
        $obj = $expense;

        $company_id = auth()->user()->company_id;

        $expenseCategories = ExpenseCategory::where('company_id', $company_id)->where('del_status',"Live")->get();
        $employees = User::where('company_id', $company_id)->where('del_status',"Live")->get();
        $accountList = Account::where('del_status',"Live")->get();

        return view('pages.expense.edit',compact('title','obj', 'expenseCategories', 'employees', 'accountList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        request()->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'category_id' => 'required|numeric',
            'account_id' => 'required',
            'employee_id' => 'required'
        ],[
            'date.required' => __('index.date_required'),
            'amount.required' => __('index.amount_required'),
            'category_id.required' => __('index.category_required'),
            'account_id.required' => __('index.account_required'),
            'employee_id.required' => __('index.responsible_required')
        ]);
        $expense->date = escape_output($request->date);
        $expense->amount = escape_output($request->amount);
        $expense->category_id = escape_output($request->category_id);
        $expense->employee_id = escape_output($request->employee_id);
        $expense->account_id = escape_output($request->account_id);
        $expense->note = escape_output($request->note);
        $expense->save();
        return redirect('expense')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->del_status = "Deleted";
        $expense->save();
        return redirect('expense')->with(deleteMessage());
    }
}
