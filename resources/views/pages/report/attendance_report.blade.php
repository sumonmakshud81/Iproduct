@extends('layouts.app')
@section('content')
<?php
$baseURL = getBaseURL();
$setting = getSettingsInfo();
$base_color = "#6ab04c";
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
?>
<section class="main-content-wrapper">
    @include('utilities.messages')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h2 class="top-left-header">{{isset($title) && $title?$title:''}}</h2>
                <input type="hidden" class="datatable_name" data-title="{{isset($title) && $title?$title:''}}" data-id_name="datatable">
            </div>
            <div class="col-md-offset-4 col-md-2">

            </div>
        </div>
    </section>

    @if($startDate != '' || $endDate != '')
    <div class="my-2">
        <h4 class="ir_txtCenter_mt0">
            Date:
            {{($startDate != '') ? getDateFormat($startDate):''}}
            {{($endDate != '') ? ' - '.getDateFormat($endDate):''}}
        </h4>
    </div>
    @endif

    <div class="box-wrapper">
        {{Form::open(['route'=>'attendance-report', 'id' => "attendance_form", 'method'=>'get'])}}
        <div class="row">
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    {!! Form::text('startDate', $startDate, ['class' => 'form-control customDatepicker', 'readonly'=>"", 'placeholder'=>"Start Date"]) !!}
                </div>
            </div>
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    {!! Form::text('endDate', $endDate, ['class' => 'form-control customDatepicker', 'readonly'=>"", 'placeholder'=>"End Date"]) !!}
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-2">
                <div class="form-group">
                    <button type="submit" value="submit" class="btn bg-blue-btn w-100">@lang('index.submit')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('index.sn')</th>
                            <th class="op_width_11_p">@lang('index.reference_no')</th>
                            <th class="op_width_9_p">@lang('index.date')</th>
                            <th class="op_width_18_p">@lang('index.employee')</th>
                            <th class="op_width_10_p">@lang('index.in_time')</th>
                            <th class="op_width_10_p">@lang('index.out_time')</th>
                            <th class="op_width_14_p">@lang('index.time_count')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_hours = 0;
                        if (!empty($obj)) {
                            $i = count($obj);
                            foreach ($obj as $value) {
                        ?>
                                <tr>
                                    <td class="text-start">{{ $i-- }}</td>
                                    <td>{{ $value->reference_no }}</td>
                                    <td>{{ getDateFormat($value->date) }}</td>
                                    <td>{{ getUserName($value->employee_id) }}</td>
                                    <td>{{ $value->in_time }}</td>
                                    <td>
                                        <?php
                                        if ($value->out_time == '00:00:00') {
                                            echo 'N/A<br>';
                                        } else {
                                            echo $value->out_time;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($value->out_time == '00:00:00') {
                                            echo 'N/A';
                                        } else {
                                            $to_time = strtotime($value->out_time);
                                            $from_time = strtotime($value->in_time);
                                            $minute = round(abs($to_time - $from_time) / 60, 2);
                                            $hour = round(abs($minute) / 60, 2);
                                            echo $hour . " hours";
                                            $total_hours += $hour;
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>@lang('index.total_hours')</b></td>
                            <td>{{ $total_hours . " " . 'Hours' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{!! $baseURL.'assets/datatable_custom/jquery-3.3.1.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/jquery.dataTables.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/dataTables.bootstrap4.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/dataTables.buttons.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/buttons.html5.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/buttons.print.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/jszip.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/pdfmake.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/vfs_fonts.js'!!}"></script>
<script src="{!! $baseURL.'frequent_changing/newDesign/js/forTable.js'!!}"></script>
<script src="{!! $baseURL.'frequent_changing/js/custom_report.js'!!}"></script>
@endsection