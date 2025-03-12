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
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {{ Form::open(['route' => 'update-password', 'method' => 'post']) }}
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('index.old_password') <span class="required_star">*</span></label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Old Password">
                                @error('current_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('index.new_password') <span class="required_star">*</span></label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label class="control-label">@lang('index.confirm_password') <span class="required_star">*</span></label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Confirm Password">
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                        icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
