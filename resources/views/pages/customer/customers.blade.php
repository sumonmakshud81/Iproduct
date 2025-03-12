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
                                <th class="width_1_p">@lang('index.sn')</th>
                                <th class="width_10_p">@lang('index.name')</th>
                                <th class="width_10_p">@lang('index.phone')</th>
                                <th class="width_10_p">@lang('index.email')</th>
                                <th class="width_10_p">@lang('index.credit_limit')</th>
                                <th class="width_10_p">@lang('index.opening_balance')</th>
                                <th class="width_10_p">@lang('index.balance_type')</th>
                                <th class="width_10_p">@lang('index.address')</th>
                                <th class="width_10_p">@lang('index.note')</th>
                                <th class="width_10_p">@lang('index.added_by')</th>
                                <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
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
                                    <td class="c_center">{{ $i-- }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ safe($value->phone) }}</td>
                                    <td>{{ safe($value->email) }}</td>
                                    <td>{{ getAmtCustom($value->credit_limit) }}</td>
                                    <td>{{ getAmtCustom($value->opening_balance) }}</td>
                                    <td>{{ safe($value->opening_balance_type) }}</td>
                                    <td>{{ safe($value->address) }}</td>
                                    <td>{{ safe($value->note) }}</td>
                                    <td>{{ getUserName($value->added_by) }}</td>
                                    <td class="ir_txt_center">
                                        @if (routePermission('customer.edit'))
                                            <a href="{{ url('customers') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}/edit"
                                                class="button-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.edit')"><i class="fa fa-edit tiny-icon"></i></a>
                                        @endif
                                        @if (routePermission('customer.delete'))
                                            <a href="#" class="delete button-danger"
                                                data-form_class="alertDelete{{ $value->id }}" type="submit"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('index.delete')">
                                                <form action="{{ route('customers.destroy', $value->id) }}"
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
@endsection
