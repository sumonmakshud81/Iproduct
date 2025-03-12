@extends('layouts.app')

@section('script_top')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    ?>
@endsection

@section('content')
    <section class="main-content-wrapper">
        @include('utilities.messages')
        <section class="content-header">
            <h3 class="top-left-header">
                {{ isset($title) && $title ? $title : '' }}
            </h3>
        </section>


        <div class="box-wrapper">
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'purchase_form',
                    'method' => 'POST',
                    'route' => isset($obj) && $obj ? ['stockAdjustUpdate', encrypt_decrypt($obj->id, 'encrypt')] : 'stockAdjustPost',
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.raw_material') <span class="required_star">*</span> (@lang('index.only_stock_available_are_listed'))</label>
                                <select tabindex="4"
                                    class="form-control @error('rmaterial') is-invalid @enderror select2 select2-hidden-accessible"
                                    name="rmaterial" id="rmaterial">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($rmaterials as $rm)
                                        @php
                                            $totalStock = $rm->total_purchase * $rm->conversion_rate - $rm->total_rm_waste + $rm->opening_stock;
                                            if ($totalStock > 0) {
                                                $last_p_price = getLastThreePurchasePrice($rm->id);
                                            }
                                        @endphp
                                        @if($totalStock > 0)
                                            <option {{ isset($obj) && $obj->rm_id == $rm->id ? 'selected' : '' }} value="{{ $rm->id . '|' . $rm->name . ' (' . $rm->code . ')|' . $rm->name . '|' . $last_p_price . '|' . $rm->purchase_unit_name . '|' . $setting->currency . '|' . $totalStock }}">{{ $rm->name . '(' . $rm->code . ')' }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @error('rmaterial')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label>@lang('index.type') <span class="required_star">*</span></label>
                                <select name="type" id="type"
                                    class="form-control @error('type') is-invalid @enderror select2">
                                    <option value="">@lang('index.select')</option>
                                    <option value="addition" {{ isset($obj) && $obj->type == 'addition' ? 'selected' : '' }}>@lang('index.addition')</option>
                                    <option value="subtraction" {{ isset($obj) && $obj->type == 'subtraction' ? 'selected' : '' }}>@lang('index.subtraction')</option>
                                </select>
                            </div>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.quantity') <span class="required_star">*</span></label>
                                <input type="text" name="quantity" id="quantity"
                                    class="check_required form-control @error('quantity') is-invalid @enderror"
                                    placeholder="{{ __('index.quantity') }}" value="{{ isset($obj) && $obj->quantity ? $obj->quantity : '' }}">
                            </div>
                            @error('quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        <a class="btn bg-second-btn" href="{{ route('getRMStock') }}"><iconify-icon
                                icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection
