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
            {{ Form::open(['route' => 'trial-balance', 'id' => 'attendance_form', 'method' => 'get']) }}
            <div class="row">
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                    <div class="form-group">
                        {!! Form::text('date', $date, [
                            'class' => 'form-control customDatepicker',
                            'readonly' => '',
                            'placeholder' => 'Select Date',
                        ]) !!}
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
                                <th class="op_width_2_p">@lang('index.sn')</th>
                                <th>@lang('index.title')</th>
                                <th>@lang('index.debit')</th>
                                <th>@lang('index.credit')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="c_center">1</td>
                                <td>@lang('index.sale')</td>
                                <td>{{ getAmtCustom(0) }}</td>
                                <td>{{ getAmtCustom($sales_credit) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">2</td>
                                <td>@lang('index.customer_due_received')</td>
                                <td>{{ getAmtCustom(0) }}</td>
                                <td>{{ getAmtCustom($customer_due_received_credit) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">3</td>
                                <td>@lang('index.supplier_due_paid')</td>
                                <td>{{ getAmtCustom($supplier_due_paid_debit) }}</td>
                                <td>{{ getAmtCustom(0) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">4</td>
                                <td>@lang('index.purchase')</td>
                                <td>{{ getAmtCustom($purchase_debit) }}</td>
                                <td>{{ getAmtCustom(0) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">5</td>
                                <td>@lang('index.production_non_inventory_cost')</td>
                                <td>{{ getAmtCustom($production_non_inventory_cost_debit) }}</td>
                                <td>{{ getAmtCustom(0) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">6</td>
                                <td>@lang('index.expense')</td>
                                <td>{{ getAmtCustom($expense_debit) }}</td>
                                <td>{{ getAmtCustom(0) }}</td>
                            </tr>
                            <tr>
                                <td class="c_center">7</td>
                                <td>@lang('index.payroll')</td>
                                <td>{{ getAmtCustom($payroll_debit) }}</td>
                                <td>{{ getAmtCustom(0) }}</td>
                            </tr>
                            @php
                                $total_debit =
                                    $supplier_due_paid_debit +
                                    $purchase_debit +
                                    $production_non_inventory_cost_debit +
                                    $expense_debit +
                                    $payroll_debit;
                                $total_credit = $sales_credit + $customer_due_received_credit;
                            @endphp
                            <tr>
                                <td class="c_center"></td>
                                <td class="fw-bold text-end">@lang('index.total')=</td>
                                <td class="fw-bold">{{ getAmtCustom($total_debit) }}</td>
                                <td class="fw-bold">{{ getAmtCustom($total_credit) }}</td>
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
