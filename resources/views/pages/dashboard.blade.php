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
    <link rel="stylesheet" href="{!! $baseURL . 'assets/dist/css/custom/dashboard.css' !!}">
    <input type="hidden" name="six_month" id="six_month_value" value="@lang('index.6_month')">
    <input type="hidden" name="one_year" id="one_year_value" value="@lang('index.12_month')">
    <input type="hidden" name="purchase_text" id="purchase_text_value" value="@lang('index.purchase')">
    <input type="hidden" name="supplier_due_payment_text" id="supplier_due_payment_text_value" value="@lang('index.supplier_payment')">
    <input type="hidden" name="non_inventory_cost_text" id="non_inventory_cost_text_value" value="@lang('index.non_inventory_cost')">
    <input type="hidden" name="sale_text" id="sale_text_value" value="@lang('index.sale')">
    <input type="hidden" name="customer_due_received_text" id="customer_due_received_text_value" value="@lang('index.customer_due_received')">
    <input type="hidden" name="expense_text" id="expense_text_value" value="@lang('index.expense')">
    <input type="hidden" name="payroll_text" id="payroll_text_value" value="@lang('index.payroll')">

    <!-- Main content -->
    <section class="main-content-wrapper dashboard-wrapper">
        @if (appMode() == 'demo')
            <section class="alert-wrapper">
                <div class="alert alert-danger alert-dismissible show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-body">
                        <i class="m-right fa fa-triangle-exclamation"></i> @lang('index.demo_instruction')
                    </div>
                </div>
            </section>
        @endif
        @include('utilities.messages')

        <section class="content-header dashboard_content_header my-2">
            <h3 class="top-left-header">
                <span>@lang('index.dashboard')</span>
            </h3>
        </section>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <a class="text-dec-none" href="{{ route('finishedproducts.index') }}">
                    <div class="small-box box4column">
                        <div class="inner b-l-primary">
                            <p>@lang('index.total_product')</p>
                            <h3>{{ numberFormat($total['product']) }}</h3>
                        </div>
                        <div class="icon">
                            <img src="{!! $baseURL . 'frequent_changing/images/products.png' !!}" alt=""
                                class="">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a class="text-dec-none" href="{{ route('rawmaterials.index') }}">
                    <div class="small-box box4column">
                        <div class="inner b-l-secondary">
                            <p>@lang('index.total_rm')</p>
                            <h3>{{ numberFormat($total['rm']) }}</h3>
                        </div>
                        <div class="icon">
                            <img src="{!! $baseURL . 'frequent_changing/images/raw-materials.png' !!}" alt=""
                                class="">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a class="text-dec-none" href="{{ route('suppliers.index') }}">
                    <div class="small-box box4column">
                        <div class="inner b-l-danger">
                            <p>@lang('index.total_supplier')</p>
                            <h3>{{ numberFormat($total['supplier']) }}
                            </h3>
                        </div>
                        <div class="icon">
                            <img src="{!! $baseURL . 'frequent_changing/images/supplier.png' !!}" alt=""
                                class="">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a class="text-dec-none" href="{{ route('customers.index') }}">
                    <div class="small-box box4column">
                        <div class="inner b-l-success">
                            <p>@lang('index.total_customer')</p>
                            <h3>{{ numberFormat($total['customer']) }}</h3>
                        </div>
                        <div class="icon">
                            <img src="{!! $baseURL . 'frequent_changing/images/customer.png' !!}" alt=""
                                class="">
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row grap-row">
            <div class="col-xl-8 col-xs-12">
                <div class="card graph_card">
                    <div class="d-flex justify-content-between">
                        <h3 class="custom-card-title mb-0 mt-2 ms-3"><iconify-icon
                                icon="solar:chart-broken"></iconify-icon><span class="ms-2">@lang('index.money_flow_comparison')</span>
                            (<span id="month_span">@lang('index.six_month')</span>)
                        </h3>
                        <div class="me-2 w-25 mt-2">
                            <select name="filter_chart_name" id="filter_chart_month" class="form-control me-2">
                                <option value="6">@lang('index.6_month')</option>
                                <option value="12">@lang('index.12_month')</option>
                            </select>
                        </div>
                    </div>
                    {{-- For Graph, used inline css --}}
                    <div class="card-body">
                        <div class="table-responsive responsive-table graphTable">
                            <canvas id="dashboardGraph"></canvas>
                        </div>
                    </div>
                </div>
                <!--table-responsive-->
            </div>
            <div class="col-xl-4 col-xs-12">
                <div class="card">
                    <div class="d-flex justify-content-between">
                        <h3 class="custom-card-title mb-0 mt-2 ms-3"><iconify-icon
                                icon="solar:pie-chart-2-broken"></iconify-icon><span
                                class="ms-2">@lang('index.account_balance')</span>
                        </h3>

                    </div>

                    <div class="card-body">
                        <div class="responsive-table balanceGraph">
                            <div id="balanceGraph"></div>
                        </div>
                    </div>
                </div>
                <!--table-responsive-->
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:database-broken"></iconify-icon><span
                                class="ms-2">@lang('index.running_productions')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_1">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.reference_no')</th>
                                        <th class="width_13_p">@lang('index.product')</th>
                                        <th class="width_13_p">@lang('index.start_date')</th>
                                        <th class="width_20_p">@lang('index.consumed_time')</th>
                                        <th class="width_26_p">@lang('index.manufacture_cost')</th>
                                        <th class="width_13_p">@lang('index.sale_price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($running_production as $value)
                                        <tr>
                                            <td>{{ safe($value->reference_no) }}</td>
                                            <td>{{ safe(getProductNameById($value->product_id)) }}</td>
                                            <td>{{ safe(getDateFormat($value->start_date)) }}</td>
                                            <td>{{ safe($value->consumed_time) }}</td>
                                            <td>{{ safe(getCurrency($value->mtotal_cost)) }}</td>
                                            <td>{{ safe(getCurrency($value->msale_price)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:database-broken"></iconify-icon><span
                                class="ms-2">@lang('index.running_customer_order')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_2">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable_2" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.reference_no')</th>
                                        <th class="width_13_p">@lang('index.customer')</th>
                                        <th class="width_13_p">@lang('index.delivery_date')</th>
                                        <th class="width_20_p">@lang('index.total_amount')</th>
                                        <th class="width_26_p">@lang('index.total_cost')</th>
                                        <th class="width_13_p">@lang('index.total_profit')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($running_order as $value)
                                        <tr>
                                            <td>{{ safe($value->reference_no) }}</td>
                                            <td>{{ safe(getCustomerNameById($value->customer_id)) }}</td>
                                            <td>{{ safe(getDateFormat($value->delivery_date)) }}</td>
                                            <td>{{ safe(getCurrency($value->total_amount)) }}</td>
                                            <td>{{ safe(getCurrency($value->total_cost)) }}</td>
                                            <td>{{ safe(getCurrency($value->total_profit)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:danger-broken"></iconify-icon><span
                                class="ms-2">@lang('index.low_raw_materials_in_stocks')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_3">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable_3" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.code')</th>
                                        <th class="width_13_p">@lang('index.material_name')</th>
                                        <th class="width_13_p">@lang('index.current_stock')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowRawMaterialStocks as $value)
                                        <tr>
                                            <td>{{ safe($value->code) }}</td>
                                            <td>{{ safe($value->name) }}</td>
                                            <td>{{ $value->total_stock }} {{ getRMUnitById($value->unit) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:close-circle-broken"></iconify-icon><span
                                class="ms-2">@lang('index.close_to_expire_finished_product')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_4">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable_4" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.manufacture')</th>
                                        <th class="width_1_p">@lang('index.name')</th>
                                        <th class="width_13_p">@lang('index.code')</th>
                                        <th class="width_13_p">@lang('index.expiry_date')</th>
                                        <th class="width_13_p">@lang('index.status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mergedProducts as $value)
                                        <tr>
                                            <td>{{ safe($value->reference_no) }}</td>
                                            <td>{{ safe($value->product->name) }}</td>
                                            <td>{{ safe($value->product->code) }}</td>
                                            <td>{{ safe(getDateFormat($value->expiry_date)) }}</td>
                                            <td>{{ safe($value->status) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:card-send-broken"></iconify-icon><span
                                class="ms-2">@lang('index.supplier_receivables')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_5">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable_5" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.date')</th>
                                        <th class="width_13_p">@lang('index.supplier')</th>
                                        <th class="width_13_p">@lang('index.amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplierPayments as $value)
                                        <tr>
                                            <td>{{ safe(getDateFormat($value->date)) }}</td>
                                            <td>{{ safe(getSupplierName($value->supplier)) }}</td>
                                            <td>{{ getAmtCustom($value->amount) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="box-wrapper">
                    <div class="box-header with-border">
                        <h3 class="box-title"><iconify-icon icon="solar:card-recive-broken"></iconify-icon><span
                                class="ms-2">@lang('index.customer_payable')</span></h3>
                        <input type="hidden" class="datatable_name"
                            data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable_6">
                    </div>
                    <div class="table-box mt-2">
                        <!-- /.box-header -->
                        <div class="table-responsive">
                            <table id="datatable_6" class="table table-striped datatable_dashboard">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.reference_no')</th>
                                        <th class="width_13_p">@lang('index.date')</th>
                                        <th class="width_13_p">@lang('index.customer')</th>
                                        <th class="width_20_p">@lang('index.amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customerPayments as $value)
                                        <tr>
                                            <td>{{ safe($value->reference_no) }}</td>
                                            <td>{{ safe(getDateFormat($value->only_date)) }}</td>
                                            <td>{{ safe(getCustomerNameById($value->customer_id)) }}</td>
                                            <td>{{ getAmtCustom($value->amount) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{!! $baseURL . 'assets/datatable_custom/jquery.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jquery.dataTables.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.bootstrap5.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.buttons.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.html5.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.print.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jszip.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/pdfmake.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/vfs_fonts.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/newDesign/js/forTable.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/custom_report.js' !!}"></script>
    <!-- Chart Js plugin -->
    <script src="{!! $baseURL . 'assets/chart/chart.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/canvasjs.min.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/custom_dashboard.js' !!}"></script>
@endsection
