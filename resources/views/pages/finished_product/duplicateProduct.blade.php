@extends('layouts.app')

@section('script_top')
    <?php
    $setting = getSettingsInfo();
    $tax_setting = getTaxInfo();
    $baseURL = getBaseURL();
    ?>
@endsection

@section('content')

    <section class="main-content-wrapper">
        <section class="content-header">
            <h3 class="top-left-header">
                {{ isset($title) && $title ? $title : '' }}
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'product_form',
                    'method' => isset($obj) && $obj ? 'PATCH' : 'POST',
                    'enctype' => 'multipart/form-data',
                    'route' => ['finishedproducts.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.name') <span class="required_star">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="check_required form-control @error('name') is-invalid @enderror"
                                    placeholder="Name" value="{{ isset($obj) && $obj ? $obj->name : old('name') }}">
                                <div class="text-danger d-none"></div>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.code') <span class="required_star">*</span></label>
                                <input type="text" name="code" id="code"
                                    class="check_required form-control @error('code') is-invalid @enderror"
                                    placeholder="Code" value="{{ isset($obj->code) ? $obj->code : $ref_no }}"
                                    onfocus="select()">
                                <div class="text-danger d-none"></div>
                                @error('code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.category') <span class="required_star">*</span></label>
                                <select class="form-control @error('category') is-invalid @enderror select2" name="category"
                                    id="category_id">
                                    <option value="">@lang('index.select_category')</option>
                                    @foreach ($categories as $value)
                                        <option
                                            {{ isset($obj->category) && $obj->category == $value->id || old('category') == $value->id ? 'selected' : '' }}
                                            value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger d-none"></div>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.unit') <span class="required_star">*</span></label>
                                <select class="form-control @error('unit') is-invalid @enderror select2" name="unit"
                                    id="unit_id">
                                    <option value="">@lang('index.select_unit')</option>
                                    @foreach ($units as $value)
                                        <option
                                            {{ isset($obj->unit) && $obj->unit == $value->id || old('unit') == $value->id ? 'selected' : '' }}
                                            value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger d-none"></div>
                                @error('unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.stock_method') <span class="required_star">*</span></label>
                                <select class="form-control @error('stock_method') is-invalid @enderror select2"
                                    name="stock_method" id="stocks">
                                    <option value="">@lang('index.select')</option>
                                    <option
                                        {{ isset($obj->stock_method) && $obj->stock_method == 'none' || old('stock_method') == 'none' ? 'selected' : '' }}
                                        value="none">@lang('index.none')</option>
                                    <option
                                        {{ isset($obj->stock_method) && $obj->stock_method == 'fifo' || old('stock_method') == 'fifo' ? 'selected' : '' }}
                                        value="fifo">@lang('index.fifo')</option>
                                    <option
                                        {{ isset($obj->stock_method) && $obj->stock_method == 'batchcontrol' || old('stock_method') == 'batchcontrol' ? 'selected' : '' }}
                                        value="batchcontrol">@lang('index.batch_control')</option>
                                    <option
                                        {{ isset($obj->stock_method) && $obj->stock_method == 'fefo' || old('stock_method') == 'fefo' ? 'selected' : '' }}
                                        value="fefo">@lang('index.fefo')</option>
                                </select>
                                <div class="text-danger d-none"></div>
                                @error('stock_method')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <hr>
                        <h4 class="">@lang('index.raw_material_consumption_cost') (BoM)</h4>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.raw_material')<span class="required_star">*</span></label>
                                <select tabindex="4"
                                    class="form-control @error('rmaterial') is-invalid @enderror select2 select2-hidden-accessible"
                                    name="rmaterial" id="rmaterial">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($rmaterials as $rm)
                                        <?php
                                $totalStock = $rm->current_stock;
                                if ($totalStock > 0) :
                                    $last_p_price = getLastThreePurchasePrice($rm->id);
                                    $last_cp_price = getLastThreeCPurchasePrice($rm->id);
                                ?>
                                        @if ($rm->consumption_check == 0)
                                            <option value="{{ $rm->id . '|' . $rm->name . ' (' . $rm->code . ')|' . $rm->name . '|' . $rm->cost_in_consumption_unit . '|' . getPurchaseSaleUnitById($rm->unit) . '|' . $setting->currency . '|' . $last_p_price }}">{{ $rm->name . '(' . $rm->code . ')' }}</option>
                                        @else
                                            <option value="{{ $rm->id . '|' . $rm->name . ' (' . $rm->code . ')|' . $rm->name . '|' . $rm->cost_in_consumption_unit . '|' . getPurchaseSaleUnitById($rm->consumption_unit) . '|' . $setting->currency . '|' . $rm->rate_per_consumption_unit }}">{{ $rm->name . '(' . $rm->code . ')' }}
                                            </option>
                                        @endif

                                        <?php
                                endif;
                                ?>
                                    @endforeach
                                </select>

                                @error('rmaterial')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive rawmaterialsec" id="purchase_cart">
                                <table class="table">
                                    <thead>
                                        <th class="w-10 text-start">@lang('index.sn')</th>
                                        <th class="w-20">@lang('index.raw_material')(@lang('index.code'))</th>
                                        <th class="w-20"> @lang('index.unit_price')<span class="required_star">*</span>
                                            <i data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('index.purchase_unit_and_consumption_unit')"
                                        class="fa fa-question-circle base_color c_pointer"></i>
                                        </th>
                                        <th class="w-20"> @lang('index.consumption')<span class="required_star">*</span></th>
                                        <th class="w-20">@lang('index.total_cost') </th>
                                        <th class="w-10 text-end">@lang('index.actions')</th>
                                    </thead>
                                    <tbody class="add_tr">
                                        @if (isset($fp_rmaterials) && $fp_rmaterials)
                                            @foreach ($fp_rmaterials as $key => $value)
                                                <tr class="rowCount" data-id="{{ $value->rmaterials_id }}">
                                                    <td class="width_1_p">
                                                        <p class="set_sn"></p>
                                                    </td>
                                                    <td><input type="hidden" value="{{ $value->rmaterials_id }}"
                                                            name="rm_id[]">
                                                        <span>{{ getRMName($value->rmaterials_id) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" tabindex="5" name="unit_price[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control @error('title') is-invalid @enderror integerchk input_aligning unit_price_c cal_row"
                                                                placeholder="Unit Price" value="{{ $value->unit_price }}"
                                                                id="unit_price_1">
                                                            <span class="input-group-text"
                                                                id="basic-addon2">{{ $setting->currency }}</span>
                                                        </div>
                                                        <div class="text-danger d-none unitPriceErr"></div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" data-countid="1" tabindex="51"
                                                                id="qty_1" name="quantity_amount[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control @error('title') is-invalid @enderror integerchk input_aligning qty_c cal_row"
                                                                value="{{ $value->consumption }}"
                                                                placeholder="Consumption">
                                                            <span class="input-group-text"
                                                                id="basic-addon2">{{ getManufactureUnitByRMID($value->rmaterials_id) }}</span>
                                                        </div>
                                                        <div class="text-danger d-none qtyErr"></div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group mb-3">
                                                            <input type="number" id="total_1" name="total[]"
                                                                class="form-control @error('title') is-invalid @enderror input_aligning total_c"
                                                                value="{{ $value->consumption_unit }}"
                                                                placeholder="Total" readonly="">
                                                            <span class="input-group-text"
                                                                id="basic-addon2">{{ $setting->currency }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end"><a
                                                            class="btn btn-xs del_row dlt_button"><iconify-icon
                                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="w-10"></td>
                                        <td class="w-20"></td>
                                        <td class="w-20"></td>
                                        <td class="w-20"></td>
                                        <td class="w-20">
                                            <label>@lang('index.total_raw_material_cost') <span class="required_star">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="rmcost_total" id="rmcost_total"
                                                    class="form-control @error('title') is-invalid @enderror" readonly
                                                    placeholder="{{ __('index.total_raw_material_cost') }}">
                                                <span class="input-group-text">{{ $setting->currency }}</span>
                                            </div>
                                        </td>
                                        <td class="w-10 text-end"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="clearfix"></div>
                        <h4 class="">@lang('index.non_inventory_cost')</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('index.non_inventory_item')</label>
                                    <select tabindex="4"
                                        class="form-control @error('title') is-invalid @enderror select2 select2-hidden-accessible"
                                        name="noniitem" id="noniitem">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($nonitem as $rm)
                                            <option value="{{ $rm->id . '|' . $rm->name . '|' . $setting->currency }}">{{ $rm->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="purchase_cart">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="w-10 text-start">@lang('index.sn')</th>
                                            <th class="w-60">@lang('index.non_inventory_item')</th>
                                            <th class="w-20"> @lang('index.non_inventory_cost') </th>
                                            <th class="w-10 text-end">@lang('index.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="add_tr1">

                                        @if (isset($fp_nonitems) && $fp_nonitems)
                                            @foreach ($fp_nonitems as $key => $value)
                                                <tr class="rowCount1" data-id="{{ $value->noninvemtory_id }}">
                                                    <td class="width_1_p">
                                                        <p class="set_sn1"></p>
                                                    </td>
                                                    <td><input type="hidden" value="{{ $value->noninvemtory_id }}"
                                                            name="noniitem_id[]">
                                                        <span>{{ getNonInventroyItem($value->noninvemtory_id) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" id="total_1" name="total_1[]"
                                                                class="cal_row  form-control @error('title') is-invalid @enderror aligning total_c1"
                                                                onfocus="select();" value="{{ $value->nin_cost }}"
                                                                placeholder="Total">
                                                            <span class="input-group-text"
                                                                id="basic-addon2">{{ $setting->currency }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end"><a
                                                            class="btn btn-xs del_row dlt_button"><iconify-icon
                                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                                                        </a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="w-10"></td>
                                        <td class="w-60"></td>
                                        <td class="w-20">
                                            <label>@lang('index.total_non_inventory_cost')</label>
                                            <div class="input-group">
                                                <input type="text" name="noninitem_total" id="noninitem_total"
                                                    class="form-control @error('title') is-invalid @enderror" readonly
                                                    placeholder="{{ __('index.total_non_inventory_cost') }}">
                                                <span class="input-group-text">{{ $setting->currency }}</span>
                                            </div>
                                        </td>
                                        <td class="w-10 text-end"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <label>@lang('index.total_cost') <span class="required_star">*</span></label>
                            <div class="input-group">
                                <input type="text" name="total_cost" id="total_cost"
                                    class="form-control @error('title') is-invalid @enderror total_cos margin_cal"
                                    readonly placeholder="Total Non Inventory Cost">
                                <span class="input-group-text">{{ $setting->currency }}</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>@lang('index.profit_margin') (%)</label>
                            <div class="input-group">
                                <input type="text" name="profit_margin" id="profit_margin"
                                    class="form-control @error('title') is-invalid @enderror profit_margin margin_cal"
                                    placeholder="Profit Margin" value="{{ isset($obj) && $obj ? $obj->profit_margin : old('profit_margin') }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <?php
                        $collect_tax = $tax_items->collect_tax;
                        $tax_type = $tax_items->tax_type;
                        $tax_information = json_decode(isset($obj->tax_information) && $obj->tax_information ? $obj->tax_information : '');
                        ?>
                        <input type="hidden" name="tax_type" class="tax_type" value="{{ $tax_type }}">
                        @foreach ($tax_fields as $tax_field)
                            <div class="col-md-3 {{ isset($collect_tax) && $collect_tax == 'Yes' ? '' : 'd-none' }}">
                                @if ($tax_information)
                                    @foreach ($tax_information as $single_tax)
                                        @if ($tax_field->id == $single_tax->tax_field_id)
                                            <label>{{ $tax_field->tax }}</label>
                                            <input onfocus="select();" tabindex="1" type="hidden"
                                                name="tax_field_id[]" value="{{ $single_tax->tax_field_id }}">
                                            <input onfocus="select();" tabindex="1" type="hidden"
                                                name="tax_field_name[]" value="{{ $single_tax->tax_field_name }}">
                                            <div class="input-group">
                                                <input onfocus="select();" tabindex="1" type="text"
                                                    name="tax_field_percentage[]"
                                                    class="form-control @error('title') is-invalid @enderror integerchk get_percentage cal_row"
                                                    placeholder="" value="{{ $single_tax->tax_field_percentage }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <label>{{ $tax_field->tax }}</label>
                                    <input onfocus="select();" tabindex="1" type="hidden" name="tax_field_id[]"
                                        value="{{ $tax_field->id }}">
                                    <input onfocus="select();" tabindex="1" type="hidden" name="tax_field_name[]"
                                        value="{{ $tax_field->tax }}">
                                    <div class="input-group">
                                        <input onfocus="select();" tabindex="1" type="text"
                                            name="tax_field_percentage[]"
                                            class="form-control @error('title') is-invalid @enderror integerchk get_percentage cal_row"
                                            placeholder="{{ $tax_field->tax }}" value="{{ $tax_field->tax_rate }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label>@lang('index.sale_price') <span class="required_star">*</span></label>
                            <div class="input-group">
                                <input type="text" name="sale_price" id="sale_price"
                                    class="form-control @error('title') is-invalid @enderror margin_cal sale_price"
                                    readonly placeholder="Sale Price" value="{{ isset($obj) && $obj ? $obj->sale_price : old('sale_price') }}">
                                <span class="input-group-text">{{ $setting->currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr>
                    <h4 class="">@lang('index.manufacture_stages')</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('index.manufacture_stages')</label>
                                <select tabindex="4"
                                    class="form-control @error('productionstage') is-invalid @enderror select2 select2-hidden-accessible"
                                    name="productionstage" id="productionstage">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($productionstage as $ps)
                                        <option value="{{ $ps->id . '|' . $ps->name }}">{{ $ps->name }}</option>
                                    @endforeach
                                </select>

                                @error('productionstage')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="purchase_cart">
                                <table class="table" id="drageable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="width_1_p text-start">@lang('index.sn')</th>
                                            <th class="width_20_p stage_header text-left">
                                                @lang('index.stage')</th>
                                            <th class="width_20_p stage_header">@lang('index.required_time')</th>
                                            <th class="width_1_p ir_txt_center">@lang('index.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="add_tr2 sort_menu">

                                        @if (isset($fp_productionstages) && $fp_productionstages)
                                            @php
                                                $total_month = 0;
                                                $total_hour = 0;
                                                $total_day = 0;
                                                $total_mimute = 0;
                                            @endphp
                                            @foreach ($fp_productionstages as $key => $value)
                                                <?php                                               

                                                $total_value = $value->stage_month * 2592000 + $value->stage_day * 86400 + $value->stage_hours * 3600 + $value->stage_minute * 60;
                                                $months = floor($total_value / 2592000);
                                                $hours = floor(($total_value % 86400) / 3600);
                                                $days = floor(($total_value % 2592000) / 86400);
                                                $minuts = floor(($total_value % 3600) / 60);
                                                
                                                $total_month += $months;
                                                $total_hour += $hours;
                                                $total_day += $days;
                                                $total_mimute += $minuts;
                                                
                                                $total_stages = $total_month * 2592000 + $total_hour * 3600 + $total_day * 86400 + $total_mimute * 60;
                                                $total_months = floor($total_stages / 2592000);
                                                $total_hours = floor(($total_stages % 86400) / 3600);
                                                $total_days = floor(($total_stages % 2592000) / 86400);
                                                $total_minutes = floor(($total_stages % 3600) / 60);
                                                
                                                ?>
                                                <tr class="rowCount2 align-middle ui-state-default" data-id="{{ $value->productionstage_id }}">
                                                <td><span class="handle me-2"><iconify-icon icon="radix-icons:move"></iconify-icon></span></td>
                                                    <td class="width_1_p">
                                                        <p class="set_sn2 m-0"></p>
                                                    </td>
                                                    <td class="stage_name text-left"><input type="hidden"
                                                            value="{{ $value->productionstage_id }}"
                                                            name="producstage_id[]">
                                                        <span>{{ getProductionStages($value->productionstage_id) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="input-group"><input
                                                                        class="form-control @error('title') is-invalid @enderror stage_aligning"
                                                                        type="text" id="month_limit"
                                                                        name="stage_month[]" min="0"
                                                                        max="02" value="{{ $value->stage_month }}"
                                                                        placeholder="Month"><span
                                                                        class="input-group-text">@lang('index.months')</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="input-group"><input
                                                                        class="form-control @error('title') is-invalid @enderror stage_aligning"
                                                                        type="text" id="day_limit" name="stage_day[]"
                                                                        min="0" max="31"
                                                                        value="{{ $value->stage_day }}"
                                                                        placeholder="Days"><span
                                                                        class="input-group-text">@lang('index.days')</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="input-group"><input
                                                                        class="form-control @error('title') is-invalid @enderror stage_aligning"
                                                                        type="text" id="hours_limit"
                                                                        name="stage_hours[]" min="0"
                                                                        max="24" value="{{ $value->stage_hours }}"
                                                                        placeholder="Hours"><span
                                                                        class="input-group-text">@lang('index.hours')</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="input-group"><input
                                                                        class="form-control @error('title') is-invalid @enderror stage_aligning"
                                                                        type="text" id="minute_limit"
                                                                        name="stage_minute[]" min="0"
                                                                        max="60" value="{{ $value->stage_minute }}"
                                                                        placeholder="Minutes"><span
                                                                        class="input-group-text">@lang('index.minutes')</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="ir_txt_center"><a
                                                            class="btn btn-xs del_row dlt_button"><iconify-icon
                                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tr class="align-middle">
                                        <td></td>
                                        <td class="width_1_p"></td>
                                        <td class="width_1_p">@lang('index.total')</td>
                                        <td class="width_20_p stage_header">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @error('title') is-invalid @enderror stage_aligning stage_color"
                                                            readonly type="text" id="t_month"
                                                            value="{{ isset($total_months) && $total_months ? $total_months : '' }}"
                                                            placeholder="Months">
                                                        <span class="input-group-text">@lang('index.months')</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @error('title') is-invalid @enderror stage_aligning stage_color"
                                                            readonly type="text" id="t_day"
                                                            value="{{ isset($total_days) && $total_days ? $total_days : '' }}"
                                                            placeholder="Days">
                                                        <span class="input-group-text">@lang('index.days')</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @error('title') is-invalid @enderror stage_aligning stage_color"
                                                            readonly type="text" id="t_hours"
                                                            value="{{ isset($total_hours) && $total_hours ? $total_hours : '' }}"
                                                            placeholder="Hours">
                                                        <span class="input-group-text">@lang('index.hours')</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <input
                                                            class="form-control @error('title') is-invalid @enderror stage_aligning stage_color"
                                                            readonly type="text" id="t_minute"
                                                            value="{{ isset($total_minutes) && $total_minutes ? $total_minutes : '' }}"
                                                            placeholder="Minutes">
                                                        <span class="input-group-text">@lang('index.minutes')</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="width_1_p ir_txt_center"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        <a class="btn bg-second-btn" href="{{ route('finishedproducts.index') }}"><iconify-icon
                                icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>


@endsection

@section('script')
    <script type="text/javascript" src="{!!  $baseURL . 'assets/bower_components/jquery-ui/jquery-ui.min.js'  !!}"></script>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/addFinishedProduct.js?v=1.2' !!}"></script>
@endsection
