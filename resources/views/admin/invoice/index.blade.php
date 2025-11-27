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
                        <div class="d-flex align-content-between">
                            <div>
                                Seleccionar any
                            </div>
                            <div class="ml-auto">
                                <div class="d-print-none with-border">
                                    <a href="{{ url('/admin/invoice/create') }}"
                                       class="btn btn-primary" data-style="zoom-in">
                                        <span class="ladda-label"><i class="la la-plus"></i> Generar rebuts</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="col-md-12" style="padding-top:10px">
                                <a class="btn btn-default btn-md ttip confirm-print"
                                   href="{{ backpack_url('invoice') }}" data-original-title="" title="">
                                    Veure tots els rebuts
                                </a>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <form action="{{ url('/admin/invoice/get-by-range-dates') }}" method="post">
                                @csrf
                                <label class="col-sm-12" style="padding-top:20px">Selecciona un rang de dates:</label>
                                <label class="col-sm-12">Data d'inici</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="date" name="start_date"
                                           value="{{ old('start_date') }}">
                                </div>
                                <label class="col-sm-12">Data fi (opcional)</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="date" name="end_date"
                                           value="{{ old('end_date') }}">
                                </div>
                                <div class="col-md-12" style="padding-top:10px">
                                    <button class="btn btn-info"><i class="la la-search" aria-hidden="true"></i> Cerca
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if($invoices->count())
                            <hr>
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="padding-top:20px">Selecciona
                                            l'any:</label>
                                        @foreach ($invoices as $invoice)
                                            <div class="col-md-12" style="padding-top:10px">
                                                <a href="{{ url('/admin/invoice?years=' . date('Y', strtotime($invoice->created_at))) }}"
                                                   class="btn btn-default">
                                                    <span class="glyphicon glyphicon-calendar"
                                                          aria-hidden="true"></span> {{date('Y', strtotime($invoice->created_at))}}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" style="padding-top:20px">Rebuts no pagats:</label>
                            <div class="col-md-12" style="padding-top:10px">
                                <a class="btn btn-default btn-md ttip confirm-print"
                                   href="{{ url('/admin/invoice?not_paid=1') }}" data-original-title="" title="">
                                    <i class="la la-info-circle" aria-hidden="true"></i> Veure rebuts no pagats ( tots )
                                </a>
                            </div>
                            <div class="col-md-12" style="padding-top:10px">
                                <a class="btn btn-default btn-md ttip confirm-print"
                                   href="{{ url('/admin/invoice?last_not_paid=1') }}" data-original-title="" title="">
                                    <i class="la la-info-circle" aria-hidden="true"></i> Veure rebuts no pagats ( últim
                                    de cada titular )
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
