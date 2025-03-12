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
            <div class="col-sm-12 mb-2 col-md-6">
                <form action="{{ request()->url() }}" method="get">
                    <div class="d-flex gap-2 align-items-center">
                        <div class="form-group w-50">
                            <label>@lang('index.raw_material') <span class="required_star">*</span></label>
                            <select class="form-control @error('raw_material') is-invalid @enderror select2" name="raw_material"
                                id="raw_material_id">
                                <option value="">@lang('index.select_raw_materials')</option>
                                @foreach ($rawMaterials as $value)
                                    <option {{ encrypt_decrypt(request()->get('raw_material'), 'decrypt') == $value->id ? 'selected' : '' }} value="{{ encrypt_decrypt($value->id, 'encrypt') }}">{{ $value->name }}({{ $value->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn bg-blue-btn mt-4"><iconify-icon
                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.filter')</button>
                    </div>
                </form>
            </div>
            @if(isset($obj) && $obj != null)
            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-5">@lang('index.sn')</th>
                                <th class="w-15">@lang('index.name')</th>
                                <th class="w-15">@lang('index.code')</th>
                                <th class="w-15">@lang('index.category')</th>
                                <th class="w-15">@lang('index.unit')</th>
                                <th class="w-15">@lang('index.date')</th>
                                <th class="w-15">@lang('index.supplier')</th>
                                <th class="w-15">@lang('index.price_per_unit')</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach ($obj as $value)
                            @foreach (priceHistory($value->id) as $history)
                                <?php
                                    $i = $loop->iteration;
                                ?>
                                <tr>
                                    <td class="c_center">{{ $i-- }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->code }}</td>
                                    <td>{{ getCategoryById($value->category) }}</td>
                                    <td>{{ getRMUnitById($value->unit) }}</td>
                                    <td>{{ getDateFormat($history->purchase->date) }}</td>
                                    <td>{{ getSupplierName($history->purchase->supplier) }}</td>
                                    <td>{{ getCurrency($history->unit_price) }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    <!-- /.box-body -->
                </div>
            @endif
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
