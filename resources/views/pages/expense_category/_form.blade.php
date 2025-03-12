<div>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
            <div class="form-group">
                <label>@lang('index.name') <span class="required_star">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Name" value="{{ isset($obj) && $obj->name ? $obj->name : old('name') }}">

                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-2 col-lg-4" id="in_time_container">
            <div class="form-group">
                <label>@lang('index.description')</label>
                <input type="text" name="description" id="description"
                    class="form-control @error('description') is-invalid @enderror" placeholder="Description" value="{{ isset($obj) && $obj->description ? $obj->description : old('description') }}">

                @error('description')
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
            <a class="btn bg-second-btn" href="{{ route('expense-category.index') }}"><iconify-icon
                    icon="solar:round-arrow-left-broken"></iconify-icon>@lang('index.back')</a>
        </div>
    </div>
</div>
