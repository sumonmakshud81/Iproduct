@extends('layouts.app')
@section('script_top')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <?php
    $setting = getSettingsInfo();
    $tax_setting = getTaxInfo();
    $baseURL = getBaseURL();
    ?>
    <link rel="stylesheet" href="{{ getBaseURL() }}frequent_changing/css/pdf_common.css">
@endsection

@section('content')
    <!-- Optional theme -->

    <section class="main-content-wrapper bg-main">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="top-left-header">{{ isset($title) && $title ? $title : '' }}</h2>
                </div>
                <div class="col-md-6">
                    @if (routePermission('order.print-invoice'))
                        <a href="javascript:void();" target="_blank" class="btn bg-second-btn print_invoice"
                            ><iconify-icon icon="solar:printer-broken"></iconify-icon>
                            @lang('index.print')</a>
                    @endif
                    @if (routePermission('order.download-invoice'))
                        <a href="{{ route('forecasting.order.download') }}"
                            target="_blank" class="btn bg-second-btn print_btn"><iconify-icon
                                icon="solar:cloud-download-broken"></iconify-icon>
                            @lang('index.download')</a>
                    @endif
                    @if (routePermission('order.index'))
                        <a class="btn bg-second-btn" href="{{ route('forecasting.order') }}"><iconify-icon
                                icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="content">

            <div class="col-md-12">
                <div class="card" id="dash_0">
                    <div class="card-body p30">
                        <div class="m-auto b-r-5">
                            <table>
                                <tr>
                                    <td class="w-50">
                                        <h3 class="pb-7">{{ getCompanyInfo()->company_name }}</h3>
                                        <p class="pb-7 rgb-71">{{ getCompanyInfo()->address }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.email') : {{ getCompanyInfo()->email }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.phone') : {{ getCompanyInfo()->phone }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.website') : {{ getCompanyInfo()->web_site }}</p>
                                    </td>
                                    <td class="w-50 text-right">
                                        <img src="{!! getBaseURL() .
                                            (isset(getWhiteLabelInfo()->logo) ? 'uploads/white_label/' . getWhiteLabelInfo()->logo : 'images/logo.png') !!}" alt="site-logo">
                                    </td>
                                </tr>
                            </table>
                            <div class="text-center pt-10 pb-10">
                                <h2 class="color-000000 pt-20 pb-20">@lang('index.demand_forecasting_by_order')</h2>
                            </div>
                            @foreach ($orders as $order)                            
                            <table class="mt-20">
                                <tr>
                                    <td class="w-50 text-left">
                                        <h4 class="pb-7">@lang('index.order_info'):</h4>
                                        <p class="pb-7">
                                            <span class="">@lang('index.reference_no'):</span>
                                            {{ $order->reference_no }}
                                        </p>
                                        <p class="pb-7 rgb-71">
                                            <span class="">@lang('index.delivery_date'):</span>
                                            {{ getDateFormat($order->date) }}
                                        </p>
                                        <p class="pb-7 rgb-71">
                                            <span class="">@lang('index.type'):</span>
                                            {{ $order->order_type }}
                                        </p>
                                        <p class="pb-7 rgb-71">
                                            <span class="">@lang('index.delivery_address'):</span>
                                            {{ $order->delivery_address }}
                                        </p>
                                    </td>
                                    <td class="w-50 text-right">
                                        <h4 class="pb-7">@lang('index.customer_info'):</h4>
                                        <p class="pb-7">{{ $order->customer->name }}</p>
                                        <p class="pb-7 rgb-71">{{ $order->customer->address }}</p>
                                        <p class="pb-7 rgb-71">{{ $order->customer->phone }}</p>
                                        <p class="pb-7 rgb-71">{{ $order->customer->email }}</p>
                                    </td>                                    
                                </tr>
                            </table>

                            {{-- Ordered Product --}}
                            <h4>@lang('index.ordered_product'):</h4>
                            <table class="w-100 mt-10">
                                <thead class="b-r-3 bg-color-000000">
                                    <tr>
                                        <th class="w-5 text-start">@lang('index.sn')</th>
                                        <th class="w-25 text-start">@lang('index.product')(@lang('index.code'))</th>
                                        <th class="w-15 text-start">@lang('index.quantity')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($order->details) && count($order->details) > 0)
                                        @php($i = 1)
                                        @foreach ($order->details as $key => $value)
                                            <?php
                                            $productInfo = getFinishedProductInfo($value->product_id);
                                            $need_to_manufacture = (int)$productInfo->current_total_stock - (int)$value->quantity;
                                            ?>
                                            <tr class="rowCount" data-id="{{ $value->product_id }}">
                                                <td class="width_1_p">
                                                    <p class="set_sn">{{ $i++ }}</p>
                                                </td>
                                                <td class="text-start">{{ $productInfo->name }}({{ $productInfo->code }})
                                                </td>
                                                <td class="text-start">{{ $value->quantity }}
                                                    {{ getRMUnitById($productInfo->unit) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">@lang('index.no_data_available')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>


                            {{-- Product Forecasting --}}
                            <h4 class="mt-20">@lang('index.product_forecasting'):</h4>
                            <table class="w-100 mt-10">
                                <thead class="b-r-3 bg-color-000000">
                                    <tr>
                                        <th class="w-5 text-start">@lang('index.sn')</th>
                                        <th class="w-25 text-start">@lang('index.product')(@lang('index.code'))</th>
                                        <th class="w-15 text-start">@lang('index.available_quantity')</th>
                                        <th class="w-15 text-start">@lang('index.required_quantity')</th>
                                        <th class="w-15 text-start">@lang('index.need_to_manufacture')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($order->details) && count($order->details) > 0)
                                        @php($i = 1)
                                        @foreach ($order->details as $key => $value)
                                            <?php
                                            $productInfo = getFinishedProductInfo($value->product_id);
                                            $need_to_manufacture = (int)$productInfo->current_total_stock - (int)$value->quantity;
                                            ?>
                                            <tr class="rowCount" data-id="{{ $value->product_id }}">
                                                <td class="width_1_p">
                                                    <p class="set_sn">{{ $i++ }}</p>
                                                </td>
                                                <td class="text-start">{{ $productInfo->name }}({{ $productInfo->code }})
                                                </td>
                                                <td class="text-start">{{ $productInfo->current_total_stock }}
                                                    {{ getRMUnitById($productInfo->unit) }}
                                                </td>
                                                <td class="text-start">{{ $value->quantity }}
                                                    {{ getRMUnitById($productInfo->unit) }}
                                                </td>
                                                <td class="text-start">{{ $need_to_manufacture > 0 ? 0 : abs($need_to_manufacture) }}
                                                    {{ getRMUnitById($productInfo->unit) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">@lang('index.no_data_available')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{-- Raw Material Forecasting --}}
                            <h4 class="mt-20">@lang('index.raw_material_forecasting'):</h4>
                            <table class="w-100 mt-10">
                                <thead class="b-r-3 bg-color-000000">
                                    <tr>
                                        <th class="w-5 text-start">@lang('index.sn')</th>
                                        <th class="w-25 text-start">@lang('index.raw_material')(@lang('index.code'))</th>
                                        <th class="w-15 text-start">@lang('index.available_quantity')</th>
                                        <th class="w-15 text-start">@lang('index.required_quantity')</th>
                                        <th class="w-15 text-start">@lang('index.need_to_purchase')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($order->details) && count($order->details) > 0)
                                        @php($i = 1)
                                        @foreach ($order->details as $key => $value)                                            
                                            <?php
                                            $rawMaterial = getProductRawMaterialByProductId($value->product_id);
                                            ?>
                                            
                                            @if (count($rawMaterial) > 0)
                                                @foreach ($rawMaterial as $rm)
                                                    <?php
                                                    $minfo = getRawMaterialInfoById($rm->rmaterials_id);
                                                    $required_quantity = $rm->consumption * $value->quantity;
                                                    $need_to_purchase = (int)$minfo->current_stock - (int)$required_quantity;
                                                    ?>
                                                    <tr class="rowCount" data-id="{{ $rm->id }}">
                                                        <td class="width_1_p">
                                                            <p class="set_sn">{{ $i++ }}</p>
                                                        </td>
                                                        <td class="text-start">{{ $minfo->name }}({{ $minfo->code }})
                                                        </td>
                                                        <td class="text-start">{{ number_format($minfo->current_stock, 2) }}
                                                            {{ getRMUnitById($minfo->unit) }}
                                                        </td>
                                                        <td class="text-start">{{ $required_quantity }}
                                                            {{ getRMUnitById($minfo->unit) }}
                                                        </td>
                                                        <td class="text-start">{{ $need_to_purchase > 0 ? 0 : abs($need_to_purchase) }}
                                                            {{ getRMUnitById($minfo->unit) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif                                            
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">@lang('index.no_data_available')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @endforeach
                            <table class="mt-50">
                                <tr>
                                    <td class="w-50">
                                    </td>
                                    <td class="w-50 text-right">
                                        <p class="rgb-71 d-inline border-top-e4e5ea pt-10">@lang('index.authorized_signature')</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
@endsection
@section('script')
    <script src="{!! $baseURL . 'frequent_changing/js/order_forecast.js' !!}"></script>
@endsection
