@php
    $orderType = isset($customerOrder->order_type) && $customerOrder->order_type ? $customerOrder->order_type : '';
@endphp
<input type="hidden" name="currency" id="only_currency_sign" value={{ getCurrencyOnly() }}>
<div>
    <div class="row">
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.reference_no') <span class="required_star">*</span></label>

                <input type="text" name="reference_no" id="code"
                    class="check_required form-control @error('reference_no') is-invalid @enderror"
                    placeholder="{{ __('index.reference_no') }}"
                    value="{{ isset($customerOrder->reference_no) ? $customerOrder->reference_no : $ref_no }}"
                    onfocus="select()" readonly>
                <div class="text-danger d-none"></div>
                @error('reference_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.customer') <span class="required_star">*</span></label>
                <select name="customer_id" id="customer_id"
                    class="form-control @error('customer_id') is-invalid @enderror select2" placeholder="Please Select">
                    @foreach ($customers as $key => $customer)
                        <?php
                        $customer_id = $key;
                        ?>
                        <option value="{{ $key }}"
                            {{ isset($customerOrder->customer_id) && $customerOrder->customer_id == $customer_id ? 'selected' : '' }}>
                            {{ $customer }}</option>
                    @endforeach
                </select>
                <div class="text-danger d-none"></div>
                @error('customer_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.type') <span class="required_star">*</span></label>

                <select name="order_type" id="order_type"
                    class="form-control @error('order_type') is-invalid @enderror select2" placeholder="Please Select">
                    @foreach ($orderTypes as $key => $orderType)
                        <option value="{{ $key }}"
                            {{ isset($customerOrder->order_type) && $customerOrder->order_type == $key ? 'selected' : '' }}>
                            {{ $orderType }}</option>
                    @endforeach
                </select>
                <div class="text-danger d-none"></div>
                @error('order_type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.delivery_date') <span class="required_star">*</span></label>
                <input type="text" name="delivery_date" id="delivery_date"
                    class="form-control @error('delivery_date') is-invalid @enderror customDatepicker" readonly
                    placeholder="Delivery Date"
                    value="{{ isset($customerOrder->delivery_date) ? $customerOrder->delivery_date : old('delivery_date') }}">
                <div class="text-danger d-none"></div>
                @error('delivery_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-8">
            <div class="form-group">
                <label>@lang('index.delivery_address')</label>

                <input type="text" name="delivery_address" id="delivery_address"
                    class="form-control @error('delivery_address') is-invalid @enderror"
                    placeholder="{{ __('index.delivery_address') }}"
                    value="{{ isset($customerOrder->delivery_address) ? $customerOrder->delivery_address : old('delivery_address') }}">
                <div class="text-danger d-none"></div>
                @error('delivery_address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" id="fprm">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-50-p">@lang('index.sn')</th>
                            <th class="w-220-p">@lang('index.product')</th>
                            <th class="w-220-p">@lang('index.quantity')</th>
                            <th class="w-220-p">@lang('index.unit_price')</th>
                            <th class="w-220-p">@lang('index.discount')</th>
                            <th class="w-220-p">@lang('index.subtotal')</th>
                            <th class="w-220-p">@lang('index.cost')</th>
                            <th class="w-220-p">@lang('index.profit')</th>
                            <th class="w-220-p">@lang('index.delivery_date')</th>
                            <th class="w-220-p">@lang('index.production_status')</th>
                            <th class="w-220-p">@lang('index.delivered')</th>
                            <th class="ir_txt_center">@lang('index.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="add_trm">
                        <?php $i = 0; ?>
                        @if (isset($orderDetails) && $orderDetails)
                            @foreach ($orderDetails as $key => $value)
                                <?php $i++; ?>
                                <tr class="rowCount" data-id="{{ $value->id }}">
                                    <td class="width_1_p ir_txt_center">{{ $i }}</td>

                                    <td>
                                        <select name="product[]" id="fproduct_id_{{ $i }}"
                                            class="form-control @error('title') is-invalid @enderror fproduct_id select2">
                                            <option value="">@lang('index.please_select')</option>
                                            @foreach ($productList as $product)
                                                <option value="{{ $product->id }}" @selected($product->id == $value->product_id)>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" name="quantity[]" onfocus="this.select();"
                                            class="check_required form-control @error('title') is-invalid @enderror integerchk quantity_c"
                                            placeholder="Quantity" value="{{ $value->quantity }}"
                                            id="quantity_{{ $i }}">
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="unit_price[]" onfocus="this.select();"
                                                class="check_required form-control @error('title') is-invalid @enderror integerchk unit_price_c"
                                                placeholder="Unit Price" value="{{ $value->unit_price }}"
                                                id="unit_price_{{ $i }}">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="text" id="discount_percent_{{ $i }}"
                                                name="discount_percent[]" onfocus="this.select();"
                                                class="check_required form-control @error('title') is-invalid @enderror integerchk discount_percent_c"
                                                value="{{ $value->discount_percent }}" placeholder="Discount">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="sub_total_{{ $i }}"
                                                name="sub_total[]"
                                                class="form-control @error('title') is-invalid @enderror sub_total_c"
                                                value="{{ $value->sub_total }}" placeholder="Subtotal"
                                                readonly="">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="cost_{{ $i }}" name="cost[]"
                                                class="form-control @error('title') is-invalid @enderror cost_c"
                                                value="{{ $value->total_cost }}" placeholder="Cost" readonly="">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="profit_{{ $i }}" name="profit[]"
                                                class="form-control @error('title') is-invalid @enderror profit_c"
                                                value="{{ $value->profit }}" placeholder="Profit" readonly="">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {!! Form::text('delivery_date_product[]', $value->delivery_date, [
                                            'class' => 'form-control customDatepicker',
                                            'placeholder' => 'Delivery Date',
                                        ]) !!}
                                    </td>
                                    <td>
                                        <select name="status[]" id="fstatus_id_{{ $i }}"
                                            class="form-control @error('title') is-invalid @enderror fstatus_id select2">
                                            <option value="none" {{ $value->status == 'none' ? 'selected' : '' }}>
                                                @lang('index.none')
                                            </option>
                                            <option value="in_progress"
                                                {{ $value->status == 'in_progress' ? 'selected' : '' }}>
                                                @lang('index.in_progress')
                                            </option>
                                            <option value="done" {{ $value->status == 'done' ? 'selected' : '' }}>
                                                @lang('index.done')
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="delivered_qty[]"
                                            class="check_required form-control @error('title') is-invalid @enderror integerchk"
                                            placeholder="@lang('index.delivered')" value="{{ $value->delivered_qty }}"
                                            id="delivered_{{ $i }}">
                                    </td>
                                    <td class="ir_txt_center"><a class="btn btn-xs del_row dlt_button"><iconify-icon
                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon></a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th>
                                <div class="input-group">
                                    <input type="number" name="total_subtotal" id="total_subtotal"
                                        value="{{ isset($customerOrder->total_amount) ? $customerOrder->total_amount : 0 }}"
                                        class="form-control input_aligning" placeholder="@lang('index.total')"
                                        readonly="">
                                    <span class="input-group-text"> {{ $setting->currency }}</span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="number" name="total_cost" id="total_cost"
                                        value="{{ isset($customerOrder->total_cost) ? $customerOrder->total_cost : 0 }}"
                                        class="form-control input_aligning" placeholder="@lang('index.cost')"
                                        readonly="">
                                    <span class="input-group-text"> {{ $setting->currency }}</span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="number" name="total_profit" id="total_profit"
                                        value="{{ isset($customerOrder->total_profit) ? $customerOrder->total_profit : 0 }}"
                                        class="form-control input_aligning" placeholder="@lang('index.profit')"
                                        readonly="">
                                    <span class="input-group-text"> {{ $setting->currency }}</span>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
                <button id="finishProduct" class="btn bg-blue-btn w-10" type="button">@lang('index.add_more')</button>
            </div>
        </div>
    </div>
    <div class="row mt-2 gap-2">
        <button class="btn bg-blue-btn w-20 stockCheck" data-bs-toggle="modal" data-bs-target="#stockCheck"
            type="button">@lang('index.check_stock')</button>
        <button class="btn bg-blue-btn w-20 estimateCost" data-bs-toggle="modal" data-bs-target="#estimateCost"
            type="button">@lang('index.estimate_cost_date')</button>
    </div>

    <div class="row mt-3 {{ isset($orderInvoice) && count($orderInvoice) > 0 ? '' : 'd-none' }}"
        id="invoice_quotations_sections">
        <div class="col-md-12">
            <h4 class="header_right">@lang('index.invoice_quotations')</h4>

            <div class="table-responsive" id="fprm">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="width_1_p">@lang('index.sn')</th>
                            <th class="width_10_p">@lang('index.type')</th>
                            <th class="width_20_p">@lang('index.date')</th>
                            <th class="width_20_p">@lang('index.amount')</th>
                            <th class="width_20_p">@lang('index.paid')</th>
                            <th class="width_20_p">@lang('index.due')</th>
                            <th class="width_20_p">@lang('index.order_due')</th>
                            <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="add_order_inv">
                        <?php $i = 0; ?>
                        @if (isset($orderInvoice) && $orderInvoice)
                            @foreach ($orderInvoice as $key => $value)
                                <?php $i++; ?>
                                <tr class="rowCount" data-id="{{ $value->id }}">
                                    <td class="width_1_p ir_txt_center">{{ $i }}</td>
                                    <td>
                                        <select name="invoice_type[]" id="invoice_type_{{ $i }}"
                                            class="form-control @error('title') is-invalid @enderror invoice_type select2">
                                            <option value="Invoice"
                                                {{ $value->invoice_type == 'Invoice' ? 'selected' : '' }}>
                                                @lang('index.invoice')
                                            </option>
                                            <option value="Quotation"
                                                {{ $value->invoice_type == 'Quotation' ? 'selected' : '' }}>
                                                @lang('index.quotation')
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        {!! Form::text('invoice_date[]', $value->invoice_date, [
                                            'class' => 'form-control customDatepicker',
                                            'placeholder' => 'Invoice Date',
                                        ]) !!}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="invoice_amount_{{ $i }}"
                                                name="invoice_amount[]"
                                                class="form-control @error('title') is-invalid @enderror invoice_amount_c"
                                                value="{{ $value->invoice_amount }}" placeholder="Amount">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="paid_amount_{{ $i }}"
                                                name="invoice_paid[]"
                                                class="form-control @error('title') is-invalid @enderror paid_amount_c"
                                                value="{{ $value->paid_amount }}" placeholder="Paid">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="due_amount_{{ $i }}"
                                                name="invoice_due[]"
                                                class="form-control @error('title') is-invalid @enderror due_amount_c"
                                                value="{{ $value->due_amount }}" placeholder="Due">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" id="order_due_amount_{{ $i }}"
                                                name="invoice_order_due[]"
                                                class="form-control @error('title') is-invalid @enderror order_due_amount_c"
                                                value="{{ $value->order_due_amount }}" placeholder="Order Due">
                                            <span class="input-group-text"> {{ $setting->currency }}</span>
                                        </div>
                                    </td>
                                    <td class="ir_txt_center">
                                        @if ($value->invoice_type !== 'Quotation' && $loop->index !== 0)
                                            <a class="btn btn-xs del_inv_row dlt_button"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <button id="orderInvoices" class="btn bg-blue-btn w-10 mt-2" data-bs-toggle="modal"
                    data-bs-target="#invoiceModal" type="button"
                    {{ $orderType == 'Quotation' ? 'disabled' : '' }}>@lang('index.add_more')</button>
            </div>
        </div>
    </div>

    <div class="row mt-3" id="deliveries_section">
        <div class="col-md-12">
            <h4 class="header_right">@lang('index.deliveries')</h4>

            <div class="table-responsive {{ isset($orderDeliveries) && count($orderDeliveries) > 0 ? '' : 'd-none' }}"
                id="fprm">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="width_1_p">@lang('index.sn')</th>
                            <th class="width_10_p">@lang('index.product')</th>
                            <th class="width_20_p">@lang('index.quantity')</th>
                            <th class="width_20_p">@lang('index.date')</th>
                            <th class="width_20_p">@lang('index.status')</th>
                            <th class="width_20_p">@lang('index.note')</th>
                            <th class="width_3_p ir_txt_center">@lang('index.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="add_deliveries">
                        <?php $i = 0; ?>
                        @if (isset($orderDeliveries) && $orderDeliveries)
                            @foreach ($orderDeliveries as $key => $value)
                                <?php $i++; ?>
                                <tr class="rowCount" data-id="{{ $value->id }}">
                                    <td class="width_1_p ir_txt_center">{{ $i }}</td>
                                    <td>
                                        <input type="hidden" name="delivaries_product[]"
                                            value="{{ $value->product_id }}">
                                        <input type="text" name="delivaries_product[]" onfocus="this.select();"
                                            class="check_required form-control @error('title') is-invalid @enderror delivery_product_c"
                                            placeholder="Product"
                                            value="{{ getProductNameById($value->product_id) }}"
                                            id="delivery_product_{{ $i }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="delivaries_quantity[]" onfocus="this.select();"
                                            class="check_required form-control @error('title') is-invalid @enderror integerchk delivery_quantity_c"
                                            placeholder="Quantity" value="{{ $value->quantity }}"
                                            id="delivery_quantity_{{ $i }}">
                                    </td>
                                    <td>
                                        {!! Form::text('delivaries_date[]', $value->delivery_date, [
                                            'class' => 'form-control customDatepicker',
                                            'placeholder' => 'Delivery Date',
                                        ]) !!}
                                    </td>
                                    <td>
                                        <select name="delivaries_status[]" id="delivery_status_{{ $i }}"
                                            class="form-control @error('title') is-invalid @enderror delivery_status select2">
                                            <option value="none" {{ $value->status == 'none' ? 'selected' : '' }}>
                                                @lang('index.none')
                                            </option>
                                            <option value="in_progress"
                                                {{ $value->status == 'in_progress' ? 'selected' : '' }}>
                                                @lang('index.in_progress')
                                            </option>
                                            <option value="done" {{ $value->status == 'done' ? 'selected' : '' }}>
                                                @lang('index.done')
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="delivaries_note[]" onfocus="this.select();"
                                            class="check_required form-control @error('title') is-invalid @enderror delivery_note_c"
                                            placeholder="Note" value="{{ $value->delivery_note }}"
                                            id="delivery_note_{{ $i }}">
                                    </td>
                                    <td class="ir_txt_center">
                                        <a class="btn btn-xs del_del_row dlt_button"><iconify-icon
                                                icon="solar:trash-bin-minimalistic-broken"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <button id="orderDeliveries" class="btn bg-blue-btn w-10 mt-2" data-bs-toggle="modal"
                data-bs-target="#deliveryModal" type="button">@lang('index.add_more')</button>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-sm-6 col-md-6 mb-2">
            <div class="form-group">
                <label>@lang('index.quotation_note')</label>
                <textarea name="quotation_note" id="quotation_note" class="form-control @error('title') is-invalid @enderror"
                    placeholder="{{ __('index.quotation_note') }}" rows="3"></textarea>
            </div>
        </div>

        <div class="col-sm-6 col-md-6 mb-2">
            <div class="form-group">
                <label>@lang('index.internal_note')</label>
                <textarea name="internal_note" id="internal_note" class="form-control @error('title') is-invalid @enderror"
                    placeholder="{{ __('index.internal_note') }}" rows="3"></textarea>
            </div>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit"
                class="btn bg-blue-btn order_submit_button"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('customer-orders.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>

<select id="hidden_product" class="display_none">
    @foreach ($productList as $value)
        <option value="{{ $value->id ?? '' }}">{{ $value->name ?? '' }}</option>
    @endforeach
</select>
<input type="hidden" id="default_currency" value="{{ $setting->currency }}" />
