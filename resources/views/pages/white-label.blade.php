@extends('layouts.app')
@section('content')
    @php
    $baseURL = getBaseURL();
    $whiteLabelInfo = getWhiteLabelInfo();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    @endphp

    <section class="main-content-wrapper">
        <section class="content-header dashboard_content_header my-2">
            <h3 class="top-left-header">
                <span>@lang('index.white_label_settings')</span>
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-box">
                        {!! Form::model(isset($whiteLabelInfo) && $whiteLabelInfo ? $whiteLabelInfo : '', [
                            'method' => 'POST',
                            'id' => 'update_white_label',
                            'enctype' => 'multipart/form-data',
                            'route' => ['white-label-update'],
                        ]) !!}
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('index.site_title') <span class="required_star">*</span></label>
                                       <input type="text" name="site_title" id="site_title" class="form-control @error('site_title') is-invalid @enderror" placeholder="Site Titile" value="{{ isset($whiteLabelInfo->site_title) ? $whiteLabelInfo->site_title : null }}">
                                        @error('site_title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('index.footer') <span class="required_star">*</span></label>
                                        <input type="text" name="footer" id="footer" class="form-control @error('footer') is-invalid @enderror" placeholder="Footer Text" value="{{ isset($whiteLabelInfo->footer) ? $whiteLabelInfo->footer : null }}">
                                        @error('footer')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('index.company_name') <span class="required_star">*</span></label>
                                        <input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="Company Name" value="{{ isset($whiteLabelInfo->company_name) ? $whiteLabelInfo->company_name : null }}">
                                        @error('company_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('index.company_website')</label>
                                        <input type="text" name="company_website" id="web_site" class="form-control @error('company_website') is-invalid @enderror" placeholder="Website" value="{{ isset($whiteLabelInfo->company_website) ? $whiteLabelInfo->company_website : null }}">
                                        @error('company_website')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group custom_table">
                                        <label>@lang('index.favicon') (@lang('index.favicon_instruction'))</label>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden"
                                                            value="{{ isset($whiteLabelInfo->favicon) && $whiteLabelInfo->favicon ? $whiteLabelInfo->favicon : '' }}"
                                                            name="favicon_old">
                                                        <input type="file" name="favicon" id="favicon" class="form-control @error('favicon') is-invalid @enderror file_checker_global" accept="image/ico" data-this_file_size_limit="50">

                                                    </td>
                                                    <td class="w_1">
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#view_favicon_preview"
                                                            class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2"
                                                            id="favicon_preview"><iconify-icon
                                                                icon="solar:eye-broken"></iconify-icon></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @error('favicon')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group custom_table">
                                        <label>@lang('index.logo') (@lang('index.logo_instructions'))</label>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden"
                                                            value="{{ isset($whiteLabelInfo->logo) && $whiteLabelInfo->logo ? $whiteLabelInfo->logo : '' }}"
                                                            name="logo_old">
                                                        <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror file_checker_global" accept="image/png,image/jpeg,image/jpg,image/gif" data-this_file_size-limit="1">

                                                    </td>
                                                    <td class="w_1">
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#view_logo_preview"
                                                            class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2"
                                                            id="logo_preview"><iconify-icon
                                                                icon="solar:eye-broken"></iconify-icon></button>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @error('logo')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group custom_table">
                                        <label>@lang('index.mini_logo') (@lang('index.mini_logo_instructions'))</label>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden"
                                                            value="{{ isset($whiteLabelInfo->mini_logo) && $whiteLabelInfo->mini_logo ? $whiteLabelInfo->mini_logo : '' }}"
                                                            name="mini_logo_old">
                                                        <input type="file" name="mini_logo" id="mini_logo" class="form-control @error('mini_logo') is-invalid @enderror file_checker_global" accept="image/png,image/jpeg,image/jpg,image/gif" data-this_file_size-limit="1">

                                                    </td>
                                                    <td class="w_1">
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#view_mini_logo_preview"
                                                            class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2"
                                                            id="mini_logo_preview"><iconify-icon
                                                                icon="solar:eye-broken"></iconify-icon></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @error('mini_logo')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row py-2">
                                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                                        <button type="submit" name="submit" value="submit"
                                            class="btn bg-blue-btn"><iconify-icon
                                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="view_logo_preview" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.logo') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            <img class="img-fluid"
                                src="{{ $baseURL }}uploads/white_label/{{ $whiteLabelInfo->logo ?? '' }}"
                                id="show_id">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-blue-btn" data-dismiss="modal"
                            data-bs-dismiss="modal">@lang('index.close')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view_mini_logo_preview" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.mini_logo') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            <img class="img-fluid"
                                src="{{ $baseURL }}uploads/white_label/{{ isset($whiteLabelInfo->mini_logo) && $whiteLabelInfo->mini_logo ? $whiteLabelInfo->mini_logo : '' }}"
                                id="show_id">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-blue-btn" data-dismiss="modal"
                            data-bs-dismiss="modal">@lang('index.close')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view_favicon_preview" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.favicon') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            <img class="img-fluid"
                                src="{{ $baseURL }}uploads/white_label/{{ isset($whiteLabelInfo->favicon) && $whiteLabelInfo->favicon ? $whiteLabelInfo->favicon : '' }}"
                                id="show_id">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-blue-btn" data-dismiss="modal"
                            data-bs-dismiss="modal">@lang('index.close')</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('script_bottom')
    <script src="{!! $baseURL . 'frequent_changing/js/whitelabel.js' !!}"></script>
@endsection
