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
                    @if (routePermission('sale.print-invoice'))
                        <a href="javascript:void();" target="_blank" class="btn bg-second-btn print_invoice"
                            data-id="{{ $obj->id }}"><iconify-icon icon="solar:printer-broken"></iconify-icon>
                            @lang('index.print')</a>
                    @endif
                    @if (routePermission('sale.download-invoice'))
                        <a href="{{ route('sales.download_invoice', encrypt_decrypt($obj->id, 'encrypt')) }}"
                            target="_blank" class="btn bg-second-btn print_btn"><iconify-icon
                                icon="solar:cloud-download-broken"></iconify-icon>
                            @lang('index.download')</a>
                    @endif
                    @if (routePermission('sale.index'))
                        <a class="btn bg-second-btn" href="{{ route('sales.index') }}"><iconify-icon
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
                                        <p class="pb-7 rgb-71">{{ safe(getCompanyInfo()->address) }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.email') : {{ safe(getCompanyInfo()->email) }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.phone') : {{ safe(getCompanyInfo()->phone) }}</p>
                                        <p class="pb-7 rgb-71">@lang('index.website') : {{ safe(getCompanyInfo()->web_site) }}
                                        </p>
                                    </td>
                                    <td class="w-50 text-right">
                                        <img src="{!! getBaseURL() .
                                            (isset(getWhiteLabelInfo()->logo) ? 'uploads/white_label/' . getWhiteLabelInfo()->logo : 'images/logo.png') !!}" alt="site-logo">
                                    </td>
                                </tr>
                            </table>
                            <div class="text-center pt-10 pb-10">
                                <h2 class="color-000000 pt-20 pb-20">@lang('index.sales_invoice')</h2>
                            </div>
                            <table>
                                <tr>
                                    <td class="w-50">
                                        <h4 class="pb-7">@lang('index.customer_info'):</h4>
                                        <p class="pb-7">{{ $obj->customer->name }}</p>
                                        <p class="pb-7 rgb-71">{{ $obj->customer->address }}</p>
                                        <p class="pb-7 rgb-71">{{ $obj->customer->phone }}</p>
                                    </td>
                                    <td class="w-50 text-right">
                                        <h4 class="pb-7">@lang('index.sale_info'):</h4>
                                        <p class="pb-7">
                                            <span class="">@lang('index.invoice_no'):</span>
                                            {{ $obj->reference_no }}
                                        </p>
                                        <p class="pb-7 rgb-71">
                                            <span class="">@lang('index.sale_date'):</span>
                                            {{ getDateFormat($obj->sale_date) }}
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
                                        <th class="w-30 text-start">@lang('index.product')(@lang('index.code'))</th>
                                        <th class="w-15 text-center">@lang('index.unit_price')</th>
                                        <th class="w-15 text-center">@lang('index.quantity')</th>
                                        <th class="w-20 text-right pr-5">@lang('index.total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($sale_details) && $sale_details)
                                        @foreach ($sale_details as $key => $value)
                                            <?php
                                            $productInfo = getFinishedProductInfo($value->product_id);
                                            $manufactureInfo = $value->manufacture_id != null ? getManufactureInfo($value->manufacture_id) : null;
                                            ?>
                                            <tr class="rowCount" data-id="{{ $value->product_id }}">
                                                <td class="width_1_p">
                                                    <p class="set_sn">{{ $key + 1 }}</p>
                                                </td>
                                                <td class="text-start">
                                                    {{ $productInfo->name }}({{ $productInfo->code }})
                                                    @if (
                                                        $manufactureInfo &&
                                                            $manufactureInfo->expiry_days !== null &&
                                                            $manufactureInfo->complete_date !== null &&
                                                            $manufactureInfo->expiry_days !== 0)
                                                        <br><small>Expiry Date:
                                                            {{ getDateFormat(expireDate($manufactureInfo->complete_date, $manufactureInfo->expiry_days)) }}</small>
                                                    @endif
                                                    @if ($manufactureInfo && $manufactureInfo->batch_no !== null && $manufactureInfo->batch_no !== '')
                                                        <br><small>Batch Number: {{ $manufactureInfo->batch_no }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ getAmtCustom($value->unit_price) }}
                                                </td>
                                                <td class="text-center">{{ $value->product_quantity }}
                                                    {{ getRMUnitById($productInfo->unit) }}
                                                </td>
                                                <td class="text-right pr-10">{{ getAmtCustom($value->total_amount) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <table>
                                <tr>
                                    <td valign="top" class="w-50">
                                        <div class="pt-20">
                                            <h4 class="d-block pb-10">@lang('index.note')</h4>
                                            <div class="">
                                                <p class="h-180 color-black">
                                                    {{ $obj->note }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-50">
                                        <table>
                                            <tr>
                                                <td class="w-50 pr-0">
                                                    <p class="">@lang('index.subtotal')</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->subtotal) }} </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td class="w-50 pr-0">
                                                    <p class="">@lang('index.other')</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->other) }} </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <table>
                                            <tr>
                                                <td class="w-50">
                                                    <p class="">@lang('index.discount')</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->discount) }} </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <table class="mt-10 mb-10">
                                            <tr>
                                                <td class="w-50 pr-0 border-top-dotted-gray border-bottom-dotted-gray">
                                                    <p class="">@lang('index.grand_total') :</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->grand_total) }} </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td class="w-50 pr-0">
                                                    <p class="">@lang('index.paid')</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->paid) }} </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <table>
                                            <tr>
                                                <td class="w-50 pr-0">
                                                    <p class="">@lang('index.due')</p>
                                                </td>
                                                <td class="w-50 pr-0 text-right">
                                                    <p>{{ getAmtCustom($obj->due) }} </p>
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
                    </div>
                </div>
            </div>

        </section>
    </section>
@endsection
@section('script')
    <script src="{!! $baseURL . 'frequent_changing/js/sales.js' !!}"></script>
@endsection
