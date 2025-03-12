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
                            <th class="op_width_2_p">@lang('index.sn')</th>
                            <th>@lang('index.account_name')</th>
                            <th>@lang('index.credit')</th>
                            <th>@lang('index.debit')</th>
                            <th>@lang('index.balance')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $totalDebit = 0;
                            $totalCredit = 0;
                            $totalBalance = 0;
                         ?>

                        @if($obj && !empty($obj))
                        <?php $i = count($obj); ?>
                        @endif

                        @foreach($obj as $value)
                        @php
                            $credit = getTotalCredit($value->id);
                            $totalCredit += $credit;
                            $debit = getTotalDebit($value->id);
                            $totalDebit += $debit;
                            $balance = $credit - $debit;
                            $totalBalance += $balance;
                        @endphp
                        <tr>
                            <td class="c_center">{{$i--}}</td>
                            <td>{{ $value->name }}</td>
                            <td>{{ getAmtCustom($credit) }}</td>
                            <td>{{ getAmtCustom($debit) }}</td>
                            <td>{{ getAmtCustom($balance) }}</td>
                        </tr>                        
                        @endforeach
                        <tr>
                            <td class="c_center"></td>
                            <td class="fw-bold text-end">@lang('index.total')=</td>
                            <td class="fw-bold">{{ getAmtCustom($totalCredit) }}</td>
                            <td class="fw-bold">{{ getAmtCustom($totalDebit) }}</td>
                            <td class="fw-bold">{{ getAmtCustom($totalBalance) }}</td>
                        </tr>
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