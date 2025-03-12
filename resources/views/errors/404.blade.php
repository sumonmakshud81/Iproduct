@php
$baseURL = getBaseURL();
@endphp
@extends('layouts.errors')

@section('content')
    <div class="container">
        <div class="image">
            <img alt="Broken light bulb illustration" height="150"
                src="{{ $baseURL }}/{{ 'frequent_changing/images/404.png' }}"
                width="150" />
        </div>
        <div class="error-code">
            404
        </div>
        <div class="message">
            @lang('index.lost')
        </div>
        <div class="sub-message">
            @lang('index.lost_message')
        </div>
        <a class="home-link" href="{{ route('home') }}">
            @lang('index.go_to_home')
        </a>
    </div>
@endsection
