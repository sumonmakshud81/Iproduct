@extends('layouts.app')
@section('content')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    ?>
    <section class="main-content-wrapper">
        @include('utilities.messages')
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h2>
                    <input type="hidden" class="datatable_name" data-title="{{ isset($title) && $title ? $title : '' }}"
                        data-id_name="datatable">
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
                                <th class="w-1">@lang('index.sn')</th>
                                <th class="w-5">@lang('index.manufacture')</th>
                                <th class="w-5">@lang('index.product')</th>
                                <th class="w-5">@lang('index.total_loss')</th>
                                <th class="w-40">@lang('index.loss_product_materials')</th>
                                <th class="w-10">@lang('index.loss_percent')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($obj && !empty($obj))
                                <?php
                                $i = count($obj);
                                ?>
                            @endif
                            @foreach ($obj as $value)
                                <tr>
                                    <td class="c_center">{{ $i-- }}</td>
                                    <td>{{ $value->reference_no }}</td>
                                    <td>{{ $value->product->name }}</td>
                                    <td>{{ $value->production_loss != null ? getCurrency($value->production_loss) : 0 }}</td>
                                    <td>
                                        <div id="stockInnerTable">
                                            <ul>
                                                <li>
                                                    <div>@lang('index.product')</div>
                                                </li>
                                                <li>
                                                    <div class="w-50">@lang('index.product')</div>
                                                    <div class="w-25">@lang('index.quantity')</div>
                                                    <div class="w-25">@lang('index.loss_amount')</div>
                                                </li>
                                                @foreach ($value->productWaste as $product)
                                                    @foreach ($product->productItems as $item)
                                                        <li>
                                                            <div class="stock-alert-color w-50">
                                                                {{ getFinishProduct($item->finish_product_id) }}
                                                            </div>
                                                            <div class="stock-alert-color w-25">
                                                                {{ $item->fp_waste_amount }}
                                                            </div>
                                                            <div class="stock-alert-color w-25">
                                                                {{ getCurrency($item->loss_amount) }}</div>
                                                        </li>
                                                    @endforeach
                                                @endforeach

                                                <li class="fw-bold">
                                                    <div>@lang('index.raw_material')</div>
                                                </li>
                                                <li>
                                                    <div class="w-50">@lang('index.material_name')</div>
                                                    <div class="w-25">@lang('index.quantity')</div>
                                                    <div class="w-25">@lang('index.loss_amount')</div>
                                                </li>
                                                @foreach ($value->materialWaste as $item)
                                                    <li>
                                                        <div class="stock-alert-color w-50">
                                                            {{ getRMName($item->rmaterials_id) }}
                                                        </div>
                                                        <div class="stock-alert-color w-25">
                                                            {{ $item->waste_amount }}
                                                        </div>
                                                        <div class="stock-alert-color w-25">
                                                            {{ getCurrency($item->loss_amount) }}</div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="stockInnerTable">
                                            <ul>
                                                <li>
                                                    <div>@lang('index.product_waste')</div>
                                                    <div>{{ $value->getWastePercentage()['product'] }}</div>
                                                </li>
                                                <li class="fw-bold">
                                                    <div>@lang('index.raw_material_waste')</div>
                                                    <div>{{ $value->getWastePercentage()['raw_material'] }}</div>
                                                </li>                                               
                                                
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
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
@endsection
