@extends('layouts.app')
@section('content')
<?php
$baseURL = getBaseURL();
$setting = getSettingsInfo();
$base_color = "#6ab04c";
if (isset($setting->base_color) && $setting->base_color) {
    $base_color = $setting->base_color;
}
?>
<section class="main-content-wrapper">
    @include('utilities.messages')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h2 class="top-left-header">{{isset($title) && $title?$title:''}}</h2>
                <input type="hidden" class="datatable_name" data-title="{{isset($title) && $title?$title:''}}" data-id_name="datatable">
            </div>
            <div class="col-md-offset-4 col-md-2">

            </div>
        </div>
    </section>

    @if($startDate != '' || $endDate != '')
    <div class="my-2">
        <h4 class="ir_txtCenter_mt0"><span>@lang('index.supplier')</span></h4>
        <h4 class="ir_txtCenter_mt0">
            @lang('index.date'):
            {{($startDate != '') ? getDateFormat($startDate) :''}}
            {{($endDate != '') ? ' - '.getDateFormat($endDate):''}}
        </h4>
    </div>
    @endif


    <div class="box-wrapper">
        {{Form::open(['route'=>'supplier-ledger', 'id' => "attendance_form", 'method'=>'GET'])}}
        @csrf
        <div class="row">
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    {!! Form::text('startDate', $startDate, ['class' => 'form-control customDatepicker', 'readonly'=>"", 'placeholder'=>"Start Date"]) !!}
                </div>
            </div>
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    {!! Form::text('endDate', $endDate, ['class' => 'form-control customDatepicker', 'readonly'=>"", 'placeholder'=>"End Date"]) !!}
                </div>
            </div>
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    <select tabindex="-1" class="form-control select2" id="supplier_id" name="supplier_id" aria-hidden="true">
                        <option value="">@lang('index.supplier')</option>
                        @foreach($suppliers as $value)
                        <option value="{{$value->id}}" {{ ($supplier_id == $value->id) ? 'selected="selected"':'' }}>{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <div class="form-group">
                    <select class="form-control select2 op_width_100_p" id="type" name="type">
                        <option value="All" {{ ($type == "All") ? 'selected="selected"':'' }}>@lang('index.all')</option>
                        <option value="Debit" {{ ($type == "Debit") ? 'selected="selected"':'' }}>@lang('index.debit')</option>
                        <option value="Credit" {{ ($type == "Credit") ? 'selected="selected"':'' }}>@lang('index.credit')</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-2">
                <div class="form-group">
                    <button type="submit" value="submit" class="btn bg-blue-btn w-100">@lang('index.submit')</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('index.sn')</th>
                            <th>@lang('index.date')</th>
                            <th>@lang('index.transaction_type')</th>
                            <th>@lang('index.transaction_no')</th>
                            <th>@lang('index.debit')</th>
                            <th>@lang('index.credit')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $grand_valance = 0;
                        $grand_debit = 0;
                        $grand_credit = 0;
                        $countTotal = 0;
                        $sum_of_grand_debit = 0;
                        $sum_of_grand_credit = 0;
                        @endphp

                        @if(isset($type) && $type == 'All')
                            @php
                            $balance = 0;
                            $sum_of_debit = 0;
                            $sum_of_credit = 0;
                            $i = count($supplierLedger);
                            @endphp
                            @if(isset($supplierLedger) && $supplierLedger)
                                @foreach($supplierLedger as $key => $supplier)
                                    @if(isset($sum_of_op_before_date) && $key == 0)
                                        @if($sum_of_op_before_date > 0)
                                            @php
                                            $balance += $sum_of_op_before_date;
                                            $sum_of_debit += $sum_of_op_before_date;
                                            @endphp
                                        @else
                                            @php
                                            $balance -= abs($sum_of_op_before_date);
                                            $sum_of_credit += abs($sum_of_op_before_date);
                                            @endphp
                                        @endif
                                    @else
                                        @if($supplier->debit != 0)
                                            @php
                                            $balance += (float)$supplier->debit;
                                            $sum_of_debit += (float)$supplier->debit;
                                            @endphp
                                        @else
                                            @php
                                            $balance -= (float)$supplier->credit;
                                            $sum_of_credit += (float)$supplier->credit;
                                            @endphp
                                        @endif
                                    @endif
                                    <tr>
                                        <td class="text-start">{{ $i-- }}</td>
                                        <td>{{ $supplier->date != '' ? getDateFormat($supplier->date) : '' }}</td>
                                        <td>{{ $supplier->type }}</td>
                                        <td>{{ $supplier->reference_no }}</td>
                                        @if(isset($sum_of_op_before_date) && $key == 0)
                                            <td>{{ $sum_of_op_before_date > 0 ? getAmtCustom($sum_of_op_before_date) : 0 }}</td>
                                            <td>{{ $sum_of_op_before_date < 0 ? getAmtCustom(abs($sum_of_op_before_date)) : 0 }}</td>
                                        @else
                                            <td>{{ $supplier->debit != 0 ? getAmtCustom($supplier->debit) : 0 }}</td>
                                            <td>{{ $supplier->credit != 0 ? getAmtCustom($supplier->credit) : 0 }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        @elseif($type == 'Credit')
                            @php
                            $balance = 0;
                            $sum_of_credit = 0;
                            @endphp
                            @if(isset($supplierLedger) && $supplierLedger)
                                @foreach($supplierLedger as $key => $supplier)
                                    @if($supplier->debit === '0' && $supplier->type != 'Opening Balance')
                                        @php
                                        $sum_of_credit += $supplier->credit;
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ $key + 1 }}</td>
                                            <td>{{ $supplier->date != '' ? getDateFormat($supplier->date) : '' }}</td>
                                            <td>{{ $supplier->type }}</td>
                                            <td>{{ $supplier->reference_no }}</td>
                                            @if(isset($sum_of_op_before_date) && $key == 0)
                                                <td>{{ $sum_of_op_before_date > 0 ? getAmtCustom($sum_of_op_before_date) : 0 }}</td>
                                                <td>{{ $sum_of_op_before_date < 0 ? getAmtCustom(abs($sum_of_op_before_date)) : 0 }}</td>
                                            @else
                                                <td></td>
                                                <td>{{ $supplier->credit != 0 ? getAmtCustom($supplier->credit) : 0 }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @elseif($type == 'Debit')
                            @php
                            $sum_of_debit = 0;
                            @endphp
                            @if(isset($supplierLedger) && $supplierLedger)
                                @foreach($supplierLedger as $key => $supplier)
                                    @if($supplier->credit === '0' && $supplier->type != 'Opening Balance')
                                        @php
                                        $sum_of_debit += $supplier->debit;
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ $key + 1 }}</td>
                                            <td>
                                                {{ $supplier->date != '' ? getDateFormat($supplier->date) : '' }}
                                            </td>
                                            <td>{{ $supplier->type }}</td>
                                            <td>{{ $supplier->reference_no }}</td>
                                            @if(isset($sum_of_op_before_date) && $key == 0)
                                                <td>{{ $sum_of_op_before_date > 0 ? getAmtCustom($sum_of_op_before_date) : 0 }}</td>
                                                <td>{{ $sum_of_op_before_date < 0 ? getAmtCustom(abs($sum_of_op_before_date)) : 0 }}</td>
                                            @else
                                                <td>{{ $supplier->debit != 0 ? getAmtCustom($supplier->debit) : 0 }}</td>
                                                <td>{{ $supplier->credit != 0 ? getAmtCustom($supplier->credit) : 0 }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>

    </div>

</section>
@endsection
@section('script')
<script src="{!! $baseURL.'assets/datatable_custom/jquery-3.3.1.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/jquery.dataTables.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/dataTables.bootstrap4.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/dataTables.buttons.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/buttons.html5.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/buttons.print.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/jszip.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/pdfmake.min.js'!!}"></script>
<script src="{!! $baseURL.'assets/dataTable/vfs_fonts.js'!!}"></script>
<script src="{!! $baseURL.'frequent_changing/newDesign/js/forTable.js'!!}"></script>
<script src="{!! $baseURL.'frequent_changing/js/custom_report.js'!!}"></script>
@endsection