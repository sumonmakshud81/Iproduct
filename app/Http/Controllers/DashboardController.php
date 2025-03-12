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
  # This is DashboardController Controller
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\Customer;
use App\CustomerDueReceive;
use App\CustomerOrder;
use App\Expense;
use App\FinishedProduct;
use App\Http\Controllers\Controller;
use App\Manufacture;
use App\Mnonitem;
use App\NonIItem;
use App\Pnonitem;
use App\RawMaterial;
use App\RawMaterialPurchase;
use App\Salary;
use App\Sales;
use App\Stock;
use App\Supplier;
use App\Supplier_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function index()
    {
        $total = [
            'product' => FinishedProduct::count(),
            'rm' => RawMaterial::count(),
            'supplier' => Supplier::count(),
            'customer' => Customer::count(),
        ];

        $running_production = Manufacture::where('manufacture_status', 'inProgress')->where('del_status', 'Live')->get();
        $running_order = CustomerOrder::where('delivery_date', '>=', today())->where('del_status', 'Live')->get();
        $lowRawMaterialStocks = new Stock();
        $lowRawMaterialStocks = $lowRawMaterialStocks->getLowRMStock();
        $supplierPayments = Supplier_payment::where('del_status',"Live")->orderBy('date','DESC')->get();
        $customerPayments = CustomerDueReceive::where('del_status',"Live")->orderBy('date','DESC')->get();
        
        // Close to Finish Products
        $closeToFinishProducts = Manufacture::with('product')->where('manufacture_status', 'done')->where('del_status', 'Live')->where('expiry_days', '!=', 0)->orderBy('complete_date', 'asc')->get()->map(function($item){
            $item->expiry_date = expireDate($item->complete_date, $item->expiry_days);
            return $item;
        });      
        $expiredProducts = [];
        $closeToExpiryProducts = [];
        foreach($closeToFinishProducts as $item){            
            if($item->expiry_date < today()){
                $expiredProducts[] = $item;
            $item->status = 'expired';
            }
            if($item->expiry_date >= today() && $item->expiry_date < today()->addDays(7)){
                $closeToExpiryProducts[] = $item;
                $item->status = 'close to expiry';
            }
        }
        $mergedProducts = array_merge($expiredProducts, $closeToExpiryProducts);
        $percentage_product = 10;
        $percentage_rm = 10;
        $percentage_supplier = 10;
        $percentage_customer = 10;       

        return view('pages.dashboard', compact('total', 'running_production', 'running_order', 'lowRawMaterialStocks', 'supplierPayments', 'customerPayments', 'closeToFinishProducts', 'percentage_product', 'percentage_rm', 'percentage_supplier', 'percentage_customer', 'mergedProducts'));
    }
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function profile()
    {
        return view('pages.profile');
    }

    /**
     * Get Balance By Account
     */

    public function getBalance()
    {
        $accountsData = Account::orderBy('id','DESC')->where('del_status',"Live")->get();
        $accounts = [];
        $balance = [];
        foreach ($accountsData as $account) {
            $accounts[] = $account->name;
            $balance[] = $account->balanceNumberFormat();
        }

        return response()->json([
            'accounts' => $accounts,
            'balance' => $balance
        ]);
    }

    /**
     * Money Flow Comparison Chart Data
     */

    public function moneyFlow()
    {
        $getMonth = request('month') ?? 6;
        $currentMonth = date('m');
        $previous_six_month = getPreviousMonthName($getMonth);
        $purchase = [];
        $supplierDuePayment = [];
        $nonInventoryCost = [];
        $sale = [];
        $customerDueReceive = [];
        $expense = [];
        $payroll = [];
        $months = [];

        for($i = 0, $iMax = count($previous_six_month); $i < $iMax; $i++) {
            $value = explode("-", $previous_six_month[$i]);

            $month = $value[0];
            $year = $value[1];
            $purchase[] = RawMaterialPurchase::whereMonth('date', $month)->whereYear('date', $year)->where('del_status', 'Live')->sum('paid');
            $supplierDuePayment[] = Supplier_payment::whereMonth('date', $month)->whereYear('date', $year)->where('del_status', 'Live')->sum('amount');
            $nonInventoryCost[] = Mnonitem::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('del_status', 'Live')->sum('nin_cost');
            $sale[] = Sales::whereMonth('sale_date', $month)->whereYear('sale_date', $year)->where('del_status', 'Live')->sum('paid');
            $customerDueReceive[] = CustomerDueReceive::whereMonth('only_date', $month)->whereYear('only_date', $year)->where('del_status', 'Live')->sum('amount');
            $expense[] = Expense::whereMonth('date', $month)->whereYear('date', $year)->where('del_status', 'Live')->sum('amount');
            $month_name = date("F", mktime(0, 0, 0, $month, 10));
            $payroll[] = Salary::where('month', $month_name)->where('year', $year)->where('del_status', 'Live')->sum('total_amount');

            $months[] = date('F', mktime(0, 0, 0, $month, 10));
        }

        return response()->json([
            'purchase' => $purchase,
            'supplierDuePayment' => $supplierDuePayment,
            'nonInventoryCost' => $nonInventoryCost,
            'sale' => $sale,
            'customerDueReceive' => $customerDueReceive,
            'expense' => $expense,
            'payroll' => $payroll,
            'months' => $months
        ]);
    }
}
