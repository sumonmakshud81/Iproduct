@php
$baseURL = getBaseURL();
$setting = getSettingsInfo();

$whiteLabelInfo = getWhiteLabelInfo();

$base_color = '#6ab04c';
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
@endphp
@extends('layouts.app_login')

@section('content')
    <div class="col-md-12 col-lg-10 login-card-wrapper">
        <div class="login-parent-wrapper">
            @include('utilities.messages')
            <div class="wrap d-md-flex">
                {{-- For Dynamic Background Image, used inline css --}}
                <div class="img business-grap" style="background-image: url('{!! $baseURL . 'frequent_changing/images/login-page.jpg' !!}');">
                    <div class="overlay">
                        <div>
                            <h4>@lang('index.start_journey_with_us')</h4>
                            <p>@lang('index.login_text')</p>
                        </div>
                    </div>
                </div>
                <div class="login-wrap">
                    <div class="d-flex justify-content-center logo-area">
                        <a href="{{ route('login') }}">
                            <img src="{!! $baseURL .
                                (isset($whiteLabelInfo->logo) ? 'uploads/white_label/' . $whiteLabelInfo->logo : 'images/logo.png') !!}" alt="site-logo">
                        </a>
                    </div>
                    <div class="d-flex">
                        <div class="w-100">
                            <h3 class="mb-3 auth-title">@lang('index.please_login')</h3>
                        </div>
                    </div>
                    <form action="{{ route('login') }}" id="login_form" autocomplete="off" method="post"
                        accept-charset="utf-8">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="label" for="email">@lang('index.email') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control @error('email') is-invalid @enderror" placeholder="@lang('index.email')"
                                type="text" id="email" autocomplete="off" name="email">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="label" for="password">@lang('index.password') <span
                                    class="text-danger">*</span></label>
                            <input type="password" autocomplete="off" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex py-10">
                            <button type="submit" name="submit" value="submit"
                                class="btn login-button btn-2 rounded submit me-1">@lang('index.submit')</button>
                        </div>
                        <div class="d-flex justify-content-end forgot-pass-wrap">
                            <a href="{{ url('forgot-password-step-one') }}" class="forgot-pass">@lang('index.forgot_password')</a>
                        </div>
                        @if (appMode() == 'demo')
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">@lang('index.email')</th>
                                            <th scope="col">@lang('index.password')</th>
                                            <th scope="col">@lang('index.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        <tr>
                                            <td>admin@doorsoft.co</td>
                                            <td>123456</td>
                                            <td>
                                                <button type="button" class="btn btn-primary login_btn_click"><iconify-icon
                                                        icon="solar:login-broken"></iconify-icon></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
