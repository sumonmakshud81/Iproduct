<?php
/*
##############################################################################
# iProduction - Production and Manufacture Management
##############################################################################
# AUTHOR:        Door Soft
##############################################################################
# EMAIL:        info@doorsoft.co
##############################################################################
# COPYRIGHT:        RESERVED BY Door Soft
##############################################################################
# WEBSITE:        https://www.doorsoft.co
##############################################################################
# This is Helper Functions
##############################################################################
 */
use App\Account;
use App\AdminSettings;
use App\Customer;
use App\CustomerDueReceive;
use App\Deposit;
use App\Expense;
use App\Manufacture;
use App\Menu;
use App\MenuActivity;
use App\Mnonitem;
use App\Mrmitem;
use App\Pnonitem;
use App\RawMaterial;
use App\RawMaterialPurchase;
use App\RMPurchase_model;
use App\Role;
use App\RolePermission;
use App\Salary;
use App\SaleDetail;
use App\Sales;
use App\StockAdjustLog;
use App\Supplier_payment;
use App\TaxItems;
use App\User;
use App\WhiteLabelSettings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

/**
 * Base Url
 */
function getBaseURL()
{
    return URL::to('/') . '/';
}

/**
 * Get White Label Info
 */
function getWhiteLabelInfo()
{
    return WhiteLabelSettings::first();
}

/**
 * Notification Date Format
 */
function notificationDateFormat($date)
{
    return $date->diffForHumans();
}

/**
 * Get User Name
 */

