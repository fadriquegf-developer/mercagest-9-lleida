@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        'Generar' => false,
    ];
    
    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">Generar Informe</span>
        </h2>
    </section>
@endsection

@section('content')
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-0">
                <div class="card col-sm-8">
                    <div class="card-body">
                        <form class="container" method="post" action="{{ backpack_url('/report/download') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="align-self-center mr-1 align-items-center"
                                            for="type">{{ __('backpack.report.type') }}</label>
                                        <select name="type" id="type" class="form-control align-self-center" id="type">
                                            @foreach (__('backpack.report.types') as $k => $type)
                                                <option value="{{ $k }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="alert alert-info" id="info-liquidacio">L'informe de <b>Liquidació d'ingressos</b> recull la informació dels rebuts generats d'un mes en concret o trimestre (depenent del tipus de mercat tindrà un rebut mensual o trimestral).</div>
                                    <div class="alert alert-info" id="info-acumulat">L'informe d'<b>Import acumulat</b> agrupa la informació de tots els rebuts generats en un any concret d'un tipus de Mercat i per cada paradista.</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group ">
                                        <label class="align-self-center mr-1 align-items-center" style="width: 130px"
                                            for="file_type">{{ __('backpack.report.file_type') }}</label>
                                        <select name="file_type" id="file_type" class="form-control align-self-center">
                                            @foreach (__('backpack.report.file_types') as $k => $type)
                                                <option value="{{ $k }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="form-group">
                                        <label class="align-self-center mr-1" style="width: 130px"
                                            for="date">{{ __('backpack.report.market_type') }}</label>
                                        <select name="marketgroup_id" class="form-control">
                                            <option selected="selected" disabled="disabled">-</option>
                                            @foreach (\App\Models\MarketGroup::get() as $marketgroup)
                                                <option value="{{ $marketgroup->id }}">
                                                    {{ $marketgroup->titleGenerateInvoice }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-6">
                                    <div class="form-group">
                                        <label class="align-self-center mr-1" style="width: 130px"
                                            for="date">{{ __('backpack.report.from') }}</label>
                                        <input id="from" type="date" name="from" class="form-control"
                                            value="{{ $today }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="align-self-center mr-1" style="width: 130px"
                                            for="date">{{ __('backpack.report.to') }}</label>
                                        <input id="to" type="date" name="to" class="form-control">
                                    </div>
                                </div> --}}

                                <div class="form-group col-sm-12" element="div" bp-field-wrapper="true"
                                    bp-field-name="marketgroup_id" bp-field-type="select_from_array">
                                    <label>Tipus de Mercat</label>
                                    <select name="marketgroup_id" class="form-control" id="marketgroup_id">
                                        @foreach (\App\Models\MarketGroup::get() as $marketgroup)
                                            <option value="{{ $marketgroup->id }}">{{ $marketgroup->titleGenerateInvoice }}
                                            </option>
                                        @endforeach
                                        <option value="" id="all_marketgroup">Tots els tipus de mercat</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12" element="div" bp-field-wrapper="true"
                                    bp-field-name="years" bp-field-type="select_from_array">
                                    <label>Any</label>
                                    <select name="years" class="form-control">
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12" element="div" bp-field-wrapper="true"
                                    bp-field-name="month" bp-field-type="select_from_array" id="month">
                                    <label>Periode</label>
                                    <select name="month" class="form-control">
                                        <option value="1">Gener</option>
                                        <option value="2">Febrer</option>
                                        <option value="3">Març</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Maig</option>
                                        <option value="6">Juny</option>
                                        <option value="7">Juliol</option>
                                        <option value="8">Agost</option>
                                        <option value="9">Setembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Novembre</option>
                                        <option value="12">Desembre</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12" element="div" bp-field-wrapper="true"
                                    bp-field-name="trimestral" bp-field-type="select_from_array" id="trimestral">
                                    <label>Trimestre</label>
                                    <select name="trimestral" class="form-control">
                                        <option value="1">1r trimestre</option>
                                        <option value="2">2n trimestre</option>
                                        <option value="3">3r trimestre</option>
                                        <option value="4">4t trimestre</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <span class="la la-ticket-alt" role="presentation" aria-hidden="true"></span> &nbsp;
                                <span>Generar Informe</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('packages/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
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
            $('.table').dataTable();

            $(document).on('change', '#date', function(e) {
                let date = $("#date").val();
                if (date) {
                    location.href = `/admin/day-report/${date}`;
                }
            });
        });
    </script>
    <script>
        $('#info-acumulat').hide();
        $('#trimestral').hide();
        $('#trimestral select').prop('disabled', true);
        $('#month').hide();
        $('#month select').prop('disabled', true);
        $('#all_marketgroup').hide();
        //Para cambiar al selector dependiendo de si el Grupo de mercado es por Trimestre o por Mensualidad
        $('#marketgroup_id').change(function() {
            if($('#type').val() != 'accumulated amount'){
                label = $("select[name='marketgroup_id'] option:selected").text();
                if (label.includes('Mensual')) {
                    $('#month').show();
                    $('#month select').prop('disabled', false);
                    $('#trimestral').hide();
                    $('#trimestral select').prop('disabled', true);
                } else {
                    $('#month').hide();
                    $('#month select').prop('disabled', true);
                    $('#trimestral').show();
                    $('#trimestral select').prop('disabled', false);
                }
            }
        }).change();

        $('#type').change( function () {
            //alert(this.value);
            if(this.value == 'accumulated amount'){
                $('#month select').prop('disabled', true);
                $('#trimestral select').prop('disabled', true);
                $('#info-acumulat').show();
                $('#info-liquidacio').hide();
                $('#all_marketgroup').show();
            }else{
                $('#info-acumulat').hide();
                $('#info-liquidacio').show();
                $('#month select').prop('disabled', false);
                $('#trimestral select').prop('disabled', false);
                $('#all_marketgroup').hide();
            }
        });
    </script>
@endsection
