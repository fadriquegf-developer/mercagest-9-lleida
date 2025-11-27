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
    @include('admin.maps.base_map_styles')
    @include('admin.maps.demo.m' . cache()->get('market' . auth()->user()->id))
    <!-- Default box -->
    <div class="row">
        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-0">
                <div class="card col-sm-12">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div class="">
                                <label class="align-self-center mr-1" for="date">
                                    {{ __('backpack.maps.index_label_info') }}
                                </label>
                                <strong>{{ \Carbon\Carbon::parse($today)->format('d-m-Y') }}</strong>
                            </div>
                            <div class="d-flex align-content-between">
                                <label class="align-self-center mr-1 w-100" for="date">{{ __('backpack.maps.index_label_date') }}</label>
                                <input id="date" type="date" class="form-control" value="{{ $today }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="has_day" name="has_day" value="{{ $has_day }}"/>
                                    <input type="hidden" id="calendar_id" name="calendar_id" value="{{ $day->id }}"/>
                                    <section id="focal">
                                        <div class="parent">
                                            <div class="panzoom"
                                                 style="position:relative; height:800px; transform:matrix(1, 0, 0, 1, 0, 0); transform-origin: 50% 50% 0;">
                                                <img class="map-styles" src="{{ asset($path_bg) }}" alt="">
                                                @foreach ($stalls as $stall)
                                                    @for ($i = 0; $i < $stall->n_zones; $i++)
                                                        @php
                                                            $forbidden_signs = [' ', '/', ',', '?', '(', ')', "'", '.'];
                                                            $id = 'parada' . str_replace($forbidden_signs, "-", $stall->num);
                                                            if($i > 0){
                                                                $id .= '_ex_' . $i;
                                                            }
                                                        @endphp

                                                        <a class="parada parada-{{ $stall->id }}
                                                        @if ($stall->has_absence === true)
                                                            @if($stall->absence_type == 'justificada')
                                                                assistencia-justificada
                                                            @else 
                                                                assistencia-no
                                                            @endif
                                                        @else
                                                            assistencia-si
                                                        @endif
                                                            "
                                                        id="{{ $id }}" 
                                                        data-id="{{ $stall->id }}"
                                                        data-parada="{{ $stall->num }}"
                                                        data-ownerstallid="{{ $stall->titular_id }}"
                                                        data-name="{{ $stall->titular_info->name }}"
                                                        data-licence="{{ $stall->titular_info->license_number }}">
                                                           <span>{{ $stall->titular_info->license_number }}</span>
                                                        </a>
                                                    @endfor
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="overlay" style="display:none"></div>
                                        
                                        <div class="modal" tabindex="-1" id="opcions">
                                            <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header py-0">
                                                    <h1 class="modal-title">
                                                        Parada nº <span></span>
                                                    </h1>
                                                    <a href="#" class="tancar"></a>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="p-3">
                                                        <p style="text-align: left;">
                                                            <b>Nom paradista:</b> <span id="person-name"></span><br>
                                                            <b>Número de llicència:</b> <span id="license-number"></span>

                                                            <h2 class="nomes_dia">Assistència:</h2>
                                                            <div class="toggle nomes_dia">
                                                                <a id="link-assistencia-si" href="#" class="si">Si</a>
                                                                <a id="link-assistencia-no" href="#" class="no">No</a>
                                                            </div>

                                                            <a id="link-info" href="#" class="btn btn-outline-primary btn-lg btn-block" target="_blank">Informació</a>
                                                            <a id="link-accreditation" href="#" class="btn btn-outline-primary btn-lg btn-block" target="_blank">Acreditació</a>
                                                            <a id="link-absences" href="#" class="btn btn-outline-primary btn-lg btn-block" target="_blank">Absències</a>
                                                            <a id="link-incidencies" href="#" class="btn btn-outline-primary btn-lg btn-block" target="_blank">Incidències</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <script src="{{ asset('packages/map/fastclick.min.js') }}"></script>
    <script src="{{ asset('packages/map/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/map/jquery.panzoom.min.js') }}"></script>
    <script src="{{ asset('packages/map/jquery.mousewheel.min.js') }}"></script>
    <script>
        var rutaServer = "{{ $base_url }}/";

        var enllacInfoGlobal = rutaServer + "mapa/getinfo";
        var enllacInfo = rutaServer + "admin/stall";
        var enllacAccreditation = rutaServer + "admin/person/accreditation";
        var enllacIncidencia = rutaServer + "admin/incidences/create?stall_id=";
        var enllacAbsence = rutaServer + "admin/absence/create?stall_id=";
        var enllacAssistencia = rutaServer + "admin/absence/toggle";

        var ownerstall_id = 0;
        var parada = 0;
        var parada_id = 0;
        var has_day = {{ $day->id !== 0 ? 'true' : 'false' }};

        if(!has_day){
            $('.nomes_dia').hide();
        }

        $(document).ready(function (){
            FastClick.attach(document.body);
            this.panning = true;
            // just grab a DOM element
            let element = $('#focal')

            // And pass it to panzoom
            let panzoom = element.find('.panzoom').panzoom({
                increment: 0.3,
                minScale: 0.25,
                maxScale: 2,
                contain: false
            }).panzoom("pan", {{ $x }}, {{ $y }}, {
                relative: true
            });
            panzoom.panzoom("zoom", {{ $zoom }}, {
                silent: true
            });

            panzoom.on('panzoomend', function(e, panzoom, matrix, changed) {
                console.log(matrix);
                if (!changed) {
                    var stall = $(e.target).closest('.parada');
                    parada = stall.attr('data-parada');
                    parada_id = stall.attr('data-id');
                    ownerstall_id = stall.attr('data-ownerstallid');
                    let te_falta = stall.hasClass('assistencia-no');
                    let name = stall.attr('data-name');
                    let licence = stall.attr('data-licence');

                    console.log('habesmus parada',parada);
                    if (!parada) {
                        return false;
                    }

                    if (te_falta) {
                        $('.si').removeClass('actiu');
                        $('.no').addClass('actiu');
                    } else {
                        $('.no').removeClass('actiu');
                        $('.si').addClass('actiu');
                    }

                    $('#opcions h1 span').html(parada);
                    $('#person-name').html(name);
                    $('#license-number').html(licence);
                    $('#link-info').prop('href', `${enllacInfo}/${parada_id}/show`);
                    $('#link-accreditation').prop('href', `${enllacAccreditation}/${ownerstall_id}`);
                    // $('#link-rebuts').prop('href',`${enllacInfo}/${parada_id}/show?tab=Rebuts`);
                    $('#link-absences').prop('href',`${enllacAbsence}${parada_id}`);
                    $('#link-incidencies').prop('href', `${enllacIncidencia}${parada_id}`);
                    $('#link-asistencia-si').prop('href', enllacAssistencia);
                    $('#link-asistencia-no').prop('href', enllacAssistencia);

                    $('.overlay').fadeIn(300);
                    $('#opcions').fadeIn(100);
                    $(e.target).closest('.parada').addClass('destacat');
                }
            });

            panzoom.parent().on('mousewheel.focal', function(e) {
                e.preventDefault();
                var delta = e.delta || e.originalEvent.wheelDelta;
                var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
                panzoom.panzoom('zoom', zoomOut, {
                    increment: 0.1,
                    animate: false,
                    focal: e
                });
            });

            $(document).on('change', '#date', function (){
                if($('#date').val()) location.href = `/admin/maps?date=${$('#date').val()}`;
            })

            $('.si,.no').click(function(event) {
                let btn = $(this);
                event.stopPropagation();
                event.preventDefault();

                // Ajax per guardar assitència
                if (btn.hasClass("si")) {
                    valortoggle = true;
                } else {
                    valortoggle = false;
                }

                if (valortoggle) {
                    // show alert
                    swal({
                        title: "{!! trans('backpack::base.warning') !!}",
                        text: "{!! trans('backpack.maps.alerts.delete_abcence') !!}",
                        icon: "warning",
                        buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.delete') !!}"],
                        dangerMode: true,
                    }).then((value) => {
                        if (value) {
                            toggleAbcence(btn);
                        }
                    });
                } else {
                    toggleAbcence(btn)
                }

            });

            $('.tancar').click(function(event) {
                event.stopPropagation();
                event.preventDefault();
                $('.overlay').fadeOut(300);
                $('#opcions').fadeOut(100);
                $('.parada').removeClass('destacat');
            });
        })

        function toggleAbcence(btn) {
            $.get(enllacAssistencia, {
                    calendar_id: {{ $day->id }},
                    stall: parada_id,
                    owner: ownerstall_id,
                    value: valortoggle
                })
                .done(function(data) {
                    $('.si,.no').removeClass('actiu');
                    btn.addClass('actiu');

                    rel_parada = '#parada' + parada;
                    if (data.success === true) {
                        $(rel_parada).removeClass('assistencia-no');
                        $(rel_parada).addClass('assistencia-si');
                    } else {
                        $(rel_parada).removeClass('assistencia-si');
                        $(rel_parada).addClass('assistencia-no');
                    }
                })
                .fail(function() {
                    alert("Error connectant al servidor");
                });
        }
    </script>
@endpush

