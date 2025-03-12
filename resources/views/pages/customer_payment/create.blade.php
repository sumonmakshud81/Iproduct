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
            <!-- form start -->
            {{Form::open(['route'=>'customer-payment.store', 'id' => "attendance_form", 'method'=>'post'])}}
                @include('pages/customer_payment/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection
@section('script')
<?php
$baseURL = getBaseURL();
?>
<script type="text/javascript" src="{!! $baseURL.'frequent_changing/js/customers.js'!!}"></script>
@endsection