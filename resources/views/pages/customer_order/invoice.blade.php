<?php
$setting = getSettingsInfo();
$tax_setting = getTaxInfo();
$baseURL = getBaseURL();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $obj->reference_no }}</title>
    <link rel="stylesheet" href="{{ getBaseURL() }}frequent_changing/css/pdf_common.css">
</head>

<body>
    <div class="m-auto b-r-5 p-30">
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
            <h2 class="color-000000 pt-20 pb-20">@lang('index.order_details')</h2>
        </div>
        <table>
            <tr>
                <td class="w-50">
                    <h4 class="pb-7">@lang('index.customer_info'):</h4>
                    <p class="pb-7">{{ $obj->customer->name }}</p>
                    <p class="pb-7 rgb-71">{{ $obj->customer->address }}</p>
                    <p class="pb-7 rgb-71">{{ $obj->customer->phone }}</p>
                    <p class="pb-7 rgb-71">{{ $obj->customer->email }}</p>
                </td>
                <td class="w-50 text-right">
                    <h4 class="pb-7">@lang('index.order_info'):</h4>
                    <p class="pb-7">
                        <span class="">@lang('index.reference_no'):</span>
                        {{ $obj->reference_no }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.delivery_date'):</span>
                        {{ getDateFormat($obj->date) }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.type'):</span>
                        {{ $obj->order_type }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.delivery_address'):</span>
                        {{ $obj->delivery_address }}
                    </p>
                </td>
            </tr>
        </table>

        <table class="w-100 mt-20">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-start">@lang('index.sn')</th>
                    <th class="w-25 text-start">@lang('index.product')(@lang('index.code'))</th>
                    <th class="w-15 text-center">@lang('index.unit_price')</th>
                    <th class="w-15 text-center">@lang('index.quantity')</th>
                    <th class="w-15 text-center">@lang('index.subtotal')</th>
                    <th class="w-15 text-center">@lang('index.discount')</th>
                    <th class="w-10 text-right pr-5">@lang('index.total')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($orderDetails) && $orderDetails)
                    @php($i = 1)
                    @foreach ($orderDetails as $key => $value)
                        <?php
                        $productInfo = getFinishedProductInfo($value->product_id);
                        ?>
                        <tr class="rowCount" data-id="{{ $value->product_id }}">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $i++ }}</p>
                            </td>
                            <td class="text-start">{{ $productInfo->name }}({{ $productInfo->code }})</td>
                            <td class="text-center">{{ getAmtCustom($value->unit_price) }}</td>
                            <td class="text-center">{{ $value->quantity }}
                                {{ getRMUnitById($productInfo->unit) }}
                            </td>
                            <td class="text-center">{{ getAmtCustom($value->sub_total) }}
                            </td>
                            <td class="text-center">{{ getAmtCustom($value->discount_percent) }}
                            </td>
                            <td class="text-right pr-10">{{ getAmtCustom($value->total_cost) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>


        <h4 class="mt-20">@lang('index.invoice_quotations')</h4>
        <table class="w-100 mt-10">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-start">@lang('index.sn')</th>
                    <th class="w-15 text-start">@lang('index.type')</th>
                    <th class="w-15">@lang('index.date')</th>
                    <th class="w-15">@lang('index.amount')</th>
                    <th class="w-15">@lang('index.paid')</th>
                    <th class="w-15">@lang('index.due')</th>
                    <th class="w-15 text-right">@lang('index.order_due')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($orderInvoice) && $orderInvoice)
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($orderInvoice as $key => $value)
                        <tr class="rowCount">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $i++ }}</p>
                            </td>
                            <td class="text-start">{{ $value->invoice_type }}</td>
                            <td class="text-center">{{ getDateFormat($value->invoice_date) }}</td>
                            <td class="text-center">{{ getAmtCustom($value->amount) }}
                            </td>
                            <td class="text-center">{{ getAmtCustom($value->paid_amount) }}
                            </td>
                            <td class="text-center">{{ getAmtCustom($value->due_amount) }}
                            </td>
                            <td class="text-right pr-10">{{ getAmtCustom($value->order_due_amount) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @if (isset($orderDeliveries) && count($orderDeliveries) > 0)
            <h4 class="w-100 mt-20">@lang('index.deliveries')</h5>
                <table class="w-100 mt-10">
                    <thead class="b-r-3 bg-color-000000">
                        <tr>
                            <th class="w-5 text-start">@lang('index.sn')</th>
                            <th class="w-20 text-start">@lang('index.product')</th>
                            <th class="w-15">@lang('index.quantity')</th>
                            <th class="w-15">@lang('index.delivery_date')</th>
                            <th class="w-15">@lang('index.status')</th>
                            <th class="w-15 text-right">@lang('index.note')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($orderDeliveries as $key => $value)
                            <?php
                            $productInfo = getFinishedProductInfo($value->product_id);
                            
                            ?>
                            <tr class="rowCount">
                                <td class="width_1_p">
                                    <p class="set_sn">{{ $i++ }}</p>
                                </td>
                                <td class="text-start">{{ safe($productInfo->name) }}</td>
                                <td class="text-center">{{ safe($value->quantity) }}
                                    {{ getRMUnitById($productInfo->unit) }}</td>
                                <td class="text-center">{{ getDateFormat($value->delivery_date) }}</td>
                                <td class="text-center">{{ safe($value->delivery_status) }}
                                </td>
                                <td class="text-right">{{ safe($value->delivery_note) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
        @endif
        <table>
            <tr>
                <td class="w-30">

                </td>
                <td class="w-50">
                    <table class="mt-10 mb-10">
                        <tr>
                            <td class="w-50 border-top-dotted-gray border-bottom-dotted-gray">
                                <p class="">@lang('index.total_cost') :</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom($obj->total_cost) }}</p>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="">@lang('index.subtotal')</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom($obj->total_amount) }}</p>
                            </td>
                        </tr>
                    </table>   
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="">@lang('index.profit')</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom($obj->total_profit) }}</p>
                            </td>
                        </tr>
                    </table>                  
                </td>
            </tr>
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
    <script src="{{ $baseURL . ('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ $baseURL . ('frequent_changing/js/onload_print.js') }}"></script>
</body>

</html>
