@extends('layouts.app')

@php
$baseURL = getBaseURL();
@endphp

@section('content')
    <main class="main_wrapper">
        <div class="container content form_box d-flex justify-content-center align-items-center">
            <div class="inner-wrapper change_pass_form">
                @include('utilities.messages')
                <h3 class="title">Login</h3>
                <form class="default_form row m-0" method="post" action="{{ route('admin.doLogin') }}">
                    @csrf
                    <div class="form-group col-sm-12">
                        <label for="s">Phone Number, Email Address Or Username <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="s"
                               placeholder="Phone Number, Email Address Or Username"
                               name="username_email_phone" value="{{old('username_email_phone')}}">
                        @if ($errors->has('username_email_phone'))
                            <div class="invalid_feedback">{{ $errors->first('username_email_phone') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="newPassword">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="newPassword" placeholder="Password"
                               name="password" value="{{old('password')}}">
                        @if ($errors->has('password'))
                            <div class="invalid_feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="col-sm-12 d-flex justify-content-between align-items-center p-0">
                        <button type="submit" class="c-btn btn-fill submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script src="{!! $baseURL.'assets/js/aos.js'!!}"></script>
    <script src="{!! $baseURL.'assets/js/jquery.dataTables.min.js'!!}"></script>
    <script src="{!! $baseURL.'assets/css/datepicker/bootstrap-datepicker.js'!!}"></script>
    <script src="{!! $baseURL.'assets/js/select2.min.js'!!}"></script>
    <script src="{!! $baseURL.'assets/js/summernote.min.js'!!}"></script>
    <script src="{!! $baseURL.'assets/js/main.js'!!}"></script>
@endsection
