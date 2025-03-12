@extends('layouts.app')
@section('content')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    ?>
    <section class="main-content-wrapper">
        @include('utilities.messages')
        <section class="content-header">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h3 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h3>
                </div>
                <div class="col-sm-12 mb-2 col-md-3">
                </div>
                <div class="col-sm-12 mb-2 col-md-3">
                    <strong class="margin_10" id="stockValue"></strong>
                </div>
            </div>
        </section>


        <div class="box-wrapper">

            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <input type="hidden" class="datatable_name" data-filter="no" data-title="RM Stock"
                        data-id_name="datatable">
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="w-5 text-start">@lang('index.sn')</th>
                                <th class="w-10">@lang('index.code')</th>
                                <th class="w-30">@lang('index.material_name')</th>
                                <th class="w-20">@lang('index.available_quantity')</th>                                
                                <th class="w-20">@lang('index.rate_per_unit') <i data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="@lang('index.rm_stock_price_calculate')"
                                        class="fa fa-question-circle base_color c_pointer"></i></th>
                                <th class="w-15">@lang('index.value')(@lang('index.available_quantity') X @lang('index.rate_per_unit'))</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($obj && !empty($obj))
                                <?php
                                $i = count($obj);
                                ?>
                            @endif
                            <?php
                            $totalStock = 0;
                            $grandTotal = 0;
                            $grandT = 0;
                            ?>
                            @foreach ($obj as $value)
                                <?php
                                
                                $totalStock = $value->total_purchase - $value->total_rm_waste;
                                $totalStock = getAdjustData($totalStock, $value->id);
                                $last_p_price = getLastThreePurchasePrice($value->id);
                                if ($last_p_price) {
                                    $totalTK = $totalStock * $last_p_price;
                                }
                                if ($totalStock >= 0) {
                                    if ($value->conversion_rate == 0 || $value->conversion_rate == '') {
                                        $grandTotal = $grandTotal + $totalStock * ($last_p_price / 1);
                                    } else {
                                        $grandTotal = $grandTotal + $totalStock * round($last_p_price / $value->conversion_rate, 2);
                                    }
                                }
                                if ($value->conversion_rate == 0 || $value->conversion_rate == '') {
                                    $total = $totalStock * ($last_p_price / 1);
                                } else {
                                    $total = $totalStock * round($last_p_price / $value->conversion_rate, 2);
                                }
                                
                                if ($value->conversion_rate == 0 || $value->conversion_rate == '') {
                                    $total_sale_unit = getRawMaterialUseInManufacture($value->id);
                                } else {
                                    $total_sale_unit = getRawMaterialUseInManufacture($value->id);
                                }
                                ?>
                                <tr>
                                    <td class="text-start">{{ $i-- }} </td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        {{ $value->current_stock_final }}@if ($value->consumption_check != 1){{ str_replace(' ', '', getRMUnitById($value->unit)) }}@endif
                                    </td>
                                    @php

                                        $grandT += $last_p_price * $value->current_stock;
                                    @endphp                                    
                                    <td>{{ getCurrency(number_format($last_p_price, 2, '.', ',')) }}</td>
                                    <td>{{ getCurrency(number_format($last_p_price * $value->current_stock, 2, '.', ',')) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <th class="c_center"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>@lang('index.total')=</th>
                            <th>{{ getCurrency(number_format($grandT, 2, '.', ',')) }} <i data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="@lang('index.rm_stock_price_calculate')"
                                    class="fa fa-question-circle base_color c_pointer"></i></th>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
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
    <script src="{!! $baseURL . 'frequent_changing/js/lowStock.js' !!}"></script>
@endsection
