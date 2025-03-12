@php
$baseURL = getBaseURL();
@endphp
@extends('layouts.errors')

@section('content')
    <div class="container">
        <div class="image">
            <img alt="Broken light bulb illustration" height="150"
                src="{{ $baseURL }}/{{ 'frequent_changing/images/403.png' }}"
                width="150" />
        </div>
        <div class="error-code">
            403
        </div>
        <div class="message">
            @lang('index.forbidden')
        </div>
        <div class="sub-message">
            @lang('index.forbidden_message')
        </div>
        <a class="home-link" href="{{ $baseURL }}">
            @lang('index.go_to_home')
        </a>
    </div>
@endsection
