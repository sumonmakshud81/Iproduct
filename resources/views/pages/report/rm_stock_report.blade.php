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
            {{ Form::open(['route' => 'rm-stock-report', 'id' => 'attendance_form', 'method' => 'get']) }}
            @csrf
            <div class="row">
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                    <div class="form-group">
                        <select name="category_id" class="form-control select2">
                            <option value="">@lang('index.select_raw_materials')</option>
                            @foreach ($rmCategory as $key => $value)
                                <option {{ $category_id == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                                    {{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                    <div class="form-group">
                        <select name="finish_p_id" class="form-control select2">
                            <option value="">@lang('index.select_product')</option>
                            @foreach ($finishProduct as $key => $value)
                                <option value="{{ $value->id }}"
                                    {{ isset($obj->name) && $obj->name == $value->id ? 'selected' : '' }}>{{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-2">
                    <div class="form-group">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100">@lang('index.submit')</button>
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
                                <th class="w-5 text-start">@lang('index.sn')</th>
                                <th class="w-15">@lang('index.code')</th>
                                <th class="w-15">@lang('index.material_name')</th>
                                <th class="w-15">@lang('index.quantity')</th>
                                <th class="w-15">@lang('index.rate_per_unit')</th>
                                <th class="w-25">@lang('index.value')(@lang('index.quantity') X @lang('index.rate_per_unit'))</th>
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
                                    <td class="text-start">{{ $i-- }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        {{ $value->current_stock_final }}@if ($value->consumption_check != 1){{ getRMUnitById($value->unit) }}
                                        @endif
                                    </td>
                                    @php

                                        $grandT += $last_p_price * $value->current_stock;
                                    @endphp
                                    <td>{{ getAmtCustom($last_p_price) }}</td>
                                    <td>{{ getAmtCustom($last_p_price * $value->current_stock) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <th class="c_center"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-end">@lang('index.total')=</th>
                            <th>{{ getAmtCustom($grandT) }}
                            </th>
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
