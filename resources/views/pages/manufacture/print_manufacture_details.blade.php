<?php
$setting = getSettingsInfo();
$tax_setting = getTaxInfo();
$baseURL = getBaseURL();
$whiteLabelInfo = App\WhiteLabelSettings::first();
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
            <h2 class="color-000000 pt-20 pb-20">@lang('index.manufacture_details')</h2>
        </div>
        <table>
            <tr>
                <td class="w-50">
                    <p class="pb-7">
                        <span class="">@lang('index.reference_no'):</span>
                        {{ $obj->reference_no }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.date'):</span>
                        {{ getDateFormat($obj->created_at) }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.manufacture_type'):</span>
                        @if ($obj->manufacture_type == 'ime')
                            Instant Manufacture Entry
                        @elseif($obj->manufacture_type == 'mbs')
                            Manufacture by Scheduling
                        @elseif($obj->manufacture_type == 'fco')
                            From Customer Order
                        @endif
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.status'):</span>
                        @if ($obj->manufacture_status == 'draft')
                            Draft
                        @elseif($obj->manufacture_status == 'inProgress')
                            In Progress
                        @elseif($obj->manufacture_status == 'done')
                            Done
                        @endif
                    </p>
                </td>
                <td class="w-50 text-right">
                    <p class="pb-7">
                        <span class="">@lang('index.product'):</span>
                        {{ getProductNameById($obj->product_id) }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.quantity'):</span>
                        {{ $obj->product_quantity }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.start_date'):</span>
                        {{ getDateFormat($obj->start_date) }}
                    </p>
                    <p class="pb-7 rgb-71">
                        <span class="">@lang('index.complete_date'):</span>
                        {{ $obj->complete_date != null ? getDateFormat($obj->complete_date) : 'N/A' }}
                    </p>
                    @if (isset($obj->batch_no) && !empty($obj->batch_no))
                        <p class="pb-7 rgb-71">
                            <span class="">@lang('index.batch_no'):</span>
                            {{ $obj->batch_no }}
                        </p>
                    @endif
                    @if (isset($obj->expiry_days) && !empty($obj->expiry_days))
                        <p class="pb-7 rgb-71">
                            <span class="">@lang('index.expiry_days'):</span>
                            {{ $obj->complete_date != null || $obj->expiry_days != null ? getDateFormat(expireDate($obj->complete_date, $obj->expiry_days)) : 'N/A' }}
                        </p>
                    @endif
                </td>
            </tr>
        </table>
        <h5>@lang('index.raw_material_consumption_cost') (RoM)</h5>
        <table class="w-100 mt-10">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-left">@lang('index.sn')</th>
                    <th class="w-30 text-left">@lang('index.raw_material')(@lang('index.code'))</th>
                    <th class="w-15 text-left">@lang('index.rate_per_unit')</th>
                    <th class="w-15 text-left">@lang('index.consumption')</th>
                    <th class="w-20 text-right">@lang('index.total_cost')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($m_rmaterials) && $m_rmaterials)
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($m_rmaterials as $key => $value)
                        <tr class="rowCount">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $i++ }}</p>
                            </td>
                            <td class="text-start">{{ getRMName($value->rmaterials_id) }}</td>
                            <td class="text-start">{{ getAmtCustom($value->unit_price) }}</td>
                            <td class="text-start">{{ $value->consumption }}
                                {{ getPurchaseUnitByRMID($value->rmaterials_id) }}
                            </td>
                            <td class="text-right padding-0">{{ getAmtCustom($value->total_cost) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">@lang('index.total_raw_material_cost') :</td>
                    <td class="text-right">{{ getAmtCustom($obj->mrmcost_total) }}</td>
                </tr>
            </tfoot>
        </table>

        <h5>@lang('index.non_inventory_cost')</h5>
        <table class="w-100 mt-10">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-left">@lang('index.sn')</th>
                    <th class="w-40 text-left">@lang('index.non_inventory_items')</th>
                    <th class="w-20 text-right">@lang('index.non_inventory_cost')</th>
                    <th class="w-20 text-right">@lang('index.account')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($m_nonitems) && $m_nonitems)
                    <?php
                    $j = 1;
                    ?>
                    @foreach ($m_nonitems as $key => $value)
                        <tr class="rowCount">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $j++ }}</p>
                            </td>
                            <td class="text-start"> {{ getNonInventroyItem($value->noninvemtory_id) }}
                            </td>
                            <td class="text-right padding-0">{{ getAmtCustom($value->nin_cost) }}</td>
                            <td class="text-right">{{ getAccountName($value->account_id) }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">@lang('index.total_non_inventory_cost') :</td>
                    <td class="text-right">{{ getAmtCustom($obj->mnoninitem_total) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <h5>@lang('index.manufacture_stages')</h5>
        <table class="w-100 mt-10">
            <thead class="b-r-3 bg-color-000000">
                <tr>
                    <th class="w-5 text-left">@lang('index.sn')</th>
                    <th class="w-30 text-left">@lang('index.stage')</th>
                    <th class="w-15 text-center">@lang('index.required_months')</th>
                    <th class="w-15 text-center">@lang('index.required_days')</th>
                    <th class="w-15 text-center">@lang('index.required_hour')</th>
                    <th class="w-15 text-center">@lang('index.required_minute')</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($m_stages) && $m_stages)
                    <?php
                    $k = 1;
                    $total_month = 0;
                    $total_day = 0;
                    $total_hour = 0;
                    $total_mimute = 0;
                    ?>
                    @foreach ($m_stages as $key => $value)
                        <?php
                        $checked = '';
                        $tmp_key = $key + 1;
                        if ($obj->stage_counter == $tmp_key) {
                            $checked = 'checked=checked';
                        }
                        $total_value = $value->stage_month * 2592000 + $value->stage_day * 86400 + $value->stage_hours * 3600 + $value->stage_minute * 60;
                        $months = floor($total_value / 2592000);
                        $hours = floor(($total_value % 86400) / 3600);
                        $days = floor(($total_value % 2592000) / 86400);
                        $minuts = floor(($total_value % 3600) / 60);
                        
                        $total_month += $months;
                        $total_hour += $hours;
                        $total_day += $days;
                        $total_mimute += $minuts;
                        
                        $total_stages = $total_month * 2592000 + $total_hour * 3600 + $total_day * 86400 + $total_mimute * 60;
                        $total_months = floor($total_stages / 2592000);
                        $total_hours = floor(($total_stages % 86400) / 3600);
                        $total_days = floor(($total_stages % 2592000) / 86400);
                        $total_minutes = floor(($total_stages % 3600) / 60);
                        
                        ?>
                        <tr class="rowCount">
                            <td class="width_1_p">
                                <p class="set_sn">{{ $k++ }}</p>
                            </td>
                            <td class="text-left">
                                {{ getProductionStages($value->productionstage_id) }}</td>
                            <td class="text-center">{{ $value->stage_month }}</td>
                            <td class="text-center">{{ $value->stage_day }}
                            </td>
                            <td class="text-center">{{ $value->stage_hours }}
                            </td>
                            <td class="text-center">{{ $value->stage_minute }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right pr-10">@lang('index.total') :</td>
                    <td class="text-center">
                        {{ isset($total_months) && $total_months ? $total_months : '' }}</td>
                    <td class="text-center">{{ isset($total_days) && $total_days ? $total_days : '' }}
                    </td>
                    <td class="text-center">
                        {{ isset($total_hours) && $total_hours ? $total_hours : '' }}</td>
                    <td class="text-center">
                        {{ isset($total_minutes) && $total_minutes ? $total_minutes : '' }}
                    </td>
                </tr>
            </tfoot>
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
                    <table class="mt-10 mb-10">
                        <tr>
                            <td class="w-50 border-top-dotted-gray border-bottom-dotted-gray">
                                <p class="">@lang('index.tax') :</p>
                            </td>
                        </tr>
                    </table>
                    @php
                        $collect_tax = $tax_items->collect_tax;
                        $tax_information = json_decode(
                            isset($obj->tax_information) && $obj->tax_information ? $obj->tax_information : '',
                        );
                    @endphp
                    @foreach ($tax_fields as $tax_field)
                        @if ($tax_information)
                            @foreach ($tax_information as $single_tax)
                                @if ($tax_field->id == $single_tax->tax_field_id)
                                    <table>
                                        <tr>
                                            <td class="w-50">
                                                <p class="">{{ $tax_field->tax }}</p>
                                            </td>
                                            <td class="w-50 text-right">
                                                <p>{{ intval($single_tax->tax_field_percentage) }}%</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            @endforeach                            
                        @endif
                    @endforeach
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="">@lang('index.total_cost')</p>
                            </td>
                            <td class="w-50 text-right">
                                <p>{{ getAmtCustom($obj->mtotal_cost) }}</p>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td class="w-50">
                                <p class="">@lang('index.profit_margin') (%)</p>
                            </td>
                            <td class="w-50 text-right">
                                <p>{{ $obj->mprofit_margin }}</p>
                            </td>
                        </tr>
                    </table>

                    <table class="mt-10 mb-10">
                        <tr>
                            <td class="w-50 border-top-dotted-gray border-bottom-dotted-gray">
                                <p class="">@lang('index.sale_price') :</p>
                            </td>
                            <td class="w-50 text-right">
                                <p>{{ getAmtCustom($obj->msale_price) }}</p>
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
    <script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('frequent_changing/js/onload_print.js') }}"></script>
</body>

</html>
