@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.add') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Generació de Rebuts</span>
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="{{ $crud->getCreateContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="post" action="{{ route('index-invoice-gtt') }}">
                {!! csrf_field() !!}
                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                @if (view()->exists('vendor.backpack.crud.form_content'))
                    @include('vendor.backpack.crud.form_content', [
                        'fields' => $crud->fields(),
                        'action' => 'create',
                    ])
                @else
                    @include('crud::form_content', ['fields' => $crud->fields(), 'action' => 'create'])
                @endif
                <!-- This makes sure that all field assets are loaded. -->
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
                <button type="submit" class="btn btn-success">
                    <span class="la la-ticket-alt" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span>Generar Informe</span>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('after_scripts')
    <script>
        //Para cambiar al selector dependiendo de si el Grupo de mercado es por Trimestre o por Mensualidad
        crud.field('marketgroup_id').onChange(function(field) {
            label = $("select[name='marketgroup_id'] option:selected").text();
            if (label.includes('Mensual')) {
                crud.field('month').show();
                crud.field('month').enable();
                crud.field('trimestral').hide();
                crud.field('trimestral').disable();
            } else {
                crud.field('month').hide();
                crud.field('month').disable();
                crud.field('trimestral').show();
                crud.field('trimestral').enable();
            }
        }).change();

        crud.field('especial_edition').onChange(function(field) {
            crud.field('liquidation_days').show(field.value == 1);
        }).change();
    </script>
@endpush
