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

        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-start">@lang('index.sn')</th>
                            <th>@lang('index.reference_no')</th>
                            <th>@lang('index.date')</th>
                            <th>@lang('index.customer')</th>
                            <th>@lang('index.g_total')</th>
                            <th>@lang('index.paid')</th>
                            <th>@lang('index.due')</th>
                            <th>@lang('index.items')</th>
                            <th>@lang('index.status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $pGrandTotal = 0; ?>

                        @if($obj && !empty($obj))
                        <?php 
                        $i = count($obj);
                        $grandTotal = 0;
                        $paid = 0;
                        $due = 0;
                         ?>
                        @endif

                        @foreach($obj as $value)
                        @php
                        $grandTotal = $grandTotal + $value->grand_total;
                        $paid = $paid + $value->paid;
                        $due = $due + $value->due;
                        @endphp
                        <tr>
                            <td class="text-start">{{$i--}}</td>
                            <td>{{ $value->reference_no }}</td>
                            <td>{{ getDateFormat($value->sale_date) }}</td>
                            <td>{{ $value->customer->name ?? 'N/A' }}</td>
                            <td>{{ getAmtCustom($value->grand_total) }}</td>
                            <td>{{ getAmtCustom($value->paid) }}</td>
                            <td>{{ getAmtCustom($value->due) }}</td>
                            <td>{!! getSaleItems($value->id) !!}</td>
                            <td>{{ $value->status }}</td>
                        </tr>
                        <?php $pGrandTotal = $pGrandTotal + $value->total_due; ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-end">@lang('index.total')=</th>
                        <th>{{ getAmtCustom($grandTotal) }}</th>
                        <th>{{ getAmtCustom($paid) }}</th>
                        <th>{{ getAmtCustom($due) }}</th>
                        <th></th>
                    </tfoot>
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