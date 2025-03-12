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
            @lang('index.date')
            {{($startDate != '') ? getDateFormat($startDate):''}}
            {{($endDate != '') ? ' - '.getDateFormat($endDate):''}}
        </h4>
    </div>
    @endif

    <div class="box-wrapper">
        {{Form::open(['route'=>'rm-purchase-report', 'id' => "attendance_form", 'method'=>'get'])}}
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
                            <th class="text-start w-5">@lang('index.sn')</th>
                            <th class="w-15">@lang('index.reference_no')</th>
                            <th class="w-15">@lang('index.date')</th>
                            <th class="w-20">@lang('index.supplier')</th>
                            <th class="w-15">@lang('index.g_total')</th>
                            <th class="w-15">@lang('index.paid')</th>
                            <th class="w-15">@lang('index.due')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($obj && !empty($obj))
                        <?php
                        $i = count($obj);
                        $grandTotal = 0;
                        $paid = 0;
                        $due = 0;
                        ?>
                        @endif
                        @foreach($obj as $value)
                        @php
                            $grandTotal += $value->grand_total;
                            $paid += $value->paid;
                            $due += $value->due;
                        @endphp
                        <tr>
                            <td class="text-start">{{$i--}}</td>
                            <td>{{$value->reference_no}}</td>
                            <td>{{getDateFormat($value->date)}}</td>
                            <td>{{getSupplierName($value->supplier)}}</td>
                            <td>{{getAmtCustom($value->grand_total)}}</td>
                            <td>{{getAmtCustom($value->paid)}}</td>
                            <td>{{getAmtCustom($value->due)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-end">@lang('index.total')=</th>
                        <th>{{ getAmtCustom($grandTotal) }}</th>
                        <th>{{ getAmtCustom($paid) }}</th>
                        <th>{{ getAmtCustom($due) }}</th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
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