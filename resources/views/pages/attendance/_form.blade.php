<div>
    <div class="row">
        <div class="col-sm-12 mb-2 col-md-4">
            <div class="form-group">
                <label>@lang('index.reference_no') <span class="required_star">*</span></label>

                <input type="text" name="reference_no" id="code"
                    class="check_required form-control @error('reference_no') is-invalid @enderror"
                    placeholder="{{ __('index.reference_no') }}"
                    value="{{ isset($obj->reference_no) ? $obj->reference_no : $ref_no }}" onfocus="select()">

                @error('reference_no')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.date') <span class="required_star">*</span></label>
                <input type="text" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror customDatepicker" readonly
                    placeholder="{{ __('index.date') }}" value="{{ isset($obj->date) ? $obj->date : old('date') }}">

                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
		@if(!isset($obj))
		<div class="col-sm-12 col-md-6 mb-2 col-lg-4 mt-2">
		<div class="form-check mt-3">
			  <input class="form-check-input" type="checkbox" name="single" value="1" id="single">
			  <label class="form-check-label" for="single">
				@lang('index.single_employee_attendance')
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" name="all" value="1" id="all">
			  <label class="form-check-label" for="all">
				@lang('index.all_employee_attendance')
			  </label>
			</div>
		</div>
		@endif
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4 employee_sec @if(!isset($obj)) d-none @endif">			
            <div class="form-group">
                <label>@lang('index.employee') <span class="required_star">*</span></label>
                <div class="d-flex align-items-center">
                    <div class="w-100">
                        <select class="form-control @error('employee_id') is-invalid @enderror select2" id="employee_id"
                            name="employee_id">
                            <option value="">@lang('index.select_employee')</option>
                            @foreach ($employees as $value)
                                <option
                                    {{ isset($obj->employee_id) && $obj->employee_id == $value->id ? 'selected' : '' }}
                                    value="{{ $value->id }}">
                                    {{ $value->name . '-' . $value->designation . '(' . $value->phone_number . ')' }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4" id="in_time_container">
            <div class="form-group">
                <label>@lang('index.in_time') <span class="required_star">*</span></label>
                <input type="text" name="in_time" id="in_time"
                    class="form-control @error('in_time') is-invalid @enderror" placeholder="{{ __('index.in_time') }}" value="{{ isset($obj->in_time) ? $obj->in_time : old('in_time') }}">
                @error('in_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-2 col-lg-4" id="in_time_container">
            <div class="form-group">
                <label>@lang('index.out_time')</label>
                <input type="text" name="out_time" id="out_time"
                    class="form-control @error('out_time') is-invalid @enderror" placeholder="{{ __('index.out_time') }}" value="{{ isset($obj->out_time) ? $obj->out_time : old('out_time') }}">

                @error('out_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="col-sm-12 col-md-6 mb-2 col-lg-4" id="in_time_container">
            <div class="form-group">
                <label>@lang('index.note')</label>
                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                    placeholder="{{ __('index.note') }}" rows="3">{{ isset($obj->note) ? $obj->note : old('note') }}</textarea>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <input type="hidden" name="in_or_out" value="">

    <div class="row mt-2">
        <div class="col-sm-12 col-md-6 mb-2 d-flex gap-3">
            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><iconify-icon
                    icon="solar:check-circle-broken"></iconify-icon>@lang('index.submit')</button>
            <a class="btn bg-second-btn" href="{{ route('attendance.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
