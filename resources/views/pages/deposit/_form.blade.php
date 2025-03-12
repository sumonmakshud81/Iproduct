<div>
    <div class="row">
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label for="code">@lang('index.reference_no') <span class="required_star">*</span></label>
                <input type="text" name="reference_no" id="code" class="check_required form-control @error('reference_no') is-invalid @enderror"
                    placeholder="reference_no No" readonly
                    value="{{ isset($obj->reference_no) && $obj->reference_no ? $obj->reference_no : $ref_no }}"
                    onfocus="select()">
                @error('opening_balance')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label for="date">@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="date" id="date" class="form-control @error('date') is-invalid @enderror customDatepicker"
                    placeholder="Date" readonly  value="{{ isset($obj->date) ? $obj->date : old('date') }}">
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label for="amount">@lang('index.amount') <span class="required_star">*</span></label>
                <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Amount"
                    value="{{ isset($obj->amount) ? $obj->amount : old('amount') }}">
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.deposit_withdraw') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select class="form-control @error('type') is-invalid @enderror select2" id="type" name="type">
                            <option value="">@lang('index.please_select')</option>
                            <option {{ isset($obj->type) && $obj->type == 'Deposit' || old('type') == 'Deposit' ? 'selected' : 'Deposit' }}
                                value="Deposit">@lang('index.deposit')</option>
                            <option {{ isset($obj->type) && $obj->type == 'Withdraw' || old('type') == 'Withdraw' ? 'selected' : 'Withdraw' }}
                                value="Withdraw">@lang('index.withdraw')</option>
                        </select>
                    </div>
                </div>
                @error('type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.account') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select class="form-control @error('account_id') is-invalid @enderror select2" id="account_id" name="account_id">
                            <option value="">@lang('index.select_account')</option>
                            @foreach ($accountList as $value)
                                <option
                                    {{ isset($obj->account_id) && $obj->account_id == $value->id || old('account_id') == $value->id ? 'selected' : '' }}
                                    value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('account_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.note')</label>
                {!! Form::text('note', null, [
                    'class' => 'form-control',
                    'id' => 'note',
                    'placeholder' => 'Note',
                    'rows' => '3',
                ]) !!}
                @error('note')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <input type="hidden" name="in_or_out" value="">

    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('deposit.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
