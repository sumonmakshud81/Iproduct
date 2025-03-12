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
  # This is TaxController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Tax;
use App\TaxItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function taxes1()
    {

        $tax_items = TaxItems::where('del_status', "Live")->get();
        $taxes = Tax::all();
        return view('pages.taxes', compact('tax_items', 'taxes'));
    }
    public function taxes()
    {
        $tax_items = TaxItems::all();
        $taxes = Tax::all();
        if (!$tax_items->first()) {
            $tax_items = null;
        } else {
            $tax_items = $tax_items->first();
        }
        return view('pages.taxes', compact('tax_items', 'taxes'));
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function tax_update0(Request $request)
    {
        $taxes = $request->get('taxes');
        $tax_rate = $request->get('tax_rate');
        Tax::truncate();
        foreach ($taxes as $row => $value) {
            $obj = new \App\Tax;
            $obj->tax = $value;
            $obj->tax_rate = $tax_rate[$row];
            if (isset($_POST['p_tax_id'][$row]) && $_POST['p_tax_id'][$row]) {
                $obj->id = escape_output($_POST['p_tax_id'][$row]);
            }
            $obj->save();
        }
        return redirect()->back()->with(updateMessage());
    }

    public function tax_update1(Request $request)
    {
        $taxItems = new \App\TaxItems;
        $taxItems->tax_registration_no = $request->tax_registration_no;
        $taxItems->tax_type = $request->tax_type;

        $taxItems->added_by = auth()->user()->id;
        $taxItems->save();

        Tax::where('tax_id', $taxItems->id)->update(['del_status' => "Deleted"]);

        $p_tax_id = $request->get('p_tax_id');
        foreach ($p_tax_id as $row => $value) {
            $obj = new \App\Tax;
            $obj->tax = $value;
            $obj->tax_rate = escape_output($_POST['tax_rate'][$row]);
            $obj->tax_id = $taxItems->id;
            $obj->save();
        }
        return redirect()->back()->with(updateMessage());
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function tax_update(Request $request)
    {
        request()->validate([
            'collect_tax' => 'required|max:50',
            'tax_registration_number' => 'required|max:50',
        ],
            [
                'collect_tax.required' => __('index.collect_tax_required'),
                'tax_registration_number.required' => __('index.tax_registration_number_required'),
            ]
    );
        
        $taxInfo = TaxItems::first();
        if(!$taxInfo){
            $taxInfo = new TaxItems;
        }
        $taxInfo->collect_tax = $request->collect_tax;
        $taxInfo->tax_registration_number = $request->tax_registration_number;
        $taxInfo->tax_type = $request->tax_type;
        $taxInfo->save();

        $taxes = $request->get('taxes');
        $tax_rate = $request->get('tax_rate');
        Tax::truncate();
        foreach ($taxes as $row => $value) {
            $obj = new \App\Tax;
            $obj->tax = $value;
            $obj->tax_rate = $tax_rate[$row];
            if (isset($_POST['p_tax_id'][$row]) && $_POST['p_tax_id'][$row]) {
                $obj->id = escape_output($_POST['p_tax_id'][$row]);
            }
            $obj->tax_id = $taxInfo->id;
            $obj->save();
        }
        return redirect()->back()->with(updateMessage());
    }
}
