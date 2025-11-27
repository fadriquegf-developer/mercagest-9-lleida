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
        <div class="col-md-4">
            <img src="{{ asset($crud->entry->image) }}" class="img-fluid" alt="">
        </div>

        <div class="col-md-12 tab-container mb-2">
            <div class="nav-tabs-custom" id="form_tabs">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach (__('backpack.stalls.show') as $k => $v)
                        <li role="presentation" class="nav-item">
                            <a href="#{{ __('backpack.stalls.show.' . $k . '.tab') }}"
                                id="tab_{{ __('backpack.stalls.show.' . $k . '.tab') }}" role="tab" data-toggle="tab"
                                aria-controls="{{ __('backpack.stalls.show.' . $k . '.tab') }}"
                                class="nav-link @if ($k == 'historicals') active @endif">{{ __('backpack.stalls.show.' . $k . '.tab') }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content p-0 ">
                    <div role="tabpanel" class="tab-pane  active" id="{{ __('backpack.stalls.show.historicals.tab') }}">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.person') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.market') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.start') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.end_vigencia') }}
                                        </th>
                                        <th>
                                            {{ __('backpack.stalls.show.historicals.table.end') }}
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
                                    @php 
                                        // order show actives first
                                        $persons = $crud->entry->historicals->sortByDesc(function ($person, $key) {
                                            return $person->pivot->ends_at ?: $person->pivot->end_vigencia ?: now();
                                        });
                                    @endphp
                                    @forelse($persons as $person)
                                        <tr>
                                            <td>
                                                <a href="{{ route('person.show', $person->id) }}">
                                                    {{ $person->name }}
                                                </a>
                                            </td>
                                            <td>{{ $crud->entry->market ? $crud->entry->market->name : '' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($person->pivot->start_at)) }}</td>
                                            <td
                                                class="{{ !is_null($person->pivot->end_vigencia) && $person->pivot->end_vigencia < now() ? 'text-danger' : '' }}">
                                                {{ !is_null($person->pivot->end_vigencia) ? date('d-m-Y', strtotime($person->pivot->end_vigencia)) : '-' }}
                                            </td>
                                            <td class="{{ $person->pivot->ends_at ? 'text-danger' : '' }}">
                                                {{ !is_null($person->pivot->ends_at) ? date('d-m-Y', strtotime($person->pivot->ends_at)) : '-' }}
                                            </td>
                                            <td>{{ \App\Models\Reason::where('slug', $person->pivot->reason)->first()->title ?? '' }}</td>
                                            <td>{{ __('backpack.historicals.family_transfer_options')[$person->pivot->family_transfer] ?? '' }}
                                            </td>
                                            <td>{{ $person->pivot->explained_reason ?? '' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="{{ __('backpack.stalls.show.absences.tab') }}">
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
                                            {{ __('backpack.stalls.show.absences.table.person') }}
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
                                    @forelse($crud->entry->absences as $absence)
                                        <tr>
                                            <td>{{ __('backpack.absences.types.' . $absence->type) }}</td>
                                            <td>{{ $absence->start->format('d-m-Y') }}</td>
                                            <td>{{ $absence->end->format('d-m-Y') }}</td>
                                            <td>
                                                @php 
                                                    $stall = $absence->stall;
                                                @endphp
                                                @if($stall)
                                                <a href="{{ route('stall.show', $stall->id) }}">
                                                    {{ $stall->num }}
                                                </a>
                                                @endif
                                            </td>
                                            <td>{{ $absence->cause }}</td>
                                            <td>
                                                @if ($absence->document)
                                                    <a href="{{ $absence->getDocumentUrl() }}"
                                                        target="_blank">Document</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="{{ __('backpack.stalls.show.incidences.tab') }}">
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
                                    @forelse($crud->entry->incidences as $incidence)
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"> {{ __('backpack.table.not_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div role="tabpanel" class="tab-pane " id="{{ __('backpack.stalls.show.checklists.tab') }}">
                        <div class="row">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>
                                        {{ __('backpack.stalls.show.checklists.table.name') }}
                                    </th>
                                    <th>
                                        {{ __('backpack.stalls.show.checklists.table.pdf') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($crud->entry->checklists->count())
                                    @foreach ($crud->entry->checklists as $checklist)
                                        <tr>
                                            <td>{{ $checklist->checklist_type->name }}</td>
                                            <td>
                                                <a href="{{ route('checklist.show', $checklist->id) }}" target="_blank">
                                                    {{ __('backpack.stalls.show.checklists.table.show') }}
                                                </a>
                                            </td>
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
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <script>
        $(document).ready(function() {
            @isset(request()->tab)
                $('#tab_{{ request()->tab }}').click();
            @endisset
        });
    </script>
@endpush
