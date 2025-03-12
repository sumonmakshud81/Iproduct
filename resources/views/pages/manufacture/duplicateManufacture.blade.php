@extends('layouts.app')

@section('script_top')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'manufacture_form',
                    'method' => 'POST',
                    'enctype' => 'multipart/form-data',
                    'route' => ['duplicate_store'],
                ]) !!}
                @csrf
                {!! Form::hidden('stage_counter', null, ['class' => 'stage_counter', 'id' => 'stage_counter']) !!}
                {!! Form::hidden('stage_name', null, ['class' => 'stage_name', 'id' => 'stage_name']) !!}
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.reference_no') <span class="required_star">*</span></label>
                                {!! Form::text('reference_no', $ref_no, [
                                    'class' => 'check_required form-control',
                                    'id' => 'code',
                                    'onfocus' => 'select()',
                                    'placeholder' => 'Reference No',
                                ]) !!}
                                @if ($errors->has('reference_no'))
                                    <div class="denger_alert">
                                        {{ $errors->first('reference_no') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.manufacture_type') <span class="required_star">*</span></label>
                                <select class="form-control select2" name="manufacture_type" id="manufactures"
                                    required="required">
                                    <option value="">@lang('index.select')</option>
                                    <option
                                        {{ isset($obj->manufacture_type) && $obj->manufacture_type == 'ime' ? 'selected' : '' }}
                                        value="ime">@lang('index.instant_manufacture_entry')</option>
                                    <option
                                        {{ isset($obj->manufacture_type) && $obj->manufacture_type == 'mbs' ? 'selected' : '' }}
                                        value="mbs">@lang('index.manufacture_by_scheduling')</option>
                                    <option
                                        {{ isset($obj->manufacture_type) && $obj->manufacture_type == 'fco' ? 'selected' : '' }}
                                        value="fco">@lang('index.from_customer_order')</option>
                                </select>

                                @if ($errors->has('manufacture_type'))
                                    <div class="denger_alert">
                                        {{ $errors->first('manufacture_type') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.status') <span class="required_star">*</span></label>
                                <select class="form-control select2" name="manufacture_status" id="m_status"
                                    required="required">
                                    <option value="">@lang('index.select')</option>
                                    <option
                                        {{ isset($obj->manufacture_status) && $obj->manufacture_status == 'draft' ? 'selected' : '' }}
                                        value="draft">@lang('index.draft')</option>
                                    <option
                                        {{ isset($obj->manufacture_status) && $obj->manufacture_status == 'inProgress' ? 'selected' : '' }}
                                        value="inProgress">@lang('index.in_progress')</option>
                                    <option
                                        {{ isset($obj->manufacture_status) && $obj->manufacture_status == 'done' ? 'selected' : '' }}
                                        value="done">@lang('index.done')</option>
                                </select>
                                @if ($errors->has('manufacture_status'))
                                    <div class="denger_alert">
                                        {{ $errors->first('manufacture_status') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.start_date') <span class="required_star">*</span></label>
                                {!! Form::text(
                                    'start_date',
                                    isset($obj->start_date) && $obj->start_date ? $obj->start_date : date('Y-m-d', strtotime('today')),
                                    ['class' => 'form-control customDatepicker', 'readonly' => '', 'placeholder' => 'Start Date'],
                                ) !!}
                                @if ($errors->has('start_date'))
                                    <div class="denger_alert">
                                        {{ $errors->first('start_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.complete_date') </label>
                                {!! Form::text(
                                    'complete_date',
                                    isset($obj->complete_date) && $obj->complete_date ? $obj->complete_date : date('Y-m-d', strtotime('today')),
                                    ['class' => 'form-control customDatepicker', 'readonly' => '', 'placeholder' => 'Complete Date'],
                                ) !!}
                                @if ($errors->has('complete_date'))
                                    <div class="denger_alert">
                                        {{ $errors->first('complete_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div id="customer_order_area" class="row"></div>

                        <div class="clearfix"></div>
                        <?php $st_method = ''; ?>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.product') <span class="required_star">*</span></label>
                                <select class="form-control select2 fproduct_id" name="product_id" id="fproduct_id"
                                    required="required">
                                    <option value="">@lang('index.select')</option>
                                    @if (isset($manufactures) && $manufactures)
                                        @foreach ($manufactures as $value)
                                            <option
                                                {{ isset($obj->product_id) && $obj->product_id == $value->id ? 'selected' : '' }}
                                                value="{{ $value->id . '|' . $value->stock_method }}">{{ $value->name }}</option>
                                            @php
                                                if (isset($obj->product_id) && $obj->product_id == $value->id) {
                                                    $st_method = $value->stock_method;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('product_id'))
                                    <div class="denger_alert">
                                        {{ $errors->first('product_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="col-sm-12 mb-2 col-md-2">
                            <div class="form-group">
                                <label>@lang('index.quantity') <span class="required_star">*</span></label>
                                {!! Form::number('product_quantity', null, [
                                    'class' => 'check_required form-control product_quantity',
                                    'id' => 'product_quantity',
                                    'placeholder' => 'Quantity',
                                ]) !!}
                                @if ($errors->has('product_quantity'))
                                    <div class="denger_alert">
                                        {{ $errors->first('product_quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-2 none_method fefo_method @if(in_array($st_method, ['none', 'fifo', 'fefo'])) d-none @endif">
                            <div class="form-group">
                                <label>@lang('index.batch_no') <span class="required_star">*</span></label>
                                {!! Form::text('batch_no', null, ['class' => 'form-control', 'id' => 'batch_no', 'placeholder' => 'Batch No']) !!}
                                @if ($errors->has('batch_no'))
                                    <div class="denger_alert">
                                        {{ $errors->first('batch_no') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-2 none_method batch_method @if(in_array($st_method, ['none', 'batchcontrol'])) d-none @endif">
                            <div class="form-group">
                                <label>@lang('index.expiry_days') <span class="required_star">*</span></label>
                                {!! Form::text('expiry_days', null, [
                                    'class' => 'form-control',
                                    'id' => 'expiry_days',
                                    'placeholder' => 'Expiry Days',
                                ]) !!}
                                @if ($errors->has('expiry_days'))
                                    <div class="denger_alert">
                                        {{ $errors->first('expiry_days') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-2">
                            <div class="form-group">
                                <button id="pr_go"
                                    class="btn bg-blue-btn w-100 goBtn govalid">@lang('index.go')</button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <h4 class="header_right">@lang('index.raw_material_consumption_cost') (BoM)</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="fprm">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="width_1_p">@lang('index.sn')</th>
                                            <th class="width_10_p">@lang('index.raw_materials')(@lang('index.code'))</th>
                                            <th class="width_20_p"> @lang('index.rate_per_unit') <i data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="Calculated based on Average of Rate Per Unit of Last 3 or 2 Purchase Price or Rate Per Unit in Material profile"
                                                    class="fa fa-question fa-lg base_color c_pointer tooltip_loss"
                                                    data-original-title="Calculated based on Average of Rate Per Unit of Last 3 or 2 Purchase Price or Rate Per Unit in Material profile"></i>
                                            </th>
                                            <th class="width_20_p"> @lang('index.consumption')</th>
                                            <th class="width_20_p">@lang('index.total_cost')</th>
                                            <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="add_trm">
                                        @if (isset($m_rmaterials) && $m_rmaterials)
                                            @foreach ($m_rmaterials as $key => $value)
                                                <tr class="rowCount" data-id="{{ $value->rmaterials_id }}">
                                                    <td class="width_1_p ir_txt_center">
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
                                                                class="check_required form-control integerchk unit_price_c cal_row"
                                                                placeholder="Unit Price" value="{{ $value->unit_price }}"
                                                                id="unit_price_1">
                                                            <span class="input-group-text">
                                                                {{ $setting->currency }}</span>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" data-countid="1" tabindex="51"
                                                                id="qty_1" name="quantity_amount[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control integerchk  qty_c cal_row"
                                                                value="{{ $value->consumption }}"
                                                                placeholder="Consumption">
                                                            <span
                                                                class="input-group-text">{{ getManufactureUnitByRMID($value->rmaterials_id) }}</span>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" id="total_1" name="total[]"
                                                                class="form-control total_c"
                                                                value="{{ $value->consumption_unit }}"
                                                                placeholder="Total" readonly="">
                                                            <span class="input-group-text">
                                                                {{ $setting->currency }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="ir_txt_center"><a class="btn btn-xs del_row"><i
                                                                class="color_red fa fa-trash"></i> </a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <button id="fprmaterial" class="btn bg-blue-btn w-10"
                                    type="button">@lang('index.add_more')</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="clearfix"></div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <label>@lang('index.total_raw_material_cost')</label>
                            <div class="input-group">
                                {!! Form::text('mrmcost_total', null, [
                                    'class' => 'form-control',
                                    'readonly' => '',
                                    'id' => 'rmcost_total',
                                    'placeholder' => __('index.total_raw_material_cost'),
                                ]) !!}
                                <span class="input-group-text">{{ $setting->currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="clearfix"></div>
                        <h4 class="">@lang('index.non_inventory_cost')</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" id="purchase_cart">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="width_1_p">@lang('index.sn')</th>
                                                <th class="width_20_p">@lang('index.non_inventory_items')</th>
                                                <th class="width_20_p"> @lang('index.non_inventory_item_cost') </th>
                                                <th class="width_20_p"> @lang('index.account') </th>
                                                <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="add_tnoni">
                                            @if (isset($m_nonitems) && $m_nonitems)
                                                @foreach ($m_nonitems as $key => $value)
                                                    <tr class="rowCount1" data-id="{{ $value->noninvemtory_id }}">
                                                        <td class="width_1_p ir_txt_center">
                                                            <p class="set_sn1"></p>
                                                        </td>
                                                        <td><input type="hidden" value="{{ $value->noninvemtory_id }}"
                                                                name="noniitem_id[]">
                                                            <span>{{ getNonInventroyItem($value->noninvemtory_id) }}</span>
                                                        </td>

                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" id="total_1" name="total_1[]"
                                                                    class="cal_row  form-control aligning total_c1"
                                                                    onfocus="select();" value="{{ $value->nin_cost }}"
                                                                    placeholder="Total">
                                                                <span class="input-group-text">
                                                                    {{ $setting->currency }}</span>
                                                            </div>
                                                        </td>
                                                        <td width="20%">
                                                            <select class="form-control select2" name="account_id[]"
                                                                id="account_id{{ $value->id }}">
                                                                <option value="">@lang('index.select')</option>
                                                                @foreach ($accounts as $account)
                                                                    <option
                                                                        @selected(isset($value->account_id) && $value->account_id == $account->id)
                                                                        id="account_id" class="account_id"
                                                                        value="{{ $account->id }}">{{ $account->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="ir_txt_center"><a class="btn btn-xs del_row"><i
                                                                    class="color_red fa fa-trash"></i> </a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <button id="fpnonitem" class="btn bg-blue-btn w-10"
                                        type="button">@lang('index.add_more')</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-3">
                                <label>@lang('index.total_non_inventory_cost')</label>
                                <div class="input-group">
                                    {!! Form::text('mnoninitem_total', null, [
                                        'class' => 'form-control',
                                        'readonly' => '',
                                        'id' => 'noninitem_total',
                                        'placeholder' => __('index.total_non_inventory_cost'),
                                    ]) !!}
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="clearfix"></div>
                        <h4 class="">@lang('index.manufacture_stages')</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="" id="purchase_cart">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="width_1_p">@lang('index.sn')</th>
                                                <th class="width_20_p stage_header">@lang('index.check')</th>
                                                <th class="width_20_p stage_header text-left">
                                                    @lang('index.stage')</th>
                                                <th class="width_20_p stage_header">@lang('index.required_time')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="add_tstage">
                                            @if (isset($finishProductStage) && $finishProductStage)
                                                <?php
                                                $total_month = 0;
                                                $total_day = 0;
                                                $total_hour = 0;
                                                $total_mimute = 0;
                                                $i = 1;
                                                ?>
                                                @foreach ($finishProductStage as $key => $value)
                                                    <?php
                                                    $checked = '';
                                                    $tmp_key = $key + 1;
                                                    if ($obj->stage_counter == $tmp_key) {
                                                        $checked = 'checked=checked';
                                                    }
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
                                                    <tr class="rowCount2" data-id="{{ $value->productionstage_id }}">
                                                        <td class="width_1_p ir_txt_center">
                                                            <p class="set_sn2"></p>
                                                        </td>
                                                        <td class="width_1_p"><input class="form-check-input set_class"
                                                                data-stage_name="{{ getProductionStages($value->productionstage_id) }}"
                                                                type="radio" name="stage_check"
                                                                value="{{ $i }}" {{ $checked }}></td>
                                                        <td class="stage_name text-left"><input
                                                                type="hidden" value="{{ $value->productionstage_id }}"
                                                                name="producstage_id[]">
                                                            <span>{{ getProductionStages($value->productionstage_id) }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input class="form-control stage_aligning"
                                                                            type="text" id="month_limit"
                                                                            name="stage_month[]" class="form-control"
                                                                            min="0" max="12"
                                                                            value="{{ $value->stage_month }}"
                                                                            placeholder="Month"><span
                                                                            class="input-group-text">@lang('index.months')</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input class="form-control stage_aligning"
                                                                            type="text" id="day_limit"
                                                                            name="stage_day[]" min="0"
                                                                            max="31"
                                                                            value="{{ $value->stage_day }}"
                                                                            placeholder="Days"><span
                                                                            class="input-group-text">@lang('index.days')</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input class="form-control stage_aligning"
                                                                            type="text" id="hours_limit"
                                                                            name="stage_hours[]" min="0"
                                                                            max="24"
                                                                            value="{{ $value->stage_hours }}"
                                                                            placeholder="Hours"><span
                                                                            class="input-group-text">@lang('index.hours')</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input class="form-control stage_aligning"
                                                                            type="text" id="minute_limit"
                                                                            name="stage_minute[]" min="0"
                                                                            max="60"
                                                                            value="{{ $value->stage_minute }}"
                                                                            placeholder="Minutes"><span
                                                                            class="input-group-text">@lang('index.minutes')</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tr>
                                            <td class="width_1_p"></td>
                                            <td class="width_1_p"></td>
                                            <td class="width_1_p">@lang('index.total')</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <input class="form-control stage_aligning stage_color" readonly
                                                                type="text" id="t_month"
                                                                value="{{ isset($total_months) && $total_months ? $total_months : '' }}"
                                                                placeholder="Months">
                                                            <span class="input-group-text">@lang('index.months')</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <input class="form-control stage_aligning stage_color" readonly
                                                                type="text" id="t_day"
                                                                value="{{ isset($total_days) && $total_days ? $total_days : '' }}"
                                                                placeholder="Days">
                                                            <span class="input-group-text">@lang('index.days')</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <input class="form-control stage_aligning stage_color" readonly
                                                                type="text" id="t_hours"
                                                                value="{{ isset($total_hours) && $total_hours ? $total_hours : '' }}"
                                                                placeholder="Hours">
                                                            <span class="input-group-text">@lang('index.hours')</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <input class="form-control stage_aligning stage_color" readonly
                                                                type="text" id="t_minute"
                                                                value="{{ isset($total_minutes) && $total_minutes ? $total_minutes : '' }}"
                                                                placeholder="Minutes">
                                                            <span class="input-group-text">@lang('index.minutes')</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>@lang('index.total_cost') <span class="required_star">*</span></label>
                            <div class="input-group">
                                {!! Form::text('mtotal_cost', null, [
                                    'class' => 'form-control total_cos margin_cal',
                                    'readonly' => '',
                                    'id' => 'total_cost',
                                    'placeholder' => 'Total Non Inventory Cost',
                                ]) !!}
                                <span class="input-group-text">{{ $setting->currency }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>@lang('index.profit_margin') (%)</label>
                            <div class="input-group">
                                {!! Form::text('mprofit_margin', null, [
                                    'class' => 'form-control profit_margin margin_cal integerchk',
                                    'id' => 'profit_margin',
                                    'placeholder' => 'Profit Margin',
                                ]) !!}
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $collect_tax = $tax_items->collect_tax;
                        $tax_information = json_decode(isset($obj->tax_information) && $obj->tax_information ? $obj->tax_information : '');
                        ?>
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
                                                    class="form-control integerchk get_percentage cal_row" placeholder=""
                                                    value="{{ $single_tax->tax_field_percentage }}">
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
                                            class="form-control integerchk get_percentage cal_row"
                                            placeholder="{{ $tax_field->tax }}" value="{{ $tax_field->tax_rate }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label>@lang('index.sale_price') <span class="required_star">*</span></label>
                            <div class="input-group">
                                {!! Form::text('msale_price', null, [
                                    'class' => 'form-control margin_cal sale_price',
                                    'readonly' => '',
                                    'id' => 'sale_price',
                                    'placeholder' => 'Sale Price',
                                ]) !!}
                                <span class="input-group-text">{{ $setting->currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md-6 mb-2">
                            <div class="form-group">
                                <label>@lang('index.note')</label>
                                {!! Form::textarea('note', null, ['class' => 'form-control', 'id' => 'note', 'placeholder' => 'Note']) !!}
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 mb-2">
                            <div class="form-group custom_table">
                                <label>@lang('index.add_a_file') (@lang('index.max_size_5_mb'))</label>
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="100%">
                                                <input type="hidden" name="file_old"
                                                    value="{{ isset($obj->file) && $obj->file ? $obj->file : '' }}">
                                                {!! Form::file('file_button', [
                                                    'class' => 'form-control file_checker_global',
                                                    'id' => 'file_button',
                                                    'accept' => 'image/png,image/jpeg,image/jgp,image/gif,application/pdf,.doc,.docx',
                                                    'data-this_file_size_limit' => '5',
                                                ]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                            <a class="btn bg-second-btn" href="{{ route('productions.index') }}"><iconify-icon icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <select id="ram_hidden" class="display_none" name="rmaterials_id">
                <option value="">@lang('index.select')</option>
                @foreach ($rmaterials as $value)
                    <option id="rmaterials_ids" class="rmaterials_ids" value="{{ $value->id . '|' . $value->unit . '|' . getPurchaseSaleUnitById($value->consumption_unit) . '|' . $setting->currency . '|' . $value->rate_per_consumption_unit . '|' . $value->rate_per_unit . '|' . getPurchaseUnitByRMID($value->id) }}">{{ $value->name }}</option>
                @endforeach
            </select>

            <select id="noni_hidden" class="display_none" name="noninvemtory_id">
                <option value="">@lang('index.select')</option>
                @foreach ($nonitem as $value)
                    <option id="noninvemtory_ids" class="noninvemtory_ids" value="{{ $value->id . '|' . $value->nin_cost . '|' . $setting->currency }}">
                        {{ $value->name }}</option>
                @endforeach
            </select>

            <select id="stages_hidden" class="display_none" name="productionstage_id">
                <option value="">@lang('index.select')</option>
                @foreach ($p_stages as $value)
                    <option id="noninvemtory_ids" class="noninvemtory_ids" value="{{ $value->id . '|' . $value->nin_cost . '|' . $setting->currency }}">
                        {{ $value->name }}</option>
                @endforeach
            </select>

            <select id="account_hidden" class="display_none" name="account_id">
                <option value="">@lang('index.select')</option>
                @foreach ($accounts as $value)
                    <option id="account_id" class="account_id" value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>

            <select id="customers_hidden" class="display_none" name="customers_id">
                <option value="">@lang('index.select')</option>
                @foreach ($customers as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
    </section>
@endsection

@section('script')
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/addManufactures.js?v=2.1' !!}"></script>
@endsection
