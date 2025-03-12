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
                <span>@lang('index.mail_settings')</span>
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <form action="{{ route('settings.mail.update') }}" method="POST" enctype="multipart/form-data" id="common-form">
                    @csrf
                    <div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.type')</label>{!! starSign() !!}
                                    <select name="mail_driver" class="form-control @error('mail_driver') is-invalid @enderror select2 mail_driver" id="mail_driver">
                                        <option {{ $mail_setting_info['mail_driver'] == 'smtp' ? 'selected' : '' }}
                                            value="smtp">@lang('index.SMTP')</option>                                        
                                    </select>
                                </div>
                                @error('mail_driver')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.host_address') {!! starSign() !!}</label>
                                    <input type="text" name="mail_host" class="form-control @error('mail_host') is-invalid @enderror"
                                        value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['mail_host'] ?? '' }} @endif" placeholder="@lang('index.host_address')">
                                </div>
                                @error('mail_host')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.port_address') {!! starSign() !!}</label>
                                    <input type="number" name="mail_port" placeholder="@lang('index.port_address')"
                                        class="form-control @error('mail_port') is-invalid @enderror" value="@if(appMode() == 'demo') 0000 @else {{ $mail_setting_info['mail_port'] ?? '' }} @endif">
                                </div>
                                @error('mail_port')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.encryption') {!! starSign() !!}</label>
                                    <input type="text" name="mail_encryption" placeholder="@lang('index.encryption')"
                                        class="form-control @error('mail_encryption') is-invalid @enderror" value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['mail_encryption'] ?? '' }} @endif">
                                </div>
                                @error('mail_encryption')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.username') {!! starSign() !!}</label>
                                    <input type="text" name="mail_username" class="form-control @error('mail_username') is-invalid @enderror"
                                        placeholder="@lang('index.username')"
                                        value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['mail_username'] ?? '' }} @endif">
                                </div>
                                @error('mail_username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.password') {!! starSign() !!}</label>
                                    <input type="text" name="mail_password" class="form-control @error('mail_password') is-invalid @enderror"
                                        placeholder="@lang('index.password')"
                                        value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['mail_password'] ?? '' }} @endif">
                                </div>
                                @error('mail_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group custom_table">
                                    <label>
                                        @lang('index.from') {!! starSign() !!}
                                    </label>
                                    <table class="">
                                        <tr>
                                            <td>
                                                <input type="text" name="mail_from" class="form-control @error('mail_from') is-invalid @enderror"
                                                    placeholder="@lang('index.from')"
                                                    value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['mail_from'] ?? '' }} @endif">
                                            </td>
                                            <td class="w_1">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#mail_from" class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2" id="logo_preview"><iconify-icon icon="solar:eye-broken"></iconify-icon></button>
                                            </td>
                                        </tr>
                                    </table>


                                </div>
                                @error('mail_from')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-2">
                                <div class="form-group custom_table">
                                    <label>@lang('index.mail_fromname') {!! starSign() !!}</label>
                                    <table class="">
                                        <tr>
                                            <td>
                                                <input type="text" name="mail_fromname" class="form-control @error('mail_fromname') is-invalid @enderror"
                                                    placeholder="@lang('index.mail_fromname')"
                                                    value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['from_name'] ?? '' }} @endif">
                                            </td>
                                            <td class="w_1">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#from_name" class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2" id="logo_preview"><iconify-icon icon="solar:eye-broken"></iconify-icon></button>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                @error('mail_fromname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-12 mb-2 col-md-12">
                                <div class="form-group">
                                    <label>@lang('index.api_key') {!! starSign() !!}</label>
                                    <input type="text" name="api_key" class="form-control @error('api_key') is-invalid @enderror"
                                        placeholder="@lang('index.api_key')"
                                        value="@if(appMode() == 'demo') ******* @else {{ $mail_setting_info['api_key'] ?? '' }} @endif">
                                </div>
                                @error('api_key')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row py-2">
                            <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                            
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="mail_from">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('index.from')</h4>
                        <button type="button" class="btn-close close_modal_mail_from" data-bs-dismiss="modal"
                            aria-label="Close"><span aria-hidden="true"><i data-feather="x"></i></span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ $baseURL }}assets/images/mail_from.png" alt="" class="w-100">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="from_name">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('index.from')</h4>
                        <button type="button" class="btn-close close_modal_from_name" data-bs-dismiss="modal"
                            aria-label="Close"><span aria-hidden="true"><i data-feather="x"></i></span></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ $baseURL }}assets/images/from_name.png" alt="" class="w-100">
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('script_bottom')
    <script src="{!! $baseURL . 'frequent_changing/js/whitelabel.js' !!}"></script>
@endsection
