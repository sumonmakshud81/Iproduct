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


    <div class="box-wrapper">
        {{Form::open(['route'=>'customer-ledger', 'id' => "attendance_form", 'method'=>'get'])}}
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
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    {!! Form::select('customer_id', $customers, $customer_id, ['class' => 'form-control select2', ]) !!}
                </div>
            </div>
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    <select class="form-control select2 op_width_100_p" id="type" name="type">
                        <option value="All" {{ ($type == "All") ? 'selected="selected"':'' }}>@lang('index.all')</option>
                        <option value="Debit" {{ ($type == "Debit") ? 'selected="selected"':'' }}>@lang('index.debit')</option>
                        <option value="Credit" {{ ($type == "Credit") ? 'selected="selected"':'' }}>@lang('index.credit')</option>
                    </select>
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
                            <th>@lang('index.date')</th>
                            <th>@lang('index.transaction_type')</th>
                            <th>@lang('index.transaction_no')</th>
                            <th>@lang('index.debit')</th>
                            <th>@lang('index.credit')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $creditBalance = 0;
                        $debitBalance = 0;
                        $closing_result = 0;
                    @endphp
                        @foreach ($customerLedger as $ledger)
                            @php
                                $creditBalance += $ledger['credit'];
                                $debitBalance += $ledger['debit'];
                                $closing_result = $debitBalance - $creditBalance;
                            @endphp
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ getDateFormat($ledger['date']) }}</td>
                                <td>{{ $ledger['type'] }}</td>
                                <td>{{ $ledger['transaction_no'] }}</td>
                                <td>{{ getAmtCustom($ledger['debit']) }}</td>
                                <td>{{ getAmtCustom($ledger['credit']) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-end">@lang('index.closing_balance')=</th>                           
                            <th>{{ ($closing_result > 0) ? getAmtCustom(abs($closing_result)):0 }}</th>
                            <th>{{ ($closing_result < 0) ? getAmtCustom(abs($closing_result)):0 }}</th>
                        </tr>
                    </tbody>
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