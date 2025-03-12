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
  # This is PayrollController
  ##############################################################################
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salary;
use App\Account;
use App\User;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Salary::where('del_status',"Live")->orderBy('id','DESC')->get();
        
        $title =  __('index.salary_payroll');

        return view('pages.payroll.index',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_salary_payroll');

        $company_id = auth()->user()->company_id;

        $accountList = Account::where('del_status',"Live")->get();

        $obj = Salary::count();

        $ref_no = str_pad($obj + 1, 6, '0', STR_PAD_LEFT);

        return view('pages.payroll.create',compact('ref_no', 'title', 'accountList'));
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
            'month' => 'required',
            'year' => 'required'
        ]);
        
        $is_exist = Salary::where('month', $request->month)
                        ->where('year', $request->year)
                        ->count();
        if($is_exist == 0) {
            $obj = new \App\Salary;
            $obj->month = escape_output($request->month);
            $obj->year = escape_output($request->year);
            $obj->date = date('Y-m-d');
            $obj->user_id = auth()->user()->id;
            $obj->company_id = auth()->user()->company_id;
            $obj->save();
            return redirect('payroll/'. encrypt_decrypt($obj->id, 'encrypt') .'/edit')->with(saveMessage());
        } else {
            return redirect()->route('payroll.create')->with(dangerMessage(__('index.select_month_year_generated')));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deposit  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payroll = Salary::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_payroll');
        $obj = $payroll;

        $company_id = auth()->user()->company_id;

        if(!empty($obj->details_info)){
            $userList = json_decode($obj->details_info);
        } else {
            $userList = User::where('email', '!=', "admin@doorsoft.co")->where('del_status',"Live")->get();
        }
        
        $accountList = Account::where('del_status',"Live")->get();

        return view('pages.payroll.edit',compact('title', 'obj', 'userList', 'accountList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salary  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $payroll)
    {
        request()->validate([
            'user_id' => 'required',
            'salary' => 'required',
            'total' => 'required',
            'account_id' => 'required'
        ],
        [
            'account_id.required' => __('index.account_required')
        ]);

        $user_id = $request->user_id;
        $salary = $request->salary;
        $additional = $request->additional;
        $subtraction = $request->subtraction;
        $total = $request->total;
        $notes = $request->notes;

        $final_arr = array();
        $total_amount = 0;
        for ($i=0;$i<sizeof($user_id);$i++){
            $txt = "product_id".$user_id[$i];
            $tmp_v = $request->$txt;
            
            $tmp = array();
            $tmp['p_status'] = isset($tmp_v) && $tmp_v?1:'';
            $tmp['user_id'] = $user_id[$i];
            $tmp['name'] = getUserName($user_id[$i]);
            $tmp['salary'] = $salary[$i];
            $tmp['additional'] = $additional[$i];
            $tmp['subtraction'] = $subtraction[$i];
            $tmp['total'] = $total[$i];
            $tmp['notes'] = $notes[$i];
            $total_amount +=$total[$i];
            $final_arr[] = $tmp;
        }

        $payroll->total_amount = $total_amount;
        $payroll->account_id = escape_output($request->account_id);
        $payroll->details_info = json_encode($final_arr);
        
        $payroll->save();

        return redirect('payroll')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deposit  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $payroll)
    {
        $payroll->del_status = "Deleted";
        $payroll->save();
        return redirect('payroll')->with(deleteMessage());
    }
}
