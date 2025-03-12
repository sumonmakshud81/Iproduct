<div>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly placeholder="Date"
                    value="{{ isset($obj->date) && $obj->date ? $obj->date : old('date') }}">

                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <input type="hidden" name="supplier" id="supplier_id" value="{{ isset($obj->supplier) ? $obj->supplier : '' }}">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.supplier') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select tabindex="2" class="form-control @error('supplier') is-invalid @enderror select2"
                            id="supplier">
                            <option value="">@lang('index.select_supplier')</option>
                            @foreach ($suppliers as $value)
                                <option {{ isset($obj->supplier) && $obj->supplier == $value->id ? 'selected' : '' }}
                                    value="{{ $value->id }}|{{ getSupplierDue($value->id) }}">
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.amount') <span class="required_star">*</span></label>
                <input type="text" name="amount" id="amount"
                    class="form-control @error('amount') is-invalid @enderror" placeholder="Amount"
                    value="{{ isset($obj->amount) && $obj->amount ? $obj->amount : old('amount') }}">

                <div class="alert alert-primary p-1 due_balance_show d-none" role="alert">
                    
                </div>
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.account') <span class="required_star">*</span></label>
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
                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note"
                    rows="3">{{ isset($obj->note) && $obj->note ? $obj->note : old('note') }}</textarea>
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
            <a class="btn bg-second-btn" href="{{ route('supplier-payment.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
