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
            <form action="{{ route('data-import.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-6">
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-group w-50">
                                <label>@lang('index.type') <span class="required_star">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror select2" name="type"
                                    id="type">
                                    <option value="">@lang('index.select_type')</option>
                                    @foreach ($type as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="text-danger" role="alert">{{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mt-1">
                                <label for="import_file">@lang('index.import_file') <span class="required_star">*</span>
                                    (@lang('index.xls_xlsx'))</label>
                                <input type="file" class="form-control @error('import_file') is-invalid @enderror"
                                    id="import_file" name="import_file" accept=".xls, .xlsx">
                                @error('import_file')
                                    <span class="text-danger" role="alert">{{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn bg-blue-btn mt-4"><iconify-icon
                                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.import')</button>
                        </div>
                    </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="sample_file">@lang('index.download_sample_file')</label>
                        <a href="{{ route('data-import.sample') }}" target="_blank">
                            <iconify-icon icon="solar:download"></iconify-icon>@lang('index.download_sample')
                        </a>
                    </div>
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
@endsection
