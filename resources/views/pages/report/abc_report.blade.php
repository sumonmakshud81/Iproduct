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
                                <th colspan="6" class="text-center">
                                    <h5>@lang('index.category') A 
                                    </h5>
                                    <small class="text-center text-danger">@lang('index.category_a_instruction') @lang('index.category_a_example')</small>
                                </th>
                            </tr>
                            <tr>
                                <th class="op_width_1_p">@lang('index.sn')</th>
                                <th class="op_width_9_p">@lang('index.name')(@lang('index.code'))</th>
                                <th class="op_width_18_p">@lang('index.category')</th>
                                <th class="op_width_10_p">@lang('index.total_value')</th>
                                <th class="op_width_10_p">@lang('index.cumulative_value')
                                    <i data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('index.cumulative_instruction')" class="fa fa-question-circle fa-lg base_color c_pointer"></i>
                                </th>
                                <th class="op_width_10_p">@lang('index.percentage')
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if (!empty($obj['a'])) {
                            $i = count($obj['a']);
                            foreach ($obj['a'] as $value) {
                        ?>
                            <tr>
                                <td>{{ $i-- }}</td>
                                <td>{{ $value->name }}({{ $value->code }})</td>
                                <td>{{ getCategoryById($value->category) }}</td>
                                <td>{{ getCurrency($value->total_value) }}</td>
                                <td>{{ getCurrency($value->cumulative_value) }}</td>
                                <td>{{ number_format($value->percentage, 2) }}%</td>
                            </tr>
                            <?php
                            }
                        }
                        ?>

                        </tbody>
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center">
                                    <h5>@lang('index.category') B</h5>
                                    <small class="text-center text-danger">@lang('index.category_b_instruction') @lang('index.category_b_example')</small>
                                </th>
                            </tr>
                            <tr>
                                <th class="op_width_1_p">@lang('index.sn')</th>
                                <th class="op_width_9_p">@lang('index.name')(@lang('index.code'))</th>
                                <th class="op_width_18_p">@lang('index.category')</th>
                                <th class="op_width_10_p">@lang('index.total_value')</th>
                                <th class="op_width_10_p">@lang('index.cumulative_value')
                                    <i data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('index.cumulative_instruction')" class="fa fa-question-circle fa-lg base_color c_pointer"></i>
                                </th>
                                <th class="op_width_10_p">@lang('index.percentage')
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if (!empty($obj['b'])) {
                            $i = count($obj['b']);
                            foreach ($obj['b'] as $value) {
                        ?>
                            <tr>
                                <td>{{ $i-- }}</td>
                                <td>{{ $value->name }}({{ $value->code }})</td>
                                <td>{{ getCategoryById($value->category) }}</td>
                                <td>{{ getCurrency($value->total_value) }}</td>
                                <td>{{ getCurrency($value->cumulative_value) }}</td>
                                <td>{{ number_format($value->percentage, 2) }}%</td>
                            </tr>
                            <?php
                            }
                        }
                        ?>

                        </tbody>
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center">
                                    <h5>@lang('index.category') C</h5>
                                    <small class="text-center text-danger">@lang('index.category_c_instruction') @lang('index.category_c_example')</small>
                                </th>
                            </tr>
                            <tr>
                                <th class="op_width_1_p">@lang('index.sn')</th>
                                <th class="op_width_9_p">@lang('index.name')(@lang('index.code'))</th>
                                <th class="op_width_18_p">@lang('index.category')</th>
                                <th class="op_width_10_p">@lang('index.total_value')</th>
                                <th class="op_width_10_p">@lang('index.cumulative_value')
                                    <i data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('index.cumulative_instruction')" class="fa fa-question-circle fa-lg base_color c_pointer"></i>
                                </th>
                                <th class="op_width_10_p">@lang('index.percentage')
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        if (!empty($obj['c'])) {
                            $i = count($obj['c']);
                            foreach ($obj['c'] as $value) {
                        ?>
                            <tr>
                                <td>{{ $i-- }}</td>
                                <td>{{ $value->name }}({{ $value->code }})</td>
                                <td>{{ getCategoryById($value->category) }}</td>
                                <td>{{ getCurrency($value->total_value) }}</td>
                                <td>{{ getCurrency($value->cumulative_value) }}</td>
                                <td>{{ number_format($value->percentage, 2) }}%</td>
                            </tr>
                            <?php
                            }
                        }
                        ?>

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
