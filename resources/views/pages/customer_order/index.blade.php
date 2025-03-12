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
                                <th class="width_10_p">@lang('index.type')</th>
                                <th class="width_10_p">@lang('index.customer')</th>
                                <th class="width_10_p">@lang('index.created_on')</th>
                                <th class="width_10_p">@lang('index.created_by')</th>
                                <th class="width_10_p">@lang('index.product_count')</th>
                                <th class="width_10_p">@lang('index.total_value')</th>
                                <th class="width_10_p">@lang('index.cost')</th>
                                <th class="width_10_p">@lang('index.profit')</th>
                                <th class="width_10_p">@lang('index.delivery_date')</th>
                                <th class="width_3_p">@lang('index.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($obj && !empty($obj))
                                <?php
                                $i = count($obj);
                                ?>
                            @endif
                            @foreach ($obj as $value)
                                <tr>
                                    <td>{{ $value->order_type }}</td>
                                    <td>{{ $value->customer->name }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ getUserName($value->created_by) }}</td>
                                    <td>{{ $value->total_product }}</td>
                                    <td>{{ getAmtCustom($value->total_amount) }}</td>
                                    <td>{{ getAmtCustom($value->total_cost) }}</td>
                                    <td>{{ getAmtCustom($value->total_profit) }}</td>
                                    <td>{{ getDateFormat($value->delivery_date) }}</td>
                                    <td>
                                        @if (routePermission('order.view-details'))
                                            <a href="{{ url('customer-orders') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}" class="button-info"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.view_details')"><i class="fa fa-eye tiny-icon"></i></a>
                                        @endif
                                        @if (routePermission('order.edit'))
                                            <a href="{{ url('customer-orders') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}/edit"
                                                class="button-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.edit')"><i class="fa fa-edit tiny-icon"></i></a>
                                        @endif
                                        @if (routePermission('order.print-invoice'))
                                            <a href="javascript:void()" class="button-info print_invoice"
                                                data-id="{{ $value->id }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="@lang('index.print_invoice')"><i
                                                    class="fa fa-print tiny-icon"></i></a>
                                        @endif
                                        @if (routePermission('order.download-invoice'))
                                            <a href="{{ route('customer-order-download', encrypt_decrypt($value->id, 'encrypt')) }}"
                                                class="button-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.download_invoice')"><i class="fa fa-download tiny-icon"></i></a>
                                        @endif
                                        @if (routePermission('order.delete'))
                                            <a href="#" class="delete button-danger"
                                                data-form_class="alertDelete{{ $value->id }}" type="submit"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('index.delete')">
                                                <form action="{{ route('customer-orders.destroy', $value->id) }}"
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
    <script src="{!! $baseURL . 'frequent_changing/js/order.js' !!}"></script>
    
@endsection
