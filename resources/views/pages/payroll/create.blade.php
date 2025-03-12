@extends('layouts.app')

@section('script_top')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <?php
    $setting = getSettingsInfo();
    $tax_setting = getTaxInfo();
    $baseURL = getBaseURL();
    ?>
@endsection

@section('content')
    <section class="main-content-wrapper">
        <section class="content-header">
            <h3 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h3>
        </section>
         @include('utilities.messages')
        <div class="box-wrapper">
            <div class="table-box">
                <!-- form start -->
                {{ Form::open(['route' => 'payroll.store', 'id' => 'attendance_form', 'method' => 'post']) }}
                <div class="row">
                    <div class="col-sm-12 col-md-4 mb-2">
                        <div class="form-group">
                        <label>@lang('index.month') <span class="required_star">*</span></label>
                            <select name="month"
                                class="form-control @error('month') is-invalid @enderror select2 width_100_p">
                                <option value="">@lang('index.select')</option>
                                <option value="January">@lang('index.january')</option>
                                <option value="February">@lang('index.february')</option>
                                <option value="March">@lang('index.march')</option>
                                <option value="April">@lang('index.april')</option>
                                <option value="May">@lang('index.may')</option>
                                <option value="June">@lang('index.june')</option>
                                <option value="July">@lang('index.july')</option>
                                <option value="August">@lang('index.august')</option>
                                <option value="September">@lang('index.september')</option>
                                <option value="October">@lang('index.october')</option>
                                <option value="November">@lang('index.november')</option>
                                <option value="December">@lang('index.december')</option>
                            </select>
                            @error('month')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-2">
                        <div class="form-group">
                        <label>@lang('index.year') <span class="required_star">*</span></label>    
                            <select name="year"
                                class="form-control @error('year') is-invalid @enderror select2 width_100_p">
                                <option value="">Select</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <option value="2038">2038</option>
                                <option value="2039">2039</option>
                                <option value="2040">2040</option>
                                <option value="2041">2041</option>
                                <option value="2042">2042</option>
                                <option value="2043">2043</option>
                                <option value="2044">2044</option>
                                <option value="2045">2045</option>
                                <option value="2046">2046</option>
                                <option value="2047">2047</option>
                                <option value="2048">2048</option>
                                <option value="2049">2049</option>
                                <option value="2050">2050</option>
                            </select>
                            @error('year')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn bg-blue-btn"><iconify-icon
                        icon="solar:check-circle-broken"></iconify-icon>
                    @lang('index.submit')</button>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <link rel="stylesheet" href="{!! $baseURL . 'assets/bower_components/buttonCSS/checkBotton2.css' !!}">
@endsection
