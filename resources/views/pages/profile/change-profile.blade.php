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
    <script src="{!! $baseURL . 'assets/js/view/settings.js' !!}"></script>
    <!-- Main content -->
    <section class="main-content-wrapper">
        <section class="content-header dashboard_content_header my-2">
            <h3 class="top-left-header">
                <span>@lang('index.change_profile')</span>
            </h3>
        </section>

        @include('utilities.messages')

        <div class="box-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-box">
                        {{ Form::model($user, ['route' => ['update-change-profile'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <!-- form start -->
                        <div class="box-body">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.name') <span class="required_star">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Enter Your Name" value="{{ $user->name ?? old('name') }}">

                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.email') <span class="required_star">*</span></label>
                                        <input type="text" name="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Enter Email Address" value="{{ $user->email ?? old('email') }}">

                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('index.phone') <span class="required_star">*</span></label>
                                        <input type="text" name="phone_number"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            id="phone_number" placeholder="Enter Phone Number"
                                            value="{{ $user->phone_number ?? old('phone_number') }}">

                                        @error('phone_number')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label>@lang('index.photo_instruction')</label>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="hidden"
                                                        value="{{ isset($user->photo) && $user->photo ? $user->photo : '' }}"
                                                        name="photo_old">
                                                    <input type="file" name="photo"
                                                        class="form-control file_checker_global @error('photo') is-invalid @enderror"
                                                        id="photo" accept="image/png,image/jpeg,image/jpg,image/gif">

                                                </td>
                                                <td class="w_1">
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#view_photo_modal"
                                                        class="btn btn-md pull-right fit-content bg-second-btn view_modal_button ms-2"
                                                        id="photo_preview"><iconify-icon
                                                            icon="solar:eye-broken"></iconify-icon></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @error('photo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                                    <button type="submit" name="submit" value="submit"
                                        class="btn bg-blue-btn"><iconify-icon
                                            icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        {!! Form::close() !!}
                    </div>

                </div>
            </div>

        </div>

        <div class="modal fade" id="view_photo_modal" aria-hidden="true" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">@lang('index.photo') </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                data-feather="x"></i></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">
                            <img class="img-fluid"
                                src="{{ $baseURL }}uploads/user_photos/{{ isset($user->photo) && $user->photo ? $user->photo : '' }}"
                                id="show_id">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-blue-btn" data-dismiss="modal"
                            data-bs-dismiss="modal">@lang('index.close')</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script_bottom')
@endsection
