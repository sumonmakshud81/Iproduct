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
        @foreach ($products as $product)
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

            <h4 class="mt-20">@lang('index.product_info'):</h4>
            <table class="w-100 mt-10">
                <thead class="b-r-3 bg-color-000000">
                    <tr>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.product')(@lang('index.code'))</th>
                        <th class="w-15">@lang('index.available_quantity')</th>
                        <th class="w-15">@lang('index.required_quantity')</th>
                        <th class="w-20 text-right pr-5">@lang('index.need_to_manufacture')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="rowCount" data-id="{{ $product->product_id }}">
                        <td class="width_1_p">
                            <p class="set_sn">{{ 1 }}</p>
                        </td>
                        <td class="text-start arabic">{{ $product->name }}({{ $product->code }})</td>

                        <td class="text-center">{{ $product->current_total_stock }}
                            {{ getRMUnitById($product->unit) }}
                        </td>
                        <td class="text-center">{{ $product->required_quantity }}
                            {{ getRMUnitById($product->unit) }}
                        </td>
                        <td class="text-right pr-10">
                            {{ $product->need_to_manufacture > 0 ? 0 : abs($product->need_to_manufacture) }}
                            {{ getRMUnitById($product->unit) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4 class="mt-20">@lang('index.raw_material_info'):</h4>
            <table class="w-100 mt-10">
                <thead class="b-r-3 bg-color-000000">
                    <tr>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.raw_material')(@lang('index.code'))</th>
                        <th class="w-15">@lang('index.available_quantity')</th>
                        <th class="w-15">@lang('index.required_quantity')</th>
                        <th class="w-20 text-right pr-5">@lang('index.need_to_purchase')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($product->rmaterials) && count($product->rmaterials) > 0)
                        @php($i = 1)
                        @foreach ($product->rmaterials as $key => $value)
                            <?php
                            $minfo = getRawMaterialInfoById($value->rmaterials_id);
                            $required_quantity = $value->consumption * $product->required_quantity;
                            $need_to_purchase = (int) $minfo->current_stock - (int) $required_quantity;
                            ?>
                            <tr class="rowCount" data-id="{{ $value->id }}">
                                <td class="width_1_p">
                                    <p class="set_sn">{{ $i++ }}</p>
                                </td>
                                <td class="text-start arabic">
                                    {{ $minfo->name }}({{ $minfo->code }})</td>

                                <td class="text-center">{{ $minfo->current_stock }}
                                    {{ getRMUnitById($minfo->unit) }}
                                </td>
                                <td class="text-center">{{ $required_quantity }}
                                    {{ getRMUnitById($minfo->unit) }}
                                </td>
                                <td class="text-right pr-10">
                                    {{ $need_to_purchase > 0 ? 0 : abs($need_to_purchase) }}
                                    {{ getRMUnitById($minfo->unit) }}
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
