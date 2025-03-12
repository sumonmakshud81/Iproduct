@php
$baseURL = getBaseURL();
@endphp
@extends('layouts.errors')

@section('content')
    <div class="container">
        <div class="image">
            <img alt="Broken light bulb illustration" height="150"
                src="{{ $baseURL }}/{{ 'frequent_changing/images/405.png' }}"
                width="150" />
        </div>
        <div class="error-code">
            405
        </div>
        <div class="message">
            @lang('index.not_allowed')
        </div>
        <div class="sub-message">
            @lang('index.not_allowed_message')
        </div>
        <a class="home-link" href="{{ $baseURL }}">
            @lang('index.go_to_home')
        </a>
    </div>
@endsection
