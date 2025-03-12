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
                        <h3 class="mb-3 auth-title">@lang('index.reset_password_step_2')</h3>
                    </div>
                </div>
                <form action="{{ route('post-forgot-password-step-two') }}" method="post"
                    accept-charset="utf-8">
                    @csrf
                <div class="form-group mb-3">
                    <label class="label" for="question">@lang('index.select_security_question')<span
                                    class="text-danger">*</span></label>
                    <select class="form-control @error('question') is-invalid @enderror select2 fly_3" name="question" id="question">
                        <?php foreach ($questions as $value) {  ?>
                        <option value="{{ ($value) }}">{{ ($value) }}</option>
                        <?php } ?>
                    </select>    
                    @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror                
                </div>
                <div class="form-group mb-3">
                    <label class="label" for="answer">@lang('index.security_answer')<span
                                    class="text-danger">*</span></label>
                    <input class="form-control @error('answer') is-invalid @enderror" id="answer" type="text" name="answer" value="{{ old('answer') }}"
                        autocomplete="off" placeholder="@lang('index.security_answer')">
                    @error('answer')
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
