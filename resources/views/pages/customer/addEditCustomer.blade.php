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
                    'route' => ['customers.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.name') <span class="required_star">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="{{ __('index.name') }}"
                                    value="{{ isset($obj) && $obj->name ? $obj->name : old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.phone') <span class="required_star">*</span></label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="{{ __('index.phone') }}" value="{{ isset($obj) && $obj->phone ? $obj->phone : old('phone') }}">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.email')</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="{{ __('index.email') }}" value="{{ isset($obj) && $obj->email ? $obj->email : old('email') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between">
                                <div class="form-group w-100 me-2">
                                    <label>@lang('index.opening_balance')</label>
                                    <input type="text" name="opening_balance" id="opening_balance"
                                        class="form-control @error('opening_balance') is-invalid @enderror integerchk"
                                        placeholder="{{ __('index.opening_balance') }}" value="{{ isset($obj) && $obj->opening_balance ? $obj->opening_balance : old('opening_balance') }}">
                                    @error('opening_balance')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group w-100">
                                    <label>&nbsp;</label>
                                    <select class="form-control @error('opening_balance_type') is-invalid @enderror select2"
                                        name="opening_balance_type" id="opening_balance_type">
                                        <option value="Debit" {{ isset($obj) && $obj->opening_balance_type == 'Debit' || old('opening_balance_type') == 'Debit' ? 'selected' : '' }}>@lang('index.debit')</option>
                                        <option value="Credit" {{ isset($obj) && $obj->opening_balance_type == 'Credit' || old('opening_balance_type') == 'Credit' ? 'selected' : '' }}>@lang('index.credit')</option>
                                    </select>
                                    @error('opening_balance_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.credit_limit')</label>
                                <input type="text" name="credit_limit" id="credit_limit"
                                    class="form-control @error('credit_limit') is-invalid @enderror integerchk"
                                    placeholder="{{ __('index.credit_limit') }}" value="{{ isset($obj) && $obj->credit_limit ? $obj->credit_limit : old('credit_limit') }}">
                                @error('credit_limit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.default_discount')</label>
                                <input type="text" name="discount" id="discount"
                                    class="form-control @error('discount') is-invalid @enderror integerchkPercent"
                                    placeholder="{{ __('index.default_discount') }}" value="{{ isset($obj) && $obj->discount ? $obj->discount : old('discount') }}">
                                @error('discount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.customer_type')</label>
                                <select class="form-control @error('customer_type') is-invalid @enderror select2"
                                    name="customer_type" id="customer_type">
                                    <option value="Retail" {{ isset($obj) && $obj->customer_type || old('customer_type') == 'Retail' ? 'selected' : '' }}>@lang('index.retail')</option>
                                    <option value="Wholesale" {{ isset($obj) && $obj->customer_type || old('customer_type') == 'Wholesale' ? 'selected' : '' }}>@lang('index.wholesale')</option>
                                </select>
                                @error('customer_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.date_of_birth')</label>
                                <input type="text" name="date_of_birth" id="date"
                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                    placeholder="{{ __('index.date_of_birth') }}" autocomplete="off" value="{{ isset($obj) && $obj->date_of_birth ? $obj->date_of_birth : old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>@lang('index.date_of_anniversary')</label>
                                <input type="text" name="date_of_anniversary" id="dates2"
                                    class="form-control @error('date_of_anniversary') is-invalid @enderror"
                                    placeholder="{{ __('index.date_of_anniversary') }}" autocomplete="off" value="{{ isset($obj) && $obj->date_of_anniversary ? $obj->date_of_anniversary : old('date_of_anniversary') }}">
                                @error('date_of_anniversary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.address')</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="{{ __('index.address') }}" rows="3">{!! isset($obj) && $obj->address ? $obj->address : old('address') !!}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.note')</label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                    placeholder="{{ __('index.note') }}" rows="3">{{ isset($obj) && $obj->note ? $obj->note : old('note') }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                            <a class="btn bg-second-btn" href="{{ route('customers.index') }}"><iconify-icon
                                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script_bottom')
@endsection
