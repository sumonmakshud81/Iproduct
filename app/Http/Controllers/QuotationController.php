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
# This is QuotationController
##############################################################################
 */

namespace App\Http\Controllers;

use App\Account;
use App\AdminSettings;
use App\Customer;
use App\FinishedProduct;
use App\Http\Controllers\Controller;
use App\Quotation;
use App\QuotationDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = Quotation::where('del_status', '!=', 'Deleted')->orderBy('id', 'DESC')->get();
        $title = __('index.quotion_list');
        return view('pages.quotation.index', compact('obj', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_quotion');
        $customers = Customer::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $finishProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $fifoProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->where('stock_method', "fifo")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $obj_qo = Quotation::count();
        $ref_no = "QO-" . str_pad($obj_qo + 1, 6, '0', STR_PAD_LEFT);
        return view('pages.quotation.addEdit', compact('title', 'ref_no', 'customers', 'finishProducts', 'accounts', 'fifoProducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_no' => 'required',
            'customer_id' => 'required',
            'date' => 'required',
            'selected_product_id' => 'required|array',
        ],
            [
                'selected_product_id.required' => __('index.selected_product_id_required'),
                'reference_no.required' => __('index.reference_no_required'),
            ]
        );

        $quotation = Quotation::create([
            'reference_no' => null_check($request->reference_no),
            'customer_id' => null_check($request->customer_id),
            'date' => null_check($request->date),
            'subtotal' => null_check($request->subtotal),
            'other' => null_check($request->other),
            'grand_total' => null_check($request->grand_total),
            'discount' => null_check($request->discount),
            'note' => ($request->note),
            'user_id' => auth()->user()->id,
            'company_id' => 1,
        ]);

        $file = '';
        if ($request->hasFile('file_button')) {
            $files = $request->file('file_button');
            $fileNames = [];
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $fileNames[] = time() . "_" . $filename;
                $file->move(base_path('uploads/quotation'), $fileNames[count($fileNames) - 1]);
            }
            $quotation->file = implode(',', $fileNames);
        }
        $quotation->save();
        if (is_array($request->selected_product_id)) {
            foreach ($request->selected_product_id as $key => $value) {
                QuotationDetail::create([
                    'finishProduct_id' => null_check($value),
                    'unit_price' => null_check($request->unit_price[$key]),
                    'quantity_amount' => null_check($request->quantity_amount[$key]),
                    'total' => null_check($request->total[$key]),
                    'quotation_id' => null_check($quotation->id),
                    'company_id' => 1,
                ]);
            }
        }

        if ($request->button_click_type == 'download') {
            $title = "Quotation Invoice";
            $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

            $obj = $quotation;
            $setting = getSettingsInfo();
            $quotation_details = $obj->quotationDetails;
            $pdf = Pdf::loadView('pages.quotation.invoice', compact('title', 'obj', 'quotation_details', 'setting'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($obj->reference_no . '.pdf');
        }

        if ($request->button_click_type == 'email') {
            $this->quotationEmail($quotation);
        }

        if ($request->button_click_type == 'print') {
            return redirect()->action('QuotationController@print', ['id' => $quotation->id]);
        }

        return redirect()->route('quotation.index')->with(saveMessage());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::findOrFail(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.quotation_details');
        $obj = $quotation;
        $quotation_details = $quotation->quotationDetails;

        return view('pages.quotation.details', compact('title', 'obj', 'quotation_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation = Quotation::findOrFail(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_quotation');
        $customers = Customer::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $finishProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $fifoProducts = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->where('stock_method', "fifo")->get();
        $accounts = Account::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        $obj_qo = Quotation::count();
        $ref_no = "QO-" . str_pad($obj_qo + 1, 6, '0', STR_PAD_LEFT);
        $obj = $quotation;
        $quotation_details = $quotation->quotationDetails;
        return view('pages.quotation.addEdit', compact('title', 'ref_no', 'customers', 'finishProducts', 'accounts', 'fifoProducts', 'obj', 'quotation_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'reference_no' => 'required',
            'customer_id' => 'required',
            'date' => 'required',
            'selected_product_id' => 'required|array',
        ],
            [
                'selected_product_id.required' => __('index.selected_product_id_required'),
                'reference_no.required' => __('index.reference_no_required'),
            ]
        );

        $quotation->update([
            'reference_no' => null_check($request->reference_no),
            'customer_id' => null_check($request->customer_id),
            'date' => $request->date,
            'subtotal' => null_check($request->subtotal),
            'other' => null_check($request->other),
            'grand_total' => null_check($request->grand_total),
            'discount' => null_check($request->discount),
            'note' => $request->note,
            'user_id' => auth()->user()->id,
            'company_id' => 1,
        ]);

        $file = $quotation->file;
        if ($request->hasFile('file_button')) {
            $files = $request->file('file_button');
            $fileNames = [];
            foreach ($files as $file) {
                @unlink(base_path('uploads/quotation/' . $file));
                $filename = $file->getClientOriginalName();
                $fileNames[] = time() . "_" . $filename;
                $file->move(base_path('uploads/quotation'), $fileNames[count($fileNames) - 1]);
            }
            $quotation->file = implode(',', $fileNames);
        }else{
            $quotation->file = $file;

        }
        $quotation->save();
        if (is_array($request->selected_product_id)) {
            foreach ($request->selected_product_id as $key => $value) {

                $quotation_detail = QuotationDetail::where('finishProduct_id', $value)->where('quotation_id', $quotation->id)->first();
                if ($quotation_detail) {
                    $quotation_detail->update([
                        'unit_price' => null_check($request->unit_price[$key]),
                        'quantity_amount' => null_check($request->quantity_amount[$key]),
                        'total' => null_check($request->total[$key]),
                    ]);
                } else {
                    QuotationDetail::create([
                        'finishProduct_id' => null_check($value),
                        'unit_price' => null_check($request->unit_price[$key]),
                        'quantity_amount' => null_check($request->quantity_amount[$key]),
                        'total' => null_check($request->total[$key]),
                        'quotation_id' => null_check($quotation->id),
                        'company_id' => null_check(1),
                    ]);
                }

            }
        }

        if ($request->button_click_type == 'download') {
            $title = __('index.quotation_invoice');

            $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

            $obj = $quotation;
            $setting = getSettingsInfo();
            $quotation_details = $obj->quotationDetails;
            $pdf = Pdf::loadView('pages.quotation.invoice', compact('title', 'obj', 'quotation_details', 'setting'))->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($obj->reference_no . '.pdf');
        }

        if ($request->button_click_type == 'email') {
            $this->quotationEmail($quotation);
        }
        if ($request->button_click_type == 'print') {
            return redirect()->action('QuotationController@print', ['id' => $quotation->id]);
        }

        return redirect()->route('quotation.index')->with(saveMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->update([
            'del_status' => 'Deleted',
        ]);
        return redirect()->route('quotation.index')->with(deleteMessage());
    }

    /**
     * Download Invoice
     */
    public function downloadInvoice($id)
    {
        $id = encrypt_decrypt($id, 'decrypt');
        $title = __('index.quotation_invoice');

        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        $obj = Quotation::findOrFail($id);
        $setting = getSettingsInfo();
        $quotation_details = $obj->quotationDetails;
        $pdf = Pdf::loadView('pages.quotation.invoice', compact('title', 'obj', 'quotation_details', 'setting'));
        return $pdf->download($obj->reference_no . '.pdf');

    }

    /**
     * print invoice
     * @access public
     * @param int
     * @return void
     */
    public function print($id)
    {
        $title = __('index.quotation_invoice');

        $company = AdminSettings::orderBy('name_company_name', 'ASC')->where('del_status', "Live")->get();

        $obj = Quotation::findOrFail($id);
        $setting = getSettingsInfo();
        $quotation_details = $obj->quotationDetails;
        return view('pages.quotation.invoice', compact('title', 'obj', 'quotation_details', 'setting'));
    }

    /**
     * Quotation Email Send
     */
    private function quotationEmail($quotation)
    {
        $to_email = [$quotation->customer->email];
        if (!empty($to_email)) {
            $mail_data = [
                'to' => $to_email,
                'subject' => "Quotation for Requested Items from " . getCompanyInfo()->company_name,
                'user_name' => $quotation->customer->name,
                'details' => $quotation->quotationDetails,
                'quotation' => $quotation,
                'view' => 'quotation',
            ];
            MailSendController::sendMailToUser($mail_data);
        }
    }
}
