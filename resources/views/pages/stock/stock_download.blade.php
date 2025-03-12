<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reference_no }}</title>
    <link rel="stylesheet" href="{{ getBaseURL() }}frequent_changing/css/pdf_common.css">
</head>

<body>
    <div class="m-auto b-r-5 p-30">
        <table>
            <tr>
                <td class="w-50">
                    <h3 class="pb-7">{{ getCompanyInfo()->company_name }}</h3>
                    <p class="pb-7 f-w-900 rgb-71">{{ getCompanyInfo()->address }}</p>
                    <p class="pb-7 f-w-900 rgb-71">@lang('index.email') : {{ getCompanyInfo()->email }}</p>
                    <p class="pb-7 f-w-900 rgb-71">@lang('index.phone') : {{ getCompanyInfo()->phone }}</p>
                    <p class="pb-7 f-w-900 rgb-71">@lang('index.website') : {{ getCompanyInfo()->web_site }}</p>
                </td>
                <td class="w-50 text-right">
                    <img src="{!! getBaseURL() .
                        (isset(getWhiteLabelInfo()->logo) ? 'uploads/white_label/' . getWhiteLabelInfo()->logo : 'images/logo.png') !!}" alt="site-logo">
                </td>
            </tr>
        </table>
        <div class="text-center pt-10 pb-10">
            <h2 class="color-000000 pt-20 pb-20">@lang('index.stock_details')</h2>
        </div>
        <table>
            <tr>
                <td class="w-50">
                    <h4 class="pb-7">@lang('index.bill_to'):</h4>
                    <p class="pb-7">{{ $customer->name }}</p>
                    <p class="pb-7 f-w-900 rgb-71">{{ $customer->address }}</p>
                    <p class="pb-7 f-w-900 rgb-71">{{ $customer->phone }}</p>
                </td>
                <td class="w-50 text-right">
                    <h4 class="pb-7">@lang('index.sale_info'):</h4>
                    <p class="pb-7">
                        <span class="f-w-600">@lang('index.invoice_no'):</span>
                        {{ $reference_no }}
                    </p>
                    <p class="pb-7 f-w-900 rgb-71">
                        <span class="f-w-600">@lang('index.date'):</span>
                        {{ getDateFormat($date) }}
                    </p>
                </td>
            </tr>
        </table>

        <table class="w-100 mt-20">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-start">@lang('index.sn')</th>
                    <th class="w-20 text-start">@lang('index.product')(@lang('index.code'))</th>
                    <th class="w-15">@lang('index.current_stock')</th>
                    <th class="w-15">@lang('index.need_to_manufacture')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($productData) && $productData)
                    @foreach ($productData as $key => $value)
                        <?php
                        $i = 1;
                        ?>
                        <tr class="rowCount">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $i++ }}</p>
                            </td>
                            <td>{{ $value['name'] }}</td>
                            <td class="text-center">{{ $value['quantity'] }}</td>
                            <td class="text-center">{{ $value['price'] }}
                            </td>
                        </tr>
                    @endforeach
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
    </div>
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('frequent_changing/js/onload_print.js') }}"></script>
</body>

</html>
