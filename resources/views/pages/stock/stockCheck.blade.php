@extends('layouts.app')
@section('content')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    ?>
    <section class="main-content-wrapper">
        @include('utilities.messages')
        <section class="content-header">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h3 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h3>
                </div>
                <div class="col-sm-12 mb-2 col-md-3">
                </div>
                <div class="col-sm-12 mb-2 col-md-3">
                    <strong class="margin_10" id="stockValue"></strong>
                </div>
            </div>
        </section>


        <div class="box-wrapper">

            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <input type="hidden" class="datatable_name" data-filter="yes" data-title="RM Stock"
                        data-id_name="datatable">
                    <table id="datatable" class="table">                                               
                        @php($j = count($obj))
                        @foreach ($obj as $key => $value)                            
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-start fs-6">{{ getFinishProduct($key) }}</th>                                
                                </tr>
                                <tr>
                                    <th class="w-5 text-start">@lang('index.sn')</th>
                                    <th class="w-10">@lang('index.code')</th>
                                    <th class="w-30">@lang('index.material_name')</th>
                                    <th class="w-20">@lang('index.available_quantity')</th>
                                    <th class="w-15">@lang('index.required_quantity')
                                    </th>
                                    <th class="w-20">@lang('index.difference')(@lang('index.need_to_purchase'))</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @php($i = count($value))

                                @foreach ($value as $item)
                                    <tr>
                                        <td class="text-start">{{ $i-- }} </td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {{ $item->current_stock_final }}@if ($item->consumption_check != 1){{ str_replace(' ', '', getRMUnitById($item->unit)) }}@endif
                                        </td>
                                        <td>{{ requiredQuantityCheck($_required[$key][$item->id], $item->id) }}</td>
                                        <td>{{ calculateDifference($item->current_stock, $_required[$key][$item->id], $item->id) }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>

        <div class="modal fade" id="filterModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('index.rm_stocks')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! Form::model(isset($obj) && $obj ? $obj : '', [
                            'id' => 'add_form',
                            'method' => isset($obj) && $obj ? 'POST' : 'POST',
                            'enctype' => 'multipart/form-data',
                            'route' => ['getRMStock'],
                        ]) !!}
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.product') </label>
                                    <select name="finish_p_id" id="finish_p_id" class="form-control select2">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($finishProduct as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ $product_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label>@lang('index.floating_stock_by_manufacture') </label>
                                    <select name="manufacture_id" id="manufacture_id" class="form-control select2">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($manufactureProduct as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ isset($obj->name) && $obj->name == $value->id ? 'selected' : '' }}>
                                                {{ $value->reference_no }} - {{ $value->product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mt-3">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#multipleProductModal"
                                    class="btn w-100 bg-blue-btn manufacture_by_requirement">@lang('index.by_manufacture_requirement')</button>
                            </div>

                            <div class="col-md-4 mb-2 subBtn">
                                <button type="submit" name="submit" value="submit"
                                    class="btn w-100 bg-blue-btn">@lang('index.submit')</button>
                            </div>

                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- Multiple Product Modal --}}
        <div class="modal fade" id="multipleProductModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                {!! Form::model(isset($obj) && $obj ? $obj : '', [
                    'id' => 'add_form',
                    'method' => isset($obj) && $obj ? 'POST' : 'POST',
                    'enctype' => 'multipart/form-data',
                    'route' => ['getRMStock'],
                ]) !!}
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            @lang('index.select_product')</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i data-feather="x"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row" id="product_list">
                            <div class="col-sm-12 mb-2 col-md-5">
                                <div class="form-group">
                                    <label>@lang('index.product') <span class="required_star">*</span></label>
                                    <select tabindex="4"
                                        class="form-control select2 select2-hidden-accessible productModal" name="product"
                                        id="productModal">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($finishProduct as $p)
                                            <option value="{{ $p->id }}|{{ $p->name }}({{ $p->code }})">
                                                {{ $p->name }}({{ $p->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger productErr"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-5">
                                <label class="custom_label">@lang('index.quantity') <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <input type="number" autocomplete="off" min="1"
                                        class="form-control @error('title') is-invalid @enderror integerchk1"
                                        onfocus="select();" name="qty_modal_product" id="qty_modal_product"
                                        placeholder="Quantity" value="1">
                                    <span class="input-group-text modal_unit_name" id="basic-addon2">Piece</span>
                                </div>
                                <div class="text-danger qtyErr"></div>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button class="btn bg-blue-btn" type="button" id="add_product_to_cart">
                                        <iconify-icon icon="solar:add-circle-broken"></iconify-icon>@lang('index.add')
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="stock_table" class="d-none">
                            <hr>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="w-5 text-start">@lang('index.sn')</th>
                                        <th class="w-20">@lang('index.name')</th>
                                        <th class="w-20">@lang('index.quantity')</th>
                                        <th class="w-5 text-end">@lang('index.actions')</th>
                                    </tr>
                                </thead>
                                <tbody id="cart_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-blue-btn" value="submit"
                            id="submit_multi_product"><iconify-icon icon="solar:check-circle-broken"></iconify-icon>
                            @lang('index.submit')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </section>
@endsection
@section('script')
    <script src="{!! $baseURL . 'assets/datatable_custom/jquery-3.3.1.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jquery.dataTables.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.bootstrap4.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/dataTables.buttons.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.html5.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/buttons.print.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/jszip.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/pdfmake.min.js' !!}"></script>
    <script src="{!! $baseURL . 'assets/dataTable/vfs_fonts.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/newDesign/js/forTable.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/custom_report.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/stock.js' !!}"></script>
    <script src="{!! $baseURL . 'frequent_changing/js/stockCheck.js' !!}"></script>
@endsection
