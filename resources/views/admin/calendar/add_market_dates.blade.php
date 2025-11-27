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
    <div class="row">
        <div class="{{ $crud->getCreateContentClass() }}">
            <!-- Default box -->

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post" action="{{ url('/admin/market/' . $market_id . '/calendar/create-range') }}">
                {!! csrf_field() !!}
                @include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'create'])
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
                <div class="float-left mr-1">
                    <a class="btn btn-outline-primary" style="cursor: pointer;" id="check-button"><span
                            class="la la-check"></span> Comprovar Dates Generades</a>
                </div>
                @include('crud::inc.form_save_buttons')
            </form>
        </div>
        <div class="col">
            <div class="col-12" id="calendar_list"></div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        let input_excluded_dates = $('input[name="excluded_dates"]');
        input_excluded_dates.val('');
        $('button[type="submit"]').attr('disabled', 'disabled');
        $('#btnGroupDrop1').attr('disabled', 'disabled');

        $(document).on('click', 'a#check-button', function(e) {
            let start = $('input[name="start"]').val();
            let end = $('input[name="end"]').val();
            let market_id = {{ $market_id }};
            $('small.text-danger').remove();
            if (start && end) {
                $.ajax({
                    type: 'POST',
                    url: `/admin/market/{{ $market_id }}/calendar/check`,
                    data: {
                        date_start: start,
                        date_end: end,
                        market_id: market_id
                    },
                    success: function(result, status, xhr) {
                        if (result.status == 'ok') {
                            $('.date-entry').remove();
                            input_excluded_dates.val('');
                            result.dates.forEach(function(entry) {
                                console.log(entry);
                                $('#calendar_list').append(
                                    '<div class="alert alert-success date-entry">Es generara data per ' +
                                    entry.date +
                                    '<button class="btn btn-danger btn-sm float-right btn-remove-date" data-date="' +
                                    entry.date +
                                    '"><i class="las la-trash"></i></button></div>'
                                );
                            });

                            $('.btn-remove-date').click(function() {
                                $(this).parent().hide();
                                let date = $(this).data('date');
                                input_excluded_dates.val(function(i, val) {
                                    return val + (val ? ',' : '') + date;
                                });
                            });

                            $('button[type="submit"]').removeAttr('disabled');
                            $('#btnGroupDrop1').removeAttr('disabled');
                        }
                    }
                });
            }

        })
    });
</script>
