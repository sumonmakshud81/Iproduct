@extends('layouts.app')
@section('script_top')
@endsection

@section('content')
    <section class="main-content-wrapper">
        <section class="content-header">
            <h3 class="top-left-header">
                {{ isset($title) && $title ? $title : '' }}
            </h3>
        </section>


        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'method' => isset($obj) && $obj ? 'PATCH' : 'POST',
                    'route' => ['currency.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.currency') <span class="required_star">*</span></label>
                                <input type="text" name="symbol" id="symbol" class="form-control @error('symbol') is-invalid @enderror" placeholder="symbol" value="{{ isset($obj) && $obj->symbol ? $obj->symbol : old('symbol') }}">
                                @error('symbol')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.conversion_rate')<span class="required_star">*</span></label>
                                <input type="text" name="conversion_rate" id="conversion_rate" class="form-control @error('conversion_rate') is-invalid @enderror" placeholder="conversion_rate" value="{{ isset($obj) && $obj->conversion_rate ? $obj->conversion_rate : old('conversion_rate') }}">
                                @error('conversion_rate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        <a class="btn bg-second-btn" href="{{ route('currency.index') }}"><iconify-icon icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script_bottom')
@endsection
