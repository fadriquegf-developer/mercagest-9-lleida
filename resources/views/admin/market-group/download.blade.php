@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid d-print-none">
        <a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')) . ' ' . $crud->entity_name !!}
                .</small>
            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i>
                        {{ trans('backpack::crud.back_to_all') }}
                        <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="card text-white bg-primary mb-2">
        <div class="card-body">
            <div>Acreditacions crear:
                <div class="text-value" id="total">-</div>
            </div>

            <div class="progress progress-white progress-xs my-2">
                <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
    <a href="{{ backpack_url("market-group/{$marketGroup->id}/download") }}" class="btn btn-success" id="download"
        style="display: none">Descarregar</a>
@endsection


@section('after_scripts')
    <script>
        $(document).ready(function() {
            let progress = $('#progress-bar');
            let total = $('#total');
            let btnDownload = $('#download');

            let intervalId = window.setInterval(function() {
                $.ajax({
                    type: 'get',
                    url: '{{ backpack_url("market-group/{$marketGroup->id}/progress") }}',
                    success: function(response) {
                        if (response.success === true) {
                            progress.attr("aria-valuenow", response.data.progress);
                            progress.css("width", response.data.progress + '%');
                            total.text((response.data.total_jobs - response.data.pending_jobs) +
                                '/' + response.data.total_jobs);

                            if (response.data.finished_at !== null) {
                                clearInterval(intervalId)
                                btnDownload.show();
                            } else {
                                btnDownload.hide();
                            }
                        }
                    },
                    error: function(e) {
                        clearInterval(intervalId)
                    }
                });
            }, 5000);
        });
    </script>
@endsection
