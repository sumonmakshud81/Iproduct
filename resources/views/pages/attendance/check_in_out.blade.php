@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ asset('frequent_changing/css/hide_search.css') }}">
@endpush
@section('content')
	@php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    @endphp
    <section class="main-content-wrapper">
    <h2 class="d-none">&nbsp;</h2>
        <div class="alert-wrapper">
            @include('utilities.messages')
        </div>

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" class="datatable_name" data-title="Users" data-id_name="datatable">
                </div>
                <div class="col-md-offset-2 col-md-4">
                    <div class="btn_list m-right d-flex">
                    </div>
                </div>
            </div>
        </section>

        <div class="box-wrapper">
                <div class="row text-center">
                    @php
                        $attendance = App\Attendance::where('employee_id',Auth::id())->whereNotNull('in_time')->where('out_time', '00:00:00')->first();
                        if(App\Attendance::where('employee_id',Auth::id())->whereNotNull('in_time')->where('out_time', '00:00:00')->exists()){
                            $logIn = false;
                            $logOut = true;
                        } else{
                            $logIn = true;
                            $logOut = false;
                        }
                    @endphp

                    @if($logIn)
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <a href="{{ route('in-attendance') }}" class="btn bg-blue-btn w-100 search-btn top ">
                                Check In
                            </a>
                        </div>
                        <div class="col-md-4"></div>
                    @endif

                    @if($logOut)
                        <h3>Last Check In {{ date("h:i:s", strtotime($attendance->in_time)) }}</h3>
                        <div class="col-md-4"></div>
                        <div class="col-md-4 pt-0">
                            <a href="{{ route('out-attendance') }}" class="btn btn-md bg-red-btn text-left">
                                Check Out
                            </a>
                        </div>
                        <div class="col-md-4"></div>
                    @endif
                </div>
            <hr>
            <div class="table-box">
                <!-- If admin want to search attendance from manual list -->
                <form action="{{ route('check-in-out') }}" method="GET">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="text" name="from_date" class="form-control customDatepicker" autocomplete="off" value="{{ $from_date ?? "" }}" placeholder="From Date" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="text" name="to_date" class="form-control customDatepicker" autocomplete="off" value="{{ $to_date ?? "" }}" placeholder="To Date" readonly>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 mb-2 mt-3">
                            <div class="form-group">
                                <button type="submit" class="btn bg-blue-btn w-100 search-btn top mt-2" id="go">
                                   Search                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <!-- Attendance search from end -->
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead>
                        <tr>
                            <th class="ir_w_1">@lang('index.sn')</th>
                            <th class="ir_w_12">@lang('index.date')</th>
                            <th class="ir_w_12">@lang ('index.in_time')</th>
                            <th class="ir_w_7">@lang ('index.out_time')</th>
                            <th class="ir_w_7">@lang ('index.time_count')</th>
                            <th class="ir_w_7">@lang ('index.note')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $data)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ getDateFormat($data->date) }}</td>
                                    <td>{{ $data->in_time }}</td>
                                    <td>{{ $data->out_time ?? 'N/A' }}</td>
                                    <td>
										@if($data->out_time == '00:00:00')
                                            N/A
                                        @else
                                            @php
                                                $get_hour = getTotalHour($data->out_time, $data->in_time);
                                            @endphp
                                            @if(isset($get_hour) && $get_hour)
                                                {{ $get_hour }} Hour(s)
                                            @else
                                                0 Hour(s)
                                            @endif
                                        @endif
									</td>
                                    <td>{{ $data->note ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{!! $baseURL . 'assets/datatable_custom/jquery-3.3.1.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jquery.dataTables.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.bootstrap4.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.buttons.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.html5.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.print.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jszip.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/pdfmake.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/vfs_fonts.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/newDesign/js/forTable.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/custom_report.js' !!}"></script>
@endsection
