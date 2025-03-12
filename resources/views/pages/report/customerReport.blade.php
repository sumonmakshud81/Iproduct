<script src="{{ base_url() }}frequent_changing/js/customer_report.js"></script>
<link rel="stylesheet" href="{{ base_url() }}frequent_changing/css/report.css">    

<div class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">{{ lang('customer_report') }}</h3>
    </section>

    <div>
        <h4 class="op_center op_margin_top_o">
            @if(isset($customer_id) && $customer_id)
                <span>{{ getCustomerName($customer_id) }}</span>
            @endif
        </h4>
        <h4>
            @if(isset($start_date) && $start_date && isset($end_date) && $end_date)
                {{ lang('report_date') }} {{ date($this->session->userdata('date_format'), strtotime($start_date)) }} - {{ date($this->session->userdata('date_format'), strtotime($end_date)) }}
            @elseif(isset($start_date) && $start_date && !$end_date)
                {{ lang('report_date') }} {{ date($this->session->userdata('date_format'), strtotime($start_date)) }}
            @elseif(isset($end_date) && $end_date && !$start_date)
                {{ lang('report_date') }} {{ date($this->session->userdata('date_format'), strtotime($end_date)) }}
            @endif
        </h4>
    </div>

    <section class="box-wrapper">
        <div class="row mb-3">
            <div class="col-md-2 mb-3">
                @form_open(base_url() . 'Report/customerReport', ['id' => 'customerReport'])
                <div class="form-group">
                    <input tabindex="1" autocomplete="off" type="text" id="" name="startDate" readonly class="form-control customDatepicker" placeholder="{{ lang('start_date') }}" value="{{ set_value('startDate') }}">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-group">
                    <input tabindex="2" autocomplete="off" type="text" id="endMonth" name="endDate" readonly class="form-control customDatepicker" placeholder="{{ lang('end_date') }}" value="{{ set_value('endDate') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <select tabindex="2" class="form-control select2 op_width_100_p" id="customer_id" name="customer_id">
                        <option value="">@lang('index.select_customer')</option>
                        @foreach ($customers as $value) 
                            <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="alert alert-error error-msg customer_id_err_msg_contnr op_padding_5_important">
                    <p id="customer_id_err_msg"></p>
                </div>
            </div>
            <div class="col-md-1 mb-3">
                <div class="form-group">
                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn">{{ lang('submit') }}</button>
                </div>
            </div>
        </div>

        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <h4 class="op_left op_margin_bottom_10">@lang('index.sale')</h4>
                <table class="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('index.sn')</th>
                            <th>@lang('index.date')</th>
                            <th>@lang('index.reference_no')</th>
                            <th>@lang('index.details'); ?> (@lang('index.Name_Price_Qty'));</th>
                            <th>@lang('index.g_total')</th>
                            <th>@lang('index.paid')</th>
                            <th>@lang('index.due')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $pGrandTotal = 0;
                        $pPaid = 0;
                        $pDue = 0;
                        @endphp
                        @if (isset($customerReport))
                            @foreach ($customerReport as $key => $value)
                                @php
                                $pGrandTotal += $value->total_payable;
                                $pPaid += $value->paid_amount;
                                $pDue += $value->due_amount;
                                $key++;
                                @endphp
                                <tr>
                                    <td class="text-start">{{ $key }}</td>
                                    <td>{{ getDateFormat($value->sale_date) }}</td>
                                    <td>{{ $value->sale_no }}</td>
                                    <td>
                                        @foreach ($value->items as $k1 => $item)
                                            {{ $item->name . " - " . $item->price . " - " . $item->qty }}
                                            @if ($k1 < (count($value->items) - 1))
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ getAmtCustom($value->total_payable) }}</td>
                                    <td>{{ getAmtCustom($value->paid_amount) }}</td>
                                    <td>{{ getAmtCustom($value->due_amount) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <th class="text-start"></th>
                        <th></th>
                        <th></th>
                        <th class="op_right">@lang('index.total')</th>
                        <th><?= getAmtCustom($pGrandTotal) ?></th>
                        <th>{{ getAmtCustom($pPaid) }}</th>
                        <th>{{ getAmtCustom($pDue) }}</th>
                    </tfoot>
                </table>
                <br>
                <h4 class="op_left op_margin_bottom_10">@lang('index.due_receive')</h4>
                <table class="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="op_width_2_p op_center">@lang('index.sn')</th>
                            <th>@lang('index.date')</th>
                            <th>@lang('index.receive_amount')</th>
                            <th class="op_width_45_p">@lang('index.note')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalAmount = 0;
                        @endphp
                        @if(isset($customerDueReceiveReport))
                            @foreach($customerDueReceiveReport as $key => $value)
                                @php
                                $totalAmount += $value->amount;
                                $key++;
                                @endphp
                                <tr>
                                    <td class="op_center">{{ $key }}</td>
                                    <td>{{ getDateFormat($value->date) }}</td>
                                    <td>{{ getAmtCustom($value->amount) }}</td>
                                    <td>{{ $value->note }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <th class="op_width_2_p op_center"></th>
                        <th class="op_right">@lang('index.total')</th>
                        <th>{{ getAmtCustom($totalAmount) }}</th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>


<?php $this->view('updater/reuseJs'); ?>