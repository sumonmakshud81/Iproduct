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
            <div class="table-box">
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'method' => isset($obj) && $obj ? 'PATCH' : 'POST',
                    'route' => ['suppliers.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.name') <span class="required_star">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Name" value="{{ isset($obj->name) && $obj->name ? $obj->name : old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.contact_person')</label>
                                <input type="text" name="contact_person" id="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    placeholder="Contact Person" value="{{ isset($obj->contact_person) && $obj->contact_person ? $obj->contact_person : old('contact_person') }}">
                                @error('contact_person')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.phone') <span class="required_star">*</span></label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Phone" value="{{ isset($obj->phone) && $obj->phone ? $obj->phone : old('phone') }}">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.email')</label>
                                <input type="text" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ isset($obj->email) && $obj->email ? $obj->email : old('email') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="d-flex justify-content-between">
                                <div class="form-group w-100 me-2">
                                    <label>@lang('index.opening_balance')</label>
                                    <input type="text" name="opening_balance" id="opening_balance"
                                        class="form-control @error('opening_balance') is-invalid @enderror integerchk"
                                        placeholder="Opening Balance" value="{{ isset($obj->opening_balance) && $obj->opening_balance ? $obj->opening_balance : old('opening_balance') }}">
                                    @error('opening_balance')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group w-100">
                                    <label>&nbsp;</label>
                                    <select class="form-control @error('opening_balance_type') is-invalid @enderror select2"
                                        name="opening_balance_type" id="opening_balance_type">
                                        <option value="Debit"
                                            {{ isset($obj) && $obj->opening_balance_type  == 'Debit' || old('opening_balance_type') == 'Debit' ? 'selected' : '' }}>
                                            @lang('index.debit')</option>
                                        <option value="Credit"
                                            {{ isset($obj) && $obj->opening_balance_type  == 'Credit' || old('opening_balance_type') == 'Credit' ? 'selected' : '' }}>
                                            @lang('index.credit')</option>
                                    </select>
                                    @error('opening_balance_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.credit_limit')</label>
                                    <div>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror integerchk"
                                            id="credit_limit" name="credit_limit" placeholder="Credit Limit"
                                            value="{{ isset($obj) && $obj->credit_limit ? $obj->credit_limit : old('credit_limit')  }}">
                                    </div>
                                </div>
                            </div>


                        

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.address')</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Address" rows="3">{{ isset($obj->address) && $obj->address ? $obj->address : old('address') }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group mb-3">
                                <label>@lang('index.note')</label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                    placeholder="note" rows="3">{{ isset($obj->note) && $obj->note ? $obj->note : old('note') }}</textarea>
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
                            <a class="btn bg-second-btn" href="{{ route('suppliers.index') }}"><iconify-icon
                                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script_bottom')
@endsection
