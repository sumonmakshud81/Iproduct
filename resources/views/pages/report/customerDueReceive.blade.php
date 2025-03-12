
<link rel="stylesheet" href="{{ url('frequent_changing/css/report.css') }}">
<div class="main-content-wrapper">

    <div class="content-header">
        <h3 class="top-left-header">{{ lang('customer_due_receive_report') }}</h3>
    </div>

    

    <div class="box-wrapper">
        <div class="text-right">
            <button class="dataFilterBy btn bg-blue-btn mb-2"><i class="fa fa-filter"></i>{{ lang('filter_by') }}</button>
        </div>

        <div>
            <h4>{{ isset($outletName) && $outletName ? lang('outlet').':' . escape_output($outletName) : '' }}</h4>
            <h4>
                @if(!empty($start_date) && $start_date != '1970-01-01')
                    {{ date($this->session->userdata('date_format'), strtotime($start_date)) }}
                @endif
                @if(isset($start_date) && isset($end_date) && $start_date != '1970-01-01' && $end_date != '1970-01-01')
                    -
                @endif
                @if(!empty($end_date) && $end_date != '1970-01-01')
                    {{ date($this->session->userdata('date_format'), strtotime($end_date)) }}
                @endif
            </h4>
            <h4 class="op_center op_margin_top_0">
                {{ lang('customer') }}: 
                <span class="text_decoration_u">
                    @if(isset($customer_id) && $customer_id)
                        {{ getName('tbl_customers', $customer_id) }}
                    @else
                        {{ lang('all') }}
                    @endif
                </span>
            </h4>
        </div>
        <div class="table-box">
            <div class="box-body">
                <div class="table-responsive">
                    
                    <table id="datatable"  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-start">@lang('index.sn')</th>
                                <th class="op_width_20_p"> @lang('index.customer')</th>
                                <th class="op_width_20_p"> @lang('index.amount')</th>
                                <th class="op_width_20_p"> @lang('index.date')</th>
                                <th> @lang('index.note')</th>
                                <th> @lang('index.received_by')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            $countTotal = 0;
                            if (isset($customerDueReceive)):
                                foreach ($customerDueReceive as $key => $value) {
                                    $grandTotal+=$value->amount;
                                    $key++;
                                    ?>
                                    <tr>
                                        <td class="text-start">{{ $key }}</td>
                                        <td>{{ getName('tbl_customers', $value->customer_id) }}</td>
                                        <td>{{ getAmtCustom($value->amount) }}</td>
                                        <td>{{ date($this->session->userdata('date_format'), strtotime($value->date)) }}</td>
                                        <td>{{ $value->note }}</td>
                                        <td>{{ userName($value->user_id) }}</td>
                                    </tr>
                                    <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-start"></th>
                                <th class="text-end">@lang('index.total')=</th>
                                <th><?= getAmtCustom($grandTotal) ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-overlay"></div>
<div id="product-filter" class="filter-modal">
    <div class="filter-modal-body">
        <header>
                <h3 class="filter-modal-title">@lang('FilterOptions')</h3>
                <button class="close-filter-modal"><i class="fa fa-times"></i></button>
        </header>
        {{ form_open(base_url() . 'Report/customerDueReceiveReport') }}
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <input tabindex="1" autocomplete="off" type="text" id="" name="startDate" readonly class="form-control customDatepicker" placeholder="{{ lang('start_date') }}" value="{{ set_value('startDate') }}">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <input tabindex="2" autocomplete="off" type="text" id="endMonth" name="endDate" readonly class="form-control customDatepicker" placeholder="{{ lang('end_date') }}" value="{{ set_value('endDate') }}">
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="form-group mx-2">
                <select tabindex="2" class="form-control select2 op_width_100_p" id="customer_id" name="customer_id">
                    <option value="">{{ lang('customer') }}</option>
                    @foreach($customers as $value)
                        <option value="{{ $value->id }}" {{ isset($customer_id) && $customer_id == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                    @endforeach
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
                        <option value="{{ $value->id }}" {{ set_select('outlet_id', $value->id) }}>{{ $value->outlet_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="col-sm-12 col-md-6 mb-2">
            <div class="mx-2">
                <button type="submit" name="submit" value="submit" class="btn bg-blue-btn pull-left">{{ lang('submit') }}</button>
            </div>
        </div>
        {{ form_close() }}
    </div>
</div>

<?php $this->view('updater/reuseJs')?>