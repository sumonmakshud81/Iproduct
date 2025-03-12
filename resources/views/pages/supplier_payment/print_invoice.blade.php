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
            <h2 class="color-000000 pt-20 pb-20">@lang('index.supplier_payment_invoice')</h2>
        </div>
        <table>
            <tr>
                <td class="w-50">
                    <h4 class="pb-7">@lang('index.supplier_info'):</h4>
                    <p class="pb-7 arabic">{{ $obj->supplierName->name }}</p>
                    <p class="pb-7 rgb-71 arabic">{{ $obj->supplierName->address }}</p>
                    <p class="pb-7 rgb-71 arabic">{{ $obj->supplierName->phone }}</p>
                </td>
                <td class="w-50 text-right">
                    <h4 class="pb-7 arabic">@lang('index.supplier_payment_info'):</h4>
                    <p class="pb-7">
                        <span class="">@lang('index.invoice_no'):</span>
                        {{ $obj->reference_no }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.payment_date'):</span>
                        {{ getDateFormat($obj->date) }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.status'):</span>
                        {{ $obj->status }}
                    </p>
                </td>
            </tr>
        </table>

        <table class="w-100 mt-20">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-start">@lang('index.sn')</th>
                    <th class="w-30 text-start arabic">@lang('index.supplier')</th>
                    <th class="w-15">@lang('index.amount')</th>
                    <th class="w-15">@lang('index.date')</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                ?>
                <tr class="rowCount" data-id="{{ $obj->id }}">
                    <td class="width_1_p">
                        <p class="set_sn">{{ $i++ }}</p>
                    </td>
                    <td class="text-start">{{ @$obj->supplierName->name }}</td>
                    <td class="text-center">{{ getAmtCustom($obj->amount) }}
                    </td>
                    <td class="text-center">
                        {{ getDateFormat($obj->date) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table>
            <tr>
                <td valign="top" class="w-50">
                    <div class="pt-20">
                        <h4 class="d-block pb-10">@lang('index.note')</h4>
                        <div class="">
                            <p class="h-180 color-black arabic">
                                {{ $obj->note }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="w-50">
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="f-w-600">Amount Pay</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom($obj->amount) }}</p>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="f-w-600">Amount Due</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom(getSupplierDue($obj->supplier)) }}</p>
                            </td>
                        </tr>
                    </table>                                        
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="f-w-600">Amount Enclosed</p>
                            </td>
                            <td class="w-50 text-right pr-0">
                                <p>{{ getAmtCustom(getSupplierDue($obj->supplier)) }}</p>
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
    <?php
        $baseURL = getBaseURL();
        $setting = getSettingsInfo();
        $base_color = '#6ab04c';
        if (isset($setting->base_color) && $setting->base_color) {
            $base_color = $setting->base_color;
        }
    ?>
    <script src="{{ $baseURL .('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ $baseURL . ('frequent_changing/js/onload_print.js') }}"></script>
</body>

</html>
