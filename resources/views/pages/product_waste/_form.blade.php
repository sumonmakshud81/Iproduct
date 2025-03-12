<div>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.reference_no') <span class="required_star">*</span></label>

                <input type="text" name="reference_no" id="code"
                    class="check_required form-control @error('reference_no') is-invalid @enderror"
                    placeholder="Reference No" value="{{ isset($obj->reference_no) ? $obj->reference_no : $ref_no }}"
                    onfocus="select()" readonly>

                @error('reference_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly
                    placeholder="Date" value="{{ isset($obj->date) ? $obj->date : old('date') }}">
                <p class="text-danger d-none m-0" id="date_error"></p>
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
                            {{ isset($obj->responsible_person) && $obj->responsible_person == $value->id || old('responsible_person') == $value->id ? 'selected' : '' }}>
                            {{ $value->name }} ({{ $value->phone_number }})</option>
                    @endforeach
                </select>
                <p class="text-danger d-none m-0" id="res_person_error"></p>
            </div>
            @error('responsible_person')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="clearfix"></div>
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <?php
                $finished_product_old = old('finished_product');
                ?>
                <label>@lang('index.finished_product') <span class="required_star">*</span></label>
                <select class="form-control @error('finish_product_id') is-invalid @enderror select2"
                    id="finished_product" name="finished_product">
                    <option value="">@lang('index.select')</option>
                    @foreach ($finished_products as $value)
                        <option
                            value="{{ $value->id }}|{{ $value->name }}|{{ $value->code }}|{{ lastProductionCost($value->id) }}|{{ getPurchaseSaleUnitById($value->unit) }}|{{ $setting->currency }}|{{ $value->current_total_stock }}|{{ $value->stock_method }}">
                            {{ $value->name }}({{ $value->code }})
                        </option>
                    @endforeach
                </select>
                @error('finish_product_id')
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
                        <th class="w-40">@lang('index.raw_materials')(@lang('index.code'))</th>
                        <th class="w-20">@lang('index.quantity_amount')</th>
                        <th class="w-20">@lang('index.loss_amount') 
                        </th>
                        <th class="w-5 ir_txt_center">@lang('index.actions')</th>
                    </thead>
                    <tbody class="add_tr">
                        @if (isset($product_wast_items) && $product_wast_items)
                            @foreach ($product_wast_items as $key => $value)
                                <tr class="rowCount" data-id="{{ $value->finish_product_id }}">
                                    <td class="width_1_p text-start">
                                        <p class="set_sn"></p>
                                    </td>
                                    <td>
                                        <input type="hidden" value="{{ $value->finish_product_id }}"
                                            name="product_id[]">
                                        <input type="hidden" value="{{ $value->manufacture_id }}"
                                            name="manufacture_id[]">
                                        <span class="name_id_{{ $value->finish_product_id }}">
                                            {{ getFinishProduct($value->finish_product_id) }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="hidden" tabindex="5" name="unit_price[]"
                                            onfocus="this.select();"
                                            class="check_required form-control @error('title') is-invalid @enderror integerchk input_aligning unit_price_c cal_row"
                                            placeholder="Unit Price" value="{{ $value->last_purchase_price }}"
                                            id="unit_price_1">
                                        <div class="input-group">
                                            <input type="number" data-countid="1" tabindex="51" id="qty_1"
                                                name="quantity_amount[]" onfocus="this.select();"
                                                class="check_required form-control @error('title') is-invalid @enderror integerchk aligning qty_c cal_row"
                                                value="{{ $value->fp_waste_amount }}"
                                                data-stock="{{ $value->fp_waste_amount + getCurrentProductStock($value->finish_product_id) }}"
                                                data-unit="{{ getPurchaseUnitByProductID($value->finish_product_id) }}"
                                                placeholder="Qty/Amount">

                                            <span
                                                class="input-group-text">{{ getPurchaseUnitByProductID($value->finish_product_id) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" onfocus="select();" id="total_1" name="total[]"
                                                class="form-control @error('title') is-invalid @enderror input_aligning total_c cal_total"
                                                value="{{ $value->loss_amount }}" placeholder="Total">
                                            <span class="input-group-text">{{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon
                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="clearfix"></div><br>

    <div class="row">
        <div class="col-md-4">
            <label>@lang('index.note')</label>
            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note"
                rows="3">{{ isset($obj->note) ? $obj->note : old('note') }}</textarea>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-3 mrl-84">
            <label class="custom_label">@lang('index.total_loss') <span class="required_star">*</span></label>
            <div class="input-group w-90">
                <input type="text" name="grand_total" id="grand_total"
                    class="form-control @error('grand_total') is-invalid @enderror" readonly placeholder="G.Total">
                <span class="input-group-text">
                    {{ $setting->currency }}
                </span>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('product-wastes.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
