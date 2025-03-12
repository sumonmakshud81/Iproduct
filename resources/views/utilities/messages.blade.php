@if (session('success'))
    <section class="alert-wrapper">
        <div class="alert alert-success alert-dismissible show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-body">
                <p><i class="m-right fa fa-check"></i><strong>@lang('index.success')!</strong> {{ session('success') }}</p>
            </div>
        </div>
    </section>
@endif

@if (session('error'))
    <section class="alert-wrapper">
        <div class="alert alert-danger alert-dismissible show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-body">
                <i class="m-right fa fa-times"></i> <strong>@lang('index.error')!</strong> {{ session('error') }}
            </div>
        </div>
    </section>
@endif


@if (Session::has('message'))
    <div class="alert alert-{{ Session::get('type') ?? 'info' }} alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
            <p class="mb-0">
                <i class="m-right fa fa-{{ Session::get('sign') ?? 'check' }}"></i>
                {{ Session::get('message') }}
            </p>
        </div>
    </div>
@endif
