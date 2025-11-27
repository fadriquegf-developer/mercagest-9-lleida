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
            <span>{{ __('backpack.persons.check_accreditation') }}</span>
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
            <form method="post" id="formCheck" action="{{ url('/admin/person/check-accreditation') }}">
                {!! csrf_field() !!}
                @include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'create'])
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
                <div class="float-left mr-1">
                    <button type="submit" class="btn btn-outline-primary">
                        <span class="la la-check" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span>Comprovar</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="col">
            <div class="col-12" id="calendar_list"></div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <script>
        $(document).ready(function() {
            $('#formCheck').on('submit', function(event) {
                // Find all buttons of type 'submit' within this form
                var $submitButtons = $(this).find('button[type="submit"], input[type="submit"]');

                // Store original texts if you want to restore them later
                var originalTexts = {};
                $submitButtons.each(function(index) {
                    var $btn = $(this);
                    originalTexts[index] = $btn.is('button') ? $btn.text() : $btn.val();
                });

                // Disable all found submit buttons
                $submitButtons.prop('disabled', true);

            });
        });
    </script>
@endpush
