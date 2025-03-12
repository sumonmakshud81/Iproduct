@extends('layouts.app')
@section('content')
    @php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    @endphp

    <!-- Main content -->
    <section class="main-content-wrapper">
        @include('utilities.messages')

        <section class="content-header">
            <h3 class="top-left-header">
                @lang('index.tax_settings')
            </h3>

        </section>

        <div class="box-wrapper">
            <div class="table-box">

                {!! Form::model(isset($tax_items) && $tax_items ? $tax_items : '', [
                    'method' => 'POST',
                    'id' => 'tax_update',
                    'route' => ['tax.update'],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-sm-12 col-md-6">
                            <div class="form-group radio_button_problem">
                                <label>@lang('index.collect_tax') <span class="required_star">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input tabindex="1" type="radio" name="collect_tax" id="collect_tax_yes"
                                            value="Yes" @checked(isset($tax_items) && $tax_items->collect_tax == 'Yes')>@lang('index.yes')
                                    </label>
                                    <label>
                                        <input tabindex="2" type="radio" name="collect_tax" id="collect_tax_no"
                                            value="No" @checked(isset($tax_items) && $tax_items->collect_tax == 'No' || isset($tax_items) && ($tax_items->collect_tax != 'Yes' && $tax_items->collect_tax != 'No'))>@lang('index.no')
                                    </label>
                                </div>
                            </div>
                            @error('collect_tax')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 tax_yes_section @if(isset($tax_items) && $tax_items->collect_tax == 'Yes') d-block; @else d-none; @endif">
                            <div class="form-group">
                                <label>@lang('index.tax_registration_number') <span class="required_star">*</span></label>
                                <input type="text" name="tax_registration_number" id="tax_registration_number"
                                    class="form-control @error('tax_registration_number') is-invalid @enderror"
                                    placeholder="{{ __('index.tax_registration_number') }}"
                                    value="{{ isset($tax_items->tax_registration_number) ? $tax_items->tax_registration_number : null }}">
                                @error('tax_registration_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-3 tax_yes_section @if(isset($tax_items) && $tax_items->collect_tax == 'Yes') d-block; @else d-none; @endif">
                            <div class="form-group">
                                <label>@lang('index.tax_type') <span class="required_star">*</span></label>

                                <select name="tax_type" id="tax_type"
                                    class="form-control @error('tax_type') is-invalid @enderror select2"
                                    placeholder="{{ __('index.tax_type') }}">
                                    <option value="Inclusive" @selected(isset($tax_items) && $tax_items->tax_type == 'Inclusive')>{{ __('index.inclusive_tax') }}</option>
                                    <option value="Exclusive" @selected(isset($tax_items) && $tax_items->tax_type == 'Exclusive')>{{ __('index.exclusive_tax') }}</option>
                                </select>
                                @error('tax_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group tax_yes_section @if(isset($tax_items) && $tax_items->collect_tax == 'Yes') d-block; @else d-none; @endif">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="width_1_p">@lang('index.sn')</th>
                                                <th class="width_20_p">@lang('index.tax_name')</th>
                                                <th class="width_20_p">@lang('index.tax_rate')</th>
                                                <th class="width_1_p ir_txt_center"></th>
                                                <th class="width_1_p ir_txt_center"><span id="remove_all_taxes"
                                                        class="txt-uh-72">@lang('index.actions')</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tax_table_body">
                                            @php
                                            $show_tax_row = '';
                                            if (isset($taxes) && count($taxes) > 0) {
                                                foreach ($taxes as $single_tax) {
                                                    $show_tax_row .= '<tr class="tax_single_row">';
                                                    $show_tax_row .= '<td class="set_sn ir_txt_center align-middle"></td>';
                                                    $show_tax_row .= '<td><input type="hidden" name="p_tax_id[]" value="' . $single_tax->id . '" /> <input type="text" onfocus="select()"  name="taxes[]" class="form-control" value="' . $single_tax->tax . '"/></td>';
                                                    $show_tax_row .= '<td><input type="text" onfocus="select()" name="tax_rate[]" class="form-control" value="' . $single_tax->tax_rate . '" /></td>';
                                                    $show_tax_row .= '<td class="align-middle">%</td>';
                                                    $show_tax_row .= '<td class="align-middle ir_txt_center"><span class="remove_this_tax_row txt-uh-72 dlt_button"><iconify-icon icon="solar:trash-bin-minimalistic-broken"></iconify-icon></span></td>';
                                                    $show_tax_row .= '</tr>';
                                                }
                                            }
                                            
                                            echo $show_tax_row;
                                            @endphp
                                        </tbody>
                                    </table>
                                    <button id="add_tax" class="btn bg-blue-btn"
                                        type="button">@lang('index.add_more')</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row py-2">
                        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        </div>

                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
    </section>
@endsection

@section('script')
    <script src="{!! $baseURL . 'frequent_changing/js/taxes.js' !!}"></script>
@endsection
