@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      __('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      __('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span>{{ __('backpack.checklists.hint') }}</span>
            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                        {{ __('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span>
                    </a>
                </small>
            @endif
        </h2>
    </div>
@endsection

@section('content')
    <div class="row">
        @foreach ($checklists as $checklist)
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $checklist->name }}</h5>
                        <a href="{{ url($crud->route . '/' . $type . '/' . $checklist->id . '/create') }}"
                            class="btn {{ $type == 'stall' ? 'btn-primary' : 'btn-secondary' }}">{{ __('backpack.maps.select') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
