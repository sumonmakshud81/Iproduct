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

        @include('utilities.messages')

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'purchase_form',
                    'method' => 'POST',
                    'enctype' => 'multipart/form-data',
                    'route' => ['rawmaterialpurchases.update', ''],
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.reference_no') <span class="required_star">*</span></label>
                                <input type="text" name="reference_no" id="reference_no"
                                    class="check_required form-control @error('reference_no') is-invalid @enderror"
                                    placeholder="Reference No"
                                    value="{{ $ref_no }}" readonly>
                                <div class="text-danger d-none"></div>
                                @error('reference_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.supplier') <span class="required_star">*</span></label>

                                <div class="d-flex align-items-center">
                                    <div class="w-100">
                                        <select tabindex="2"
                                            class="form-control @error('supplier') is-invalid @enderror select2"
                                            id="supplier_id" name="supplier">
                                            <option value="">@lang('index.select_supplier')</option>
                                            @foreach ($suppliers as $value)
                                                <?php
                                                $current_due = currentSupplierDue($value->id);
                                                ?>
                                                <option
                                                    {{ (isset($obj->supplier) && $obj->supplier == $value->id) || old('supplier') == $value->id ? 'selected' : '' }}
                                                    data-credit_limit="{{ $value->credit_limit }}"
                                                    data-current_due="{{ $current_due }}" value="{{ $value->id }}">
                                                    {{ $value->name }}({{ $value->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="btn btn-md pull-right fit-content bg-second-btn plus_add_btn ms-2 view_modal_button"
                                            data-bs-toggle="modal" data-bs-target="#supplierModal">
                                            <iconify-icon icon="solar:add-circle-broken"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-danger supplierErr d-none"></div>

                            <div class="alert alert-primary p-1 supplier_due d-none mt-1" role="alert">
                                
                            </div>
                            <input type="hidden" name="supplier_due">
                            @error('supplier')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.date') <span class="required_star">*</span></label>
                                <input type="text" name="date" id="date"
                                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly
                                    placeholder="Date" value="{{ isset($obj->date) ? $obj->date : old('date') }}">
                                <div class="text-danger d-none"></div>
                                @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.type') <span class="required_star">*</span></label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror" name="type" id="type"
                                    value="purchase" readonly>
                            </div>
                            <div class="text-danger d-none"></div>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.status') <span class="required_star">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror select2" name="status"
                                    id="status">
                                    <option value="Draft"
                                        {{ (isset($obj->status) && $obj->status == 'Draft') || old('status') == 'Draft' ? 'selected' : '' }}>
                                        @lang('index.draft')</option>
                                    <option value="Final"
                                        {{ (isset($obj->status) && $obj->status == 'Final') || old('status') == 'Final' ? 'selected' : '' }}>
                                        @lang('index.final')</option>
                                </select>
                            </div>
                            <div class="text-danger d-none"></div>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.invoice_no')</label>
                                <input type="text" name="invoice_no"
                                    class="form-control @error('invoice_no') is-invalid @enderror" placeholder="Invoice No"
                                    value="{{ isset($obj->invoice_no) && $obj->invoice_no ? $obj->invoice_no : old('invoice_no') }}">
                                @error('invoice_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.raw_material') <span class="required_star">*</span></label>
                                <select tabindex="4"
                                    class="form-control @error('rmaterial') is-invalid @enderror select2 select2-hidden-accessible"
                                    name="rmaterial" id="rmaterial">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($rmaterials as $rm)
                                        <option value="{{ $rm->id . '|' . $rm->name . ' (' . $rm->code . ')|' . $rm->name . '|' . $rm->rate_per_unit . '|' . getPurchaseSaleUnitById($rm->unit) . '|' . $setting->currency }}">{{ $rm->name . '(' . $rm->code . ')' }}</option>
                                    @endforeach
                                </select>

                                @error('rmaterial')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-8 mt-c-17 d-flex">
                            <div class="form-group me-2">
                                @if (!isset($pruchse_rmaterials))
                                    <button type="button" class="btn bg-blue-btn low_stock">@lang('index.low_stock')</button>
                                @endif
                            </div>
                            <div class="form-group me-2">
                                @if (!isset($pruchse_rmaterials))
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#multipleProductModal"
                                        class="btn bg-blue-btn multi_product">@lang('index.generate_purchase_from_multiple_product')</button>
                                @endif
                            </div>
                            <div class="form-group ">
                                @if (!isset($pruchse_rmaterials))
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#productionProductModal"
                                        class="btn bg-blue-btn production_button">@lang('index.generate_purchase_from_order')</button>
                                @endif
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
                                        <th class="w-20">@lang('index.unit_price')<span class="required_star">*</span></th>
                                        <th class="w-20">@lang('index.quantity')<span class="required_star">*</span></th>
                                        <th class="w-20">@lang('index.total')</th>
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
                                                            name="rm_id[]">
                                                        <span>{{ getRMName($value->rmaterials_id) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" tabindex="5" name="unit_price[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control @error('title') is-invalid @enderror integerchk input_aligning unit_price_c cal_row"
                                                                placeholder="Unit Price" value="{{ $value->unit_price }}"
                                                                id="unit_price_1">
                                                            <span class="input-group-text">
                                                                {{ $setting->currency }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" data-countid="1" tabindex="51"
                                                                id="qty_1" name="quantity_amount[]"
                                                                onfocus="this.select();"
                                                                class="check_required form-control @error('title') is-invalid @enderror integerchk input_aligning qty_c cal_row"
                                                                value="{{ isset($value->shortage) ? $value->shortage : $value->quantity_amount }}"
                                                                placeholder="Qty/Amount">
                                                            <span
                                                                class="input-group-text">{{ getPurchaseSaleUnitById($value->rmaterials_id) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" id="total_1" name="total[]"
                                                                class="form-control @error('title') is-invalid @enderror total_c"
                                                                value="{{ isset($value->shortage_total) ? (int) $value->shortage_total : $value->total }}"
                                                                placeholder="Total" readonly="">
                                                            <span class="input-group-text">
                                                                {{ $setting->currency }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="ir_txt_center"><a
                                                            class="btn btn-xs text-danger del_row"><iconify-icon
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
                    <div class="row mt-4">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label>@lang('index.note')</label>
                                        <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note">{{ isset($obj->note) ? $obj->note : old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group custom_table">
                                        <label>@lang('index.relavent_file') (@lang('index.max_size_5_mb'))</label>
                                        <table width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="100%">
                                                        <input type="hidden" name="file_old"
                                                            value="{{ isset($obj->file) && $obj->file ? $obj->file : '' }}">
                                                        <input type="file" name="file_button" id="file_button"
                                                            class="form-control @error('file_button') is-invalid @enderror file_checker_global"
                                                            accept="image/png,image/jpeg,image/jgp,image/gif,application/pdf,.doc,.docx">
                                                        <p class="text-danger errorFile"></p>
                                                    </td>
                                                    @if (isset($obj->file) && $obj->file)
                                                        <td class="w_1 d-flex">
                                                            <a href="{{ $baseURL }}uploads/purchase/{{ $obj->file }}"
                                                                target="_blank"
                                                                class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2"
                                                                id="favicon_preview"><iconify-icon
                                                                    icon="solar:eye-broken"></iconify-icon></a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.subtotal') <span
                                        class="required_star">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="subtotal" id="subtotal"
                                        class="form-control @error('subtotal') is-invalid @enderror" readonly
                                        placeholder="Sub Total"
                                        value="{{ isset($obj->subtotal) ? $obj->subtotal : (isset($subtotal_shoratage) ? $subtotal_shoratage : old('subtotal')) }}">
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.other')</label>
                                <div class="input-group">
                                    <input type="text" name="other" id="other"
                                        class="form-control @error('other') is-invalid @enderror integerchk cal_row"
                                        placeholder="Other"
                                        value="{{ isset($obj->other) ? $obj->other : old('other') }}">
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="form-group">
                                    <label>@lang('index.discount')</label>
                                    <input type="text" name="discount" id="discount"
                                        class="form-control @error('title') is-invalid @enderror discount cal_row"
                                        data-special_ignore="ignore" placeholder="Discount"
                                        value="{{ isset($obj->discount) ? $obj->discount : old('discount') }}">
                                </div>
                            </div>

                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.g_total') <span
                                        class="required_star">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="grand_total" id="grand_total"
                                        class="form-control @error('grand_total') is-invalid @enderror" readonly
                                        placeholder="G.Total"
                                        value="{{ isset($obj->grand_total) ? $obj->grand_total : (isset($subtotal_shoratage) ? $subtotal_shoratage : old('grand_total')) }}">
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.paid') <span
                                        class="required_star">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="paid" id="paid"
                                        class="form-control @error('paid') is-invalid @enderror check_required integerchk cal_row"
                                        placeholder="Paid" value="{{ isset($obj->paid) ? $obj->paid : old('paid') }}"
                                        onfocus="select()"></td>
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                                <div class="text-danger d-none paidErr"></div>
                                @error('paid')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.account') <span
                                        class="required_star">*</span></label>
                                <div class="d-flex align-items-center">
                                    <div class="w-100">
                                        <select tabindex="2"
                                            class="form-control @error('account') is-invalid @enderror select2"
                                            id="accounts" name="account">
                                            <option value="">@lang('index.select')</option>
                                            @foreach ($accounts as $value)
                                                <option
                                                    {{ (isset($obj->account) && $obj->account == $value->id) || old('account') == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger d-none"></div>
                                    </div>

                                    <div class="paid_err_msg_contnr">
                                        <p id="account_err_msg"></p>
                                    </div>
                                    @error('account')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-1">
                                <label class="custom_label">@lang('index.due')</label>
                                <div class="input-group">
                                    <input type="text" name="due" id="due"
                                        class="form-control @error('title') is-invalid @enderror integerchk supplier_current_due check"
                                        readonly placeholder="Due"
                                        value="{{ isset($obj->due) ? $obj->due : old('due') }}">
                                    <span class="input-group-text">{{ $setting->currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input class="form-control supplier_credit_limit"
                    value="{{ isset($obj) ? $obj->getSupplier->credit_limit : null }}" type="hidden">
            </div>
            <!-- /.box-body -->

            <div class="row mt-2">
                <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                            icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                    <a class="btn bg-second-btn" href="{{ route('rawmaterialpurchases.index') }}"><iconify-icon
                            icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>


    <div class="modal fade" id="supplierModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('index.add_supplier')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.name')<span class="ir_color_red">
                                            *</span></label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="name" id="name" placeholder="Name" value="">
                                        <div class="supplier_err_msg_contnr">
                                            <p class="text-danger supplier_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.contact_person')</label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="contact_person" id="contact_person" placeholder="Contact Person"
                                            value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.phone') <span
                                            class="required_star">*</span></label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="phone" name="phone" placeholder="Phone" value="">
                                        <div class="customer_phone_err_msg_contnr err_cust">
                                            <p class="text-danger customer_phone_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.email')</label>
                                    <div>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="emailAddress" name="emailAddress" placeholder="Email" value="">
                                        <div class="supplier_email_err_msg_contnr err_cust">
                                            <p class="text-danger supplier_email_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div class="form-group w-100 me-2">
                                        <label>@lang('index.opening_balance')</label>
                                        <input type="text" name="opening_balance" id="opening_balance"
                                            class="form-control @error('opening_balance') is-invalid @enderror integerchk"
                                            placeholder="Opening Balance">
                                        <div class="supplier_opening_balace_err_msg_contnr err_cust">
                                            <p class="text-danger supplier_opening_balace_err_msg"></p>
                                        </div>
                                    </div>
                                    <div class="form-group w-100">
                                        <label>&nbsp;</label>
                                        <select
                                            class="form-control @error('opening_balance_type') is-invalid @enderror select2"
                                            name="opening_balance_type" id="opening_balance_type">
                                            <option value="Debit">
                                                @lang('index.debit')</option>
                                            <option value="Credit">
                                                @lang('index.credit')</option>
                                        </select>
                                        <div class="supplier_balance_type_err_msg_contnr err_cust">
                                            <p class="text-danger supplier_balance_type_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.credit_limit')</label>
                                    <div>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror integerchk"
                                            id="credit_limit" name="credit_limit" placeholder="Credit Limit"
                                            value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.address')</label>
                                    <div>
                                        <textarea tabindex="4" class="form-control @error('title') is-invalid @enderror" rows="3" name="supAddress"
                                            placeholder="Address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.note')</label>
                                    <div>
                                        <textarea tabindex="4" class="form-control @error('title') is-invalid @enderror" rows="4" name="suppNote"
                                            placeholder="Enter ..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn" value="submit" id="addSupplier"><iconify-icon
                            icon="solar:check-circle-broken"></iconify-icon> @lang('index.submit')
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Multiple Product Modal --}}
    <div class="modal fade" id="multipleProductModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('index.select_multiple_product')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-5">
                            <div class="form-group">
                                <label>@lang('index.product') <span class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 select2-hidden-accessible"
                                    name="product" id="productModal">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}|{{ $p->name }}({{ $p->code }})">{{ $p->name }}({{ $p->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-danger productErr"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-5">
                            <label class="custom_label">@lang('index.quantity') <span class="required_star">*</span></label>
                            <div class="input-group">
                                <input type="text" autocomplete="off" min="1"
                                    class="form-control @error('title') is-invalid @enderror integerchk1"
                                    onfocus="select();" name="qty_modal_product" id="qty_modal_product"
                                    placeholder="Quantity" value="1">
                                <span class="input-group-text modal_unit_name" id="basic-addon2">Piece</span>
                            </div>
                            <div class="text-danger qtyErr"></div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-2 mt-3">
                        <button type="button"
                                    class="btn w-100 bg-blue-btn add_to_cart_multiple_product">@lang('index.add')</button>
                        </div>
                    </div>
                    <div id="addToCartSec" class="d-none">
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('index.sn')</th>
                                    <th>@lang('index.name')</th>
                                    <th>@lang('index.quantity')</th>
                                    <th class="text-end">@lang('index.actions')</th>
                                </tr>
                            </thead>
                            <tbody id="cart_data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn" value="submit" id="generate_purchase"><iconify-icon
                            icon="solar:check-circle-broken"></iconify-icon>
                        @lang('index.generate_purchase')
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Production Model --}}
    <div class="modal fade" id="productionProductModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('index.select_order')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <div class="form-group">
                                <label>@lang('index.product') <span class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 select2-hidden-accessible"
                                    name="order" id="orderModal">
                                    <option value="">@lang('index.select')</option>
                                    @foreach ($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->reference_no }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-danger orderErrMsg"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn" value="submit"
                        id="generate_purchase_from_production"><iconify-icon
                            icon="solar:check-circle-broken"></iconify-icon> @lang('index.generate_purchase')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cartPreviewModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('index.select_raw_materials')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">@lang('index.name'): </label>
                            <div class="col-sm-7">
                                <p class="item_name_modal"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-sm-12 control-label custom_label mb-1">@lang('index.unit_price') <span
                                        class="required_star">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" autocomplete="off"
                                        class="form-control @error('title') is-invalid @enderror integerchk1"
                                        onfocus="select();" name="unit_price_modal" id="unit_price_modal"
                                        placeholder="Unit Price" value="">
                                    <input type="hidden" name="item_id_modal" id="item_id_modal" value="">
                                    <input type="hidden" name="item_name_modal" id="item_name_modal" value="">
                                    <input type="hidden" name="item_currency_modal" id="item_currency_modal"
                                        value="">
                                    <input type="hidden" name="item_unit_modal" id="item_unit_modal" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-sm-12 control-label custom_label mb-1">@lang('index.quantity') <span
                                        class="required_star">*</span></label>
                                <div class="col-sm-12">

                                    <div class="input-group mb-3">
                                        <input type="text" autocomplete="off" min="1"
                                            class="form-control @error('title') is-invalid @enderror integerchk1"
                                            onfocus="select();" name="qty_modal" id="qty_modal" placeholder="Quantity"
                                            value="1">
                                        <span class="input-group-text modal_unit_name" id="basic-addon2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer ir_d_block">
                    <button type="button" class="btn bg-blue-btn" id="addToCart"><iconify-icon
                            icon="solar:add-circle-broken"></iconify-icon>@lang('index.add_to_cart')
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <?php
    /*get base url from helper function*/
    $baseURL = getBaseURL();
    
    ?>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/addRMPurchase.js' !!}"></script>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/supplier.js' !!}"></script>
@endsection
