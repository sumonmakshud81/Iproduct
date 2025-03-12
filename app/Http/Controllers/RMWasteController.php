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
  # This is RMWasteController
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\RawMaterial;
use App\RMWaste;
use App\RMWasteItem_model;
use App\Stock;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RMWasteController extends Controller
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
        $obj = RMWaste::orderBy('id','DESC')->where('del_status',"Live")->get();
        $title =  __('index.raw_material_waste');
        return view('pages.rmwaste.rmwastes',compact('title','obj'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_rm_waste');
        $obj_rm = RMWaste::count();
        $obj1 = new Stock();
        $rmaterials = $obj1->getRMStock();
        $users = User::orderBy('name','ASC')->where('del_status',"Live")->get();
        //generate code
        $ref_no = "RMW-".str_pad($obj_rm + 1, 6, '0', STR_PAD_LEFT);
        return view('pages.rmwaste.addEditRmwaste',compact('title','ref_no','rmaterials','users'));
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
            'date' => 'required|max:50',
            'responsible_person' => 'required|max:50'
        ]);

        $obj = new \App\RMWaste;
        $obj->reference_no = escape_output($request->get('reference_no'));
        $obj->date = escape_output($request->get('date'));
        $obj->responsible_person = escape_output($request->get('responsible_person'));
        $obj->total_loss = escape_output($request->get('grand_total'));
        $obj->note = escape_output($request->get('note'));
        $obj->added_by = auth()->user()->id;
        $obj->save();
        $last_id = $obj->id;

        $rm_id = $request->get('rm_id');
        if(isset($rm_id) && $rm_id) {
            foreach ($rm_id as $row => $value) {
                $obj = new \App\RMWasteItem_model;
                $obj->rmaterials_id = $value;
                $obj->last_purchase_price = escape_output($_POST['unit_price'][$row]);
                $obj->waste_amount = escape_output($_POST['quantity_amount'][$row]);
                $obj->loss_amount = escape_output($_POST['total'][$row]);
                $obj->waste_id = $last_id;;
                $obj->save();
            }
        }
        return redirect('rmwastes')->with(saveMessage());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RMWaste  $rMWaste
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rmwaste = RMWaste::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_rm_waste');
        $obj = $rmwaste;
        $rmaterials = RawMaterial::orderBy('name','ASC')->where('del_status',"Live")->get();
        $pruchse_rmaterials = RMWasteItem_model::orderBy('id','ASC')->where('waste_id',$rmwaste->id)->where('del_status',"Live")->get();
        $users = User::orderBy('name','ASC')->where('del_status',"Live")->get();
        return view('pages.rmwaste.addEditRmwaste',compact('title','obj','rmaterials','pruchse_rmaterials','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RMWaste  $rmwaste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RMWaste $rmwaste)
    {
        request()->validate([
            'reference_no' => 'required|max:50',
            'date' => 'required|max:50',
            'responsible_person' => 'required|max:50'
        ]);

        $rmwaste->reference_no = escape_output($request->get('reference_no'));
        $rmwaste->date = escape_output($request->get('date'));
        $rmwaste->responsible_person = escape_output($request->get('responsible_person'));
        $rmwaste->total_loss = escape_output($request->get('grand_total'));
        $rmwaste->note = escape_output($request->get('note'));
        $rmwaste->added_by = auth()->user()->id;
        $rmwaste->save();
        $last_id = $rmwaste->id;

        //delete previous data before add
        RMWasteItem_model::where('waste_id', $rmwaste->id)->update(['del_status' => "Deleted"]);

        $rm_id = $request->get('rm_id');
        foreach ($rm_id as $row=>$value){
            $obj = new \App\RMWasteItem_model;
            $obj->rmaterials_id = $value;
            $obj->last_purchase_price = escape_output($_POST['unit_price'][$row]);
            $obj->waste_amount = escape_output($_POST['quantity_amount'][$row]);
            $obj->loss_amount = escape_output($_POST['total'][$row]);
            $obj->waste_id = $last_id;;
            $obj->save();
        }
        return redirect('rmwastes')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RMWaste  $rmwaste
     * @return \Illuminate\Http\Response
     */
    public function destroy(RMWaste $rmwaste)
    {
        //delete previous data before add
        RMWasteItem_model::where('waste_id', $rmwaste->id)->update(['del_status' => "Deleted"]);
        $rmwaste->del_status = "Deleted";
        $rmwaste->save();
        return redirect('rmwastes')->with(deleteMessage());
    }
}
