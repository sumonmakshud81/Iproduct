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
  # This is ExpenseCategoryController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = ExpenseCategory::where('del_status',"Live")->orderBy('id','DESC')->get();
        $title =  __('index.expense_category_list');

        return view('pages.expense_category.index',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_expense_category');
        return view('pages.expense_category.create',compact('title'));
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
            'name' => 'required',
        ]);

        $obj = new \App\ExpenseCategory;
        $obj->name = escape_output($request->name);
        $obj->description = escape_output($request->description);
        $obj->user_id = auth()->user()->id;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();
        return redirect('expense-category')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenseCategory = ExpenseCategory::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_expense_category');
        $obj = $expenseCategory;

        return view('pages.expense_category.edit',compact('title','obj'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
         request()->validate([
            'name' => 'required',
        ]);

        $expenseCategory->name = escape_output($request->name);
        $expenseCategory->description = escape_output($request->description);
        $expenseCategory->save();
        return redirect('expense-category')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->del_status = "Deleted";
        $expenseCategory->save();
        return redirect('expense-category')->with(deleteMessage());
    }
}
