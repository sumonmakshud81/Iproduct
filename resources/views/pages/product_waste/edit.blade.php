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
        <h3 class="top-left-header">{{isset($title) && $title?$title:''}}</h3>
    </section>

    @include('utilities.messages')

    <div class="box-wrapper">
        <!-- general form elements -->
        <div class="table-box">
            <!-- form start -->
            {{Form::model($obj,['route'=>['product-wastes.update',$obj->id],'method'=>'put'])}}
                @include('pages/product_waste/_form')
            {!! Form::close() !!}
        </div>
    </div>
</section>
<div class="modal fade" id="cartPreviewModal" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        @lang('index.select_finish_product')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i data-feather="x"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-12 control-label">@lang('index.name'): </label>
                            <div class="col-sm-12">
                                <p id="item_name_modal"></p>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="params" id="params">
                            <div class="col-md-6 d-none" id="batch_sec">
                                <label class="custom_label">@lang('index.batch_no') <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2" name="batch_no" id="batch_no"></select>
                                </div>
                                <div class="text-danger batch_err_msg d-none">The Batch No field is required</div>
                            </div>
                            <div class="col-md-6 d-none" id="expiry_sec">
                                <label class="custom_label">@lang('index.expiry_date') <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2" name="expiry_date" id="expiry_date"></select>
                                </div>
                                <div class="text-danger expiry_date_err_msg d-none">The Expiry Date field is required</div>
                            </div>
                            <div class="col-md-6">
                                <label class="custom_label">@lang('index.quantity_amount') <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <input type="number" autocomplete="off" min="1" class="form-control integerchk1"
                                        onfocus="select();" name="qty_modal" id="qty_modal" placeholder="Quantity/Amount"
                                        value="1">
                                    <span class="input-group-text modal_unit_name"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer ir_d_block">
                    <button type="button" class="btn bg-blue-btn" id="addToCart"><iconify-icon
                            icon="solar:add-circle-broken"></iconify-icon>@lang('index.add_to_cart')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <?php
    $baseURL = getBaseURL();
    ?>
    <script type="text/javascript" src="{!! $baseURL.'frequent_changing/js/product_waste.js'!!}"></script>

@endsection