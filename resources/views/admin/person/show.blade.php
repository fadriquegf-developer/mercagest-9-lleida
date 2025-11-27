@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.preview') => false,
    ];

    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

$meses = [
    1 => 'Gener',
    2 => 'Febrer',
    3 => 'Març',
    4 => 'Abril',
    5 => 'Maig',
    6 => 'Juny',
    7 => 'Juliol',
    8 => 'Agost',
    9 => 'Setembre',
    10 => 'Octubre',
    11 => 'Novembre',
    12 => 'Decembre',
    ];
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
        <div class="col-md-4 text-center">
            <img src="{{ $crud->entry->imageUrl ?? asset('/images/person_default.png') }}" width="250" alt="">
        </div>
        <div class="col-md-8">
            <!-- Default box -->
            <div class="">
                @if ($crud->model->translationEnabled())
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <!-- Change translation button group -->
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ trans('backpack::crud.language') }}
                                    :
                                    {{ $crud->model->getAvailableLocales()[request()->input('_locale') ? request()->input('_locale') : App::getLocale()] }}
                                    &nbsp; <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                        <a class="dropdown-item"
                                            href="{{ url($crud->route . '/' . $entry->getKey() . '/show') }}?_locale={{ $key }}">{{ $locale }}</a>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card no-padding no-border">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach ($crud->columns() as $column)
                                <tr>
                                    <td>
                                        <strong>{!! $column['label'] !!}:</strong>
                                    </td>
                                    <td>
                                        @php
                                            // create a list of paths to column blade views
                                            // including the configured view_namespaces
                                            $columnPaths = array_map(function ($item) use ($column) {
                                                return $item . '.' . $column['type'];
                                            }, config('backpack.crud.view_namespaces.columns'));

                                            // but always fall back to the stock 'text' column
                                            // if a view doesn't exist
