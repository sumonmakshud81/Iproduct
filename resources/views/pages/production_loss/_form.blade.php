<div>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly
                    placeholder="Date" value="{{ old('date') }}">
                <div class="text-danger date_error"></div>
            </div>
        </div>
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.responsible_person') <span class="required_star">*</span></label>
                <select name="responsible_person" id="res_person"
                    class="form-control @error('responsible_person') is-invalid @enderror select2">
                    <option value="">@lang('index.select')</option>
                    @foreach ($users as $key => $value)
                        <option value="{{ $value->id }}"
                            {{ isset($obj->responsible_person) && $obj->responsible_person || old('responsible_person') == $value->id ? 'selected' : '' }}>
                            {{ $value->name }} ({{ $value->phone_number }})</option>
                    @endforeach
                </select>
            </div>
            <div class="text-danger res_person_error"></div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.manufacture') <span class="required_star">*</span></label>
                <select class="form-control @error('manufacture') is-invalid @enderror select2" id="manufacture"
                    name="manufacture">
                    <option value="">@lang('index.select')</option>
                    @foreach ($manufactures as $value)
                        <?php
                        $finished_product_old = getFinishedProductInfo($value->product_id);
                        ?>
                        <option value="{{ $value->id }}">
                            {{ $finished_product_old->name }}({{ $value->reference_no }})
                        </option>
                    @endforeach
                </select>
                <div class="text-danger manufacture_error"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="mt-2">@lang('index.product')</h4>
            <div class="table-responsive" id="product_table">
                <table class="table">
                    <thead>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.product')(@lang('index.code'))</th>
                        <th class="w-20 text-start">@lang('index.manufacture_qty')</th>
                        <th class="w-20 text-start">@lang('index.loss_qty')<span class="required_star">*</span></th>
                        <th class="w-20 text-start">@lang('index.loss_amount')<span class="required_star">*</span>
                        </th>
                        <th class="w-5 ir_txt_center">@lang('index.actions')</th>
                    </thead>
                    <tbody class="product_body">

                    </tbody>
                </table>
            </div>
            <h4 class="mt-2">@lang('index.raw_material')</h4>
            <div class="table-responsive" id="rm_table">
                <table class="table">
                    <thead>
                        <th class="w-5 text-start">@lang('index.sn')</th>
                        <th class="w-30 text-start">@lang('index.raw_materials')(@lang('index.code'))</th>
                        <th class="w-20 text-start">@lang('index.manufacture_qty')</th>
                        <th class="w-20 text-start">@lang('index.loss_qty')<span class="required_star">*</span></th>
                        <th class="w-20 text-start">@lang('index.loss_amount')<span class="required_star">*</span>
                        </th>
                        <th class="w-5 ir_txt_center">@lang('index.actions')</th>
                    </thead>
                    <tbody class="rm_body">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div><br>

        <div class="row">
            <div class="col-md-4">
                <label>@lang('index.note')</label>
                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" placeholder="Note"
                    rows="3"></textarea>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-3">
                <label>@lang('index.total_loss') <span class="required_star">*</span></label>
                <div class="input-group">
                    <input type="text" name="grand_total" id="grand_total"
                        class="form-control @error('grand_total') is-invalid @enderror" readonly placeholder="G.Total">
                    <span class="input-group-text">
                        {{ $setting->currency }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('production-loss-report') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
