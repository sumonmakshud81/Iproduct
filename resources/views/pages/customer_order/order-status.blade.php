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
                                <th class="width_10_p">@lang('index.quotation')</th>
                                <th class="width_10_p">@lang('index.waiting_for_confirmation')</th>
                                <th class="width_10_p">@lang('index.waiting_for_production')</th>
                                <th class="width_10_p">@lang('index.in_production')</th>
                                <th class="width_10_p">@lang('index.ready_for_shipment')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center align-baseline">
                                    @foreach ($order_quotation as $value)
                                        <div class="card border-bottom mb-2">
                                            <div class="card-body text-left fs-6">
                                                <p>#{{ $value->reference_no }}</p>
                                                <ul>
                                                    @foreach ($value->details as $details)
                                                        <li>{{ $details->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                                <p>@lang('index.total') : <strong class="text-right">{{ $value->total_amount }}</strong></p>
                                                <p>@lang('index.cost') : <strong class="text-right">{{ $value->total_cost }}</strong></p>
                                                <p>@lang('index.profit') : <strong class="text-right">{{ $value->total_profit }}</strong></p>
                                                <p>@lang('index.delivery_date') : <strong class="text-right">{{ $value->delivery_date }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-center align-baseline">
                                    @foreach ($waiting_for_confirmation as $value)
                                        <div class="card border-bottom mb-2">
                                            <div class="card-body text-left fs-6">
                                                <p>#{{ $value->reference_no }}</p>
                                                <ul>
                                                    @foreach ($value->details as $details)
                                                        <li>{{ $details->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                                <p>@lang('index.total') : <strong class="text-right">{{ $value->total_amount }}</strong></p>
                                                <p>@lang('index.cost') : <strong class="text-right">{{ $value->total_cost }}</strong></p>
                                                <p>@lang('index.profit') : <strong class="text-right">{{ $value->total_profit }}</strong></p>
                                                <p>@lang('index.delivery_date') : <strong class="text-right">{{ $value->delivery_date }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-center align-baseline">
                                    @foreach ($waiting_for_production as $value)
                                        <div class="card border-bottom mb-2">
                                            <div class="card-body text-left fs-6">
                                                <p>#{{ $value->reference_no }}</p>
                                                <ul>
                                                    @foreach ($value->details as $details)
                                                        <li>{{ $details->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                                <p>@lang('index.total') : <strong class="text-right">{{ $value->total_amount }}</strong></p>
                                                <p>@lang('index.cost') : <strong class="text-right">{{ $value->total_cost }}</strong></p>
                                                <p>@lang('index.profit') : <strong class="text-right">{{ $value->total_profit }}</strong></p>
                                                <p>@lang('index.delivery_date') : <strong class="text-right">{{ $value->delivery_date }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-center align-baseline">
                                    @foreach ($in_production as $value)
                                        <div class="card border-bottom mb-2">
                                            <div class="card-body text-left fs-6">
                                                <p>#{{ $value->reference_no }}</p>
                                                <ul>
                                                    @foreach ($value->details as $details)
                                                        <li>{{ $details->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                                <p>@lang('index.total') : <strong class="text-right">{{ $value->total_amount }}</strong></p>
                                                <p>@lang('index.cost') : <strong class="text-right">{{ $value->total_cost }}</strong></p>
                                                <p>@lang('index.profit') : <strong class="text-right">{{ $value->total_profit }}</strong></p>
                                                <p>@lang('index.delivery_date') : <strong class="text-right">{{ $value->delivery_date }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="text-center align-baseline">
                                    @foreach ($ready_for_shipment as $value)
                                        <div class="card border-bottom mb-2">
                                            <div class="card-body text-left fs-6">
                                                <p>#{{ $value->reference_no }}</p>
                                                <ul>
                                                    @foreach ($value->details as $details)
                                                        <li>{{ $details->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                                <p>@lang('index.total') : <strong class="text-right">{{ $value->total_amount }}</strong></p>
                                                <p>@lang('index.cost') : <strong class="text-right">{{ $value->total_cost }}</strong></p>
                                                <p>@lang('index.profit') : <strong class="text-right">{{ $value->total_profit }}</strong></p>
                                                <p>@lang('index.delivery_date') : <strong class="text-right">{{ $value->delivery_date }}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
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
