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
        <h3 class="top-left-header">{{isset($title) && $title?$title:''}}</h3>
    </section>

    <div class="box-wrapper">
        <!-- general form elements -->
        <div class="table-box">
            <div class="row">
                <div class="col-md-12"><br>
                    <div class="form-group">
                        <h3 class="top-left-header txt-uh-82">@lang('index.generate_salary_for') : {{$obj->month}} - {{$obj->year}} </h3>
                    </div>
                </div>
            </div>
            <!-- form start -->
            {{Form::model($obj,['route'=>['payroll.update',$obj->id],'method'=>'put'])}}
            @include('pages/payroll/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{!! $baseURL.'frequent_changing/js/salary.js'!!}"></script>

<link rel="stylesheet" href="{!! $baseURL.'assets/bower_components/buttonCSS/checkBotton2.css'!!}">
@endsection