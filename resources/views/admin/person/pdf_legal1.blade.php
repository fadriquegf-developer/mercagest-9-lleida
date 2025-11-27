<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" href="{{ asset('css/bootstrap-3.css') }}" rel="stylesheet" />
    <title>Comprovacions deute</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
        }

        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }


        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .page_break {
            page-break-before: always;
        }

        .mt-1 {
            margin-top: 15px;
        }

        .title-header {
            text-align: center;
            border: 1px dotted;
            border-radius: 6px;
            padding: 15px;
        }

        .form-check {
            position: relative;
            display: block;
            /* padding-left: 1.25rem; */
        }

        .form-check-input {
            position: absolute;
            margin-top: 0.3rem;
            margin-left: -1.25rem;
        }

        .form-check-input[disabled]~.form-check-label,
        .form-check-input:disabled~.form-check-label {
            color: #6c757d;
        }

        .form-check-label {
            margin-bottom: 0;
            font-weight: 400 !important;
        }

        .form-check-inline {
            display: -ms-inline-flexbox;
            display: inline-flex;
            -ms-flex-align: center;
            align-items: center;
            padding-left: 0;
            /* margin-right: 0.75rem; */
        }

        .form-check-inline .form-check-input {
            position: static;
            margin-top: 0;
            margin-right: 0.3125rem;
            margin-left: 0;
        }

        th,
        td {
            padding: 2px 8px !important;
        }

        .table td,
        .table th {
            border: 1px solid black !important
        }
    </style>

</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-xs-3 text-center">
                    <img src="{{ asset('/images/logos/lleida/lleida-logo.jpg') }}" height="80px">
                </div>
                <div class="col-xs-8">
                    <div class="title-header">
                        <b>AUTORITZACIÓ PER A LA CONSULTA DE DADES<br>
                            Venda no sedentària de Lleida</b>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="padding-top:35px !important;">

        <table class="table">
            <tr>
                <th colspan="2">DADES DE LA PERSONA INTERESSADA</th>
            </tr>
            <tr>
                <td>CIF/NIF</td>
                <td>Raó Social / Nom i cognoms</td>
            </tr>
            <tr>
                <td style="background-color: #f1f4ff">
                    @if(isset($data['myself_nif'])) {{ $data['myself_nif'] }} @endif
                </td>
                <td style="background-color: #f1f4ff">
                    @if(isset($data['myself_name'])) {{ $data['myself_name'] }} @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" >
                    <label class="radio-inline">
                        <label class="form-check-label">
                            Actua:
                        </label>
                    </label>
                    <label class="radio-inline" style="padding-top: 6px;">
                        @if (isset($data['act']) && $data['act'] == 'myself')
                            <img src="{{ asset('images/radio.png') }}" height="16px" alt="">
                        @else
                            <img src="{{ asset('images/no-radio.png') }}" height="16px" alt="">
                        @endif
                        <label class="form-check-label">
                            En nom propi
                        </label>
                    </label>
                    <label class="radio-inline" style="padding-top: 6px;">
                        @if (isset($data['act']) && $data['act'] == 'other')
                            <img src="{{ asset('images/radio.png') }}" height="16px" alt="">
                        @else
                            <img src="{{ asset('images/no-radio.png') }}" height="16px" alt="">
                        @endif
                        <label class="form-check-label">
                            Mitjançant representant
                        </label>
                    </label>
                </td>
            </tr>
            <tr>
                <th colspan="2">DADES DE LA PERSONA REPRESENTANT (en cas de persones jurídiques)</th>
            </tr>
            <tr>
                <td>NIF</td>
                <td>Nom i cognoms</td>
            </tr>
            <tr>
                <td style="background-color: #f1f4ff">
                    @if(isset($data['other_nif'])) {{ $data['other_nif'] }} @endif
                </td>
                <td style="background-color: #f1f4ff">
                    @if(isset($data['other_name'])) {{ $data['other_name'] }} @endif
                </td>
            </tr>
        </table>

        <table class="table table-bordered mt-1" style="margin-bottom: 12px;">
            <tr>
                <th>
                    DADES DE L'AUTORITZACIÓ
                </th>
            </tr>
            <tr>
                <td>
                    D'acord amb els articles 5 i 7 A).4 de l'Ordenança municipal de la venda no
                    sedentària de
                    Lleida i l'art. 28 de la Llei 39/2015, d'1 d'octubre, LPAC, per verificar el
                    compliment de la
                    normativa relativa a les autoritzacions per la venda no sedentària,
                    l'Ajuntament
                    de Lleida
                    accedirà o obtindrà les dades de les administracions seguents:
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
                <td>
                    <div style="margin: 10px 0px;"><b>AUTORITZO</b></div>
                    <div class="form-check" style="margin-top: 4px;">
                        @if (isset($data['national_insurance']))
                            <img src="{{ asset('images/checked.png') }}" height="16px" alt="">
                        @else
                            <img src="{{ asset('images/no-checked.png') }}" height="16px" alt="">
                        @endif
                        <label class="form-check-label" for="national_insurance">
                            la consulta de la situació del deute davant la Tresoreria General de la
                            Seguretat Social.
                        </label>
                    </div>
                    <p>
                        Aquesta autorització <b>tindrà vigència durant l'any natural en curs</b> en
                        que
                        es presenta.<br>
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
            @if (isset($data['accept']))
                <img src="{{ asset('images/checked.png') }}" height="16px" alt="">
            @else
                <img src="{{ asset('images/no-checked.png') }}" height="16px" alt="">
            @endif
            <label class="form-check-label" for="true">
                He llegit i accepto els termes i condicions relatius a protecció de dades.
            </label>
        </div>

        <table class="table mt-1">
            <tr>
                <td style="width: 8%;">Lleida, </td>
                <td style="background-color: #f1f4ff">{{ $data['signature_date'] }}</td>
            </tr>
            <tr>
                <th style="width: 10%;">Signatura: </th>
                <td style="padding: 8px !important;">
                    <img src="{{ $data['signature'] }}" height="65px">
                </td>
            </tr>
        </table>
    </div>


    <div class="page_break"></div>
    <div class="container" style="padding-top:35px !important;">
        <table class="table">
            <tr>
                <th colspan="2" style="background-color: #d9d9d9;text-align:center;">Informació detallada sobre
                    Protecció de Dades</th>
            </tr>
            <tr>
                <td>Responsable</td>
                <td>Ajuntament de Lleida. CIF P2515100B. Plaça Paeria, 1. 25007 Lleida.
                    Adreça del delegat de protecció de dades: <a href="mailto:dpd@paeria.es">dpd@paeria.es</a></td>
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
                    portabilitat, oposar-vos al tractament i sol·licitar-ne la limitació, enviant la vostra
                    sol·licitud al Registre General de l'Ajuntament de Lleida, Oficina Municipal
                    d'Atenció Ciutadana, Rambla Ferran 32, baixos, 25007 Lleida.
                    Si considereu que els vostres drets no s'han atès adequadament, teniu dret a
                    presentar una reclamació davant l'Autoritat Catalana de Protecció de Dades.
                    No obstant, si us adreceu prèviament al delegat de protecció de dades aquest us
                    ajudarà a resoldre el cas: <a href="mailto:dpd@paeria.es">dpd@paeria.es</a></td>
            </tr>
        </table>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
        $text = "Pàg. {PAGE_NUM} de {PAGE_COUNT}";
        $size = 8;
        $font = $fontMetrics->getFont("Arial, Helvetica, sans-serif");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
    </script>
</body>

</html>
