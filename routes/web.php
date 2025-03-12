<?php

use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\MailSettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('clear-all', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('clear-compiled');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    dd('Cached Cleared');
});

Route::get('/set-collapse', function () {
    session()->put('is_collapse', request()->get('status'));
    return response()->json(['status' => 'success']);
});

Route::get('/test', function () {
    dd(session()->get('is_collapse'));
});


Route::get('set-locale/{language}', function ($language) {
    $user = Auth::user();
    $user->language = $language;
    $user->save();
    session()->forget('lan');
    app()->setLocale($language);
    $message = __('index.language_changed_successfully');
    return redirect()->back();
})->name('set-locale');

Auth::routes();

Route::group(['middleware' => ['XSS']], function () {
    Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('index');

    Route::get('/forgot-password-step-one', [App\Http\Controllers\UserLoginController::class, 'forgotPasswordStepOne'])->name('forgot-password-step-one');
    Route::get('/forgot-password-step-two', [App\Http\Controllers\UserLoginController::class, 'forgotPasswordStepTwo'])->name('forgot-password-step-two');
    Route::get('/forgot-password-step-final', [App\Http\Controllers\UserLoginController::class, 'forgotPasswordStepFinal'])->name('forgot-password-step-final');
    Route::post('/post-forgot-password-step-one', [App\Http\Controllers\UserLoginController::class, 'postStepOne'])->name('post-forgot-password-step-one');
    Route::post('/post-forgot-password-step-two', [App\Http\Controllers\UserLoginController::class, 'postStepTwo'])->name('post-forgot-password-step-two');
    Route::post('/post-forgot-password-step-final', [App\Http\Controllers\UserLoginController::class, 'postStepFinal'])->name('post-forgot-password-step-final');

    Route::middleware(['auth','set_locale', 'has_permission', 'is_first_login'])->group(function () {
        Route::get('profile/edit-credentials', [App\Http\Controllers\ProfileController::class, 'editCredentials']);
        Route::post('profile/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('update-set-question');
        Route::get('/force-logout', [App\Http\Controllers\UserLoginController::class, 'forceLogout']);

        Route::get('change-profile', [App\Http\Controllers\ProfileController::class, 'changeProfile']);
        Route::post('update-change-profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('update-change-profile');

        Route::get('security-question', [App\Http\Controllers\ProfileController::class, 'securityQuestion'])->name('set-security-question')->withoutMiddleware('has_permission');
        Route::post('update-security-question', [App\Http\Controllers\ProfileController::class, 'updateSecurityQuestion'])->name('update-security-question')->withoutMiddleware('has_permission');

        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/home', [App\Http\Controllers\DashboardController::class, 'profile'])->name('home')->withoutMiddleware('has_permission');
        Route::get('/balance-by-account', [App\Http\Controllers\DashboardController::class, 'getBalance'])->name('balance-by-account');
        Route::get('/money-flow', [App\Http\Controllers\DashboardController::class, 'moneyFlow'])->name('money-flow');

        Route::get('change-password', [App\Http\Controllers\ChangePasswordController::class, 'changePassword'])->name('change-password');
        Route::post('update-password', [App\Http\Controllers\ChangePasswordController::class, 'updatePassword'])->name('update-password');

        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::post('/setting_update', [App\Http\Controllers\AdminController::class, 'setting_update'])->name('setting.update');

        Route::get('/white-label', [App\Http\Controllers\AdminController::class, 'whiteLabel'])->name('white-label')->middleware('is_white_label_change_able');
        Route::post('/white-label-update', [App\Http\Controllers\AdminController::class, 'whiteLabelUpdate'])->name('white-label-update')->middleware('is_white_label_change_able');

        Route::get('/taxes', [App\Http\Controllers\TaxController::class, 'taxes'])->name('taxes');
        Route::post('/tax_update', [App\Http\Controllers\TaxController::class, 'tax_update'])->name('tax.update');
        Route::get('/addSupplierByAjax', [App\Http\Controllers\AjaxController::class, 'addSupplierByAjax'])->name('addSupplierByAjax');
        Route::get('/addCustomerByAjax', [App\Http\Controllers\AjaxController::class, 'addCustomerByAjax'])->name('addCustomerByAjax');
        Route::get('/getProductionData', [App\Http\Controllers\AjaxController::class, 'getProductionData'])->name('getProductionData');
        Route::get('/getRMStock', [App\Http\Controllers\StockController::class, 'getRMStock'])->name('getRMStock');
        Route::post('/getRMStock', [App\Http\Controllers\StockController::class, 'getRMStock'])->name('getRMStock');
        Route::get('/getLowStock', [App\Http\Controllers\StockController::class, 'getLowStock'])->name('getLowStock');
        Route::get('/stock-adjustment-list', [App\Http\Controllers\StockController::class, 'stockAdjustList'])->name('stockAdjustList');
        Route::get('/stock-adjustment', [App\Http\Controllers\StockController::class, 'stockAdjust'])->name('stockAdjust');
        Route::post('/stock-adjustment', [App\Http\Controllers\StockController::class, 'stockAdjustPost'])->name('stockAdjustPost');
        Route::get('/stock-adjustment/{id}/edit', [App\Http\Controllers\StockController::class, 'stockAdjustEdit'])->name('stockAdjustEdit');
        Route::post('/stock-adjustment/{id}/update', [App\Http\Controllers\StockController::class, 'stockAdjustUpdate'])->name('stockAdjustUpdate');
        Route::post('/rawMaterialStockCheck', [App\Http\Controllers\StockController::class, 'rawMaterialStockCheck'])->name('rawMaterialStockCheck');
        Route::post('/rawMaterialStockCheckByMaterial', [App\Http\Controllers\StockController::class, 'rawMaterialStockCheckByMaterial'])->name('rawMaterialStockCheckByMaterial');
        Route::post('/checkSingleMaterialStock', [App\Http\Controllers\StockController::class, 'checkSingleMaterialStock'])->name('checkSingleMaterialStock');
        Route::post('/downloadStockCheck', [App\Http\Controllers\StockController::class, 'downloadStockCheck'])->name('downloadStockCheck');
        Route::post('/downloadEstimateCost', [App\Http\Controllers\StockController::class, 'downloadEstimateCost'])->name('downloadEstimateCost');
        Route::post('getFinishProductRM', [App\Http\Controllers\AjaxController::class, 'getFinishProductRM'])->name('getFinishProductRM.post');
        Route::post('getFinishProductRManufacture', [App\Http\Controllers\AjaxController::class, 'getFinishProductRMForManufacture'])->name('getFinishProductRM.post');
        Route::post('getFinishProductNONI', [App\Http\Controllers\AjaxController::class, 'getFinishProductNONI'])->name('getFinishProductNONI.post');
        Route::post('getFinishProductStages', [App\Http\Controllers\AjaxController::class, 'getFinishProductStages'])->name('getFinishProductStages.post');
        Route::get('/getLowRMStock', [App\Http\Controllers\AjaxController::class, 'getLowRMStock'])->name('getLowRMStock');
        Route::get('/getSupplierDue', [App\Http\Controllers\AjaxController::class, 'getSupplierDue'])->name('getSupplierDue');
        Route::get('/getCustomerDue', [App\Http\Controllers\AjaxController::class, 'getCustomerDue'])->name('getCustomerDue');
        Route::get('/getSupplierBalance', [App\Http\Controllers\AjaxController::class, 'getSupplierBalance'])->name('getSupplierBalance');
        Route::get('/getSupplierCreditLimit', [App\Http\Controllers\AjaxController::class, 'getSupplierCreditLimit'])->name('getSupplierCreditLimit');
        Route::get('/getRMByFinishProduct', [App\Http\Controllers\AjaxController::class, 'getRMByFinishProduct'])->name('getRMByFinishProduct');
        Route::get('/checkCreditLimit', [App\Http\Controllers\AjaxController::class, 'checkCreditLimit'])->name('checkCreditLimit');
        Route::get('/sortingPage', [App\Http\Controllers\AjaxController::class, 'sortingPage'])->name('sortingPage');
        Route::post('getFinishProductDetails', [App\Http\Controllers\AjaxController::class, 'getFinishProductDetails'])->name('getFinishProductDetails.post');
        Route::post('getFifoFProduct', [App\Http\Controllers\AjaxController::class, 'getFifoFProduct'])->name('getFifoFProduct.post');
        Route::post('getFefoFProduct', [App\Http\Controllers\AjaxController::class, 'getFefoFProduct'])->name('getFefoFProduct.post');
        Route::get('getBatchControlProduct', [App\Http\Controllers\AjaxController::class, 'getBatchControlProduct'])->name('getBatchControlProduct.post');
        Route::get('getProduct', [App\Http\Controllers\AjaxController::class, 'getProduct'])->name('getProduct');

        /*resource routing*/
        Route::resource('accounts', App\Http\Controllers\AccountController::class);
        Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
        Route::resource('customers', App\Http\Controllers\CustomerController::class);
        Route::resource('rmcategories', App\Http\Controllers\RawMaterialCategoryController::class);
        Route::resource('productionstages', App\Http\Controllers\ProductionStageController::class);
        Route::resource('units', App\Http\Controllers\UnitController::class);
        Route::resource('rawmaterials', App\Http\Controllers\RawMaterialController::class);
        Route::get('/rm-price-history', [App\Http\Controllers\RawMaterialController::class, 'priceHistory'])->name('price-history');
        Route::resource('noninventoryitems', App\Http\Controllers\NonInventoryItemController::class);
        Route::resource('rawmaterialpurchases', App\Http\Controllers\RawMaterialPurchaseController::class);
        Route::get('/print_purchase_invoice/{id}', [App\Http\Controllers\RawMaterialPurchaseController::class, 'printPurchase'])->name('print_purchase_invoice');
        Route::get('/download_purchase_invoice/{id}', [App\Http\Controllers\RawMaterialPurchaseController::class, 'downloadPurchase'])->name('download_purchase_invoice');
        Route::get('/generate-purchase/{id}',[App\Http\Controllers\RawMaterialPurchaseController::class, 'generatePurchase'])->name('generate_purchase');
        Route::resource('rmwastes', App\Http\Controllers\RMWasteController::class);
        Route::resource('fpcategories', App\Http\Controllers\FPCategoryController::class);

        Route::resource('finishedproducts', App\Http\Controllers\FinishedProductController::class)->only(['index', 'create', 'store', 'destroy', 'update', 'duplicate', 'duplicate_store', 'edit']);
        Route::get('product-price-history', [App\Http\Controllers\FinishedProductController::class, 'priceHistory'])->name('product.price.history');

        Route::get('/finishedproducts/{fproducts}', [App\Http\Controllers\FinishedProductController::class, 'duplicate']);
        Route::get('/fduplicate_store', [App\Http\Controllers\FinishedProductController::class, 'duplicate_store'])->name('finiduplicate_store');
        Route::post('/fduplicate_store', [App\Http\Controllers\FinishedProductController::class, 'duplicate_store'])->name('finiduplicate_store');
        Route::resource('product-wastes', App\Http\Controllers\ProductWasteController::class);

        Route::resource('productions', App\Http\Controllers\ProductionController::class)->only(['index', 'create', 'store', 'destroy', 'update', 'duplicate', 'duplicate_store', 'edit', 'show']);

        Route::get('/productions/{fproducts}/duplicate', [App\Http\Controllers\ProductionController::class, 'duplicate']);
        Route::get('/duplicate_store', [App\Http\Controllers\ProductionController::class, 'duplicate_store'])->name('duplicate_store');
        Route::post('/duplicate_store', [App\Http\Controllers\ProductionController::class, 'duplicate_store'])->name('duplicate_store');
        Route::get('/print_productions_details/{id}', [App\Http\Controllers\ProductionController::class, 'printManufactureDetails'])->name('print_manufacture_details');
        Route::get('/download_productions_details/{id}', [App\Http\Controllers\ProductionController::class, 'downloadManufactureDetails'])->name('download_manufacture_details');
        Route::post('/changePartiallyDone', [App\Http\Controllers\ProductionController::class, 'changePartiallyDone'])->name('manufacture.changePartiallyDone');
        Route::post('/updateProducedQuantityData', [App\Http\Controllers\ProductionController::class, 'updateProducedQuantityData'])->name('manufacture.updateProducedQuantityData');
        Route::post('/updateProducedQuantity', [App\Http\Controllers\ProductionController::class, 'updateProducedQuantity'])->name('manufacture.updateProducedQuantity');
        Route::post('/production/getProductionScheduling', [App\Http\Controllers\ProductionController::class, 'getProductionScheduling'])->name('manufacture.getProductionScheduling');

        Route::get('/forecasting/order', [ForecastingController::class, 'order'])->name('forecasting.order');
        Route::get('/forecasting/order/view', [ForecastingController::class, 'orderView'])->name('forecasting.order.view');
        Route::get('/forecasting/order/print', [ForecastingController::class, 'orderPrint'])->name('forecasting.order.print');
        Route::get('/forecasting/order/download', [ForecastingController::class, 'orderDownload'])->name('forecasting.order.download');
        Route::get('/forecasting/product', [ForecastingController::class, 'product'])->name('forecasting.product');
        Route::get('/forecasting/product/view', [ForecastingController::class, 'productView'])->name('forecasting.product.view');
        Route::get('/forecasting/product/print', [ForecastingController::class, 'productPrint'])->name('forecasting.product.print');
        Route::get('/forecasting/product/download', [ForecastingController::class, 'productDownload'])->name('forecasting.product.download');

        Route::resource('sales', App\Http\Controllers\SalesController::class);
        Route::get('/sale-invoice/{id}', [App\Http\Controllers\SalesController::class, 'invoice']);
        Route::get('/download_invoice/{id}', [App\Http\Controllers\SalesController::class, 'downloadInvoice'])->name('sales.download_invoice');
        Route::get('/download_challan/{id}', [App\Http\Controllers\SalesController::class, 'downloadChallan'])->name('sales.download_challan');
        Route::get('/challan/{id}', [App\Http\Controllers\SalesController::class, 'challan'])->name('sales.challan');
        Route::get('/invoice/{id}', [App\Http\Controllers\SalesController::class, 'invoice'])->name('sales.invoice');

        Route::resource('customer-orders', App\Http\Controllers\CustomerOrdersController::class);
        Route::post('/storeUpdateInvoice', [App\Http\Controllers\CustomerOrdersController::class, 'storeUpdateInvoice'])->name('storeUpdateInvoice');
        Route::post('/storeUpdateDelivery', [App\Http\Controllers\CustomerOrdersController::class, 'storeUpdateDelivery'])->name('storeUpdateDelivery');
        Route::post('getCustomerOrderList', [App\Http\Controllers\AjaxController::class, 'getCustomerOrderList'])->name('getCustomerOrderList.post');
        Route::post('getCustomerOrderProducts', [App\Http\Controllers\AjaxController::class, 'getCustomerOrderProducts'])->name('getCustomerOrderProducts.post');

        Route::get('/customer-order-download/{id}', [App\Http\Controllers\CustomerOrdersController::class, 'downloadInvoice'])->name('customer-order-download');
        Route::get('/customer-order-print/{id}', [App\Http\Controllers\CustomerOrdersController::class, 'print'])->name('customer-order-print');

        //Attendance Module
        Route::resource('attendance', App\Http\Controllers\AttendanceController::class);
		Route::get('check-in-out', [App\Http\Controllers\AttendanceController::class, 'checkInOut'])
            ->name('check-in-out');
		Route::any('in-attendance', [App\Http\Controllers\AttendanceController::class, 'inAttendance'])
            ->name('in-attendance');
        Route::any('out-attendance', [App\Http\Controllers\AttendanceController::class, 'outAttendance'])
            ->name('out-attendance');
		Route::post('updateStatus', [App\Http\Controllers\AttendanceController::class, 'updateStatus'])
            ->name('attendance.updateStatus');

        //Expense Module
        Route::resource('expense-category', App\Http\Controllers\ExpenseCategoryController::class);
        Route::resource('expense', App\Http\Controllers\ExpenseController::class);

        //Supplier Due Payment
        Route::resource('supplier-payment', App\Http\Controllers\SupplierPaymentController::class);
		Route::get('/supplier-payment-download/{id}', [App\Http\Controllers\SupplierPaymentController::class, 'download'])->name('supplier-payment-download');
        Route::get('/supplier_payment_print/{id}', [App\Http\Controllers\SupplierPaymentController::class, 'print'])->name('supplier-payment-print');

        //Customer Due Payment
        Route::resource('customer-payment', App\Http\Controllers\CustomerPaymentController::class);
		Route::get('/customer-payment-download/{id}', [App\Http\Controllers\CustomerPaymentController::class, 'download'])->name('customer-payment-download');
        Route::get('/customer_payment_print/{id}', [App\Http\Controllers\CustomerPaymentController::class, 'print'])->name('customer-payment-print');

        //Deposit or Withdraw
        Route::resource('deposit', App\Http\Controllers\DepositController::class);

        //Payroll
        Route::resource('payroll', App\Http\Controllers\PayrollController::class);

        //All Reports
        Route::get('/rm-purchase-report', [App\Http\Controllers\ReportController::class, 'rmPurchaseReport'])->name('rm-purchase-report');
        Route::get('/rm-item-purchase-report', [App\Http\Controllers\ReportController::class, 'rmItemPurchaseReport'])->name('rm-item-purchase-report');
        Route::get('/rm-stock-report', [App\Http\Controllers\ReportController::class, 'rmStockReport'])->name('rm-stock-report');
        Route::get('/supplier-due-report', [App\Http\Controllers\ReportController::class, 'supplierDueReport'])->name('supplier-due-report');
        Route::get('/supplier-balance-report', [App\Http\Controllers\ReportController::class, 'supplierBalanceReport'])->name('supplier-balance-report');
        Route::get('/supplier-ledger', [App\Http\Controllers\ReportController::class, 'supplierLedger'])->name('supplier-ledger');
        Route::get('/production-report', [App\Http\Controllers\ReportController::class, 'productionReport'])->name('production-report');
        Route::get('/fp-production-report', [App\Http\Controllers\ReportController::class, 'fpProductionReport'])->name('fp-production-report');
        Route::get('/balance-sheet', [App\Http\Controllers\ReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('/trial-balance', [App\Http\Controllers\ReportController::class, 'trialBalance'])->name('trial-balance');
        Route::get('/fp-sale-report', [App\Http\Controllers\ReportController::class, 'fpSaleReport'])->name('fp-sale-report');
        Route::get('/fp-item-sale-report', [App\Http\Controllers\ReportController::class, 'fpItemSaleReport'])->name('fp-item-sale-report');
        Route::get('/customer-due-report', [App\Http\Controllers\ReportController::class, 'customerDueReport'])->name('customer-due-report');
        Route::get('/customer-ledger', [App\Http\Controllers\ReportController::class, 'customerLedger'])->name('customer-ledger');
        Route::get('/profit-loss-report', [App\Http\Controllers\ReportController::class, 'profitLossReport'])->name('profit-loss-report');
        Route::get('/product-profit-report', [App\Http\Controllers\ReportController::class, 'productProfitReport'])->name('product-profit-report');
        Route::get('/attendance-report', [App\Http\Controllers\ReportController::class, 'attendanceReport'])->name('attendance-report');
        Route::get('/expense-report', [App\Http\Controllers\ReportController::class, 'expenseReport'])->name('expense-report');
        Route::get('/salary-report', [App\Http\Controllers\ReportController::class, 'salaryReport'])->name('salary-report');
        Route::get('/rmwaste-report', [App\Http\Controllers\ReportController::class, 'rmwasteReport'])->name('rmwaste-report');
        Route::get('/fpwaste-report', [App\Http\Controllers\ReportController::class, 'fpwasteReport'])->name('fpwaste-report');
        Route::get('/abc-analysis-report', [App\Http\Controllers\ReportController::class, 'abcReport'])->name('abc-analysis-report');

        //Customer Order Status
        Route::get('/customer-order-status', [App\Http\Controllers\OrderStatusController::class, 'customerOrderStatus'])->name('customer-order-status');

        // Product Stock
        Route::get('/product-stock', [App\Http\Controllers\ProductStockController::class, 'productStock'])->name('product-stock');

        // Quotation Controller
        Route::resource('quotation', App\Http\Controllers\QuotationController::class);
        Route::get('/download-quotation/{id}', [App\Http\Controllers\QuotationController::class, 'downloadInvoice'])->name('download-quotation');
        Route::get('/print-quotation/{id}', [App\Http\Controllers\QuotationController::class, 'print'])->name('print-quotation');
        // Role Controller
        Route::resource('role', RoleController::class);

        // User Controller
        Route::resource('user', UserController::class);

        // Mail Settings
        Route::get('/mail-settings', [MailSettingController::class, 'index'])->name('settings.mail.index');
        Route::post('/mail-settings-update', [MailSettingController::class, 'update'])->name('settings.mail.update');

        // Purchase Generate From Customer Order
        Route::post('/purchase-generate', [App\Http\Controllers\RawMaterialPurchaseController::class, 'purchaseGenerate'])->name('purchase-generate-customer-order');

        // Production Loss
        Route::get('/production-loss', [App\Http\Controllers\ProductionLossController::class, 'index'])->name('production-loss');
        Route::post('/production-loss', [App\Http\Controllers\ProductionLossController::class, 'store'])->name('production-loss.store');
        Route::post('/production_data', [App\Http\Controllers\ProductionLossController::class, 'productionData'])->name('production-data');
        Route::get('/production-loss-report', [App\Http\Controllers\ProductionLossController::class, 'productionLossReport'])->name('production-loss-report');

        // Data Import
        Route::get('/data-import', [App\Http\Controllers\DataImportController::class, 'index'])->name('data-import');
        Route::post('/data-import', [App\Http\Controllers\DataImportController::class, 'import'])->name('data-import.import');
        Route::get('/data-import/sample', [App\Http\Controllers\DataImportController::class, 'sample'])->name('data-import.sample');

        // Multi Currency Controller
        Route::controller(App\Http\Controllers\CurrencyController::class)->group(function () {
            Route::get('/currency', 'index')->name('currency.index');
            Route::get('/currency/create', 'create')->name('currency.create');
            Route::post('/currency', 'store')->name('currency.store');
            Route::get('/currency/{id}/edit', 'edit')->name('currency.edit');
            Route::patch('/currency/{id}', 'update')->name('currency.update');
            Route::delete('/currency/{id}', 'destroy')->name('currency.destroy');
        });
    });
});
