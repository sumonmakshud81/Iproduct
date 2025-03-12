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

    <div class="box-wrapper">
        {{Form::open(['route'=>'supplier-due-report', 'id' => "attendance_form", 'method'=>'get'])}}
        <div class="row">
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
                            <th>@lang('index.supplier')</th>
                            <th>@lang('index.current_balance')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $grandCredit = 0;
                        $grandDebit = 0;
                        $i = count($supplierDueReport)-1;
                        @endphp

                        @if(isset($type) && $type == 'Credit')
                            @if(isset($supplierDueReport))
                                @foreach($supplierDueReport as $key => $value)
                                    @php
                                    $totalDue = getSupplierDue($value->id);
                                    @endphp
                                    @if($totalDue != 0 && $totalDue > 0)
                                        @php
                                        $grandCredit += $totalDue;
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ $i-- }}</td>
                                            <td class="text-left">{{ $value->name }}</td>
                                            <td class="text-left">{{ getAmtCustom(abs($totalDue)) }} (@lang('index.credit'))</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <th class="op_width_2_p op_center"></th>
                                <th class="text-end">@lang('index.total_credit_amount')=</th>
                                <th>{{ $grandCredit == 0 ? '' : getAmtCustom(abs($grandCredit)) }}</th>
                            </tr>
                        @elseif(isset($type) && $type == 'Debit')
                            @if(isset($supplierDueReport))
                                @foreach($supplierDueReport as $key => $value)
                                    @php
                                    $totalDue = getSupplierDue($value->id);
                                    @endphp
                                    @if($totalDue != 0 && $totalDue < 0)
                                        @php
                                        $grandDebit += $totalDue;
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ $i-- }}</td>
                                            <td class="text-left">{{ $value->name }}</td>
                                            <td class="text-left">{{ getAmtCustom(abs($totalDue)) }}(@lang('index.debit'))</td>
                                        </tr>                                        
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <th class="op_width_2_p op_center"></th>
                                <th class="text-end">@lang('index.total_debit_amount')=</th>
                                <th>{{ $grandDebit == 0 ? '' : getAmtCustom(abs($grandDebit)) }}</th>
                            </tr>
                        @else
                            @if(isset($supplierDueReport))
                                @foreach($supplierDueReport as $key => $value)
                                    @php
                                    $totalDue = getSupplierDue($value->id);
                                    @endphp
                                    @if($totalDue != 0)
                                        <tr>
                                            <td class="text-start">{{ $i-- }}</td>
                                            <td class="text-left">{{ $value->name }}</td>
                                            @if($totalDue > 0)
                                                @php
                                                $grandCredit += $totalDue;
                                                @endphp
                                                <td class="text-left">{{ getAmtCustom(abs($totalDue)) }} (@lang('index.credit'))</td>
                                            @elseif($totalDue < 0)
                                                @php
                                                $grandDebit += $totalDue;
                                                @endphp
                                                <td class="text-left">{{ getAmtCustom(abs($totalDue)) }}(@lang('index.debit'))</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <th class="op_width_2_p op_center"></th>
                                <th class="text-end">@lang('index.total_credit_amount')=</th>
                                <th>{{ $grandCredit == 0 ? '' : getAmtCustom(abs($grandCredit)) }}</th>
                            </tr>
                            <tr>
                                <th class="op_width_2_p op_center"></th>
                                <th class="text-end">@lang('index.total_debit_amount')=</th>
                                <th>{{ $grandDebit == 0 ? '' : getAmtCustom(abs($grandDebit)) }}</th>
                            </tr>
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