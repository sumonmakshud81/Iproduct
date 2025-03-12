<div>
    <div class="row">
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="width_10_p">

                        </th>
                        <th class="width_15_p">@lang('index.name')</th>
                        <th class="width_10_p">@lang('index.salary')</th>
                        <th class="width_10_p">@lang('index.additional')</th>
                        <th class="width_10_p">@lang('index.subtraction')</th>
                        <th class="width_10_p">@lang('index.total')</th>
                        <th class="width_15_p">@lang('index.note')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userList as $user)
                        <tr class="row_counter" data-id="{{ isset($user->user_id) ? $user->user_id : $user->id }}">
                            <th class="width_10_p">
                                <label class="container width_83_p"> @lang('index.select')
                                    <input class="checkbox_user" type="checkbox" @checked(isset($user->p_status) && $user->p_status)
                                        name="product_id{{ isset($user->user_id) ? $user->user_id : $user->id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <td>
                                {{ $user->name }}
                                <input type="hidden" name="user_id[]"
                                    value="{{ isset($user->user_id) ? $user->user_id : $user->id }}">
                            </td>
                            <td>
                                <input type="text" class="form-control"
                                    id="salary_{{ isset($user->user_id) ? $user->user_id : $user->id }}" name="salary[]"
                                    value="{{ isset($user->salary) && $user->salary ? $user->salary : '0.00' }}"
                                    readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control cal_row integerchk" onfocus="select()"
                                    id="additional_{{ isset($user->user_id) ? $user->user_id : $user->id }}"
                                    name="additional[]"
                                    value="{{ isset($user->additional) && $user->additional ? $user->additional : '0.00' }}">
                            </td>
                            <td>
                                <input type="text" class="form-control cal_row integerchk" onfocus="select()"
                                    id="subtraction_{{ isset($user->user_id) ? $user->user_id : $user->id }}"
                                    name="subtraction[]"
                                    value="{{ isset($user->subtraction) && $user->subtraction ? $user->subtraction : '0.00' }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" readonly
                                    id="total_{{ isset($user->user_id) ? $user->user_id : $user->id }}" name="total[]"
                                    value="{{ isset($user->total) && $user->total ? $user->total : '0.00' }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="notes[]"
                                    value="{{ isset($user->notes) && $user->notes ? $user->notes : '' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="width_10_p">
                            <label class="container width_83_p"> @lang('index.select_all')
                                <input class="checkbox_userAll" type="checkbox" id="checkbox_userAll">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th class="width_15_p">@lang('index.name')</th>
                        <th class="width_10_p">@lang('index.salary')</th>
                        <th class="width_10_p">@lang('index.additional')</th>
                        <th class="width_10_p">@lang('index.subtraction')</th>
                        <th class="width_10_p">@lang('index.total') = <span class="total_amount"></span></th>
                        <th class="width_15_p">@lang('index.note')</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <br>
        <div class="col-md-1"></div>
        <div class="clearfix"></div>
        <div class="col-md-8"></div>
    </div>

    <div class="row">
        <div class="col-sm-12 mb-2 col-md-8"></div>
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.account') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select tabindex="2" class="form-control select2 @error('account_id') is-invalid @enderror"
                            id="account_id" name="account_id">
                            <option value="">@lang('index.select_account')</option>
                            @foreach ($accountList as $value)
                                <option
                                    {{ isset($obj->account_id) && $obj->account_id == $value->id ? 'selected' : '' }}
                                    value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('payroll.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
