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
            {{Form::model($obj,['route'=>['deposit.update',$obj->id],'method'=>'put'])}}
                @include('pages/deposit/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>

@endsection