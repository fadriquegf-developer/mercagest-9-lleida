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
                        <div class="container">
                            <form method="POST"
                                action="{{ route('person.save_legal_doc', ['id' => $person->id, 'type' => $type]) }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        <div class="text-danger">Comproveu que tots els camps del document estiguin
                                            degudament emplenats.
                                        </div>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif


                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2">DADES DE LA PERSONA INTERESSADA</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="myself_nif">CIF/NIF</label>
                                                <input type="text"
                                                    class="form-control @error('myself_nif') is-invalid @enderror"
                                                    id="myself_nif" name="myself_nif" value="{{ $person->dni }}" disabled>
                                            </div>

                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="myself_name">Raó Social / Nom i cognoms</label>
                                                <input type="text"
                                                    class="form-control @error('myself_name') is-invalid @enderror"
                                                    id="myself_name" name="myself_name" value="{{ $person->name }}" disabled>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">Actua: </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input @error('act') is-invalid @enderror"
                                                    type="radio" name="act" id="myself" value="myself"
                                                    @checked(old('act') == 'myself')>
                                                <label class="form-check-label" for="myself">En nom propi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input @error('act') is-invalid @enderror"
                                                    type="radio" name="act" id="other" value="other"
                                                    @checked(old('act') == 'other')>
                                                <label class="form-check-label" for="other">Mitjançant
                                                    representant</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">DADES DE LA PERSONA REPRESENTANT (en cas de persones jurídiques)
                                        </th>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="other_nif">NIF</label>
                                                <input type="text"
                                                    class="form-control @error('other_nif') is-invalid @enderror"
                                                    id="other_nif" name="other_nif" value="{{ old('other_nif') }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="other_name">Nom i cognoms</label>
                                                <input type="text"
                                                    class="form-control @error('other_name') is-invalid @enderror"
                                                    id="other_name" name="other_name" value="{{ old('other_name') }}">
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table class="table table-bordered mt-1">
                                    <tr>
                                        <th>
                                            DADES DE L'AUTORITZACIÓ
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                D'acord amb els articles 5 i 7 A).4 de l'Ordenança municipal de la venda no
                                                sedentària de
                                                Lleida i l'art. 28 de la Llei 39/2015, d'1 d'octubre, LPAC, per verificar el
                                                compliment de la
                                                normativa relativa a les autoritzacions per la venda no sedentària,
                                                l'Ajuntament
                                                de Lleida
                                                accedirà o obtindrà les dades de les administracions seguents:
                                            </p>
                                            <ul>
                                                <li>Agència Estatal d'Administració Tributària: comprovació que no hi hagi
                                                    deutes pendents
                                                    de pagament.</li>
                                                <li>Tresoreria General de la Seguretat Social: comprovació que no hagi
                                                    deutes pendents
                                                    de pagament.</li>
                                            </ul>
                                            <p>
                                                Per tal que l'Ajuntament de Lleida pugui realitzar les comprovacions, la
                                                persona
                                                adjudicatària ha de manifestar la seva conformitat amb aquestes.
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            AUTORITZO
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input @error('tax_administration') is-invalid @enderror"
                                                    type="checkbox" value="tax_administration" id="tax_administration"
                                                    name="tax_administration" @checked(old('tax_administration') == 'tax_administration')>
                                                <label class="form-check-label" for="tax_administration">
                                                    la consulta de la situació del deute davant l'Agència Estatal
                                                    d'Administració Tributària.
                                                </label>
                                            </div>
                                            <p>
                                                Aquesta autorització <b>tindrà vigència durant l'any natural en curs</b> en
                                                que
                                                es presenta.
                                                En cas que no aporteu l'autorització a les consultes, caldrà acreditar els
                                                requisits
                                                sol·licitats, aportant els certificats negatius de deutes de les
                                                administracions
                                                corresponents
                                                per a continuar amb el procediment d'adjudicació.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-check">
                                    <input class="form-check-input @error('accept') is-invalid @enderror" type="checkbox"
                                        value="true" id="accept" name="accept" @checked(old('accept') == 'accept')>
                                    <label class="form-check-label" for="accept">
                                        He llegit i accepto els termes i condicions relatius a protecció de dades.
                                    </label>
                                </div>

                                <table class="mt-1">
                                    <tr>
                                        <td colspan="2">
                                            <div class="form-inline my-3">
                                                <label for="inlineFormInputName2">Lleida,</label>
                                                <input type="text"
                                                    class="form-control ml-2 @error('signature_date') is-invalid @enderror"
                                                    id="signature_date" name="signature_date"
                                                    value="{{ old('signature_date') }}">
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@include('admin.inc.signature-pad')</td>
                                    </tr>
                                </table>

                            </form>

                            <table class="table table-bordered mt-4">
                                <tr>
                                    <th colspan="2">Informació detallada sobre Protecció de Dades</th>
                                </tr>
                                <tr>
                                    <td>Responsable</td>
                                    <td>Ajuntament de Lleida. CIF P2515100B. Plaça Paeria, 1. 25007 Lleida.
                                        Adreça del delegat de protecció de dades: <a
                                            href="mailto:dpd@paeria.es">dpd@paeria.es</a></td>
                                </tr>
                                <tr>
                                    <td>Finalitat</td>
                                    <td>La finalitat del tractament de dades és la de gestionar el tràmit sol·licitat.
                                        Respecte el termini de conservació de les dades, la regulació de la Llei 39/2015,
                                        d'1 d'octubre, del Procediment Administratiu Comú de les Administracions
                                        Públiques, estableix que la supressió dels documents haurà de ser autoritzada
                                        d'acord amb el que disposi la normativa aplicable.</td>
                                </tr>
                                <tr>
                                    <td>Legitimació</td>
                                    <td>La base legal per al tractament de les vostres dades és el compliment de la Llei
                                        39/2015, d'1 d'octubre, del Procediment Administratiu Comú de les
                                        Administracions Públiques.</td>
                                </tr>
                                <tr>
                                    <td>Destinataris</td>
                                    <td>Les dades es comunicaran als òrgans o unitats responsables de la seva
                                        tramitació dins de l'Ajuntament de Lleida o dels seus organismes autònoms.</td>
                                </tr>
                                <tr>
                                    <td>Drets</td>
                                    <td>Podeu accedir a les vostres dades, rectificar-les o suprimir-les, sol·licitar-ne la
                                        portabilitat, oposar-vos al tractament i sol·licitar-ne la limitació, enviant la
                                        vostra
                                        sol·licitud al Registre General de l'Ajuntament de Lleida, Oficina Municipal
                                        d'Atenció Ciutadana, Rambla Ferran 32, baixos, 25007 Lleida.
                                        Si considereu que els vostres drets no s'han atès adequadament, teniu dret a
                                        presentar una reclamació davant l'Autoritat Catalana de Protecció de Dades.
                                        No obstant, si us adreceu prèviament al delegat de protecció de dades aquest us
                                        ajudarà a resoldre el cas: <a href="mailto:dpd@paeria.es">dpd@paeria.es</a></td>
                                </tr>
                            </table>
                        </div>

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
@endsection
