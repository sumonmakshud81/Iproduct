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
    <script src="{!! $baseURL . 'assets/js/view/settings.js' !!}"></script>
    <!-- Main content -->
    <section class="main-content-wrapper">
        <section class="content-header dashboard_content_header my-2">
            <h3 class="top-left-header">
                <span>@lang('index.set_security_question')</span>
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="table-box">
                        {{ Form::model($user, ['route' => ['update-security-question'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <!-- form start -->
                        <div class="box-body">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.security_question') <span class="required_star">*</span></label>
                                        <select class="form-control @error('question') is-invalid @enderror select2 fly_3"
                                            name="question" id="question">
                                            @foreach ($questions as $value)
                                                <option value="{{ $value }}"
                                                    {{ $user->question != '' && $value == $user->question ? ' selected="selected"' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('question')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.security_answer') <span class="required_star">*</span></label>
                                        <input type="text" name="answer"
                                            class="form-control @error('answer') is-invalid @enderror"
                                            id="answer" placeholder="Enter Question Answer" value="{{ auth()->user()->answer ?? old('answer') }}">

                                        @error('answer')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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
    </section>
@endsection

@section('script_bottom')
@endsection
