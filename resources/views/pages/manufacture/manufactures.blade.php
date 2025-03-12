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
                    <input type="hidden" class="datatable_name" data-filter="yes"
                        data-title="{{ isset($title) && $title ? $title : '' }}" data-id_name="datatable">
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
                                <th class="width_1_p">@lang('index.sn')</th>
                                <th class="width_10_p">@lang('index.reference_no')</th>
                                <th class="width_10_p">@lang('index.product')</th>
                                <th class="width_10_p">@lang('index.status')</th>
                                <th class="width_10_p">@lang('index.start_date')</th>
                                <th class="width_10_p">@lang('index.complete_date')</th>
                                <th class="width_10_p">@lang('index.batch_no')</th>
                                <th class="width_10_p">@lang('index.manufacture_stages')</th>
                                <th class="width_10_p">@lang('index.remaining_time')</th>
                                <th class="width_10_p">@lang('index.consumed_time')</th>
                                <th class="width_10_p">@lang('index.quantity')</th>
                                <th class="width_10_p">@lang('index.partially_done_quantity')</th>
                                <th class="width_10_p">@lang('index.remaining_quantity')</th>
                                <th class="width_10_p">@lang('index.expiry_date')</th>
                                <th class="width_10_p">@lang('index.manufacture_cost')</th>
                                <th class="width_10_p">@lang('index.sale_price')</th>
                                <th class="width_1_p">@lang('index.actions')</th>
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
                                    <td>{{ safe($value->reference_no) }}</td>
                                    <td>{{ safe(getProductNameById($value->product_id)) }}</td>
                                    <td>{{ safe(manufactureStatusShow($value->manufacture_status)) }}</td>
                                    <td>{{ safe(getDateFormat($value->start_date)) }}</td>
                                    <td>{{ $value->complete_date != null ? safe(getDateFormat($value->complete_date)) : 'N/A' }}
                                    </td>
                                    <td>{{ safe($value->batch_no) }}</td>
                                    <td>{{ safe($value->stage_name) }}</td>
                                    <td>N/A</td>
                                    <td>{{ safe($value->consumed_time) }}</td>
                                    <td>{{ $value->product_quantity ?? 0 }}</td>
                                    <td>{{ $value->partially_done_quantity ?? 0 }}</td>
                                    <td>{{ $value->product_quantity - ($value->partially_done_quantity ?? 0) }}</td>
                                    <td>{{ $value->expiry_days == null || $value->expiry_days == 0 || $value->complete_date == null ? 'N/A' : getDateFormat(expireDate($value->complete_date, $value->expiry_days)) }}
                                    </td>
                                    <td>{{ safe(getCurrency($value->mtotal_cost)) }}</td>
                                    <td>{{ safe(getCurrency($value->msale_price)) }}</td>
                                    <td>
                                        @if ($value->manufacture_status != 'done')
                                            @if (routePermission('productions.edit'))
                                                <a href="{{ url('productions') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}/edit"
                                                    class="button-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('index.edit')"><i class="fa fa-edit"></i></a>
                                            @endif
                                            <a href="javascript:void(0)" class="button-info changePartillyDone"
                                                data-bs-toggle="modal" data-bs-target="#changeStatusModal"
                                                data-id="{{ $value->id }}"
                                                data-total_quantity="{{ $value->product_quantity }}"
                                                data-partially_done="{{ $value->partially_done_quantity ?? 0 }}"><i
                                                    class="fa fa-pencil" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('index.update_partially_done')"></i></a>
                                        @endif

                                        @if (routePermission('manufacture.view'))
                                            <a href="{{ url('productions') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}"
                                                class="button-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.view_details')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                        @if (routePermission('manufacture.duplicate'))
                                            <a href="{{ url('productions') }}/{{ encrypt_decrypt($value->id, 'encrypt') }}/duplicate"
                                                class="button-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@lang('index.clone')"><i class="fa fa-clone"></i></a>
                                        @endif
                                        <a href="{{ route('download_manufacture_details', encrypt_decrypt($value->id, 'encrypt')) }}"
                                            class="button-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="@lang('index.download')"><i class="fa fa-download"></i></a>
                                        @if (routePermission('manufacture.print'))
                                        <a href="javascript:void();" target="_blank" data-id="{{ $value->id }}"
                                            class="button-info print_invoice" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="@lang('index.print')"><i
                                                class="fa fa-print"></i></a>
                                        @endif
                                        @if ($value->manufacture_status != 'done')
                                            @if (routePermission('manufacture.delete'))
                                                <a href="#" class="delete button-danger"
                                                    data-form_class="alertDelete{{ $value->id }}" type="submit"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('index.delete')">
                                                    <form action="{{ route('productions.destroy', $value->id) }}"
                                                        class="alertDelete{{ $value->id }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <i class="c_padding_13 fa fa-trash tiny-icon"></i>
                                                    </form>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        {{-- Change Status --}}
        <div class="modal fade" id="changeStatusModal" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.update_partially_done') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <form action="{{ route('manufacture.changePartiallyDone') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <p><strong>@lang('index.total_quantity') : </strong> <span class="total_quantity"></span></p>
                            <p class="d-flex align-items-center"><strong>@lang('index.partially_done_quantity') : </strong>
                             <input type="number" name="partially_done_quantity" placeholder="Enter Partially Done Quantity"
                                    class="form-control partially_done_quantity w-50 ms-2" required>
                            </p>
                            <p><strong>@lang('index.remaining_quantity') : </strong> <span class="remaining_quantity"></span></p>
                            <input type="hidden" name="manufacture_id" class="manufacture_id">                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-blue-btn">@lang('index.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Update Produces History --}}
        <div class="modal fade" id="updateProducedQuantity" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.update_produced_quantity') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <form action="{{ route('manufacture.updateProducedQuantity') }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <table id="producedHistory" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('index.sn')</th>
                                        <th>@lang('index.produced_quantity')</th>
                                        <th>@lang('index.produced_date')</th>
                                    </tr>
                                </thead>
                                <tbody class="producedHistoryBody">

                                </tbody>
                            </table>

                            <p><strong>@lang('index.remaining_produced') : </strong> <span class="remaining_quantity"></span></p>

                            <input type="hidden" name="manufacture_id" class="manufacture_id">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="produced_quantity">@lang('index.produced_quantity')</label>
                                    <input type="number" name="produced_quantity" class="form-control produced_quantity"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="produced_date">@lang('index.produced_date')</label>
                                    <input type="date" name="produced_date"
                                        class="form-control produced_date customDatepicker" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn bg-blue-btn">@lang('index.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Filter Modal --}}
        <div class="modal fade" id="filterModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('index.rm_stocks')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! Form::model(isset($obj) && $obj ? $obj : '', [
                            'id' => 'add_form',
                            'method' => isset($obj) && $obj ? 'GET' : 'GET',
                            'enctype' => 'multipart/form-data',
                            'route' => ['productions.index'],
                        ]) !!}
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 mb-2">
                                <div class="form-group rmCatclass">
                                    <label>@lang('index.status') </label>
                                    <select name="status" class="form-control select2" id="status">
                                        <option value="">@lang('index.select')</option>
                                        <option {{ $status == 'draft' ? 'selected' : '' }} value="draft">
                                            @lang('index.draft')</option>
                                        <option {{ $status == 'inProgress' ? 'selected' : '' }} value="inProgress">
                                            @lang('index.in_progress')</option>
                                        <option {{ $status == 'done' ? 'selected' : '' }} value="done">
                                            @lang('index.done')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div class="form-group rmCatclass">
                                    <label>@lang('index.product') </label>
                                    <select name="finish_p_id" class="form-control select2">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($finishProduct as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ $product_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div class="form-group rmCatclass">
                                    <label>@lang('index.batch_no') </label>
                                    <input type="text" name="batch_no" class="form-control"
                                        value="{{ $batch_no ?? '' }}" placeholder="Enter Batch no">
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2">
                                <div class="form-group rmCatclass">
                                    <label>@lang('index.customer') </label>
                                    <select name="customer" class="form-control select2">
                                        <option value="">@lang('index.select')</option>
                                        @foreach ($customers as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ $customer == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mb-2 subBtn">
                                <button type="submit" name="submit" value="submit"
                                    class="btn w-100 bg-blue-btn">@lang('index.submit')</button>
                            </div>

                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
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
    <script src="{!! $baseURL . 'frequent_changing/js/manufacture.js' !!}"></script>    
@endsection
