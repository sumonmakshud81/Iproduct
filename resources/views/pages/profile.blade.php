@extends('layouts.app')
@section('content')
    @php
        $baseURL = getBaseURL();
        $setting = getSettingsInfo();
        $base_color = '#6ab04c';
        if (isset($setting->base_color) && $setting->base_color) {
            $base_color = $setting->base_color;
        }
    @endphp
    <section class="main-content-wrapper">
        @include('utilities.messages')
        @if (appMode() == 'demo')
            <section class="alert-wrapper">
                <div class="alert alert-danger alert-dismissible show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-body">
                        <i class="m-right fa fa-triangle-exclamation"></i> @lang('index.demo_instruction')
                    </div>
                </div>
            </section>
        @endif
            <div class="row">
                <div class="col-md-4 col-lg-3 mt-2">
                    <h2 class="top-left-header mb-2 mt-2">@lang('index.profile_home')</h2>
                    <div class="user-profile-card">
                        <div class="d-flex align-items-center">
                            <div class="media-size-email">
                                @if (Auth::user()->photo != null and file_exists('uploads/user_photos/' . Auth::user()->photo))
                                    <img width="52" height="52" class="me-3 rounded-circle"
                                        src="{{ $baseURL }}uploads/user_photos/{{ auth()->user()->photo }}"
                                        alt="Image">
                                @else
                                    <img width="52" height="52" class="me-3 rounded-circle"
                                        src="{{ $baseURL }}assets/images/avatar.png" alt="Image">
                                @endif
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="m-0">{{ Auth::check() ? Auth::user()->name : 'Admin' }}</h5>
                                <p class="text-muted my-1 font-weight-bolder text-lowercase text-email">
                                    {{ Auth::check() ? Auth::user()->email : 'Admin' }}
                                </p>
                            </div>
                        </div>
                        <!-- End User Profile Info -->

                        <ul class="menu-list">
                            <li class="item">
                                <a href="{{ url('change-profile') }}">
                                    <span class="iconbg badge-light-primary">
                                        <iconify-icon icon="solar:user-circle-broken" width="20"></iconify-icon>
                                    </span>
                                    <span class="user-profile-card-text">
                                        @lang('index.change_profile')
                                    </span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="{{ url('change-password') }}">
                                    <span class="iconbg badge-light-success">
                                        <iconify-icon icon="solar:key-minimalistic-2-broken" width="20"></iconify-icon>
                                    </span>
                                    <span class="user-profile-card-text">
                                        @lang('index.change_password')
                                    </span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="{{ url('security-question') }}">
                                    <span class="iconbg badge-light-danger">
                                        <iconify-icon icon="solar:info-circle-broken" width="20"></iconify-icon>
                                    </span>
                                    <span class="user-profile-card-text">
                                        @lang('index.set_security_question')
                                    </span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="{{ route('logout') }}">
                                    <span class="iconbg badge-light-info">
                                        <iconify-icon icon="solar:logout-2-broken" width="18"></iconify-icon>
                                    </span>
                                    <span class="user-profile-card-text">
                                        @lang('index.logout')
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End User Profile -->
                <div class="col-md-8 col-lg-9 mt-2">
                    <h2 class="top-left-header mb-2 mt-2">@lang('index.running_productions')</h2>
                    <div class="table-card">
                        <div class="card-body table-responsive profile_min_height">
                            <input type="hidden" class="datatable_name" data-title="@lang('index.running_productions')"
                                data-id_name="datatable">
                            <table id="datatable" class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th class="width_1_p">@lang('index.reference_no')</th>
                                        <th class="width_13_p">@lang('index.product')</th>
                                        <th class="width_13_p">@lang('index.start_date')</th>
                                        <th class="width_20_p">@lang('index.consumed_time')</th>
                                        <th class="width_26_p">@lang('index.manufacture_cost')</th>
                                        <th class="width_13_p">@lang('index.sale_price')</th>
                                    </tr>
                                </thead>
                                @php
                                    $running_production = App\Manufacture::where('manufacture_status', 'inProgress')
                                        ->where('del_status', 'Live')
                                        ->get();
                                @endphp
                                <tbody>
                                    @foreach ($running_production as $value)
                                        <tr>
                                            <td>{{ safe($value->reference_no) }}</td>
                                            <td>{{ safe(getProductNameById($value->product_id)) }}</td>
                                            <td>{{ safe(getDateFormat($value->start_date)) }}</td>
                                            <td>{{ safe($value->consumed_time) }}</td>
                                            <td>{{ safe(getCurrency($value->mtotal_cost)) }}</td>
                                            <td>{{ safe(getCurrency($value->msale_price)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Tickets Table -->
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
