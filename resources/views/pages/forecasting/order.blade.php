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
            <form action="{{ route('forecasting.order.view') }}" method="get">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-group w-50">
                                <label>@lang('index.customer_order') <span class="required_star">*</span></label>
                                <select class="form-control @error('order') is-invalid @enderror select2" name="order_id"
                                    id="order_id">
                                    <option value="">@lang('index.select_order')</option>
                                    @foreach ($orders as $value)
                                        <option value="{{ $value->id }}">
                                            {{ $value->reference_no }}</option>
                                    @endforeach
                                </select>
                            </div>                            
                            <button type="submit" class="btn bg-blue-btn mt-4" id="add_order"><iconify-icon
                                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.forecast')</button>
                        </div>
                        <p id="order_error" class="text-danger d-none">Order No is required</p>
                    </div>
                </div>
            </form>            
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
    <script src="{!! $baseURL . 'frequent_changing/js/forecasting.js' !!}"></script>
@endsection
