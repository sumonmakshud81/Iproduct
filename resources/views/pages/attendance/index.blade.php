@extends('layouts.app')
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
        @include('utilities.messages')
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h2>
                    <input type="hidden" class="datatable_name" data-title="{{ isset($title) && $title ? $title : '' }}"
                        data-id_name="datatable">
                </div>
                <div class="col-md-offset-4 col-md-2">

                </div>
            </div>
        </section>


        <div class="box-wrapper">  
            <form action="{{ request()->url() }}" class="updateStatus mb-2" method="get">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-select" name="status" id="status_input" aria-label="Default select example" onchange="this.form.submit()">
                            <option selected>Select Status</option>
                            <option value="1" {{ $status == 1 ? 'selected' : '' }}>Present</option>
                            <option value="0" {{ $status == 0 ? 'selected' : '' }}>Absent</option>
                        </select>
                    </div>
                </div>
            </form>          
            <div class="table-box">				
                <!-- /.box-header -->                
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped attendance-table">
                        <thead>
                            <tr>
                            <tr>
                                <th class="width_1_p">@lang('index.sn')</th>
                                <th class="width_5_p">@lang('index.reference_no')</th>
                                <th class="width_10_p">@lang('index.date')</th>
                                <th class="width_10_p">@lang('index.employee')</th>
                                <th class="width_10_p">@lang('index.in_time')</th>
                                <th class="width_10_p">@lang('index.out_time')</th>
                                <th class="width_10_p">@lang('index.update_time')</th>
								<th class="width_10_p">@lang('index.status')</th>
                                <th class="width_10_p">@lang('index.time_count')</th>
                                <th class="width_10_p">@lang('index.note')</th>
                                <th class="width_10_p">@lang('index.added_by')</th>
                                <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($obj && !empty($obj))
                                @php
                                $i = count($obj);
                                @endphp
                            @endif
                            @foreach ($obj as $value)
                                <tr>
                                    <td>{{ $i-- }}</td>
                                    <td>{{ $value->reference_no }}</td>
                                    <td>{{ getDateFormat($value->date) }}</td>
                                    <td>{{ $value->user->name }}</td>
                                    <td>{{ $value->in_time }}</td>
                                    <td>{{ $value->out_time ?? 'N/A' }}</td>
                                    <td>
                                    @if (routePermission('attendance.edit'))
                                    <a href="{{ url('attendance/' . encrypt_decrypt($value->id, 'encrypt') . '/edit') }}">@lang('index.update_time')
                                    @endif
                                    </a>
                                    </td>
									<td>
										<form action="{{ route('attendance.updateStatus') }}"
                                                    class="updateStatus" method="post">
											@csrf
											<input type="hidden" name="id" value="{{ $value->id }}" />
											<select class="form-select" name="status" id="status_input" aria-label="Default select example" onchange="this.form.submit()">
											  <option selected>Select Status</option>
											  <option value="1" {{ $value->status == 1 ? 'selected' : '' }}>Present</option>
											  <option value="0" {{ $value->status == 0 ? 'selected' : '' }}>Absent</option>
											</select>
										</form>
									</td>
                                    <td>
                                        @if($value->out_time == '00:00:00')
                                            N/A
                                        @else
                                            @php
                                                $get_hour = getTotalHour($value->out_time, $value->in_time);
                                            @endphp
                                            @if(isset($get_hour) && $get_hour)
                                                {{ $get_hour }} Hour(s)
                                            @else
                                                0 Hour(s)
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ safe($value->note) }}</td>
                                    <td>{{ $value->addedBy->name ?? 'N/A' }}</td>
                                    <td class="ir_txt_center">
                                        @if (routePermission('attendance.delete'))
                                            <a href="#" class="delete button-danger"
                                                data-form_class="alertDelete{{ $value->id }}" type="submit"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('index.delete')">
                                                <form action="{{ route('attendance.destroy', $value->id) }}"
                                                    class="alertDelete{{ $value->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i class="fa fa-trash tiny-icon"></i>
                                                </form>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>

    </section>
@endsection
@section('script')
    <script src="{!! $baseURL . 'assets/datatable_custom/jquery-3.3.1.js' !!}"></script>	
	<script src="{!! $baseURL.'assets/plugins/local/jquery.timepicker.min.js'!!}"></script>
	<link rel="stylesheet" href="{!! $baseURL.'assets/plugins/local/jquery.timepicker.min.css'!!}">
	
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
