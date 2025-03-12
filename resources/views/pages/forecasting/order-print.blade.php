<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>@lang('index.demand_forecasting_by_order')</title>
    <link rel="stylesheet" href="{{ getBaseURL() }}frequent_changing/css/pdf_common.css">
</head>

<body>
    <div class="m-auto b-r-5 p-30">
        @foreach ($orders as $order)
            <table>
                <tr>
                    <td class="w-50">
                        <h3 class="pb-7">{{ getCompanyInfo()->company_name }}</h3>
                        <p class="pb-7 rgb-71">{{ safe(getCompanyInfo()->address) }}</p>
                        <p class="pb-7 rgb-71">@lang('index.email') : {{ safe(getCompanyInfo()->email) }}</p>
                        <p class="pb-7 rgb-71">@lang('index.phone') : {{ safe(getCompanyInfo()->phone) }}</p>
                        <p class="pb-7 rgb-71">@lang('index.website') : {{ safe(getCompanyInfo()->website) }}</p>
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

            <table>
                <tr>
                    <td class="w-50">
                        <h4 class="pb-7">@lang('index.customer_info'):</h4>
                        <p class="pb-7 rgb-71 arabic">{{ $order->customer->name }}</p>
                        <p class="pb-7 rgb-71 arabic">{{ $order->customer->address }}</p>
                        <p class="pb-7 rgb-71">{{ $order->customer->phone }}</p>
                    </td>
                    <td class="w-50 text-right">
                        <h4 class="pb-7">@lang('index.sale_info'):</h4>
                        <p class="pb-7">
                            <span class="">@lang('index.invoice_no'):</span>
                            {{ $order->reference_no }}
                        </p>
                        <p class="pb-7 rgb-71">
                            <span class="">@lang('index.sale_date'):</span>
                            {{ getDateFormat($order->sale_date) }}
                        </p>
                        <p class="pb-7 rgb-71">
                            <span class="">@lang('index.status'):</span>
                            {{ $order->status }}
                        </p>
                    </td>
                </tr>
            </table>

            <h4 class="mt-20">@lang('index.ordered_product'):</h4>
            <table class="w-100 mt-10">
                <thead class="b-r-3 bg-color-000000">
                    <tr>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.product')(@lang('index.code'))</th>
                        <th class="w-15 text-start">@lang('index.quantity')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($order->details) && count($order->details) > 0)
                        @foreach ($order->details as $key => $value)
                            <?php
                            $i = 1;
                            $productInfo = getFinishedProductInfo($value->product_id);
                            $need_to_manufacture = (int)$productInfo->current_total_stock - (int)$value->quantity;
                            ?>
                            <tr class="rowCount" data-id="{{ $value->product_id }}">
                                <td class="width_1_p">
                                    <p class="set_sn">{{ $i++ }}</p>
                                </td>
                                <td class="text-start arabic">{{ $productInfo->name }}({{ $productInfo->code }})</td>                               
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

            <h4 class="mt-20">@lang('index.product_forecasting'):</h4>
            <table class="w-100 mt-10">
                <thead class="b-r-3 bg-color-000000">
                    <tr>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.product')(@lang('index.code'))</th>
                        <th class="w-15 text-start">@lang('index.available_quantity')</th>
                        <th class="w-15 text-start">@lang('index.required_quantity')</th>
                        <th class="w-20 text-right pr-5">@lang('index.need_to_manufacture')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($order->details) && count($order->details) > 0)
                        @foreach ($order->details as $key => $value)
                            <?php
                            $i = 1;
                            $productInfo = getFinishedProductInfo($value->product_id);
                            $need_to_manufacture = (int)$productInfo->current_total_stock - (int)$value->quantity;
                            ?>
                            <tr class="rowCount" data-id="{{ $value->product_id }}">
                                <td class="width_1_p">
                                    <p class="set_sn">{{ $i++ }}</p>
                                </td>
                                <td class="text-start arabic">{{ $productInfo->name }}({{ $productInfo->code }})</td>

                                <td class="text-start">{{ $productInfo->current_total_stock }}
                                    {{ getRMUnitById($productInfo->unit) }}
                                </td>
                                <td class="text-start">{{ $value->quantity }}
                                    {{ getRMUnitById($productInfo->unit) }}
                                </td>
                                <td class="text-right pr-10">
                                    {{ $need_to_manufacture > 0 ? 0 : abs($need_to_manufacture) }}
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
            <h4 class="mt-20">@lang('index.raw_material_forecasting'):</h4>
            <table class="w-100 mt-10">
                <thead class="b-r-3 bg-color-000000">
                    <tr>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.raw_material')(@lang('index.code'))</th>
                        <th class="w-15 text-start">@lang('index.available_quantity')</th>
                        <th class="w-15 text-start">@lang('index.required_quantity')</th>
                        <th class="w-20 text-right pr-5">@lang('index.need_to_purchase')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($order->details) && count($order->details) > 0)
                        @foreach ($order->details as $key => $value)
                            <?php
                            $i = 1;
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
                                        <td class="text-start arabic">
                                            {{ $minfo->name }}({{ $minfo->code }})</td>

                                        <td class="text-start">{{ $minfo->current_stock }}
                                            {{ getRMUnitById($minfo->unit) }}
                                        </td>
                                        <td class="text-start">{{ $required_quantity }}
                                            {{ getRMUnitById($minfo->unit) }}
                                        </td>
                                        <td class="text-right pr-10">
                                            {{ $need_to_purchase > 0 ? 0 : abs($need_to_purchase) }}
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



            <table class="mt-50">
                <tr>
                    <td class="w-50">
                    </td>
                    <td class="w-50 text-right">
                        <p class="rgb-71 d-inline border-top-e4e5ea pt-10">@lang('index.authorized_signature')</p>
                    </td>
                </tr>
            </table>
            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    $base_color = '#6ab04c';
    if (isset($setting->base_color) && $setting->base_color) {
        $base_color = $setting->base_color;
    }
    ?>
    <script src="{{ $baseURL . 'assets/bower_components/jquery/dist/jquery.min.js' }}"></script>
    <script src="{{ $baseURL . 'frequent_changing/js/onload_print.js' }}"></script>

</body>

</html>
