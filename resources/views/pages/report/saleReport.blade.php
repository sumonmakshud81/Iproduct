<!-- shuvo -->
<link rel="stylesheet" href="{{ url('frequent_changing/css/report.css') }}">

<div class="main-content-wrapper">

    <div class="content-header">
        <h3 class="top-left-header">@lang('index.sales_report')</h3>
    </div>

    <div class="box-wrapper">
        <div class="text-right">
            <button class="dataFilterBy btn bg-blue-btn mb-2"><i class="fa fa-filter"></i>{{ lang('filter_by') }}</button>
        </div>
        <div>
            <h4>{{ isset($outletName) && $outletName ? lang('outlet').':' . $outletName : '' }}</h4>
            <h4>
                @if(!empty($start_date) && $start_date != '1970-01-01')
                    {{ "Date: " . date($this->session->userdata('date_format'), strtotime($start_date)) }}
                @endif
                @if(isset($start_date) && isset($end_date) && $start_date != '1970-01-01' && $end_date != '1970-01-01')
                    -
                @endif
                @if(!empty($end_date) && $end_date != '1970-01-01')
                    {{ date($this->session->userdata('date_format'), strtotime($end_date)) }}
                @endif
            </h4>
        </div>
        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('index.sn')</th>
                            <th>@lang('index.code')</th>
                            <th>@lang('index.item_name')</th>
                            <th>@lang('index.unit_price')</th>
                            <th>@lang('index.quantity')</th>
                            <th>@lang('index.discount')</th>
                            <th>@lang('index.total')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($saleReport))
                            @php
                                $total = 0;
                            @endphp
                            @foreach($saleReport as $key => $value)
                                @php
                                    $total += $value->menu_price_with_discount;
                                    $key++;
                                @endphp
                                <tr>
                                    <td class="text-start">{{ $key }}</td> 
                                    <td>{{ $value->code }}</td>
                                    <td>{{ $value->menu_name }}</td>
                                    <td>{{ getAmtCustom($value->menu_unit_price) }}</td>
                                    <td>{{ $value->totalQty }}</td>
                                    <td>{{ getAmtCustom($value->menu_discount_value) }}</td>
                                    <td>{{ getAmtCustom($value->menu_price_with_discount) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot> 
                        <tr>
                            <th class="text-start">@lang('index.sn')</th> 
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-end">@lang('index.total')=</th>
                            <th>{{ getAmtCustom($total ?? '0') }}</th>
                        </tr>           
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>


<div class="filter-overlay"></div>
<div id="product-filter" class="filter-modal">
    <div class="filter-modal-body">
        <header>
                <h3 class="filter-modal-title">@lang('index.FilterOptions')</h3>
                <button class="close-filter-modal"><i class="fa fa-times"></i></button>
        </header>
        {!! Form::open(['url' => url('Report/saleReport'), 'id' => 'saleReport']) !!}
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <input tabindex="1" autocomplete="off" type="text" id="" name="startDate" readonly class="form-control customDatepicker" placeholder="@lang('index.start_date')" value="{{ old('startDate') }}">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <input tabindex="2" autocomplete="off" type="text" id="endMonth" name="endDate" readonly class="form-control customDatepicker" placeholder="@lang('index.end_date')" value="{{ old('endDate') }}">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <select tabindex="3" class="form-control select2 ir_w_100" name="selling_type">
                    <option @selected($selling_type == 1) value="1">@lang('index.top_selling')</option>
                    <option @selected($selling_type == 2) value="2">@lang('index.less_selling')</option>
                </select>
            </div>
        </div>
        <?php
            if(isLMni()):
        ?>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <select tabindex="2" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                    @foreach($outlets as $value)
                        <option @selected(old('outlet_id') == $value->id) value="{{ $value->id }}">{{ $value->outlet_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <?php
            endif;
        ?> 
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="mx-2">
                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn pull-left">@lang('index.submit')</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>




<?php $this->view('updater/reuseJs')?>