<?php
$baseURL = getBaseURL();
$setting = getSettingsInfo();

$whiteLabelInfo = getWhiteLabelInfo();
$base_color = '#6ab04c';
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
?>
@extends('layouts.app_login')

@section('content')
    <div class="col-md-6 col-lg-5 login-card-wrapper-2">
        <div class="msg-wrap-2">
            @include('utilities.messages')
        </div>
        <div class="wrap-2">
            <div class="login-wrap-2">
                <div class="d-flex justify-content-center logo-area">
                    <img src="{!! $baseURL .
                        (isset($whiteLabelInfo->logo) ? 'uploads/white_label/' . $whiteLabelInfo->logo : 'images/logo.png') !!}" alt="site-logo">
                </div>
                <div class="d-flex">
                    <div class="w-100">
                        <h3 class="mb-3 auth-title">@lang('index.reset_password_step_3')</h3>
                    </div>
                </div>
                <form action="{{ route('post-forgot-password-step-final') }}" method="post">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="label" for="password">@lang('index.password')<span class="text-danger">*</span></label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password"
                            name="password" value="" placeholder="@lang('index.password')">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="label" for="password_confirmation">@lang('index.confirm_password')<span
                                class="text-danger">*</span></label>
                        <input class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" type="password" name="password_confirmation" value=""
                            placeholder="@lang('index.confirm_password')">
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <button type="submit" name="submit" value="submit"
                            class="btn login-button btn-2 rounded submit me-1"><span>@lang('index.submit')</button>
                    </div>
                    <div class="text-center py-3">
                        <a class="forgot-pass" href="{{ url('login') }}">@lang('index.back_to_login')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
