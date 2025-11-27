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
            <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}
                .</small>
            @if ($crud->hasAccess('list'))
                <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i
                            class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }}
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

            <form method="post" action="{{ url($crud->route) }}">
            {!! csrf_field() !!}
                @include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>


                <div class="btn-group" role="group">
                    <button id="check" class="btn btn-info">
                        <span class="la la-check" role="presentation" aria-hidden="true"></span>
                        <span data-value="save_and_back">{{ __('backpack.communications.check') }}</span>
                    </button>
                    <button id="submit" type="submit" class="btn btn-success ml-2 d-none">
                        <span class="la la-save" role="presentation" aria-hidden="true"></span>
                        <span data-value="save_and_back">{{ __('backpack.communications.save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        $(document).on('click', '#check', function (e){
            e.preventDefault();

            $('#submit').addClass('d-none');
            $.ajax(
                {
                    type: 'POST',
                    url: `/admin/communication/check`,
                    data: $("form").serialize(),
                    success: function (result, status, xhr) {
                        if (result.status == 'Success'){
                            $('#submit').removeClass('d-none');
                            new Noty({
                                type: "success",
                                text: `S'enviarà a ${result.group.length} persona/es`,
                            }).show();
                        }
                        if (result.status == 'Error'){
                            new Noty({
                                type: "error",
                                text: `${result.message}`,
                            }).show();
                        }
                    }
                });
        })
    });
</script>
