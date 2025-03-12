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
# This is ReportController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\Attendance;
use App\Customer;
use App\CustomerDueReceive;
use App\Expense;
use App\FinishedProduct;
use App\FPrmitem;
use App\Manufacture;
use App\Pnonitem;
use App\ProductWaste;
use App\RawMaterial;
use App\RawMaterialCategory;
use App\RawMaterialPurchase;
use App\RMWaste;
use App\Salary;
use App\Sales;
use App\Stock;
use App\Supplier;
use App\Supplier_payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
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
    public function rmPurchaseReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);

        $rmPurchase = RawMaterialPurchase::orderBy('id', 'DESC')->where('status', 'Final')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $rmPurchase->whereDate('created_at', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $rmPurchase->whereDate('created_at', '<=', $request->endDate);
        }

        $obj = $rmPurchase->get();

        $title = __('index.rm_purchase_report');

        return view('pages.report.rm_purchase_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rmItemPurchaseReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);

        $rmPurchase = RawMaterialPurchase::orderBy('id', 'DESC')->where('status', 'Final')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $rmPurchase->whereDate('created_at', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $rmPurchase->whereDate('created_at', '<=', $request->endDate);
        }

        $obj = $rmPurchase->get();

        $title = __('index.rm_item_purchase_report');

        return view('pages.report.rm_item_purchase_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rmStockReport(Request $request)
    {
        unset($request->_token);

        $category_id = escape_output($request->get('category_id'));
        $product_id = escape_output($request->get('finish_p_id'));
        $obj1 = new Stock();
        $obj = $obj1->getRMStock($category_id);
        if ($product_id != '') {
            $rm = new FPrmitem();
            $rmObj = $rm->getFinishProductRM($product_id);
            $rm_id = $rmObj[0]->rmaterials_id;
            $obj = array_filter($obj, function ($v) use ($rm_id) {
                return $v->id == $rm_id;
            });
        }
        $rmCategory = RawMaterialCategory::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $finishProduct = FinishedProduct::orderBy('id', 'DESC')->where('del_status', "Live")->get();

        $title = __('index.rm_stock_report');

        return view('pages.report.rm_stock_report', compact('title', 'obj', 'rmCategory', 'finishProduct', 'category_id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplierDueReport(Request $request)
    {
        $type = '';
        if (isset($request->type)) {
            $type = $request->type;
        }

        $supplierDueReport = Supplier::where('del_status', 'Live')->get();

        $title = __('index.supplier_due_report');

        return view('pages.report.supplier_due_report', compact('title', 'type', 'supplierDueReport'));
    }

    /**
     * Supplier Balance Report
     */
    public function supplierBalanceReport(Request $request)
    {
        $type = '';
        if (isset($request->type)) {
            $type = $request->type;
        }

        $supplierDueReport = Supplier::where('del_status', 'Live')->get();

        $title = __('index.supplier_balance_report');

        return view('pages.report.supplier_balance_report', compact('title', 'type', 'supplierDueReport'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function supplierLedger(Request $request)
    {
        $startDate = '';
        $endDate = '';
        $selectString = '';
        $supplier_id = '';
        $type = '';
        $supplierLedger = [];

        if (isset($request->supplier_id)) {
            unset($request->_token);
            $supplier_id = $request->supplier_id;

            $startDate = $request->startDate ?? null;
            $endDate = $request->endDate ?? null;
            $type = $request->type ?? null;

            $s_type = getSupplierOpeningBalanceType($supplier_id);

            if ($s_type == 'Debit') {
                $selectString = "0 as credit, opening_balance as debit";
            } else {
                $selectString = "opening_balance as credit, 0 as debit";
            }

            $purchaseDateRange = '';
            $supplierPaymentDateRange = '';
            $purchaseReturnDateRange = '';

            if (!empty($startDate) && !empty($endDate)) {
                $purchaseDateRange = " AND date BETWEEN '$startDate' AND '$endDate'";
                $supplierPaymentDateRange = " AND date BETWEEN '$startDate' AND '$endDate'";
                $purchaseReturnDateRange = " AND r.date BETWEEN '$startDate' AND '$endDate'";
            }

            $supplierLedger = DB::select("
                SELECT s.* FROM (
                    (SELECT $selectString, '' as date, 'Opening Balance' as type, '' as reference_no FROM tbl_suppliers WHERE id = ?)
                    UNION
                    (SELECT paid as credit, 0 as debit, date, 'Purchase Payment' as type, reference_no FROM tbl_purchase WHERE supplier = ? AND paid != 0 AND del_status = 'Live' $purchaseDateRange)
                    UNION
                    (SELECT amount as credit, 0 as debit, date, 'Supplier Payment' as type, '' as reference_no FROM tbl_supplier_payments WHERE supplier = ? AND del_status = 'Live' $supplierPaymentDateRange)
                    UNION
                    (SELECT 0 as credit, total_return_amount as debit, r.date, 'Purchase Return' as type, r.reference_no FROM tbl_purchase_return r JOIN tbl_purchase p ON r.supplier_id = p.supplier WHERE r.supplier_id = ? AND r.del_status = 'Live' $purchaseReturnDateRange)
                ) as s
                ORDER BY s.date ASC", [$supplier_id, $supplier_id, $supplier_id, $supplier_id]
            );
        } else {
            unset($request->_token);
            $supplierLedger = DB::select("
                SELECT s.* FROM (
                    (SELECT '' as credit, '' as debit, '' as date, 'Opening Balance' as type, '' as reference_no FROM tbl_suppliers WHERE id = ?)
                    UNION
                    (SELECT paid as credit, 0 as debit, date, 'Purchase Payment' as type, reference_no FROM tbl_purchase WHERE supplier = ? AND paid != 0 AND del_status = 'Live')
                    UNION
                    (SELECT amount as credit, 0 as debit, date, 'Supplier Payment' as type, '' as reference_no FROM tbl_supplier_payments WHERE supplier = ? AND del_status = 'Live')
                    UNION
                    (SELECT 0 as credit, total_return_amount as debit, r.date, 'Purchase Return' as type, r.reference_no FROM tbl_purchase_return r JOIN tbl_purchase p ON r.supplier_id = p.supplier WHERE r.supplier_id = ? AND r.del_status = 'Live')
                ) as s
                ORDER BY s.date ASC", [$supplier_id, $supplier_id, $supplier_id, $supplier_id]
            );
        }

        $title = __('index.supplier_ledger');

        $suppliers = Supplier::where('del_status', "Live")->get();
        return view('pages.report.supplier_ledger', compact('title', 'startDate', 'endDate', 'type', 'supplier_id', 'supplierLedger', 'suppliers'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productionReport()
    {
        $obj = Manufacture::with(['customer', 'product'])->where('manufacture_status', 'inProgress')->where('del_status', "Live")->get();

        $title = __('index.production_report');

        return view('pages.report.production_report', compact('title', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fpProductionReport()
    {
        $obj = Manufacture::with(['customer', 'product'])->where('manufacture_status', 'done')->orderBy('id', 'desc')->where('del_status', "Live")->get();

        $title = __('index.fp_production_report');

        return view('pages.report.fp_production_report', compact('title', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function balanceSheet()
    {
        $obj = Account::where('del_status', "Live")->get();

        $title = __('index.balance_sheet');

        return view('pages.report.balance_sheet', compact('title', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trialBalance()
    {

        $date = request()->get('date') ?? '';

        $sales_credit = Sales::singleDate($date)->where('del_status', "Live")->sum('grand_total');
        $customer_due_received_credit = CustomerDueReceive::singleDate($date)->where('del_status', "Live")->sum('amount');
        $supplier_due_paid_debit = Supplier_payment::singleDate($date)->where('del_status', "Live")->sum('amount');
        $purchase_debit = RawMaterialPurchase::singleDate($date)->where('del_status', "Live")->sum('paid');
        $production_non_inventory_cost_debit = Pnonitem::singleDate($date)->where('del_status', "Live")->sum('totalamount');
        $expense_debit = Expense::singleDate($date)->where('del_status', "Live")->sum('amount');
        $payroll_debit = Salary::singleDate($date)->where('del_status', "Live")->sum('total_amount');

        $title = __('index.trial_balance');

        return view('pages.report.trial_balance', compact('title', 'sales_credit', 'customer_due_received_credit', 'supplier_due_paid_debit', 'purchase_debit', 'production_non_inventory_cost_debit', 'expense_debit', 'payroll_debit', 'date'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fpSaleReport()
    {
        $obj = Sales::with('customer')->where('del_status', "Live")->get();
        $title = __('index.fp_sale_report');

        return view('pages.report.fp_sale_report', compact('title', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fpItemSaleReport()
    {
        $obj = Sales::with(['customer', 'details'])->where('del_status', "Live")->get();

        $title = __('index.fp_item_sale_report');

        return view('pages.report.fp_item_sale_report', compact('title', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerDueReport(Request $request)
    {
        $type = '';
        if (isset($request->type)) {
            $type = $request->type;
        }
        $customerDueReport = Customer::where('del_status', 'Live')->get();
        $title = __('index.customer_due_report');

        return view('pages.report.customer_due_report', compact('title', 'type', 'customerDueReport'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerLedger(Request $request)
    {
        $startDate = '';
        $endDate = '';
        $selectString = '';
        $customer_id = '';
        $type = '';
        $customerLedger = [];

        if (isset($request->customer_id)) {
            unset($request->_token);
            $customer_id = $request->customer_id;
            if (isset($request->startDate)) {
                $startDate = $request->startDate;
            }
            if (isset($request->endDate)) {
                $endDate = $request->endDate;
            }

            if (isset($request->type)) {
                $type = $request->type;
            }

            $customer = Customer::find($customer_id);
            if ($startDate != '' && $endDate != '') {
                $customer = Customer::where('id', $customer_id)->whereBetween('created_at', [$startDate, $endDate])->first();
            }
            if ($customer) {
                $customerLedger[0]['date'] = $customer->created_at;
                $customerLedger[0]['type'] = 'Opening Balance';
                $customerLedger[0]['transaction_no'] = '';
                if ($customer->opening_balance_type == 'Debit') {
                    $customerLedger[0]['debit'] = $customer->opening_balance;
                    $customerLedger[0]['credit'] = 0;
                } else {
                    $customerLedger[0]['debit'] = 0;
                    $customerLedger[0]['credit'] = $customer->opening_balance;
                }
            }

            $sales = Sales::where('customer_id', $customer_id)->where('del_status', 'Live')->get();
            if ($startDate != '' && $endDate != '') {
                $sales = Sales::where('customer_id', $customer_id)->where('del_status', 'Live')->whereBetween('sale_date', [$startDate, $endDate])->get();
            }

            $customerDueReceive = CustomerDueReceive::where('customer_id', $customer_id)->where('del_status', 'Live')->get();
            if ($startDate != '' && $endDate != '') {
                $customerDueReceive = CustomerDueReceive::where('customer_id', $customer_id)->where('del_status', 'Live')->whereBetween('only_date', [$startDate, $endDate])->get();
            }

            $i = 1;
            foreach ($sales as $sale) {
                $customerLedger[$i]['date'] = $sale->sale_date;
                $customerLedger[$i]['type'] = 'Sales Due';
                $customerLedger[$i]['transaction_no'] = $sale->reference_no;
                $customerLedger[$i]['debit'] = $sale->due;
                $customerLedger[$i]['credit'] = 0;
                $i++;
            }

            foreach ($customerDueReceive as $dueReceive) {
                $customerLedger[$i]['date'] = $dueReceive->only_date;
                $customerLedger[$i]['type'] = 'Customer Due Receive';
                $customerLedger[$i]['transaction_no'] = $dueReceive->reference_no;
                $customerLedger[$i]['debit'] = 0;
                $customerLedger[$i]['credit'] = $dueReceive->amount;
                $i++;
            }
        }
        // dd($customerLedger);
        $title = __('index.customer_ledger');
        $customers = Customer::orderBy('id', 'DESC')->where('del_status', "Live")->pluck('name', 'id');

        return view('pages.report.customer_ledger', compact('title', 'startDate', 'endDate', 'type', 'customer_id', 'customerLedger', 'customers'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profitLossReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);

        if (isset($request->startDate)) {
            $startDate = $request->startDate;
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
        }

        $totalSales = Sales::where('status', 'Final')->dateFilter($startDate, $endDate)->where('del_status', "Live")->sum('grand_total');
        $costOfGoodsSold = Sales::where('status', 'Final')->dateFilter($startDate, $endDate)->where('del_status', "Live")->get();
        $costOfTransferred = Sales::where('status', 'Final')->dateFilter($startDate, $endDate)->where('del_status', "Live")->get();
        $totalCostOfGoodsSold = 0;
        $totalCostOfTransferred = 0;
        foreach ($costOfGoodsSold as $cost) {
            $totalCostOfGoodsSold += $cost->cost_of_goods;
        }

        foreach ($costOfTransferred as $cost) {
            $totalCostOfTransferred += $cost->cost_of_transferred;
        }

        $grossProfit = $totalSales - $totalCostOfGoodsSold - $totalCostOfTransferred;
        $totalTax = Sales::where('status', 'Final')->dateFilter($startDate, $endDate)->where('del_status', "Live")->get();
        $totalTaxAmount = 0;
        foreach ($totalTax as $tax) {
            $totalTaxAmount += $tax->total_tax;
        }
        $total_waste = ProductWaste::where('del_status', "Live")->sum('total_loss');
        $total_expense = Expense::where('del_status', "Live")->sum('amount');
        $netProfit = $grossProfit - $totalTaxAmount - $total_waste - $total_expense;
        $title = __('index.profit_loss_report');

        return view('pages.report.profit_loss_report', compact('title', 'startDate', 'endDate', 'totalSales', 'totalCostOfGoodsSold', 'totalCostOfTransferred', 'grossProfit', 'totalTaxAmount', 'total_waste', 'total_expense', 'netProfit'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productProfitReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);

        if (isset($request->startDate)) {
            $startDate = $request->startDate;
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
        }

        $obj = FinishedProduct::where('del_status', "Live")->get();

        $title = __('index.product_profit_report');

        return view('pages.report.product_profit_report', compact('title', 'startDate', 'endDate', 'obj'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendanceReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);

        $attendance = Attendance::orderBy('id', 'DESC')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $attendance->where('date', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $attendance->where('date', '<=', $request->endDate);
        }

        $obj = $attendance->get();

        $company_id = auth()->user()->company_id;
        $employees = User::where('company_id', $company_id)->where('del_status', "Live")->get();

        $title = __('index.attendance_report');

        return view('pages.report.attendance_report', compact('title', 'obj', 'startDate', 'endDate', 'employees'));
    }

    /**
     * Expense Report
     */

    public function expenseReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);
        $expense = Expense::orderBy('id', 'DESC')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $expense->where('date', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $expense->where('date', '<=', $request->endDate);
        }

        $obj = $expense->get();
        $title = __('index.expense_report');
        return view('pages.report.expense_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * Salary Report
     */

    public function salaryReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);
        $salary = Salary::orderBy('id', 'DESC')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $salary->where('date', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $salary->where('date', '<=', $request->endDate);
        }

        $obj = $salary->get();
        $title = __('index.salary_report');
        return view('pages.report.salary_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * RM Waste Report
     */

    public function rmwasteReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);
        $rmwaste = RMWaste::orderBy('id', 'DESC')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $rmwaste->where('date', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $rmwaste->where('date', '<=', $request->endDate);
        }

        $obj = $rmwaste->get();
        $title = __('index.rmwaste_report');
        return view('pages.report.rmwaste_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * Product Waste Report
     */

    public function fpwasteReport(Request $request)
    {
        $startDate = '';
        $endDate = '';

        unset($request->_token);
        $pw = ProductWaste::orderBy('id', 'DESC')->where('del_status', "Live");
        if (isset($request->startDate)) {
            $startDate = $request->startDate;
            $pw->where('date', '>=', $request->startDate);
        }
        if (isset($request->endDate)) {
            $endDate = $request->endDate;
            $pw->where('date', '<=', $request->endDate);
        }

        $obj = $pw->get();
        $title = __('index.product_waste_report');
        return view('pages.report.productwaste_report', compact('title', 'obj', 'startDate', 'endDate'));
    }

    /**
     * ABC Analysis Report
     */

    public function abcReport()
    {
        $materials = RawMaterial::with(['purchase'])->where('del_status', 'Live')->get();
        $materials->map(function ($material) {
            $material->total_value = $material->purchase->sum(function ($purchase) {
                return $purchase->quantity_amount * $purchase->unit_price;
            });
            return $material;
        });
        $sortedMaterials = $materials->sortByDesc('total_value');
        $totalValue = $sortedMaterials->sum('total_value');
        $cumulativeValue = 0;
        foreach ($sortedMaterials as $material) {
            $cumulativeValue += $material->total_value;
            $material->cumulative_value = $cumulativeValue;
            $material->percentage = ($cumulativeValue / $totalValue) * 100;
        }

        $aMaterials = $sortedMaterials->filter(function ($material) {
            return $material->percentage <= 70;
        });

        $bMaterials = $sortedMaterials->filter(function ($material) {
            return $material->percentage > 70 && $material->percentage <= 90;
        });

        $cMaterials = $sortedMaterials->filter(function ($material) {
            return $material->percentage > 90;
        });

        $obj = [
            'a' => $aMaterials,
            'b' => $bMaterials,
            'c' => $cMaterials,
        ];

        $title = __('index.abc_analysis_report');
        return view('pages.report.abc_report', compact('title', 'obj'));
    }
}
