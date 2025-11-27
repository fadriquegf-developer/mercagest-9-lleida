@extends(backpack_view('blank'))

@php
$breadcrumbs = '\\';
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize"></span>
        </h2>
    </div>
@endsection

@section('content')
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">
            <div class="row mb-0">
                <div class="card col-sm-12">
                    <div class="card-header">
                        <div class="form-inline">
                            <label class="mr-1"
                                for="date">{{ __('backpack.report.daily_report.select') }}</label>
                            <input id="date" type="date" class="form-control" value="{{ $today }}">
                            <div class="ml-sm-auto">
                                <a href="{{ backpack_url('day-report/' . $today . '/export') }}" class="btn btn-secondary"
                                    data-style="zoom-in">
                                    <span class="ladda-label"><i class="la la-download"></i>
                                        {{ trans('backpack::crud.export.export') }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card col-sm-12">
                    <div class="card-header">
                        <div class="h4 m-0"> {{ __('backpack.report.daily_report.presence') }}</div>
                    </div>
                    <div class="card-body">
                        
                        <table id="crudTable"
                            class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2">
                            <thead>
                                <tr>
                                    <th>
                                        {{ __('backpack.report.daily_report.table.person_name') }}
                                    </th>
                                    <th>
                                        {{ __('backpack.report.daily_report.table.stall') }}
                                    </th>
                                    <th>
                                        {{ __('backpack.report.daily_report.table.market') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($stalls->count())
                                    @foreach ($stalls as $stall)
                                        <tr>
                                            <td>
                                                @if($stall->titular_id)
                                                <a href="{{ route('person.show', $stall->titular_id) }}">
                                                    {{ $stall->titular }}
                                                </a>
                                                @else 
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('stall.show', $stall->id) }}">
                                                    {{ $stall->num_market }}
                                                </a>
                                            </td>
                                            <td>{{ $stall->market->name }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card col-sm-12">
                    <div class="card-header">
                        <div class="h4 m-0">{{ __('backpack.persons.show.incidences.tab') }}</div class="h4 m-0">
                    </div>
                    <div class="card-body">
           
                        <table id="incidences"
                            class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2">
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
                                @if ($incidences->count())
                                    @foreach ($incidences as $incidence)
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
                                                    class="alert py-1 {{ $incidence->status == 'solved' ? 'alert-success' : 'alert-warning' }}">
                                                    {{ __('backpack.incidences.statuses.' . $incidence->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $incidence->stall && $incidence->stall->market ? $incidence->stall->market->name : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card col-sm-12">
                    <div class="card-header">
                        <div class="h4 m-0">{{ __('backpack.persons.show.absences.tab') }}</div>
                    </div>
                    <div class="card-body">
                        <table id="absences"
                            class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2">
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
                                @if ($absences->count())
                                    @foreach ($absences as $absence)
                                        <tr>
                                            <td>{{ __('backpack.absences.types.' . $absence->type) }}</td>
                                            <td>{{ $absence->start->format('d-m-Y') }}</td>
                                            <td>{{ $absence->end->format('d-m-Y') }}</td>
                                            <td>
                                                <a href="{{ route('stall.show', $absence->stall->id) }}">
                                                    {{ $absence->stall->num_market }}
                                                </a>
                                            </td>
                                            <td>{{ $absence->cause }}</td>
                                            <td>
                                                @if ($absence->document)
                                                    <a href="{{ $absence->getDocumentUrl() }}"
                                                        target="_blank">Document</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    @stack('crud_list_styles')
@endsection


@section('after_scripts')
    <script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('packages/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('packages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('packages/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('packages/datatables.net-fixedheader-bs4/js/fixedHeader.bootstrap4.min.js') }}"></script>

    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    @stack('crud_list_scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            $('.table').dataTable({
                "language": {
                    "sProcessing": "Processant...",
                    "sLengthMenu": "Mostrar _MENU_ registres",
                    "sZeroRecords": "No s'han trobat resultats",
                    "sEmptyTable": "Cap dada disponible en aquesta taula",
                    "sInfo": "Mostrant registres del _START_ al _END_ d'un total de _TOTAL_ registres",
                    "sInfoEmpty": "Mostrant registres del 0 al 0 d'un total de 0 registres",
                    "sInfoFiltered": "(filtrat d'un total de _MAX_ registres)",
                    "sInfoPostFix": "",
                    "sSearch": "Cercar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Carregant...",
                    "oPaginate": {
                        "sFirst": "Primer",
                        "sLast": "Darrer",
                        "sNext": "Següent",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar per ordenar la columna de manera ascendent",
                        "sSortDescending": ": Activar per ordenar la columna de manera descendent"
                    }
                }
            });

            $(document).on('change', '#date', function(e) {
                let date = $("#date").val();
                if (date) {
                    location.href = `/admin/day-report/${date}`;
                }
            });
        });
    </script>
@endsection
