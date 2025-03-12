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
  # This is AttendanceController Controller
  ##############################################################################
 */

namespace App\Http\Controllers;

use App\Attendance;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = request()->get('status') ?? null;
        $obj = Attendance::where('del_status',"Live");
        if($status != null){
            $obj->where('status',$status);
        }
        $obj = $obj->orderBy('id','DESC')->get();
        $title =  __('index.attendance_list');

        return view('pages.attendance.index',compact('title','obj', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  __('index.add_attendance');

        $total_attendance = Attendance::count();
        $ref_no = str_pad($total_attendance + 1, 6, '0', STR_PAD_LEFT);

        $company_id = auth()->user()->company_id;
        $employees = User::where('company_id', $company_id)->where('del_status',"Live")->get();

        return view('pages.attendance.create',compact('title', 'employees', 'ref_no'));
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
            'employee_id' => 'required_if:single,1|numeric',
            'date' => 'required|date',
            'in_time' => 'required'
        ],
        [
            'reference_no.required' => __('index.reference_no_required'),
            'employee_id.required' => __('index.employee_required'),
            'date.required' => __('index.date_required'),
            'in_time.required' => __('index.in_time_required')
        ]);
		$company_id = auth()->user()->company_id;
		if($request->has('all')){
			 $employees = User::where('company_id', $company_id)->where('del_status',"Live")->get();
			foreach($employees as $employee){
				$this->attendanceStore($request, $employee->id);
			}
		}else{
			$this->attendanceStore($request);
		}
        
        return redirect('attendance')->with(saveMessage());
    }
	
	private function attendanceStore($request, $employee_id = null)
	{
		$total_attendance = Attendance::count();
        $ref_no = str_pad($total_attendance + 1, 6, '0', STR_PAD_LEFT);
		$obj = new \App\Attendance;
        $obj->reference_no = $ref_no;
        $obj->date = escape_output($request->date);
        $obj->employee_id = $employee_id != null ? $employee_id : escape_output($request->employee_id);
        $obj->in_time = escape_output($request->in_time);
        $obj->out_time = escape_output($request->out_time);
        $obj->note = escape_output($request->note);
        $obj->user_id = auth()->user()->id;
		$obj->status = 1;
        $obj->company_id = auth()->user()->company_id;
        $obj->save();
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attendance = Attendance::find(encrypt_decrypt($id, 'decrypt'));
        $title =  __('index.edit_attendance');
        $obj = $attendance;

        $company_id = auth()->user()->company_id;
        $employees = User::where('company_id', $company_id)->where('del_status',"Live")->get();

        return view('pages.attendance.edit',compact('title','obj','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        
        request()->validate([
            'reference_no' => 'required|max:50',
            'employee_id' => 'required|numeric',
            'date' => 'required|date',
            'in_time' => 'required'
        ]);

        $attendance->reference_no = escape_output($request->reference_no);
        $attendance->date = escape_output($request->date);
        $attendance->employee_id = escape_output($request->employee_id);
        $attendance->in_time = escape_output($request->in_time);
        $attendance->out_time = escape_output($request->out_time);
        $attendance->note = escape_output($request->note);
        $attendance->save();
        return redirect('attendance')->with(updateMessage());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->del_status = "Deleted";
        $attendance->save();
        return redirect('attendance')->with(deleteMessage());
    }
	
	public function checkInOut() {
        $data = Attendance::where('employee_id', Auth::id());
        $from_date  = request()->get('from_date');
        $to_date  = request()->get('to_date');

        if (!empty($from_date) && empty($to_date)) {
            $data->whereDate('date',date($from_date));
        }
        if (!empty($to_date) && empty($from_date)) {
            $data->whereDate('date','<=',date($to_date));
        }
        if (!empty($from_date) AND !empty($to_date)) {
            $data->whereBetween('date', [date($from_date), date($to_date)]);
        }

        $data->orderBy('id','asc');
        $results = $data->get();
        return view('pages.attendance.check_in_out',compact('results','from_date','to_date'));
      }
	
	/**
      * Check in attendance
      */
     public function inAttendance(){
        $count = Attendance::count();
        $code = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
        $in_time =  Carbon::now()->format('H:i');
        $data = new  Attendance();
        $data->reference_no = $code;
        $data->date = now();
        $data->employee_id = Auth::id();
        $data->in_time = $in_time;
		$data->out_time = '00:00:00';
        $data->save();
        return redirect()->back()->with(saveMessage("Work hour has been started successfully!"));

    }

    /**
     * Check out attendance
     */
    public function outAttendance(){
        $data = Attendance::where('employee_id',Auth::id())
            ->where('out_time', '00:00:00')->first();
        $current_time = Carbon::now()->format('H:i');
        $data->out_time = $current_time;
        $data->save();
        return redirect()->back()->with(saveMessage("Work hour end successfully!"));

    }
	
	public function updateStatus(Request $request)
	{
		$id = $request->id;
		$status = $request->status;
		
		$attendance = Attendance::where('id',$id)->first();
		$attendance->status = $status;
		$attendance->save();
		
		return redirect('attendance')->with(updateMessage());
	}
}