function getUserName($id)
{
    $row = \App\User::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Total Purchase Amount
 */
function getTotalPurchaseAmount($id)
{
    $purchaseAmount = \App\RawMaterialPurchase::where('id', $id)->sum('grand_total');
    return $purchaseAmount;
}
/**
 * Get Supplier Credit Limit
 */
function getSupplierCreditLimit($id)
{
    $creditLimit = \App\Supplier::where('id', $id)->sum('credit_limit');
    return $creditLimit;
}

/**
 * Get Customer Credit Limit
 */
function getCustomerCreditLimit($id)
{
    $creditLimit = \App\Customer::where('id', $id)->sum('credit_limit');
    return $creditLimit;
}

/**
 * Get Supplier Due
 */
function getSupplierDue($id)
{
    $openingBalance = \App\Supplier::where('id', $id)->first();
    $supplierDue = \App\RawMaterialPurchase::where('supplier', $id)->where('status', 'Final')->where('del_status', 'Live')->sum('due');
    $supplierPayment = \App\Supplier_payment::where('supplier', $id)->where('del_status', 'Live')->sum('amount');

    if (isset($openingBalance->opening_balance_type) && $openingBalance->opening_balance_type == "Credit") {
        return $supplierDue - $supplierPayment + $openingBalance->opening_balance;
    } else {
        return $supplierDue - $supplierPayment - isset($openingBalance->opening_balance) ? $openingBalance->opening_balance : 0;
    }
}

/**
 * Get Customer Due
 */
function getCustomerDue($id)
{
    $customer = Customer::where('id', $id)->first();
    $customerDue = Sales::where('customer_id', $id)->where('status', 'Final')->where('del_status', 'Live')->sum('due');
    $customerPayment = CustomerDueReceive::where('customer_id', $id)->where('del_status', 'Live')->sum('amount');

    if (isset($customer->opening_balance_type) && $customer->opening_balance_type == "Credit") {
        return ($customerDue - $customerPayment) - $customer->opening_balance;
    } else {
        return ($customerDue - $customerPayment) + isset($customer->opening_balance) ? $customer->opening_balance : 0;
    }
}

/**
 * Get Category By ID
 */
function getCategoryById($id)
{
    $row = \App\RawMaterialCategory::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}

/**
 * Get Finish Product Category
 */

function getFPCategory($id)
{
    $row = \App\FPCategory::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}

/**
 * Get Total Waste
 */
function getTotalWaste($id)
{
    $row = \App\RMWasteItem_model::where('waste_id', $id)->where('del_status', "Live")->count();
    return isset($row) && $row ? $row : '0';
}

/**
 * Get Total Item
 */
function getTotalItem($id)
{
    $row = \App\FPrmitem::where('finish_product_id', $id)->where('del_status', "Live")->count();
    $row1 = \App\FPnonitem::where('finish_product_id', $id)->where('del_status', "Live")->count();
    $total = (isset($row) && $row ? $row : 0) + (isset($row1) && $row1 ? $row1 : 0);
    return isset($total) && $total ? $total : '0';
}

/**
 * Get Raw Material Name
 */
function getRMName($id)
{
    $row = \App\RawMaterial::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name . "(" . $row->code . ")" : '-';
}

/**
 * Get Finished Product
 */
function getFinishProduct($id)
{
    $row = \App\FinishedProduct::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name . "(" . $row->code . ")" : '-';
}

/**
 * Get Non Inventory Item
 */
function getNonInventroyItem($id)
{
    $row = \App\NonIItem::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Production Stage
 */
function getProductionStage($id)
{
    $row = \App\ProductionStage::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Production stages
 */
function getProductionStages($id)
{
    $row = \App\ProductionStage::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Purchase Unit By Raw Materials ID
 */
function getPurchaseUnitByRMID($id)
{
    $row = \App\RawMaterial::where('id', $id)->first();
    $rm_p_id = $row->unit;
    return getPurchaseSaleUnitById($rm_p_id);
}

/**
 * Get Manufacture Unit By Raw Materials ID
 */
function getManufactureUnitByRMID($id)
{
    $row = \App\RawMaterial::where('id', $id)->first();
    $rm_p_id = isset($row->consumption_check) && $row->consumption_check == 1 ? $row->consumption_unit : $row->unit;
    return getPurchaseSaleUnitById($rm_p_id);
}

/**
 * Get Purchase Sale Unit By ID
 */
function getPurchaseSaleUnitById($id)
{
    $row = \App\Unit::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Pcs';
}
/**
 * Get Product Name
 */
function getProductNameById($id)
{
    $row = \App\FinishedProduct::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Customer Name
 */
function getCustomerNameById($id)
{
    $row = \App\Customer::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'Unknown';
}
/**
 * Get Finished Product Raw Materials
 */
function getFPRMById($id)
{
    $row = \App\FPrmitem::where('id', $id)->first();
    return isset($row->rmaterials_id) && $row->rmaterials_id;
}
/**
 * Get Unit By Raw Materials ID
 */
function getUnitByRMID($id)
{
    $row = \App\RawMaterial::where('id', $id)->first();
    $rm_id = isset($row->unit) && $row->unit ? $row->unit : '0';
    return getRMUnitById($rm_id);
}
/**
 * Get Raw Material Unit By Id
 */
function getRMUnitById($id)
{
    $row = \App\Unit::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : 'N/A';
}
/**
 * Get Account Name
 */
function getAccountName($id)
{
    $row = \App\Account::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : '';
}
/**
 * Get Current Stock
 */
function getCurrentStock($id)
{
    $where = 'AND i.id = ' . $id;
    $result = DB::select("SELECT i.*, i.rate_per_unit as last_purchase_price,tbl_total_purchase.total_purchase,tbl_total_rm_waste.total_rm_waste,cat_tbl.name as category_name,unit_s_tbl.name as consumption_unit_name,unit_p_tbl.name as purchase_unit_name
FROM tbl_rawmaterials i
LEFT JOIN (select rmaterials_id, SUM(quantity_amount) as total_purchase from tbl_purchase_rmaterials where del_status='Live' group by rmaterials_id) tbl_total_purchase ON tbl_total_purchase.rmaterials_id = i.id
LEFT JOIN (select rmaterials_id, SUM(waste_amount) as total_rm_waste from tbl_waste_materials where del_status='Live' group by rmaterials_id) tbl_total_rm_waste ON tbl_total_rm_waste.rmaterials_id = i.id
LEFT JOIN (select id,name from tbl_rmcategory where del_status='Live') cat_tbl ON cat_tbl.id = i.category
LEFT JOIN (select id,name from tbl_rmunits where del_status='Live') unit_s_tbl ON unit_s_tbl.id = i.consumption_unit
LEFT JOIN (select id,name from tbl_rmunits where del_status='Live') unit_p_tbl ON unit_p_tbl.id = i.unit
WHERE i.del_status='Live' $where ORDER BY i.name ASC");
    $total_stock_return = 0;
    foreach ($result as $value) {
        $totalStock = @($value->total_purchase * $value->conversion_rate) - $value->total_rm_waste + $value->opening_stock;
        $total_stock_return += $totalStock;
    }
    return $total_stock_return;
}
/**
 * Get Consumption Unit By Raw Material ID
 */
function getConsumptionUnitByRMID($id)
{
    $row = \App\RawMaterial::where('id', $id)->first();
    $rm_p_id = isset($row->consumption_unit) && $row->consumption_unit ? $row->consumption_unit : '0';
    return getPurchaseSaleUnitById($rm_p_id);
}
/**
 * Get Supplier Name
 */
function getSupplierName($id)
{
    $row = \App\Supplier::where('id', $id)->first();
    return isset($row->name) && $row->name ? $row->name : '-';
}
/**
 * Check Credit Limit
 */
function checkCreditLimit($supplier_id, $current_due)
{
    $supplier = \App\Supplier::where('id', $supplier_id)->first();
    $due = \App\RawMaterialPurchase::where('supplier', $supplier_id)->sum('due');
    $total_due = $due + $current_due;
    if ($total_due <= $supplier->credit_limit) {
        return true;
    } else {
        return false;
    }
}
/**
 * Current Supplier Due
 */
function currentSupplierDue($supplier_id)
{
    $due = \App\RawMaterialPurchase::where('supplier', $supplier_id)->sum('due');
    $supplier_payment = \App\Supplier_payment::where('supplier', $supplier_id)->sum('amount');
    $total_due = $due - $supplier_payment;
    return $total_due;
}
/**
 * Get Last Purchase Price
 */
function getLastPurchasePrice($id)
{
    $row_p = \App\RMPurchase_model::where('rmaterials_id', $id)->orderBy("id", "DESC")->first();
    if (isset($row_p) && $row_p) {
        return $row_p->unit_price;
    } else {
        $row_rm = \App\RawMaterial::where('id', $id)->first();
        return $row_rm->rate_per_unit;
    }
}
/**
 * Get Last Three Purchase Price
 */
function getLastThreePurchasePrice($id)
{

    $last_three_purchase = \App\RMPurchase_model::where('rmaterials_id', $id)->where('del_status', "Live")->latest()->take(3)->get()->sum('unit_price');
    $row_count = \App\RMPurchase_model::where('rmaterials_id', $id)->where('del_status', "Live")->get();
    $no_of_rows = $row_count->count();

    if (isset($last_three_purchase) && $last_three_purchase) {

        if ($no_of_rows == 1) {
            return $last_three_purchase / 1;
        } elseif ($no_of_rows == 2) {
            return $last_three_purchase / 2;
        } else {
            return $last_three_purchase / 3;
        }
    } else {
        $row_rm = \App\RawMaterial::where('id', $id)->first();
        return $row_rm->rate_per_unit;
    }
}
/**
 * Get Last Three Purchase Price
 */
function getLastThreeCPurchasePrice($id)
{

    $last_three_purchase = \App\RMPurchase_model::where('rmaterials_id', $id)->where('del_status', "Live")->latest()->take(3)->get()->sum('unit_price');
    $row_count = \App\RMPurchase_model::where('rmaterials_id', $id)->where('del_status', "Live")->get();
    $no_of_rows = $row_count->count();

    if (isset($last_three_purchase) && $last_three_purchase) {

        if ($no_of_rows == 1) {
            return $last_three_purchase / 1;
        } elseif ($no_of_rows == 2) {
            return $last_three_purchase / 2;
        } else {
            return $last_three_purchase / 3;
        }
    } else {
        $row_rm = \App\RawMaterial::where('id', $id)->first();
        return $row_rm->rate_per_unit;
    }
}
/**
 * Get Currency
 */
function getCurrency($amount)
{
    $setting = getSettingsInfo();
    if ($setting->currency_position == "Before") {
        return $setting->currency . "" . $amount;
    } else {
        return $amount . "" . $setting->currency;
    }
}
/**
 * Get Date Format
 */
function getDateFormat($date)
{
    $setting = getSettingsInfo();
    return date($setting->date_format, strtotime($date));
}
/**
 * get Currency Only
 */
function getCurrencyOnly()
{
    $setting = getSettingsInfo();
    return $setting->currency;
}
/**
 * get Generated ID
 */
function getGeneratedId($id)
{
    $id = str_pad($id, 6, '0', STR_PAD_LEFT);
    return $id;
}
/**
 * Return Payment Info
 */
function returnPaymentInfo($id)
{
    if ($id) {
        $payment = \App\PaymentSettings::find($id);
        if ($payment) {
            return [$payment->type, $payment->app_username, $payment->app_password, $payment->app_secret_key];
        }
    }
    return ['sandbox', '', '', ''];
}

/**
 * Sends sms to user using Twilio's programmable sms client or text local
 * @param String $msg Body of sms
 * @param Number $phone_number string
 * @param String $sender
 */
function sendSMS($msg, $phone_number, $sender)
{
    $config = \App\SMSSetting::first();
    if (isset($config) && $config->sms_type == "text local") {
        $sms = new \Rahulreghunath\Textlocal\Textlocal();
        $sms->send($msg, $phone_number, $sender);
    } else if (isset($config) && $config->sms_type == "Twilio") {
        $account_sid = $config->account_sid;
        $auth_token = $config->auth_token;
        $twilio_number = $config->twilio_number;

        $client = new \Twilio\Rest\Client($account_sid, $auth_token);
        $client->messages->create(
            (int) $phone_number,
            ['from' => $twilio_number, 'body' => $msg]
        );
    }
}
/**
 * Send Email
 */
function sendEmail($msg, $email, $subject)
{
    $config = \App\SMTPEmailSetting::first();
    if (isset($config) && $config->email_type == "smtp") {
        $emailbody = $msg;
        $emailcontent = array('emailBody' => $emailbody);
        $sender_email = "";
        Config::set('mail.mailers.smtp.host', $config->smtp_host);
        Config::set('mail.mailers.smtp.port', $config->port_address);
        Config::set('mail.mailers.smtp.username', $config->user_name);
        Config::set('mail.mailers.smtp.password', $config->password);

        Mail::send(['text' => 'mail'], $emailcontent, function ($message) use ($email, $sender_email, $subject) {
            $message->to($email, 'Angel Doctor')
                ->subject($subject);
            $message->from($sender_email, 'Angel Doctor');
        });
    }
}
/**
 * Get SMS Config
 */
function getSMSConfig()
{
    $config = \App\SMSSetting::first();
    return $config;
}
/**
 * Get Settings Info
 */
function getSettingsInfo()
{

    return AdminSettings::first();
}
/**
 * Get Tax Info
 */
function getTaxInfo()
{

    return TaxItems::first();
}

/**
 * Get Delivery Date By Order Id
 */
function getDeliveryDateByOrderId($id, $product_id)
{
    $row = \App\Manufacture::where('customer_order_id', $id)->where('product_id', $product_id)->first();
    return isset($row->complete_date) && $row->complete_date ? $row->complete_date : 'N/A';
}
/**
 * Get Manufacture Status By Order ID
 */
function getManufactureStatusByOrderId($id, $product_id)
{
    $row = \App\Manufacture::where('customer_order_id', $id)->where('product_id', $product_id)->first();
    return isset($row->manufacture_status) && $row->manufacture_status ? ucfirst($row->manufacture_status) : 'N/A';
}
/**
 * Get Total Manufacture By Id
 */
function getTotalManufactureByOrderId($id, $product_id)
{
    $row = \App\Manufacture::where('customer_order_id', $id)->where('product_id', $product_id)->sum('product_quantity');
    return $row ?? 'N/A';
}

/**
 * Get Finished Product Info
 */
function getFinishedProductInfo($product_id)
{
    $row = \App\FinishedProduct::where('del_status', 'Live')->where('id', $product_id)->first();
    return $row;
}

/**
 * Get Manufacture Info
 */
function getManufactureInfo($manufacture_id)
{
    $row = \App\Manufacture::where('del_status', 'Live')->where('id', $manufacture_id)->first();
    return $row;
}

/**
 * Get Total Hour
 */
function getTotalHour($out_time, $in_time)
{
    $time1 = $out_time;
    $time2 = $in_time;
    $array1 = explode(':', $time1);
    $array2 = explode(':', $time2);

    $minutes1 = ($array1[0] * 60.0 + $array1[1]);
    $minutes2 = ($array2[0] * 60.0 + $array2[1]);

    $total_min = $minutes1 - $minutes2;
    $total_tmp_hour = (int) ($total_min / 60);
    $total_tmp_hour_minus = ($total_min % 60);

    return $total_tmp_hour . "." . get_numb_with_zero($total_tmp_hour_minus);
}

/**
 * Get Number With Zero
 */
function get_numb_with_zero($number)
{
    $numb = str_pad($number, 2, '0', STR_PAD_LEFT);
    return $numb;
}

/**
 * Get Total Product Waste
 */
function getTotalProductWaste($id)
{
    $row = \App\ProductWasteItems::where('fpwaste_id', $id)->where('del_status', "Live")->count();
    return isset($row) && $row ? $row : '0';
}

/**
 * Get Current product Stock
 */
function getCurrentProductStock($id)
{
    $row = \App\FinishedProduct::findOrFail($id);
    return isset($row->current_total_stock) && $row->current_total_stock ? $row->current_total_stock : '0';
}

/**
 * Get Purchase Unit By Product Id
 */
function getPurchaseUnitByProductID($id)
{
    $row = \App\FinishedProduct::findOrFail($id);
    $rm_p_id = isset($row->unit) && $row->unit ? $row->unit : '0';
    return getPurchaseSaleUnitById($rm_p_id);
}

/**
 * get Purchase Ingredients
 * @access public
 * @return string
 * @param int
 */
function getRMPurchaseItems($id)
{
    $purchase_items = \App\RMPurchase_model::where('purchase_id', $id)->where('del_status', 'Live')->get();

    if (!empty($purchase_items)) {
        $pur_ingr_all = "";
        $key = 1;
        $pur_ingr_all .= "<b>SN-Items-Qty-Unit Price-Total</b><br>";
        foreach ($purchase_items as $value) {
            $pur_ingr_all .= $key . "-" . getRMName($value->rmaterials_id) . "-" . $value->quantity_amount . "-" . $value->unit_price . "-" . $value->total . "<br>";
            $key++;
        }
        return $pur_ingr_all;
    } else {
        return "Not found!";
    }
}

/**
 * Get Sale Item
 * @access public
 * @return string
 */

function getSaleItems($id)
{
    $sale_items = \App\SaleDetail::where('sale_id', $id)->get();
    if (!empty($sale_items)) {
        $sale_ingr_all = "";
        $key = 1;
        $sale_ingr_all .= "<b>SN-Items-Qty-Unit Price-Total</b><br>";
        foreach ($sale_items as $value) {
            $sale_ingr_all .= $key . "-" . getProductNameById($value->product_id) . "-" . $value->product_quantity . "-" . $value->unit_price . "-" . $value->total_amount . "<br>";
            $key++;
        }
        return $sale_ingr_all;
    } else {
        return "Not found!";
    }
}

/**
 * Get Custom Amount
 */
function getAmtCustom($amount)
{
    if (!is_numeric($amount)) {
        $amount = 0;
    }
    $getCompanyInfo = getSettingsInfo();
    $currency_position = $getCompanyInfo->currency_position;
    $currency = $getCompanyInfo->currency;
    $precision = $getCompanyInfo->precision;
    $str_amount = '';
    $decimals_separator = isset($getCompanyInfo->decimals_separator) && $getCompanyInfo->decimals_separator ? $getCompanyInfo->decimals_separator : '.';
    $thousands_separator = isset($getCompanyInfo->thousands_separator) && $getCompanyInfo->thousands_separator ? $getCompanyInfo->thousands_separator : '';
    if (isset($currency_position) && $currency_position != "Before") {
        $str_amount = (number_format(isset($amount) && $amount ? $amount : 0, $precision, $decimals_separator, $thousands_separator)) . $currency;
    } else {
        $str_amount = $currency . (number_format(isset($amount) && $amount ? $amount : 0, $precision, $decimals_separator, $thousands_separator));
    }
    return $str_amount;
}

/**
 * get Company Info
 * @access public
 * @return object
 * @param no
 */
function getCompanyInfo()
{
    return \App\Company::findOrFail(1);
}

/**
 * return amount format as per setting
 * @access public
 * @return string
 * @param int
 */
function getAmt($amount)
{
    if (!is_numeric($amount)) {
        $amount = 0;
    }
    $getCompanyInfo = getCompanyInfo();
    $currency_position = $getCompanyInfo->currency_position;
    $currency = $getCompanyInfo->currency;
    $precision = $getCompanyInfo->precision;
    $str_amount = '';
    if (isset($currency_position) && $currency_position != "Before Amount") {
        $str_amount = (number_format(isset($amount) && $amount ? $amount : 0, $precision, '.', '')) . $currency;
    } else {
        $str_amount = $currency . (number_format(isset($amount) && $amount ? $amount : 0, $precision, '.', ''));
    }
    return $str_amount;
}

/**
 * Get Supplier Opening Balance Type
 */
function getSupplierOpeningBalanceType($supplier_id)
{
    return \App\Supplier::findOrFail($supplier_id)->opening_balance_type;
}

/**
 * getCustomerOpeningDue
 * @access public
 * @param int
 * @param string
 * @param int
 * @return int
 */
function getCustomerOpeningDue($customer_id, $date)
{
    $customer = \App\Customer::findOrFail($customer_id);

    $opening_sale_due = \App\Sales::select(DB::raw('SUM(due) as due'))
        ->where('customer_id', $customer_id)
        ->where('created_at', '<', $date)
        ->where('del_status', 'Live')
        ->first();

    $opening_due_receive = \App\CustomerDueReceive::select(DB::raw('SUM(amount) as amount'))
        ->where('customer_id', $customer_id)
        ->where('date', '<', $date)
        ->where('del_status', 'Live')
        ->first();

    if ($customer->opening_balance_type == "Credit") {
        $opening_balance_sum = -(int) $customer->opening_balance - (int) $opening_due_receive->amount + (int) $opening_sale_due->due;
    } else {
        $opening_balance_sum = +(int) $customer->opening_balance - (int) $opening_due_receive->amount + (int) $opening_sale_due->due;
    }

    return $opening_balance_sum;
}
/**
 * getCustomerGrantTotalByDate
 * @access public
 * @param int
 * @param string
 * @param int
 * @return object
 */

function getCustomerGrantTotalByDate($customer_id, $date)
{
    $sale_info = \App\Sales::select('reference_no', 'grand_total as total', 'paid', 'due')
        ->where('customer_id', $customer_id)
        ->where('due', '!=', 0)
        ->where('created_at', $date)
        ->where('del_status', 'Live')
        ->get();
    return $sale_info;
}
/**
 * getCustomerDuePaymentByDate
 * @access public
 * @param int
 * @param string
 * @param int
 * @return object
 */
function getCustomerDuePaymentByDate($customer_id, $date)
{
    $customer_due_rec = \App\CustomerDueReceive::select('reference_no', 'amount')
        ->where('customer_id', $customer_id)
        ->where('date', $date)
        ->where('del_status', 'Live')
        ->get();
    return $customer_due_rec;
}

/**
 * getSaleReturnByDate
 * @access public
 * @param int
 * @param string
 * @param int
 * @return object
 */
function getSaleReturnByDate($customer_id, $date)
{
    $sale_return = \App\SaleReturn::select('reference_no', 'total_return_amount')
        ->where('customer_id', $customer_id)
        ->where('date', $date)
        ->where('del_status', 'Live')
        ->get();
    return $sale_return;
}

/**
 * Company Supplier Balance
 */
function companySupplierBalance($supplier_id)
{
    $supplier = \App\Supplier::select('opening_balance', 'opening_balance_type')
        ->where('id', $supplier_id)
        ->where('del_status', 'Live')
        ->first();
    $supplier_due = \App\RawMaterialPurchase::select(DB::raw('SUM(due) as due'))
        ->where('supplier', $supplier_id)
        ->where('status', 'Final')
        ->where('del_status', 'Live')
        ->first();
    $supplierPurchasePaid = \App\RawMaterialPurchase::select(DB::raw('SUM(paid) as paid'))
        ->where('supplier', $supplier_id)
        ->where('status', 'Final')
        ->where('del_status', 'Live')
        ->first();
    $supplierPurchaseTotalAmount = \App\RawMaterialPurchase::select(DB::raw('SUM(grand_total) as grand_total'))
        ->where('supplier', $supplier_id)
        ->where('status', 'Final')
        ->where('del_status', 'Live')
        ->first();
    $supplier_payment = \App\Supplier_payment::select(DB::raw('SUM(amount) as amount'))
        ->where('supplier', $supplier_id)
        ->where('del_status', 'Live')
        ->first();

    //Calculate Total Supplier Balance
    if ($supplier->opening_balance_type == "Credit") {
        $purchaseAmount = $supplierPurchaseTotalAmount->grand_total;
        $supplierDue = (int) $supplier_due->due - (int) $supplier_payment->amount;
        $opening_balance_sum = +(int) $supplier->opening_balance;
        $paidAmount = $supplierPurchasePaid->paid;

        $total_balance = $purchaseAmount - $paidAmount - $supplierDue + $opening_balance_sum;
    } else {
        $purchaseAmount = $supplierPurchaseTotalAmount->grand_total;
        $supplierDue = (int) $supplier_due->due - (int) $supplier_payment->amount;
        $opening_balance_sum = +(int) $supplier->opening_balance;
        $paidAmount = $supplierPurchasePaid->paid;

        $total_balance = $purchaseAmount - $paidAmount - $supplierDue - $opening_balance_sum;
    }

    return $total_balance;
}
/**
 * Get Customer Balance
 */
function getCustomerBalance($customer_id)
{
    $customer = \App\Customer::findOrFail($customer_id);

    $opening_sale_due = \App\Sales::select(DB::raw('SUM(due) as due'))
        ->where('customer_id', $customer_id)
        ->where('del_status', 'Live')
        ->first();

    $opening_due_receive = \App\CustomerDueReceive::select(DB::raw('SUM(amount) as amount'))
        ->where('customer_id', $customer_id)
        ->where('del_status', 'Live')
        ->first();

    if ($customer->opening_balance_type == "Credit") {
        $opening_balance_sum = -(int) $customer->opening_balance - (int) $opening_due_receive->amount + (int) $opening_sale_due->due;
    } else {
        $opening_balance_sum = +(int) $customer->opening_balance - (int) $opening_due_receive->amount + (int) $opening_sale_due->due;
    }

    return $opening_balance_sum;
}

/**
 * Manufacture Type
 */

function getManufactureType($type)
{
    $manufacture_type = [
        'ime' => 'Instant Manufacture Entry',
        'mbs' => 'Manufacture by Scheduling',
        'fco' => 'From Customer Order',
    ];
    return $manufacture_type[$type] ?? '';
}

/**
 * Safe Function
 */

function safe($value)
{
    return isset($value) && !empty($value) ? $value : 'N/A';
}

/**
 * Safe integer
 */

function safe_integer($value)
{
    return isset($value) && !empty($value) ? $value : 0;
}

/**
 * Product Total Cost
 */

function getProductTotalCost($id)
{
    $manufacture = \App\Manufacture::where('product_id', $id)->where('del_status', 'Live')->get();
    $total_cost = 0;
    foreach ($manufacture as $value) {
        $total_cost += $value->mtotal_cost;
    }
    return $total_cost;
}

/**
 * Product Total Sale
 */

function getProductTotalSale($id)
{
    $saleDetails = SaleDetail::where('product_id', $id)->where('del_status', 'Live')->pluck('sale_id');
    $sales = Sales::whereIn('id', $saleDetails)->where('del_status', 'Live')->get();

    $total_sale = 0;
    foreach ($sales as $value) {
        $total_sale += $value->grand_total;
    }
    return $total_sale;
}

/**
 * Profit Loss Calculate
 */

function getProfitLoss($id)
{
    $total_sale = getProductTotalSale($id);
    $total_cost = getProductTotalCost($id);
    $profit_loss = $total_sale - $total_cost;
    return getAmtCustom($profit_loss) . ' (' . ($profit_loss > 0 ? 'Profit' : 'Loss') . ')';
}

/**
 * Total Credit
 */

function getTotalCredit($id)
{
    $totalOpeningBalance = Account::where('id', $id)->where('del_status', 'Live')->sum('opening_balance');
    $deposit = Deposit::where('account_id', $id)->where('type', 'Deposit')->where('del_status', 'Live')->sum('amount');
    $sales = Sales::where('account_id', $id)->where('status', 'Final')->where('del_status', 'Live')->sum('paid');
    $customerDueReceive = CustomerDueReceive::where('account_id', $id)->where('del_status', 'Live')->sum('amount');

    $total_credit = $totalOpeningBalance + $deposit + $sales + $customerDueReceive;
    return $total_credit;
}

/**
 * Total Debit
 */

function getTotalDebit($id)
{
    $withdraw = Deposit::where('account_id', $id)->where('type', 'Withdraw')->where('del_status', 'Live')->sum('amount');
    $purchase = RawMaterialPurchase::where('account', $id)->where('status', 'Final')->where('del_status', 'Live')->sum('paid');
    $supplierDuePay = Supplier_payment::where('account_id', $id)->where('del_status', 'Live')->sum('amount');
    $productionNonInventory = Pnonitem::where('account', $id)->where('del_status', 'Live')->sum('totalamount');
    $expense = Expense::where('account_id', $id)->where('del_status', 'Live')->sum('amount');
    $payroll = Salary::where('account_id', $id)->where('del_status', 'Live')->sum('total_amount');
    //Get manufacture non inventory item cost which manufacture is not draft
    $manufactureNonInventory = Mnonitem::where('account_id', $id)->where('del_status', 'Live')->whereHas('manufacture', function ($query) {
        $query->where('manufacture_status', '!=', 'draft');
    })->sum('nin_cost');

    $total_debit = $withdraw + $purchase + $supplierDuePay + $productionNonInventory + $expense + $payroll + $manufactureNonInventory;
    return $total_debit;
}

/**
 * Payment Status
 */

function paymentStatus($due, $paid)
{
    if ($paid == 0) {
        return 'Unpaid';
    }

    if ($due == 0) {
        return 'Paid';
    }

    if ($due > 0 && $paid > 0) {
        return 'Partial Paid';
    }
}

/**
 * Fetch all language folder names
 */
if (!function_exists('languageFolders')) {
    function languageFolders(): array
    {
        $filtered = ['.', '..'];
        $dirs = [];
        $d = dir(resource_path('lang'));
        while (($entry = $d->read()) !== false) {
            if (is_dir(resource_path('lang') . '/' . $entry) && !in_array($entry, $filtered)) {
                $dirs[] = $entry;
            }
        }

        return $dirs;
    }
}

/**
 * Get language full name
 */
if (!function_exists('lanFullName')) {
    function lanFullName($short_name)
    {
        $lg['ar'] = 'Arabic';
        $lg['az'] = 'Azerbaijani';
        $lg['bg'] = 'Bulgarian';
        $lg['bn'] = 'Bengali';
        $lg['bs'] = 'Bosnian';
        $lg['ca'] = 'Catalan';
        $lg['cn'] = 'Chinese (S)';
        $lg['cs'] = 'Czech';
        $lg['da'] = 'Danish';
        $lg['de'] = 'German';
        $lg['fi'] = 'Finnish';
        $lg['fr'] = 'French';
        $lg['ea'] = 'Spanish (Argentina)';
        $lg['el'] = 'Greek';
        $lg['en'] = 'English';
        $lg['es'] = 'Spanish';
        $lg['et'] = 'Estonian';
        $lg['he'] = 'Hebrew';
        $lg['hi'] = 'Hindi';
        $lg['hr'] = 'Croatian';
        $lg['hu'] = 'Hungarian';
        $lg['hy'] = 'Armenian';
        $lg['id'] = 'Indonesian';
        $lg['is'] = 'Icelandic';
        $lg['it'] = 'Italian';
        $lg['ir'] = 'Persian';
        $lg['jp'] = 'Japanese';
        $lg['ka'] = 'Georgian';
        $lg['ko'] = 'Korean';
        $lg['lt'] = 'Lithuanian';
        $lg['lv'] = 'Latvian';
        $lg['mk'] = 'Macedonian';
        $lg['ms'] = 'Malay';
        $lg['mx'] = 'Mexico';
        $lg['nb'] = 'Norwegian';
        $lg['ne'] = 'Nepali';
        $lg['nl'] = 'Dutch';
        $lg['pl'] = 'Polish';
        $lg['pt-BR'] = 'Brazilian';
        $lg['pt'] = 'Portuguese';
        $lg['ro'] = 'Romanian';
        $lg['ru'] = 'Russian';
        $lg['sr'] = 'Serbian (Latin)';
        $lg['sq'] = 'Albanian';
        $lg['sk'] = 'Slovak';
        $lg['sl'] = 'Slovenian';
        $lg['sv'] = 'Swedish';
        $lg['th'] = 'Thai';
        $lg['tr'] = 'Turkish';
        $lg['tw'] = 'Chinese (T)';
        $lg['uk'] = 'Ukrainian';
        $lg['ur'] = 'Urdu (Pakistan)';
        $lg['uz'] = 'Uzbek';
        $lg['vi'] = 'Vietnamese';
		$lg['kur'] = 'Kurdish';
        if (isset($lg[$short_name])) {
            return $lg[$short_name];
        } else {
            return "English";
        }
    }
}

/**
 * Data saved notification message
 */
if (!function_exists('saveMessage')) {
    function saveMessage($message = 'Information has been saved successfully !'): array
    {
        return [
            'type' => 'success',
            'message' => $message,
            'sign' => 'check',
        ];
    }
}

/**
 * Data update notification message
 */
if (!function_exists('updateMessage')) {
    function updateMessage($message = 'Information has been updated successfully !'): array
    {
        return [
            'type' => 'success',
            'message' => $message,
            'sign' => 'check',
        ];
    }
}

/**
 * Warning notification message
 */
if (!function_exists('waringMessage')) {
    function waringMessage($message = "Something wrong !"): array
    {
        return [
            'type' => 'warning',
            'message' => $message,
            'sign' => 'exclamation-triangle',
        ];
    }
}

/**
 * Error notification message
 */
if (!function_exists('deleteMessage')) {
    function deleteMessage($message = "Information has been deleted successfully !"): array
    {
        return [
            'type' => 'success',
            'message' => $message,
            'sign' => 'check',
        ];
    }
}

/**
 * Error notification message
 */
if (!function_exists('dangerMessage')) {
    function dangerMessage($message): array
    {
        return [
            'type' => 'danger',
            'message' => $message,
            'sign' => 'times',
        ];
    }
}

/**
 * Get Role Permission Name
 */
if (!function_exists('getRolePermissionName')) {
    function getRolePermissionName($id)
    {
        $data = Role::where("id", $id)->first();
        if ($data) {
            return $data->title;
        } else {
            return "N/A";
        }
    }
}

/**
 * Display required star dynamically
 */
if (!function_exists('starSign')) {
    function starSign()
    {
        return "<span class='required_star'>" . " *" . "</span>";
    }
}

/**
 * Get Stock Adjustment Data
 */

if (!function_exists('getAdjustData')) {
    function getAdjustData($totalStock, $rmId)
    {
        $totalStock = $totalStock;
        $rmLog = StockAdjustLog::where('rm_id', $rmId)->first();
        if ($rmLog && $rmLog->type == 'addition') {
            $totalStock += $rmLog->quantity;
        }
        if ($rmLog && $rmLog->type == 'subtraction') {
            $totalStock -= $rmLog->quantity;
        }

        return $totalStock;
    }
}

/**
 * Get user language
 * @returns string
 */

if (!function_exists('getUserLanguage')) {
    function getUserLanguage(): string
    {
        if ((Auth::check()) and (Auth::user()->language != null)) {
            $language = Auth::user()->language;
        } else {
            $language = "en";
        }
        return $language;
    }
}

/**
 * Get pusher info from file
 */
if (!function_exists('smtpInfo')) {
    function smtpInfo()
    {
        if (file_exists('assets/json/smtp.json')) {
            $jsonString = File::get('assets/json/smtp.json');
            return json_decode($jsonString, true);
        } else {
            return "";
        }
    }
}

/**
 * test sendinblue api
 */
if (!function_exists('testSendinBlueApi')) {
    function testSendinBlueApi()
    {
        $client = new Client();

        try {
            //sendinblue api URL
            $response = $client->get('https://api.sendinblue.com/v3/account', [
                'headers' => [
                    'api-key' => smtpInfo()['api_key'],
                ],
            ]);

            return '200';
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 401) {
                return '401';
            } else {
                return 'Error: ' . $e->getMessage();
            }
        }
    }
}

/**
 * Check mail connection
 */

if (!function_exists('mailConnection')) {
    function mailConnection()
    {
        if (testSendinBlueApi() == "200") {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Get user name by id
 * @returns string
 * @param string $id
 */

if (!function_exists('userNameByEmail')) {
    function userNameByEmail($email): string
    {
        if (User::where('email', $email)->exists()) {
            $id = User::where('email', $email)->first()->id;
            return getUserName($id);
        } else if (Customer::where('email', $email)->exists()) {
            $id = Customer::where('email', $email)->first()->id;
            return getCustomerNameById($id);
        } else {
            return "User";
        }
    }
}

/**
 * Get Previous 6 or 12 month name
 */
if (!function_exists('getPreviousMonthName')) {
    function getPreviousMonthName($number)
    {
        $current_month = date('m');
        $previous_six_months = array();
        $current_month_number = date('m');
        $current_year = date('Y');
        $dateTime = new DateTime();
        for ($i = 1; $i < $number; $i++) {
            $dateTime->modify('-1 month');
            $month_name = $dateTime->format('m');
            $year = $dateTime->format('Y');
            $previous_six_months[] = "$month_name-$year";
        }

        $previous_six_months = array_reverse($previous_six_months);

        $previous_six_months[] = "$current_month_number-$current_year";

        return $previous_six_months;
    }
}

/**
 * Price History
 */

if (!function_exists('priceHistory')) {
    function priceHistory($id)
    {
        $priceHistory = RMPurchase_model::with(['purchase'])->where('rmaterials_id', $id)->orderBy('id', 'desc')->get();
        return $priceHistory;
    }
}

/**
 * Product Price History
 */

if (!function_exists('productPriceHistory')) {
    function productPriceHistory($id)
    {
        $priceHistory = SaleDetail::with(['sale'])->where('product_id', $id)->orderBy('id', 'desc')->get();
        return $priceHistory;
    }
}

/**
 * Get authenticate user role
 */

if (!function_exists('authUserRole')) {
    function authUserRole()
    {
        return auth()->user()->role;
    }
}

/**
 * Check route permission
 */
if (!function_exists('routePermission')) {
    function routePermission($route_name)
    {
        if (authUserRole() != 2) {
            return true;
        } else {
            $activity = MenuActivity::where('route_name', $route_name)->first();
            if (isset($activity)) {
                $activity_id = $activity->id;
                $role_id = auth()->user()->permission_role;
                if (isset($activity_id)) {
                    $condition = [
                        'role_id' => $role_id,
                        'activity_id' => $activity_id,
                    ];
                    if (RolePermission::where($condition)->exists()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}

/**
 * Check Menu permission
 */
if (!function_exists('menuPermission')) {
    function menuPermission($menu_name)
    {
        if (authUserRole() != 2) {
            return true;
        } else {
            $role_id = auth()->user()->permission_role;
            $menu = Menu::where("title", $menu_name)->first();
            if (isset($menu)) {
                $menu_id = $menu->id;
                $activity_ids = MenuActivity::where('menu_id', $menu_id)->where('is_dependant', 'No')->pluck("id");
                if (isset($menu_id)) {
                    $condition = [
                        'role_id' => $role_id,
                        'menu_id' => $menu_id,
                    ];
                    if (RolePermission::whereIn('activity_id', $activity_ids)->where($condition)->exists()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}

/**
 * Get Floating Stock Raw Material Id
 */

if (!function_exists('getFloatingStockRawMaterialId')) {
    function getFloatingStockRawMaterialId($id, $manufacture_id)
    {
        if($manufacture_id != null){
            $manufacture = Manufacture::where('id', $manufacture_id)->where('manufacture_status', 'inProgress')->where('del_status', 'Live')->get();
        }else{
            $manufacture = Manufacture::where('manufacture_status', 'inProgress')->where('del_status', 'Live')->get();
        }
        
        $totalFloatingStock = 0;
        foreach ($manufacture as $key => $value) {
            $rmMaterials = Mrmitem::where('manufacture_id', $value->id)->where('rmaterials_id', $id)->where('del_status', 'Live')->get();
            foreach ($rmMaterials as $key => $value) {
                $totalFloatingStock += $value->consumption;
            }
        }

        return $totalFloatingStock ?? 0;
    }
}

/**
 * Get Total FLoatiog Stock Raw Material
 */

if (!function_exists('getTotalFloatingStockRawMaterial')) {
    function getTotalFloatingStockRawMaterial($id)
    {
        $manufacture = Manufacture::where('manufacture_status', 'inProgress')->where('del_status', 'Live')->get();
        $totalFloatingStock = 0;
        foreach ($manufacture as $key => $value) {
            $rmMaterials = Mrmitem::where('manufacture_id', $value->id)->where('rmaterials_id', $id)->where('del_status', 'Live')->get();
            foreach ($rmMaterials as $key => $value) {
                $totalFloatingStock += $value->consumption;
            }
        }

        return $totalFloatingStock ?? 0;
    }
}

/**
 * Get Raw Material Use In Manufacture
 */

if (!function_exists('getRawMaterialUseInManufacture')) {
    function getRawMaterialUseInManufacture($id)
    {
        $manufacture = Manufacture::where('manufacture_status', '!=', 'draft')->where('del_status', 'Live')->get();
        $totalUse = 0;
        foreach ($manufacture as $key => $value) {
            $rmMaterials = Mrmitem::where('manufacture_id', $value->id)->where('rmaterials_id', $id)->where('del_status', 'Live')->get();
            foreach ($rmMaterials as $key => $value) {
                $totalUse += $value->consumption;
            }
        }

        return $totalUse ?? 0;
    }
}

/**
 * Number Format with precision
 */

if (!function_exists('numberFormat')) {
    function numberFormat($number)
    {
        $getSettingsInfo = getSettingsInfo();
        $precision = $getSettingsInfo->precision;
        return number_format($number, $precision, '.', '');
    }
}

/**
 * Get decrypted string ulr from id
 * @returns string
 */
if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt($key, $type)
    {
        $str_rand = "XxOx*4e!hQqG5b~9a";

        if (!$key) {
            return false;
        }
        if ($type == 'decrypt') {
            $en_slash_added1 = trim(str_replace(array('tcktly'), '/', $key));
            $en_slash_added = trim(str_replace(array('dstcktly'), '%', $en_slash_added1));
            $key_value = $return = openssl_decrypt($en_slash_added, "AES-128-ECB", $str_rand);
            return $key_value;

        } elseif ($type == 'encrypt') {
            $key_value = openssl_encrypt($key, "AES-128-ECB", $str_rand);
            $en_slash_remove1 = trim(str_replace(array('/'), 'tcktly', $key_value));
            $en_slash_remove = trim(str_replace(array('%'), 'dstcktly', $en_slash_remove1));
            return $en_slash_remove;
        }
        return false;
    }
}

/**
 * Function escape output
 * @returns string
 */
if (!function_exists('escape_output')) {
    function escape_output($string)
    {
        if ($string) {
            $output = htmlentities($string, ENT_QUOTES, 'UTF-8');
            $output = str_replace("&amp;", "&", $output);
            return $output;
        } else {
            return '';
        }
    }
}

/**
 * convert raw material unit to purchase unit
 */

if (!function_exists('convertUnit')) {
    function convertUnit($value, $conversion_rate, $bigUnit, $smallUnit)
    {
        if($conversion_rate == 0){
            return $value . $bigUnit;
        }
        $kilograms = floor($value / $conversion_rate);
        $remainingGrams = $value - ($kilograms * $conversion_rate);

        return sprintf("%.2f{$bigUnit} %.2f{$smallUnit}", $kilograms, $remainingGrams);
    }
}

/**
 * Is White Label Change Able
 */
if (!function_exists('isWhiteLabelChangeAble')) {
    function isWhiteLabelChangeAble()
    {
        $getSettingsInfo = getCompanyInfo();
        return $getSettingsInfo->is_white_label_change_able;
    }
}

/**
 * Opening Stock Calculate
 */
if (!function_exists('openingStock')) {
    function openingStock($id)
    {
        $rm = RawMaterial::where('id', $id)->first();
        $openingStock = $rm->opening_stock;
        $consumption_check = $rm->consumption_check;

        if ($consumption_check == 1) {
            $openingStock = convertUnit($openingStock, $rm->conversion_rate, getRMUnitById($rm->unit), getRMUnitById($rm->consumption_unit));
        } else {
            $openingStock = $openingStock . getRMUnitById($rm->unit);
        }

        return $openingStock;
    }
}

/**
 * Expire Date
 */

if (!function_exists('expireDate')) {
    function expireDate($completeData, $expiry_days)
    {
        $date = date('Y-m-d', strtotime($completeData . ' + ' . $expiry_days . ' days'));
        return $date;
    }
}

/**
 * Last Production cost
 */

if (!function_exists('lastProductionCost')) {
    function lastProductionCost($id)
    {
        $manufacture = Manufacture::where('product_id', $id)->where('manufacture_status', 'done')->orderBy('id', 'desc')->first();
        if ($manufacture) {
            if($manufacture->product_quantity == 0){
                return 0;
            }
            return (float)$manufacture->mtotal_cost / $manufacture->product_quantity;
        } else {
            return 0;
        }
    }
}

/**
 * Product Sale Price Calculate
 */

if (!function_exists('productSalePrice')) {
    function productSalePrice($id)
    {
        $manufacture = Manufacture::where('product_id', $id)->where('manufacture_status', 'done')->orderBy('id', 'desc')->first();
        if ($manufacture) {
            if($manufacture->product_quantity == 0){
                return 0;
            }
            return number_format((float)$manufacture->msale_price / $manufacture->product_quantity, 2, '.', '');
        } else {
            return 0;
        }
    }
}

/**
 * Manufacture Status Show
 */

if (!function_exists('manufactureStatusShow')) {
    function manufactureStatusShow($status)
    {
        $manufacture_status = [
            'draft' => 'Draft',
            'inProgress' => 'In Progress',
            'done' => 'Done',
        ];
        return $manufacture_status[$status] ?? '';
    }
}

/**
 * Required Quanitty Check
 */

if (!function_exists('requiredQuantityCheck')) {
    function requiredQuantityCheck($requiredQuantity, $rm_id)
    {
        $rawMaterial = RawMaterial::where('id', $rm_id)->where('del_status', 'Live')->first();
        if($rawMaterial->consumption_check == 1){
            $requiredQuantity = convertUnit($requiredQuantity, $rawMaterial->conversion_rate, getRMUnitById($rawMaterial->unit), getRMUnitById($rawMaterial->consumption_unit));           
        }else{
            $requiredQuantity = $requiredQuantity . getRMUnitById($rawMaterial->unit);
        }

        return $requiredQuantity;
    }
}

/**
 * Calculate Difference
 */

if (!function_exists('calculateDifference')) {
    function calculateDifference($available, $required, $rm_id)
    {
        $rawMaterial = RawMaterial::where('id', $rm_id)->where('del_status', 'Live')->first();
        if($rawMaterial->consumption_check == 1){
            $available = $available * $rawMaterial->conversion_rate;
            if($available >= $required){
                return 0;
            }
            $difference = abs($available - $required);
            $difference = convertUnit($difference, $rawMaterial->conversion_rate, getRMUnitById($rawMaterial->unit), getRMUnitById($rawMaterial->consumption_unit));
        }else{
            $available = $available;
            if($available >= $required){
                return 0;
            }
            $difference = abs($available - $required);
            $difference = $difference . getRMUnitById($rawMaterial->unit);
        }

        return $difference;
    }
}

/**
 * Expiry Date Product
 */

if (!function_exists('expiryDateProduct')) {
    function expiryDateProduct($id)
    {
        $manufacturesProduct = Manufacture::where('product_id', $id)
                            ->where('expiry_days', '!=', 0)
                            ->where('manufacture_status', 'done')
                            ->where('del_status', 'Live')
                            ->get();
        return $manufacturesProduct;
    }
}

/**
 * Batch Control Product
 */

if (!function_exists('batchControlProduct')) {
    function batchControlProduct($id)
    {
        $manufacturesProduct = Manufacture::where('product_id', $id)
                    ->where('batch_no', '!=', '')
                    ->where('manufacture_status', 'done')
                    ->where('del_status', 'Live')
                    ->get();
        return $manufacturesProduct;
    }
}

/**
 * Stock Method
 */

if (!function_exists('stockMethod')) {
    function stockMethod($method)
    {
        $m = [
            'fifo' => 'FIFO',
            'lifo' => 'LIFO',
            'fefo' => 'FEFO',
            'batchcontrol' => 'Batch Control',
            'none' => 'None',
        ];
        return $m[$method] ?? '';
    }
}


/**
 * Get application mode
 */
if (!function_exists('appMode')) {
    function appMode()
    {
        return env('APPLICATION_MODE') ?? 'live';
    }
}

/**
 * Null check
 */

 if(!function_exists('null_check')){
    function null_check($value){
        if($value != null && $value != "" && $value != "null"){
            return $value;
        }else{
            return 0;
        }
    }
 }

 /**
  * string_date_null_check
  */
 if(!function_exists('string_date_null_check')){
    function string_date_null_check($value){
        if($value != null && $value != ""){
            return $value;
        }else{
            return null;
        }
    }
 }

/**
 * Check user languate arabic or not
 * @returns string
 */

if (!function_exists('isArabic')) {
    function isArabic(): string
    {
        if (getUserLanguage() == "ar" || getUserLanguage() == "kur") {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * all currency
 */
if(!function_exists('allCurrency')){
    function allCurrency(){
        return App\Currency::where('del_status', 'Live')->get();
    }
}

/**
 * Single Currency
 */

if(!function_exists('singleCurrency')){
    function singleCurrency($id){
        return App\Currency::where('id', $id)->first();
    }
}

/**
 * Currency Conversion
 */

if(!function_exists('currencyConversion')){
    function currencyConversion($amount, $conversion_rate){
        return $amount * $conversion_rate;
    }
}

/**
 * Get Product Raw Material By Product Id
 */

if(!function_exists('getProductRawMaterialByProductId')){
    function getProductRawMaterialByProductId($id){
        return App\FPrmitem::where('finish_product_id', $id)->where('del_status', 'Live')->get();
    }
}

/**
 * Get Raw Material Info by Id
 */

if(!function_exists('getRawMaterialInfoById')){
    function getRawMaterialInfoById($id){
        return App\RawMaterial::where('id', $id)->where('del_status', 'Live')->first();
    }
}