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
        {{Form::open(['route'=>'profit-loss-report', 'id' => "attendance_form", 'method'=>'get'])}}
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
                <table class="table" id="datatable" data-ordering="false">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="ir_w_100 text-center">
                                <h4 class="ir_txt_center">@lang('index.profit_loss_report')</h4>
                                @if($startDate != '' || $endDate != '')
                                <h4 class="ir_txtCenter_mt0">
                                    @lang('index.date')
                                    {{($startDate != '') ? getDateFormat($startDate):''}}
                                    {{($endDate != '') ? ' - '.getDateFormat($endDate):''}}
                                </h4>
                                @endif
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="ir_w_40">@lang('index.total_sales') (@lang('index.paid_unpaid')) (@lang('index.inc_tax'))</td>
                            <td>{{ isset($totalSales) ? getAmtCustom($totalSales) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>@lang('index.total_cost_goods_sold') (@lang('index.based_on_average_price'))</td>
                            <td>{{ isset($totalCostOfGoodsSold) ? getAmtCustom($totalCostOfGoodsSold) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>@lang('index.total_cost_of_transferred_item') (@lang('index.based_on_average_price'))</td>
                            <td>{{ isset($totalCostOfTransferred) ? getAmtCustom($totalCostOfTransferred) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr class="profit_txt">
                            <td>4</td>
                            <td>@lang('index.gross_profit')</td>
                            <td> {{ isset($grossProfit) ? getAmtCustom($grossProfit) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>@lang('index.total_tax')</td>
                            <td>{{ isset($totalTaxAmount) ? getAmtCustom($totalTaxAmount) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>@lang('index.total_waste')</td>
                            <td>{{ isset($total_waste) ? getAmtCustom($total_waste) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>@lang('index.total_expense')</td>
                            <td>{{ isset($total_expense) ? getAmtCustom($total_expense) : getAmtCustom(0) }}</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>@lang('index.total_refund')</td>
                            <td>{{ isset($saleReportByDate['profit_8_1']) ? getAmt($saleReportByDate['profit_8_1']) : getAmt(0) }}</td>
                        </tr>
                        <tr class="profit_txt">
                            <td>9</td>
                            <td>@lang('index.net_profit')</td>
                            <td>{{ isset($netProfit) ? getAmtCustom($netProfit) : getAmtCustom(0) }}</td>
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