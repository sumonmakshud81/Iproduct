@extends('layouts.app')

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
                    'route' => ['rawmaterials.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <div class="form-group">
                            <label>@lang('index.name') <span class="required_star">*</span></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Name"
                                value="{{ isset($obj->name) ? $obj->name : old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-4">
                        <div class="form-group">
                            <label>@lang('index.code') <span class="required_star">*</span></label>
                            <input type="text" name="code" id="code"
                                class="form-control @error('code') is-invalid @enderror" placeholder="Code"
                                value="{{ isset($obj->code) ? $obj->code : $code }}" onfocus="select()">
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-4">
                        <div class="form-group">
                            <label>@lang('index.category') <span class="required_star">*</span></label>
                            <select class="form-control @error('category') is-invalid @enderror select2" name="category"
                                id="category">
                                <option value="">@lang('index.select')</option>
                                @foreach ($categories as $value)
                                    <option
                                        {{ (isset($obj->category) && $obj->category == $value->id) || old('category') == $value->id ? 'selected' : '' }}
                                        value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <div class="form-group">
                            <label>@lang('index.unit') <span class="required_star">*</span> </label>
                            <select class="form-control @error('unit') is-invalid @enderror select2 con_unit" name="unit"
                                id="unit">
                                <option value="">@lang('index.select')</option>
                                @foreach ($units as $value)
                                    <option
                                        {{ (isset($obj->unit) && $obj->unit == $value->id) || old('unit') == $value->id ? 'selected' : '' }}
                                        value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('unit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-2 col-md-4">
                        <label class="custom_label">@lang('index.rate_per_unit') <small>(@lang('index.purchase'))</small><span
                                class="required_star">*</span></label>
                        <div class="input-group">
                            <input type="text" name="rate_per_unit" id="rate_per_unit"
                                class="form-control @error('rate_per_unit') is-invalid @enderror integerchk consumption"
                                placeholder="Rate Per Unit"
                                value="{{ isset($obj->rate_per_unit) ? $obj->rate_per_unit : old('rate_per_unit') }}">
                            <span class="input-group-text">
                                {{ getCurrencyOnly() }}
                            </span>
                        </div>
                        @error('rate_per_unit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <div class="form-group">
                            <label>@lang('index.consumption_unit_is_different')</label>
                            <input type="checkbox" name="consumption_check" id="consumption_check"
                                class="consumption_check field_clear" value="1"
                                {{ isset($obj->consumption_check) && $obj->consumption_check || old('consumption_check') ? 'checked' : '' }}>
                            @error('consumption_check')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row {{ isset($obj->consumption_check) && $obj->consumption_check == '1' || old('consumption_check') == 1 ? 'd-flex' : 'd-none' }}"
                    id="consumption_div">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>@lang('index.consumption_unit') <span class="required_star">*</span></label>
                            <select class="form-control @error('consumption_unit') is-invalid @enderror select2"
                                name="consumption_unit" id="consumption_unit">
                                <option class="cuempty" value="">Select</option>
                                @foreach ($units as $value)
                                    <option
                                        {{ (isset($obj->consumption_unit) && $obj->consumption_unit == $value->id) || old('consumption_unit') == $value->id ? 'selected' : '' }}
                                        value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('consumption_unit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <table class="width_100_p">
                                <tr>
                                    <td> <label>@lang('index.conversion_rate') <span class="required_star">*</span></label>
                                        <input type="text" name="conversion_rate" id="conversion_rate"
                                            class="form-control @error('conversion_rate') is-invalid @enderror integerchk consumption"
                                            onfocus="select()" min="1" placeholder="Conversion Rate"
                                            value="{{ isset($obj->conversion_rate) ? $obj->conversion_rate : old('conversion_rate') }}">
                                        @error('conversion_rate')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="custom_label">@lang('index.rate_per_consumption_unit')</label>
                        <div class="input-group">
                            <input type="text" name="rate_per_consumption_unit" id="rate_per_consumption_unit"
                                class="form-control @error('rate_per_consumption_unit') is-invalid @enderror integerchk change_consumption_cost rate_per_consumption_unit"
                                readonly placeholder="Cost in Consumption Unit"
                                value="{{ isset($obj->rate_per_consumption_unit) ? $obj->rate_per_consumption_unit : old('rate_per_consumption_unit') }}">
                            <span class="input-group-text">
                                {{ getCurrencyOnly() }}
                            </span>
                        </div>
                        @error('rate_per_consumption_unit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-4">
                        <label class="custom_label">@lang('index.opening_stock')</label>
                        <div class="input-group">
                            <input type="text" name="opening_stock" id="opening_stock"
                                class="form-control @error('opening_stock') is-invalid @enderror integerchk"
                                min="1" placeholder="Opening Stock"
                                value="{{ isset($obj->opening_stock) ? $obj->opening_stock : old('opening_stock') }}">
                            <span class="input-group-text opening_stock_unit">
                                {{ isset($obj) ? (isset($obj->consumption_check) && $obj->consumption_check == '1' ? getRMUnitById($obj->consumption_unit) : getRMUnitById($obj->unit)) : 'pcs' }}
                            </span>
                        </div>
                        @error('opening_stock')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="col-sm-12 mb-2 col-md-4">
                        <label class="custom_label">@lang('index.alter_level')</label>
                        <div class="input-group">
                            <input type="text" name="alert_level" id="alert_level"
                                class="form-control @error('alert_level') is-invalid @enderror integerchk" min="1"
                                placeholder="Alert Level" value="{{ isset($obj->alert_level) ? $obj->alert_level : old('alert_level') }}">
                            <span class="input-group-text opening_stock_unit">
                                {{ isset($obj) ? (isset($obj->consumption_check) && $obj->consumption_check == '1' ? getRMUnitById($obj->consumption_unit) : getRMUnitById($obj->unit)) : 'pcs' }}
                            </span>
                        </div>
                        @error('alert_level')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        <a class="btn bg-second-btn" href="{{ route('rawmaterials.index') }}"><iconify-icon
                                icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    </div>
                </div>
                <!-- /.box-body -->
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <?php
    $baseURL = getBaseURL();
    ?>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/addRawMaterial.js' !!}"></script>
    
@endsection
