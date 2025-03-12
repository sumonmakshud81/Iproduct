@extends('layouts.app')

@section('script_top')
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
$setting = getSettingsInfo();
$tax_setting = getTaxInfo();
$baseURL = getBaseURL();
@endphp
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
            {{Form::open(['route'=>'attendance.store', 'id' => "attendance_form", 'method'=>'post'])}}
                @include('pages/attendance/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>

@endsection
@section('script')
<script src="{!! $baseURL.'assets/plugins/local/jquery.timepicker.min.js'!!}"></script>
<link rel="stylesheet" href="{!! $baseURL.'assets/plugins/local/jquery.timepicker.min.css'!!}">
<script src="{!! $baseURL.'frequent_changing/js/attendance.js'!!}"></script>
<script>
	$(document).ready(function () {
        // Toggle visibility of .employee_sec based on the "single" checkbox
        $('#single').change(function () {
            if ($(this).is(':checked')) {
                $('.employee_sec').removeClass('d-none'); // Show section
            } else {
                $('.employee_sec').addClass('d-none'); // Hide section
            }
        });
		
		 // When the "single" checkbox is clicked
        $('#single').change(function () {
            if ($(this).is(':checked')) {
                $('#all').prop('checked', false); // Uncheck "all"
            }
        });

        // When the "all" checkbox is clicked
        $('#all').change(function () {
            if ($(this).is(':checked')) {
				$('.employee_sec').addClass('d-none');
                $('#single').prop('checked', false); // Uncheck "single"
            }
        });
    });
</script>
@endsection