if (!in_array('crud::columns.text', $columnPaths)) {
    $columnPaths[] = 'crud::columns.text';
                                            }
                                        @endphp
                                        @includeFirst($columnPaths)
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <div class="col-md-12 tab-container mb-2">

            <div class="nav-tabs-custom" id="form_tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="#historical" aria-controls="tab_tab1" role="tab" tab_name="tab1" data-toggle="tab"
                            class="nav-link active">{{ __('backpack.persons.show.historicals.tab') }}</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#absences" aria-controls="tab_tab2" role="tab" tab_name="tab2" data-toggle="tab"
                            class="nav-link ">{{ __('backpack.persons.show.absences.tab') }}</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#incidences" aria-controls="tab_tab3" role="tab" tab_name="tab3" data-toggle="tab"
                            class="nav-link ">{{ __('backpack.persons.show.incidences.tab') }}</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#receipts" aria-controls="tab_tab3" role="tab" tab_name="tab3" data-toggle="tab"
                            class="nav-link ">{{ __('backpack.persons.show.receipts.tab') }}</a>
                    </li>
                    <li role="presentation" class="nav-item">
                        <a href="#legal-docs" aria-controls="tab_tab3" role="tab" tab_name="tab3" data-toggle="tab"
                            class="nav-link ">{{ __('backpack.persons.show.legal_docs.tab') }}</a>
                    </li>
                </ul>
                <div class="tab-content p-0 ">
                    <div role="tabpanel" class="tab-pane  active" id="historical">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.persons.show.historicals.table.stall') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.persons.show.historicals.table.market') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.persons.show.historicals.table.start') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.end_vigencia') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.persons.show.historicals.table.end') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.reason') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.family_transfer') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.explained_reason') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($crud->entry->historicals->count())
                                        @php
                                            // order show actives first
                                            $stalls = $crud->entry->historicals->sortByDesc(function ($stall, $key) {
                                                return $stall->pivot->ends_at ?: $stall->pivot->end_vigencia ?: now();
                                            });
                                        @endphp
                                        @foreach ($stalls as $stall)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('stall.show', $stall->id) }}">
                                                        {{ $stall->num }}
                                                    </a>
                                                </td>
                                                <td>{{ $stall->market ? $stall->market->name : '' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($stall->pivot->start_at)) }}</td>
                                                <td
                                                    class="{{ !is_null($stall->pivot->end_vigencia) && $stall->pivot->end_vigencia < now() ? 'text-danger' : '' }}">
                                                    {{ !is_null($stall->pivot->end_vigencia) ? date('d-m-Y', strtotime($stall->pivot->end_vigencia)) : '-' }}
                                                </td>
                                                <td class="{{ $stall->pivot->ends_at ? 'text-danger' : '' }}">
                                                    {{ !is_null($stall->pivot->ends_at) ? date('d-m-Y', strtotime($stall->pivot->ends_at)) : '-' }}
                                                </td>
                                                <td>{{ \App\Models\Reason::where('slug', $stall->pivot->reason)->first()->title ?? '' }}
                                                </td>
                                                <td>{{ __('backpack.historicals.family_transfer_options')[$stall->pivot->family_transfer] ?? '' }}
                                                </td>
                                                <td>{{ $stall->pivot->explained_reason ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="absences">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.persons.show.absences.table.type') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.absences.table.start') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.absences.table.end') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.absences.table.stall') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.absences.table.cause') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.absences.table.document') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($crud->entry->absences->count())
                                        @foreach ($crud->entry->absences as $absence)
                                            <tr>
                                                <td>{{ __('backpack.absences.types.' . $absence->type) }}</td>
                                                <td>{{ $absence->start->format('d-m-Y') }}</td>
                                                <td>{{ $absence->end->format('d-m-Y') }}</td>
                                                <td>
                                                    @php
                                                        $stall = $absence->stall;
                                                    @endphp
                                                    @if ($stall)
                                                        <a href="{{ route('stall.show', $stall->id) }}">
                                                            {{ $stall->num }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $absence->cause }}</td>
                                                <td>
                                                    @if ($absence->document)
                                                        <a href="{{ $absence->getDocumentUrl() }}"
                                                            target="_blank">{{ __('backpack.absences.show_doc') }}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="incidences">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.stalls.show.incidences.table.date') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.incidences.table.title') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.incidences.table.type') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.incidences.table.status') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.incidences.table.market') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($crud->entry->incidences->count())
                                        @foreach ($crud->entry->incidences as $incidence)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($incidence->date_incidence)) }}</td>
                                                <td>
                                                    <a href="{{ route('incidences.show', $incidence->id) }}">
                                                        {{ $incidence->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $incidence->transType() }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="alert py-1 {{ $entry->status == 'solved' ? 'alert-success' : 'alert-warning' }}">
                                                        {{ __('backpack.incidences.statuses.' . $incidence->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $incidence->stall && $incidence->stall->market ? $incidence->stall->market->name : '' }}
                                                </td>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="receipts">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.persons.show.receipts.table.id') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.invoices.type') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.invoices.month') . '/' . __('backpack.invoices.trimestral') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.invoices.years') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.market_group_id') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.persons.show.receipts.table.total') }}
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($crud->entry->invoices->count())
                                        @foreach ($crud->entry->invoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('invoice.show', $invoice->id) }}">
                                                        {{ $invoice->id }}
                                                    </a>
                                                </td>
                                                <td>{{ ucfirst($invoice->type) }}</td>
                                                <td>
                                                    @if ($invoice->month)
                                                        {{ $meses[$invoice->month] }}
                                                    @else
                                                        {{ 'Trimestre ' . $entry->trimestral }}
                                                    @endif
                                                </td>
                                                <td>{{ $invoice->years }}</td>
                                                <td>{{ $invoice->market_group->title ?? '' }}</td>
                                                <td class="text-success">{{ $invoice->total }}€</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="legal-docs">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.persons.show.legal_docs.table.type') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.persons.show.legal_docs.table.date') }}
                                        </th>
                                        <th>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @php
                                            $showLegalDoc1 = $crud->entry->legal_doc1_signature_date;
                                            $createdBy = $crud->entry->getUserName('legal_doc1_user');
                                        @endphp
                                        <td>
                                            {{ __('backpack.persons.legal_doc1') }}
                                        </td>
                                        <td>{{ $showLegalDoc1 ? date('d-m-Y', strtotime($crud->entry->legal_doc1_signature_date)) : '-' }}
                                            @if ($createdBy != '-')
                                                ({{ $crud->entry->getUserName('legal_doc1_user') }})
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ backpack_url("person/{$crud->entry->id}/legal-doc/1") }}"
                                                class="btn btn-sm btn-link"
                                                @if ($showLegalDoc1) target="_blank" @endif>
                                                <i
                                                    class="la la-file-signature"></i>{{ $showLegalDoc1 ? __('backpack.persons.show.legal_docs.table.show') : __('backpack.persons.show.legal_docs.table.to_sign') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        @php
                                            $showLegalDoc2 = $crud->entry->legal_doc2_signature_date;
                                            $createdBy = $crud->entry->getUserName('legal_doc2_user');
                                        @endphp
                                        <td>
                                            {{ __('backpack.persons.legal_doc2') }}
                                        </td>
                                        <td>{{ $showLegalDoc2 ? date('d-m-Y', strtotime($crud->entry->legal_doc2_signature_date)) : '-' }}
                                            @if ($createdBy != '-')
                                                ({{ $crud->entry->getUserName('legal_doc2_user') }})
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ backpack_url("person/{$crud->entry->id}/legal-doc/2") }}"
                                                class="btn btn-sm btn-link"
                                                @if ($showLegalDoc2) target="_blank" @endif>
                                                <i
                                                    class="la la-file-signature"></i>{{ $showLegalDoc2 ? __('backpack.persons.show.legal_docs.table.show') : __('backpack.persons.show.legal_docs.table.to_sign') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @foreach ($crud->entry->docs as $doc)
                                        <tr>
                                            <td>
                                                {{ $doc->type }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($doc->created_at)) }}
                                            </td>
                                            <td>
                                                @if ($doc->doc)
                                                    <a href="{{ backpack_url('storage/' . $doc->doc) }}"
                                                        class="btn btn-sm btn-link" target="_blank">
                                                        <i class="la la-file-signature"></i>
                                                        {{ __('backpack.persons.show.legal_docs.table.show') }}
                                                    </a>
                                                @else
                                                    -
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
