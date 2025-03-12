@extends('layouts.app')
@section('content')
    @php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    @endphp
    <script src="{!! $baseURL . 'frequent_changing/js/settings.js' !!}"></script>
    <!-- Main content -->
    <section class="main-content-wrapper">
        <section class="content-header dashboard_content_header my-2">
            <h3 class="top-left-header">
                <span>@lang('index.company_profile')</span>
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-box">
                        {!! Form::model(isset($settingsInfo) && $settingsInfo ? $settingsInfo : '', [
                            'method' => 'POST',
                            'id' => 'setting_update',
                            'enctype' => 'multipart/form-data',
                            'route' => ['setting.update'],
                        ]) !!}
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.company_name') <span class="required_star">*</span></label>
                                        <input type="text" name="name_company_name"
                                            class="form-control @error('name_company_name') is-invalid @enderror"
                                            id="name_company_name" placeholder="Name/Business Name"
                                            value="{{ isset($settingsInfo->name_company_name) && $settingsInfo->name_company_name ? $settingsInfo->name_company_name : '' }}">

                                        @error('name_company_name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.contact_person') <span class="required_star">*</span></label>
                                        <input type="text" name="contact_person"
                                            class="form-control @error('contact_person') is-invalid @enderror"
                                            id="contact_person" placeholder="Contact Person"
                                            value="{{ isset($settingsInfo->contact_person) && $settingsInfo->contact_person ? $settingsInfo->contact_person : '' }}">

                                        @error('contact_person')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.phone') <span class="required_star">*</span></label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" placeholder="Phone"
                                            value="{{ isset($settingsInfo->phone) && $settingsInfo->phone ? $settingsInfo->phone : '' }}">

                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.email') <span class="required_star">*</span></label>
                                        <input type="text" name="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Email"
                                            value="{{ isset($settingsInfo->email) && $settingsInfo->email ? $settingsInfo->email : '' }}">

                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.website')</label>
                                        <input type="text" name="web_site"
                                            class="form-control @error('web_site') is-invalid @enderror" id="web_site"
                                            placeholder="Website"
                                            value="{{ isset($settingsInfo->web_site) && $settingsInfo->web_site ? $settingsInfo->web_site : '' }}">

                                        @error('web_site')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.address') <span class="required_star">*</span></label>
                                        <input type="text" name="address"
                                            class="form-control @error('address') is-invalid @enderror" id="address"
                                            placeholder="Address"
                                            value="{{ isset($settingsInfo->address) && $settingsInfo->address ? $settingsInfo->address : '' }}">

                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.currency') <span class="required_star">*</span></label>
                                        <input type="text" name="currency" id="currency"
                                            class="form-control @error('currency') is-invalid @enderror"
                                            placeholder="Currency"
                                            value="{{ isset($settingsInfo->currency) ? $settingsInfo->currency : null }}">
                                        @error('currency')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.currency_position') <span class="required_star">*</span></label>
                                        <select name="currency_position"
                                            class="form-control @error('currency_position') is-invalid @enderror select2">
                                            <option
                                                {{ isset($settingsInfo->currency_position) && $settingsInfo->currency_position == 'Before' ? 'selected' : '' }}
                                                value="Before">Before</option>
                                            <option
                                                {{ isset($settingsInfo->currency_position) && $settingsInfo->currency_position == 'After' ? 'selected' : '' }}
                                                value="After">After</option>
                                        </select>
                                        @error('currency_position')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.precision') <span class="required_star">*</span></label>
                                        <select name="precision"
                                            class="form-control @error('precision') is-invalid @enderror select2">
                                            <option
                                                {{ isset($settingsInfo->precision) && $settingsInfo->precision == '0' ? 'selected' : '' }}
                                                value="0">None</option>
                                            <option
                                                {{ isset($settingsInfo->precision) && $settingsInfo->precision == '2' ? 'selected' : '' }}
                                                value="2">@lang('index.2_digit')</option>
                                            <option
                                                {{ isset($settingsInfo->precision) && $settingsInfo->precision == '3' ? 'selected' : '' }}
                                                value="3">@lang('index.3_digit')</option>
                                        </select>
                                        @error('precision')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.decimals_separator') <span class="required_star">*</span></label>
                                        <select name="decimals_separator"
                                            class="form-control @error('decimals_separator') is-invalid @enderror select2">
                                            <option
                                                {{ isset($settingsInfo->decimals_separator) && $settingsInfo->decimals_separator == '.' ? 'selected' : '' }}
                                                value=".">@lang('index.dot')(.)</option>
                                            <option
                                                {{ isset($settingsInfo->decimals_separator) && $settingsInfo->decimals_separator == ',' ? 'selected' : '' }}
                                                value=",">@lang('index.comma')(,)</option>
                                            <option
                                                {{ isset($settingsInfo->decimals_separator) && $settingsInfo->decimals_separator == ' ' ? 'selected' : '' }}
                                                value=" ">@lang('index.space')</option>
                                        </select>
                                        @error('decimals_separator')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.thousands_separator') <span class="required_star">*</span></label>
                                        <select name="thousands_separator"
                                            class="form-control @error('thousands_separator') is-invalid @enderror select2">
                                            <option
                                                {{ isset($settingsInfo->thousands_separator) && $settingsInfo->thousands_separator == '.' ? 'selected' : '' }}
                                                value=".">@lang('index.dot')(.)</option>
                                            <option
                                                {{ isset($settingsInfo->thousands_separator) && $settingsInfo->thousands_separator == ',' ? 'selected' : '' }}
                                                value=",">@lang('index.comma')(,)</option>
                                            <option
                                                {{ isset($settingsInfo->thousands_separator) && $settingsInfo->thousands_separator == ' ' ? 'selected' : '' }}
                                                value=" ">@lang('index.space')</option>
                                        </select>
                                        @error('thousands_separator')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.timezone') <span class="required_star">*</span></label>
                                        <select class="form-control @error('time_zone') is-invalid @enderror select2"
                                            name="time_zone">
                                            <option value="">@lang('index.select')</option>
                                            @foreach ($time_zone_list as $value)
                                                <option
                                                    {{ isset($settingsInfo) && $settingsInfo->time_zone == $value->zone_name ? 'selected' : '' }}
                                                    value="{{ $value->zone_name }}">{{ $value->zone_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('time_zone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.date_format') <span class="required_star">*</span></label>
                                        <select class="form-control @error('date_format') is-invalid @enderror select2"
                                            name="date_format" id="date_format">
                                            <option value="">@lang('index.select')</option>
                                            <option
                                                {{ isset($settingsInfo) && $settingsInfo->date_format == 'd/m/Y' ? 'selected' : '' }}
                                                value="d/m/Y">D/M/Y</option>
                                            <option
                                                {{ isset($settingsInfo) && $settingsInfo->date_format == 'm/d/Y' ? 'selected' : '' }}
                                                value="m/d/Y">M/D/Y</option>
                                            <option
                                                {{ isset($settingsInfo) && $settingsInfo->date_format == 'Y/m/d' ? 'selected' : '' }}
                                                value="Y/m/d">Y/M/D</option>
                                        </select>
                                        @error('date_format')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group custom_table">
                                        <label>@lang('index.logos') (@lang('index.logo_instructions'))</label>
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
                                                            data-bs-target="#view_logo_modal"
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

        <div class="modal fade" id="view_logo_modal" aria-hidden="true" aria-labelledby="myModalLabel">
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
                                src="{{ $baseURL }}uploads/settings/{{ isset($settingsInfo->logo) && $settingsInfo->logo ? $settingsInfo->logo : '' }}"
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
@endsection
