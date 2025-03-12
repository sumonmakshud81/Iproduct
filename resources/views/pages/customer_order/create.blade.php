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
            <h3 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {{ Form::open(['route' => 'customer-orders.store', 'id' => 'manufacture_form', 'method' => 'post']) }}
                @include('pages/customer_order/_form')
                {!! Form::close() !!}
            </div>
        </div>
    </section>

    {{-- Invoice Modal --}}
    <div class="modal fade" id="invoiceModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('index.add_invoice')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="invoice_form">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.type')<span class="ir_color_red">
                                            *</span></label>
                                    <div>
                                        <select class="form-control select2" name="invoice_type" id="invoice_type"
                                            placeholder="@lang('index.invoice_type')">
                                            <option value="Invoice">@lang('index.invoice')</option>
                                            <option value="Quotation">@lang('index.quotation')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.paid_amount') <span
                                            class="required_star">*</span></label>
                                    <div>
                                        <input type="number" class="form-control" name="paid_amount" id="paid_amount"
                                            placeholder="@lang('index.paid_amount')" value="">
                                    </div>
                                    <div class="paid_amount_err_msg_contnr err_cust">
                                        <p class="paid_amount_err_msg text-danger"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.due_amount')</label>
                                    <div>
                                        <input type="number" class="form-control" id="due_amount" name="due_amount"
                                            placeholder="@lang('index.due_amount')" value="">
                                        <div class="due_amount_err_msg_contnr err_cust">
                                            <p class="due_amount_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.order_due_amount')</label>
                                    <div>
                                        <input type="number" class="form-control" id="order_due_amount"
                                            name="order_due_amount" placeholder="@lang('index.order_due_amount')" value="">
                                        <div class="order_due_amount_err_msg_contnr err_cust">
                                            <p class="order_due_amount_err_msg"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="customer_order_id" value="{{ @$customerOrder->id }}">
                    <button type="submit" class="btn bg-blue-btn invoice_submit"><iconify-icon icon="solar:check-circle-broken"></iconify-icon></i>
                        @lang('index.submit')</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Delivery Modal --}}
    <div class="modal fade" id="deliveryModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('index.add_delivery')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.product')<span class="ir_color_red">
                                            *</span></label>
                                    <div>
                                        {!! Form::select('product_id', $product, null, [
                                            'class' => 'form-control select2',
                                            'id' => 'product_id',
                                            'placeholder' => 'Please Select',
                                            'required',
                                        ]) !!}
                                    </div>
                                    <p class="text-danger product_error"></p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.quantity')<span class="ir_color_red">
                                            *</span></label>
                                    <div>
                                        <input type="number" class="form-control" name="quantity" id="delivary_quantity"
                                            placeholder="Quantity" value="" required="">
                                    </div>
                                    <p class="text-danger quantity_error"></p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.delivery_date') <span
                                            class="required_star">*</span></label>
                                    <div>
                                        <input type="text" class="form-control customDatepicker" id="ddelivery_date"
                                            name="delivery_date" placeholder="Delivery Date" readonly=""
                                            required="">
                                    </div>
                                    <p class="text-danger delivery_date_error"></p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.delivery_status') <span
                                            class="required_star">*</span></label>
                                    <div>
                                        <select class="form-control select2" name="delivery_status" id="delivery_status"
                                            placeholder="Please Select" required="">
                                            <option value="In Progress">@lang('index.in_progress')</option>
                                            <option value="Done">@lang('index.done')</option>
                                        </select>
                                    </div>
                                    <p class="text-danger delivery_status_error"></p>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="control-label">@lang('index.delivery_note')</label>
                                    <div>
                                        <textarea tabindex="4" class="form-control" rows="4" id="delivery_note" name="delivery_note" placeholder="Delivery Note ..."></textarea>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-blue-btn delivaries_button"><iconify-icon icon="solar:check-circle-broken"></iconify-icon>
                        @lang('index.submit')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Check Modal --}}
    <div class="modal fade" id="stockCheck" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('index.current_stock')</h4>                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="fprm">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="width_1_p">@lang('index.sn')</th>
                                    <th class="width_20_p">@lang('index.product_name')(@lang('index.code'))</th>
                                    <th class="width_20_p">@lang('index.current_stock')</th>
                                    <th class="width_20_p">@lang('index.need_to_manufacture')</th>                                    
                                </tr>
                            </thead>
                            <tbody class="stock_check_table">
                                
                            </tbody>
                        </table>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn download_button"><iconify-icon icon="solar:download-square-broken"></iconify-icon>
                        @lang('index.download')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Estimate Cost and Date --}}
    <div class="modal fade" id="estimateCost" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('index.estimate_cost_date')</h4>                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="fprm">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="width_1_p">@lang('index.sn')</th>
                                    <th class="width_20_p">@lang('index.product_name')(@lang('index.code'))</th>
                                    <th class="width_20_p">@lang('index.quantity')</th>
                                    <th class="width_20_p">@lang('index.costing')</th>
                                    <th class="width_20_p">@lang('index.required_time')</th>                                    
                                </tr>
                            </thead>
                            <tbody class="estimate_cost_table">
                                
                            </tbody>
                        </table>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn download_button_cost"><iconify-icon icon="solar:download-square-broken"></iconify-icon>
                        @lang('index.download')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/order.js?v=1.0' !!}"></script>
@endsection
