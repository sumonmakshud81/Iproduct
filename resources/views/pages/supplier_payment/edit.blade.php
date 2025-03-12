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
        <div class="table-box">
            {{Form::model($obj,['route'=>['supplier-payment.update',$obj->id],'method'=>'put'])}}
                @include('pages/supplier_payment/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>

@endsection

@section('script')
<?php
$baseURL = getBaseURL();
?>
<script type="text/javascript" src="{!! $baseURL.'frequent_changing/js/supplier.js'!!}"></script>
@endsection