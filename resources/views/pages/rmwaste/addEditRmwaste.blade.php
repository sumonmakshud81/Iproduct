@extends('layouts.app')

@section('script_top')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
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
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'purchase_form',
                    'method' => isset($obj) && $obj ? 'PATCH' : 'POST',
                    'route' => ['rmwastes.update', isset($obj->id) && $obj->id ? $obj->id : ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.reference_no') <span class="required_star">*</span></label>
                                <input type="text" name="reference_no" id="reference_no"
                                    class="check_required form-control @error('reference_no') is-invalid @enderror" readonly
                                    placeholder="Reference No"
                                    value="{{ isset($obj->reference_no) ? $obj->reference_no : $ref_no }}">
                            </div>
                            @error('reference_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label>@lang('index.date') <span class="required_star">*</span></label>
                                <input type="text" name="date" id="date"
                                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly
                                    placeholder="Date"
                                    value="{{ isset($obj->date) ? $obj->date : old('date') }}">
                                <p class="text-danger d-none" id="date_error"></p>
                                @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label>@lang('index.responsible_person') <span class="required_star">*</span></label>
                                <select name="responsible_person" id="res_person"
                                    class="form-control @error('responsible_person') is-invalid @enderror select2">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($users as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ (isset($obj->responsible_person) && $obj->responsible_person) || old('responsible_person') == $value->id ? 'selected' : '' }}>
                                            {{ $value->name }} ({{ $value->phone_number }})</option>
                                    @endforeach
                                </select>                                
                            </div>
                            <p class="text-danger d-none" id="responsible_person_error"></p>
                            @error('responsible_person')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.raw_material') <span class="required_star">*</span> (@lang('index.only_stock_available_are_listed'))</label>
                                <select
                                    class="form-control @error('rmaterial') is-invalid @enderror select2 select2-hidden-accessible"
                                    name="rmaterial" id="rmaterial">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($rmaterials as $rm)
                                        @if ($rm->current_stock > 0)
                                            @php
                                                $last_p_price = getLastThreePurchasePrice($rm->id);
                                                $option_value = $rm->id . '|' . $rm->name . ' (' . $rm->code . ')|' . $rm->name . '|' . $last_p_price . '|' . getPurchaseUnitByRMID($rm->id) . '|' . $setting->currency . '|' . $rm->current_stock . '|' . $rm->current_stock_final . '|' . getTotalFloatingStockRawMaterial($rm->id) . '|' . $rm->consumption_check ;
                                            @endphp
                                            <option value="{{ $option_value }}">{{ $rm->name . '(' . $rm->code . ')' }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @error('rmaterial')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="purchase_cart">                                
                                <table class="table">
                                    <thead>
                                        <th class="w-5 text-start">@lang('index.sn')</th>
                                        <th class="w-30">@lang('index.raw_material')(@lang('index.code'))</th>
                                        <th class="w-20"></th>
                                        <th class="w-20">@lang('index.quantity')</th>
                                        <th class="w-20">@lang('index.loss_amount')
                                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Calcualted based on Average of Rate Per Unit of Last 3 or 2 Purchase Price or Rate Per Unit in Material profile"></i>
                                            
                                        </th>
                                        <th class="w-5 ir_txt_center">@lang('index.actions')</th>
                                    </thead>
                                    <tbody class="add_tr">                                        
                                        @if (isset($pruchse_rmaterials) && $pruchse_rmaterials)
                                            @foreach ($pruchse_rmaterials as $key => $value)
                                                <tr class="rowCount" data-id="{{ $value->rmaterials_id }}">
                                                    <td class="width_1_p ir_txt_center">
                                                        <p class="set_sn"></p>
                                                    </td>
                                                    <td><input type="hidden" value="{{ $value->rmaterials_id }}"
                                                            name="rm_id[]"> <span
                                                            class="name_id_{{ $value->rmaterials_id }}">{{ getRMName($value->rmaterials_id) }}</span>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <input type="hidden" tabindex="5" name="unit_price[]"
                                                            onfocus="this.select();"
                                                            class="check_required form-control @error('unit_price') is-invalid @enderror integerchk input_aligning unit_price_c cal_row"
                                                            placeholder="Unit Price" value="{{ $value->loss_amount }}"
                                                            id="unit_price_1">
                                                        <div class="input-group">
                                                            <input type="number" data-countid="1" tabindex="51"
                                                                id="qty_1" name="quantity_amount[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control @error('quantity_amount') is-invalid @enderror integerchk aligning qty_c cal_row"
                                                                value="{{ $value->waste_amount }}"
                                                                data-stock="{{ $value->waste_amount + getCurrentStock($value->rmaterials_id) }}"
                                                                data-unit="{{ getPurchaseUnitByRMID($value->rmaterials_id) }}"
                                                                placeholder="Qty/Amount">
                                                            <span
                                                                class="input-group-text">{{ getPurchaseUnitByRMID($value->rmaterials_id) }}</span>
                                                        </div>

                                                    </td>

                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" onfocus="select();" id="total_1"
                                                                name="total[]"
                                                                class="form-control @error('total') is-invalid @enderror input_aligning total_c cal_total"
                                                                value="{{ $value->loss_amount }}" placeholder="Total">
                                                            <span class="input-group-text">{{ $setting->currency }}</span>
                                                        </div>
                                                    </td>

                                                    <td class="ir_txt_center"><a
                                                            class="remove_this_tax_row del_row dlt_button"><iconify-icon
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
                                        <td class="w-5"></td>
                                        <td class="w-30"></td>
                                        <td class="w-20"></td>
                                        <td class="w-20"></td>
                                        <td class="w-20">
                                            <label>@lang('index.g_total') <span class="required_star">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="grand_total" id="grand_total"
                                                    class="form-control @error('title') is-invalid @enderror" readonly
                                                    placeholder="G.Total"
                                                    value="{{ isset($obj->grand_total) ? $obj->grand_total : (isset($subtotal_shoratage) ? $subtotal_shoratage : null) }}">
                                                <span class="input-group-text">{{ $setting->currency }}</span>
                                            </div>
                                        </td>
                                        <td class="w-5 text-end"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('index.note')</label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note"
                                    rows="3">{{ isset($obj->note) ? $obj->note : old('note') }}</textarea>
                                @error('note')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                        <a class="btn bg-second-btn" href="{{ route('rmwastes.index') }}"><iconify-icon
                                icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>

@endsection

@section('script')
    <?php
    $baseURL = getBaseURL();
    ?>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/addRMWaste.js' !!}"></script>
@endsection
