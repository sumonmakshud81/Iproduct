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
# This is ProductWasteController
##############################################################################
 */

namespace App\Http\Controllers;

use App\FinishedProduct;
use App\Manufacture;
use App\ProductWaste;
use App\ProductWasteItems;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductWasteController extends Controller
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
        $obj = ProductWaste::orderBy('id', 'DESC')->where('del_status', "Live")->get();
        $title = __('index.product_waste');
        return view('pages.product_waste.index', compact('title', 'obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = __('index.add_product_waste');
        $obj_rm = ProductWaste::count();

        $finished_products = FinishedProduct::orderBy('name', 'ASC')->where('current_total_stock', '>', 0)->where('del_status', "Live")->get();

        $users = User::orderBy('name', 'ASC')->where('del_status', "Live")->get();
        //generate code
        $ref_no = "PW-" . str_pad($obj_rm + 1, 6, '0', STR_PAD_LEFT);

        return view('pages.product_waste.create', compact('title', 'ref_no', 'finished_products', 'users'));
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
            'reference_no' => 'required|max:50',
            'date' => 'required|date',
            'responsible_person' => 'required|max:50',
            'grand_total' => 'required',
            'product_id' => 'required',
        ],
            [
                'reference_no.required' => 'The Reference No field is required.',
                'date.required' => 'The Date field is required.',
                'responsible_person.required' => 'The Responsible Person field is required.',
                'product_id.required' => 'At least one product is required.',
            ]
        );

        DB::beginTransaction();
        try {
            $obj = new \App\ProductWaste;
            $obj->reference_no = null_check(escape_output($request->get('reference_no')));
            $obj->date = escape_output($request->get('date'));
            $obj->responsible_person = escape_output($request->get('responsible_person'));
            $obj->total_loss = null_check(escape_output($request->get('grand_total')));
            $obj->note = escape_output($request->get('note'));
            $obj->user_id = auth()->user()->id;
            $obj->company_id = auth()->user()->company_id;
            $obj->save();
            $last_id = $obj->id;

            $product_id = $request->get('product_id');

            if (isset($product_id) && $product_id) {
                foreach ($product_id as $row => $value) {
                    $manufacture_id = null_check(escape_output($_POST['manufacture_id'][$row]));
                    $obj = new \App\ProductWasteItems;
                    $obj->finish_product_id = null_check($value);
                    $obj->manufacture_id = null_check($manufacture_id);
                    $obj->last_purchase_price = null_check(escape_output($_POST['unit_price'][$row]));
                    $obj->fp_waste_amount = null_check(escape_output($_POST['quantity_amount'][$row]));
                    $obj->loss_amount = null_check(escape_output($_POST['total'][$row]));
                    $obj->fpwaste_id = null_check($last_id);
                    $obj->company_id = auth()->user()->company_id;
                    $obj->save();

                    //update finished product stock
                    $finished_product = FinishedProduct::find($value);
                    $finished_product->current_total_stock = $finished_product->current_total_stock - escape_output($_POST['quantity_amount'][$row]);
                    $finished_product->save();
                    //update manufacture stock
                    $manufacture = Manufacture::find($manufacture_id);
                    if ($manufacture) {
                        $manufacture->product_quantity = $manufacture->product_quantity - escape_output($_POST['quantity_amount'][$row]);
                        $manufacture->save();
                    }
                }
            }
            DB::commit();
            return redirect('product-wastes')->with(saveMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(dangerMessage($e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductWaste  $productWaste
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productWaste = ProductWaste::find(encrypt_decrypt($id, 'decrypt'));
        $title = __('index.edit_product_waste');
        $obj = $productWaste;

        $finished_products = FinishedProduct::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        $product_wast_items = ProductWasteItems::orderBy('id', 'ASC')->where('fpwaste_id', $productWaste->id)->where('del_status', "Live")->get();
        $users = User::orderBy('name', 'ASC')->where('del_status', "Live")->get();

        return view('pages.product_waste.edit', compact('title', 'obj', 'finished_products', 'product_wast_items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductWaste  $productWaste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductWaste $productWaste)
    {
        try {
            request()->validate([
                'reference_no' => 'required|max:50',
                'date' => 'required|date',
                'responsible_person' => 'required|max:50',
                'grand_total' => 'required',
            ]);

            DB::beginTransaction();

            $productWaste->reference_no = null_check(escape_output($request->get('reference_no')));
            $productWaste->date = escape_output($request->get('date'));
            $productWaste->responsible_person = escape_output($request->get('responsible_person'));
            $productWaste->total_loss = null_check(escape_output($request->get('grand_total')));
            $productWaste->note = escape_output($request->get('note'));

            $productWaste->save();

            $last_id = $productWaste->id;

            //delete previous data before add

            $product_id = $request->product_id;

            if (isset($product_id) && $product_id) {
                foreach ($product_id as $row => $value) {
                    $items = ProductWasteItems::where('fpwaste_id', $productWaste->id)->where('finish_product_id', $value)->where('del_status', "Live")->first();
                    $currentQuantity = $items->fp_waste_amount;
                    $manufacture_id = null_check(escape_output($_POST['manufacture_id'][$row]));
                    $items->update(['del_status' => "Deleted"]);
                    $obj = new \App\ProductWasteItems;
                    $obj->finish_product_id = null_check($value);
                    $obj->manufacture_id = null_check($manufacture_id);
                    $obj->last_purchase_price = null_check(escape_output($_POST['unit_price'][$row]));
                    $obj->fp_waste_amount = null_check(escape_output($_POST['quantity_amount'][$row]));
                    $obj->loss_amount = null_check(escape_output($_POST['total'][$row]));
                    $obj->fpwaste_id = null_check($last_id);
                    $obj->company_id = auth()->user()->company_id;
                    $obj->save();
                    //update finished product stock
                    $finished_product = FinishedProduct::find($value);
                    $manufacture = Manufacture::find($manufacture_id);
                    if ($manufacture) {
                        if ($currentQuantity > escape_output($_POST['quantity_amount'][$row])) {
                            $finished_product->current_total_stock = $finished_product->current_total_stock + ($currentQuantity - escape_output($_POST['quantity_amount'][$row]));
                            $manufacture->product_quantity = $manufacture->product_quantity + ($currentQuantity - escape_output($_POST['quantity_amount'][$row]));
                        } else {
                            $finished_product->current_total_stock = $finished_product->current_total_stock - (escape_output($_POST['quantity_amount'][$row]) - $currentQuantity);
                            $manufacture->product_quantity = $manufacture->product_quantity - (escape_output($_POST['quantity_amount'][$row]) - $currentQuantity);

                        }
                    }
                    $finished_product->save();
                    if ($manufacture) {
                        $manufacture->save();
                    }
                }
            }

            DB::commit();

            return redirect('product-wastes')->with(updateMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(dangerMessage($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductWaste  $productWaste
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductWaste $productWaste)
    {
        //delete previous data before add
        ProductWasteItems::where('fpwaste_id', $productWaste->id)->update(['del_status' => "Deleted"]);
        $productWaste->del_status = "Deleted";
        $productWaste->save();
        return redirect('product-wastes')->with(deleteMessage());
    }
}
