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
                <div class="table-responsive">
                    <table id="datatable" class="table text-nowrap">
                        <thead>
                            <tr>
                                <th class="ir_w_1">@lang('index.sn')</th>
                                <th class="ir_w_12">@lang('index.title')</th>
                                <th class="ir_w_1_txt_center">@lang('index.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $value)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $value->title ?? '' }}</td>
                                    <td class="ir_w_1_txt_center">
                                        @if (routePermission('role.edit'))
                                            <a href="{{ route('role.edit', encrypt_decrypt($value->id, 'encrypt')) }}" class="button-success"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.edit')"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if (routePermission('role.delete'))
                                            <a href="#" class="delete button-danger"
                                                data-form_class="alertDelete{{ $value->id }}" type="submit"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('index.delete')">
                                                <form action="{{ route('role.destroy', $value) }}"
                                                    class="alertDelete{{ $value->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i class="c_padding_13 fa fa-trash tiny-icon"></i>
                                                </form>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
