<div>
    <div class="row">
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.reference_no') <span class="required_star">*</span></label>

                <input type="text" name="reference_no" id="code"
                    class="check_required form-control @error('reference_no') is-invalid @enderror" placeholder="Reference No"
                    value="{{ isset($obj->reference_no) ? $obj->reference_no : $ref_no }}" onfocus="select()">
                @error('reference_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="only_date" id="only_date"
                    class="form-control @error('only_date') is-invalid @enderror customDatepicker" readonly
                    placeholder="Date" value="{{ isset($obj->only_date) ? $obj->only_date : old('only_date') }}">
                @error('only_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <input type="hidden" name="customer_id" id="customer_id">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.customers') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select tabindex="2" class="form-control @error('customer_id') is-invalid @enderror select2"
                            id="customer" name="customer">
                            <option value="">@lang('index.select_customer')</option>
                            @foreach ($customers as $value)
                                <option
                                    {{ isset($obj->customer_id) && $obj->customer_id == $value->id ? 'selected' : '' }}
                                    value="{{ $value->id }}|{{ getCustomerDue($value->id) }}">
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.amount') <span class="required_star">*</span></label>
                <input type="text" name="amount" id="amount"
                    class="form-control @error('amount') is-invalid @enderror" placeholder="Amount" value="{{ isset($obj->amount) ? $obj->amount : old('amount') }}">
                <div class="alert alert-primary p-1 due_balance_show d-none" role="alert">
                    
                </div>
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.accounts') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select tabindex="2" class="form-control @error('account_id') is-invalid @enderror select2"
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

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4" id="in_time_container">
            <div class="form-group">
                <label>@lang('index.note')</label>
                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note" rows="3">{{ isset($obj->note) ? $obj->note : old('note') }}</textarea>
                @error('note')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('customer-payment.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
