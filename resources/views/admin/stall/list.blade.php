@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.list') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small id="datatable_info_stack">{!! $crud->getSubheading() ?? '' !!}</small>
        </h2>
    </div>
@endsection

@section('content')
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-0">
                <div class="col-sm-6">
                    @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
                        <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">

                            @include('crud::inc.button_stack', ['stack' => 'top'])

                        </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
                </div>
            </div>

            {{-- Backpack List Filters --}}
            @if ($crud->filtersEnabled())
                @include('crud::inc.filters_navbar')
            @endif


            <table
                id="crudTable"
                class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2"
                data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
          data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
          data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
          data-has-line-buttons-as-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdown') }}"
                cellspacing="0">
                <thead>
                <tr>
                    {{-- Table columns --}}
                    @foreach ($crud->columns() as $column)
                        <th
                            data-orderable="{{ var_export($column['orderable'], true) }}"
                            data-priority="{{ $column['priority'] }}"
                            data-column-name="{{ $column['name'] }}"
                            {{--
                            data-visible-in-table => if developer forced field in table with 'visibleInTable => true'
                            data-visible => regular visibility of the field
                            data-can-be-visible-in-table => prevents the column to be loaded into the table (export-only)
                            data-visible-in-modal => if column apears on responsive modal
                            data-visible-in-export => if this field is exportable
                            data-force-export => force export even if field are hidden
                            --}}

                            {{-- If it is an export field only, we are done. --}}
                            @if(isset($column['exportOnlyField']) && $column['exportOnlyField'] === true)
                            data-visible="false"
                            data-visible-in-table="false"
                            data-can-be-visible-in-table="false"
                            data-visible-in-modal="false"
                            data-visible-in-export="true"
                            data-force-export="true"
                            @else
                            data-visible-in-table="{{var_export($column['visibleInTable'] ?? false)}}"
                            data-visible="{{var_export($column['visibleInTable'] ?? true)}}"
                            data-can-be-visible-in-table="true"
                            data-visible-in-modal="{{var_export($column['visibleInModal'] ?? true)}}"
                            @if(isset($column['visibleInExport']))
                            @if($column['visibleInExport'] === false)
                            data-visible-in-export="false"
                            data-force-export="false"
                            @else
                            data-visible-in-export="true"
                            data-force-export="true"
                            @endif
                            @else
                            data-visible-in-export="true"
                            data-force-export="false"
                            @endif
                            @endif
                        >
                            {{-- Bulk checkbox --}}
                            @if($loop->first && $crud->getOperationSetting('bulkActions'))
                                {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                            @endif
                            {!! $column['label'] !!}
                        </th>
                    @endforeach

                    @if ( $crud->buttons()->where('stack', 'line')->count() )
                        <th data-orderable="false"
                            data-priority="{{ $crud->getActionsColumnPriority() }}"
                            data-visible-in-export="false"
                            data-action-column="true"
                        >{{ trans('backpack::crud.actions') }}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    {{-- Table columns --}}
                    @foreach ($crud->columns() as $column)
                        <th>
                            {{-- Bulk checkbox --}}
                            @if($loop->first && $crud->getOperationSetting('bulkActions'))
                                {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                            @endif
                            {!! $column['label'] !!}
                        </th>
                    @endforeach

                    @if ( $crud->buttons()->where('stack', 'line')->count() )
                        <th>{{ trans('backpack::crud.actions') }}</th>
                    @endif
                </tr>
                </tfoot>
            </table>

            @if ( $crud->buttons()->where('stack', 'bottom')->count() )
                <div id="bottom_buttons" class="d-print-none text-center text-sm-left">
                    @include('crud::inc.button_stack', ['stack' => 'bottom'])

                    <div id="datatable_button_stack" class="float-right text-right hidden-xs"></div>
                </div>
            @endif

        </div>

    </div>

@endsection

@section('after_styles')
    <!-- DATA TABLES -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

    @loadOnce('packages/select2/dist/css/select2.min.css')
    @loadOnce('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')
    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection

@section('after_scripts')
    @include('crud::inc.datatables_logic')

    @loadOnce('packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
        @loadOnce('packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @loadOnce('bpFieldInitSelect2Element')
    <script>
        function bpFieldInitSelect2Element(element) {
            // element will be a jQuery wrapped DOM node
            if (!element.hasClass("select2-hidden-accessible")) 
            {
                let $isFieldInline = element.data('field-is-inline');

                element.select2({
                    theme: "bootstrap",
                    dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : $(document.body)
                });
            }
        }
    </script>
    @endLoadOnce

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')

    <script>
        $(document).ready(function () {
            const modalSubscribe = $('#modal_alta');
            const alertSubscribe = $('#subscribe-alert');
            const modalUnsubscribe = $('#modal_info');
            const alertUnsubscribe = $('#unsubscribe-alert');

            $(document).on('click', '.unsubscribe', function () {
                alertUnsubscribe.hide();
                let id = $(this).attr('attr-id')
                $('#modal_info form').attr('action', `/admin/stall/unsubscribe/${id}`);
                modalUnsubscribe.modal('show');
            });
            $("#form-unsubscribe").submit(function(e) {
                e.preventDefault();
                var form = $(this);
               
                ajaxForm(form, modalUnsubscribe, alertUnsubscribe);
            });

            $(document).on('click', '.subscribe', function () {
                alertSubscribe.hide();
                let id = $(this).attr('attr-id')
                $('#modal_alta form').attr('action', `/admin/stall/subscribe/${id}`);
                modalSubscribe.modal('show');
            });
            $("#form-subscribe").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var form = $(this);

                ajaxForm(form, modalSubscribe, alertSubscribe);
            });

            $('.select2').each(function(index, element) {
                $(this).select2({
                    theme: "bootstrap",
                    dropdownParent: $("#modal_alta")
                });
            });

            function ajaxForm(form, model, alert){
                var actionUrl = form.attr('action');
                
                alert.hide();

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        model.modal('hide');
                        new Noty({
                            type: "success",
                            text: data.message
                        }).show();
                        crud.table.ajax.reload();
                    },
                    error: function(e)
                    {
                        alert.show();
                        alert.text(e.responseJSON.error);
                    }
                });
            }
        });
    </script>
