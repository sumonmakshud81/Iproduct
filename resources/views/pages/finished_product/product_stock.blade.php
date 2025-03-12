@extends('layouts.app')
@section('content')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    ?>
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

            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-1 text-left">@lang('index.sn')</th>
                                <th class="width_10_p">@lang('index.name')(@lang('index.code'))</th>
                                <th class="width_10_p">@lang('index.category')</th>
                                <th class="width_10_p">@lang('index.stock_segmentation')</th>
                                <th class="width_10_p text-center">@lang('index.available_quantity')</th>
                                <th class="width_10_p">@lang('index.sale_price') <i data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="@lang('index.product_stock_price_calculate')"
                                        class="fa fa-question-circle base_color c_pointer"></i></th>
                                <th class="width_10_p">@lang('index.value') (@lang('index.available_quantity') x @lang('index.sale_price'))</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($obj && !empty($obj))
                                <?php
                                $i = count($obj);
                                $grandTotal = 0;
                                ?>
                            @endif
                            @foreach ($obj as $value)
                                <tr>
                                    <td class="text-left">{{ $i-- }}</td>
                                    <td>{{ $value->name }}({{ $value->code }})</td>
                                    <td>{{ getFPCategory($value->category) }}</td>
                                    <td>@if(count(expiryDateProduct($value->id)) > 0)
                                        <div id="stockInnerTable">
                                            <ul>
                                                <li>
                                                    <div class="w-50">@lang('index.expiry_date')</div>
                                                    <div class="w-50 text-end">@lang('index.quantity')</div>
                                                </li>
                                                @foreach (expiryDateProduct($value->id) as $history)
                                                    <li>
                                                        <div class="stock-alert-color w-50">
                                                            {{ getDateFormat(expireDate($history->complete_date, $history->expiry_days)) }}
                                                        </div>
                                                        <div class="stock-alert-color w-50 text-end">
                                                            {{ $history->product_quantity }}@lang('index.pcs')
                                                        </div>                                                        
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                        @elseif(count(batchControlProduct($value->id)) > 0)
                                        <div id="stockInnerTable">
                                            <ul>
                                                <li>
                                                    <div class="w-50">@lang('index.batch_no')</div>
                                                    <div class="w-50 text-end">@lang('index.quantity')</div>
                                                </li>
                                                @foreach (batchControlProduct($value->id) as $history)
                                                    <li>
                                                        <div class="stock-alert-color w-50">
                                                            {{ $history->batch_no }}
                                                        </div>
                                                        <div class="stock-alert-color w-50 text-end">
                                                            {{ $history->product_quantity }}@lang('index.pcs')
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @else
                                        @lang('index.not_available')
                                        @endif
                                    </td>
                                    <td class="text-center">{{ getCurrentProductStock($value->id) }}@lang('index.pcs')</td>
                                    <td>{{ getAmtCustom(productSalePrice($value->id)) }}</td>
                                    <td>
                                        <?php
                                        $total = $value->current_total_stock * productSalePrice($value->id);
                                        $grandTotal += $total;
                                        echo getAmtCustom($total);
                                        ?>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <th class="c_center"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>@lang('index.total')=</th>
                            <th>{{ getAmtCustom($grandTotal) }}</th>
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
@endsection
