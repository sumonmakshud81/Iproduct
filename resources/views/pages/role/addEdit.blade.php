@extends('layouts.app')

@section('script_top')
    <?php
    $baseURL = getBaseURL();
    $setting = getSettingsInfo();
    ?>
@endsection

@section('content')
    <section class="main-content-wrapper">
        <section class="content-header">
            <h3 class="top-left-header">
                {{ isset($title) && $title ? $title : '' }}
            </h3>
        </section>


        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                {!! Form::model(isset($data) && $data ? $data : '', [
                    'method' => isset($data) && $data ? 'PATCH' : 'POST',
                    'files' => true,
                    'route' => ['role.update', isset($data->id) && $data->id ? $data->id : ''],
                    'id' => 'common-form',
                ]) !!}
                @csrf
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label>@lang('index.role_name'){!! starSign() !!}</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                    placeholder="{{ __('index.role_name') }}"
                                    value="{{ isset($data) && $data->title ? $data->title : old('title') }}">
                            </div>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label><b>@lang('index.role_permission')</b></label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" class="form-check-input" id="select_all_role">
                            <label for="select_all_role">@lang('index.select_all')</label>
                        </div>

                        @foreach ($menus as $menu_key => $menu)
                            @if ($menu->title != 'Home')
                                <div class="col-md-12 mt-4">
                                    <div class="form-group">
                                        <input {{ isset($data) && in_array($menu->id, $data->menu_ids) ? 'checked' : '' }}
                                            id="menu_{{ $menu->id }}"
                                            class="menu_class form-check-input check_menu_{{ $menu->id }}"
                                            data-name = "{{ $menu_key + 1 }}" data-id={{ $menu->id }} type="checkbox"
                                            value="{{ $menu->id }}">
                                        <label for="menu_{{ $menu->id }}"><b>{{ $menu->title }}</b></label>
                                    </div>
                                </div>
                                <span>
                                    <hr class="m-0">
                                </span>
                            @endif

                            @foreach ($menu->activities as $activity_key => $activity)
                                <div class="col-md-4 mt-2">
                                    <div class="form-group">

                                        <input
                                            {{ (isset($data) && in_array($activity->id, $data->activity_ids)) || $activity->auto_select == 'Yes' ? 'checked' : '' }}
                                            id="menu_activity_{{ $activity->id }}" data-id = "{{ $menu->id }}"
                                            class="activity_class form-check-input menu_activities_{{ $menu->id }}"
                                            type="checkbox" name="activity_id[]" value="{{ $activity->id }}">
                                        <label
                                            for="menu_activity_{{ $activity->id }}">{{ $activity->activity_name }}</label>
                                    </div>

                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
                            <a class="btn bg-second-btn" href="{{ route('role.index') }}"><iconify-icon
                                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <?php
    $baseURL = getBaseURL();
    ?>
    <script type="text/javascript" src="{!! $baseURL . 'frequent_changing/js/role.js' !!}"></script>
@endsection