@endsection

<div class="modal fade" id="modal_info" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('backpack.stalls.list.modal.header') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="form-unsubscribe">
                @csrf
                <div class="modal-body">
                    <div id="unsubscribe-alert" class="alert alert-danger" style="display: none" role="alert"></div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{ __('backpack.stalls.reason') }}</label>
                        <select name="reason" class="form-control" required>
                            <option value="" selected="selected" disabled="disabled">{{ trans('backpack.historicals.family_transfer_default') }}</option>
                            @foreach(\App\Models\Reason::get()->sortBy('title') as $reason)
                                <option value="{{ $reason->slug }}">{{ $reason->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="col-form-label">{{ __('backpack.stalls.explained_reason') }}</label>
                        <input type="text" class="form-control" name="explained_reason" required>
                    </div>
                    <div class="form-group">
                        <label for="ends_at">{{ __('backpack.historicals.ends_at') }}</label>
                        <input type="date" name="ends_at" id="ends_at" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('backpack.stalls.list.modal.footer.close') }}</button>
                    <button type="submit"
                            class="btn btn-primary">{{ __('backpack.stalls.list.modal.footer.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_alta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('backpack.stalls.list.modal.header-alta') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="form-subscribe">
                @csrf
                <div class="modal-body">
                    <div id="subscribe-alert" class="alert alert-danger" style="display: none" role="alert"></div>
                    <div class="form-group">
                        <label for="person_id" class="col-form-label">{{ __('backpack.historicals.person_id') }}</label>
                        <select name="person_id" class="form-control select2" required>
                            @foreach(\App\Models\Person::active()->get() as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="family_transfer" class="col-form-label">{{ __('backpack.historicals.family_transfer') }}</label>
                        <select name="family_transfer" class="form-control">
                            <option value="" selected="selected">{{ trans('backpack.historicals.family_transfer_default') }}</option>
                            @foreach(__('backpack.historicals.family_transfer_options') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_at">{{ __('backpack.historicals.start_at') }}</label>
                        <input type="date" name="start_at" id="start_at" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_vigencia">{{ __('backpack.historicals.end_vigencia') }}</label>
                        <input type="date" name="end_vigencia" id="end_vigencia" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('backpack.stalls.list.modal.footer.close') }}</button>
                    <button type="submit"
                            class="btn btn-primary">{{ __('backpack.stalls.list.modal.footer.submit-alta') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

