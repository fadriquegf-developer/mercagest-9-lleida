<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 120px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            background-color: lightblue;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: 60px;
            left: 0px;
            right: 0px;
            background-color: lightblue;
            height: 50px;
        }

        p:last-child {
            page-break-after: never;
        }

        table {
            border-collapse: collapse;
        }

        #footer {
            position: fixed;
            right: 0px;
            top: -100px;
            text-align: center;
            border-top: 1px solid black;
        }

        #footer .page:after {
            content: counter(page, decimal);
        }

        #company-name {
            position: fixed;
            left: 0px;
            top: -140px;
            text-align: center;
        }

        .info {
            float: right;
            border: 1px solid black;
            width: 40%;
            margin-bottom: 20px;
        }

        .customer {}

        tr,
        td {
            border: 1px solid black;
        }

        .company-name {
            font-size: 40px;
        }

        .items {
            width: 100%;
            padding-top: 20px;
            border: 1px solid black;
        }

        .items td {
            text-align: center;
        }

        .company-info {
            line-height: 10px;
            padding: 6px 0px;
        }

        .firstline th {
            border: 0;
            text-align: left;
        }

        .footer {
            width: 100%;
            border: 0;
            bottom: 0px;
        }

        .global.footer {
            position: fixed;
            width: 100%;
            border: 0;
            bottom: 0;
        }

        .footer th {
            border: 0;
        }

        .header td {
            font-weight: bold;
        }

        p {
            line-height: 0.5;
        }
    </style>
</head>

<body>

    <p id="company-name" class="company-name"><img src="{{ public_path('/images/logos/lleida/lleida-logo.jpg') }}"
            alt="Logo Lleida" height="100px"></p>

    <div id="footer">
        <p class="page">Plana </p>
    </div>
    <div class="company-info">

    </div>
    @php
        if ($invoices->first()->type == 'mensual') {
            $meses = ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];
            $periode = $meses[$invoices->first()->month - 1] . ' - ' . $invoices->first()->years;
        } else {
            $periode = $invoices->first()->trimestral . ' trimestre - ' . $invoices->first()->years;
        }
        
    @endphp
    <main>
        <p style="line-height: 20px;">
            <b>Unitat administrativa:</b> Mercats<br>
            <b>Assumpte:</b> Liquidació de rebuts {{ strtoupper($invoices->first()->market_group->title) }}<br>
            <b>Periode:</b> {{ $periode }}
        </p>
        <p style="line-height: 20px;">
            <b>
                DECRET D’ALCALDIA <br>
                Sra. Montserrrat Pifarré Torres, 4a tinenta d’Alcaldia de Recursos Municipals, Hisenda i Salut.
            </b>
        </p>
        <p style="line-height: 20px;">
            <b>Antecedents</b>
        </p>
        <p style="line-height: 20px;">
            Atès l’informe favorable de la cap de Servei de Consum, Comerç i Promoció Econòmica per l’aprovació de
            rebuts d’acord amb les tarifes regulades per l´Ordenança Fiscal 2.9, taxa per mercats.
        </p>

        <table class="items">
            <thead>
                <tr id="firstline" class="firstline">
                    <th colspan="5">RELACIO DE REBUTS DE MERCATS MUNICIPALS QUE ES LIQUIDEN PER EL SEU COBRAMENT A *
                        {{ strtoupper($invoices->first()->market_group->title) }}</th>
                </tr>
                <tr id="firstline" class="firstline">
                    <th id="invoicenumber" colspan="2">PERIODE: {{ $periode }}
                        {{ $invoices->first()->years }}</th>
                    <th id="date" colspan="2">DATA PROCES: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</th>
                    <th></th>
                </tr>
                <tr id="0" class="header">
                    <td>Rebut</td>
                    <td>NIF</td>
                    <td colspan="2">Nom</td>
                    <td>Import</td>
                </tr>
            </thead>
            <tbody>
                @php($total_invoices = 0)
                @forelse($invoices as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->person->dni }}</td>
                        <td colspan="2">{{ $item->person->name }}</td>
                        <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                    @php($total_invoices = $total_invoices + $item->total)
                @empty
                    <p>No hi ha rebuts</p>
                @endforelse
            </tbody>
            <tfoot class="global-footer">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>TOTAL QUOTES</td>
                    <td>{{ number_format($total_invoices, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        <div style="page-break-after: always;"></div>
        <p style="line-height: 20px;">
            <b>Fonaments de dret </b>
        <ul>
            <li>El Reial decret legislatiu 2/2004, de 5 de març, que aprova el text refós de la Llei d´Hisendes Locals.
            </li>
            <li>La Llei 7/1985, de 2 d´abril, reguladora de les bases del Règim Local. </li>
            <li>La Llei 58/2003, de 17 de desembre, general tributària. </li>
            <li>El Decret Llei 2/2003 de 28 d´abril del Text Refós de la Llei Municipal i de Règim Local de Catalunya,
            </li>
        </ul>
        </p>
        <p style="line-height: 20px;">
            Vista la proposta anterior, en virtut de les facultats i d’acord amb les atribucions delegades pel senyor
            Alcalde per resolució de data 2 d’agost de 2019,
        </p>
        <p style="line-height: 20px;">
            <b>RESOLC :</b>
        </p>
        <p style="line-height: 20px;">
            @php($total_invoices = 0)
            @foreach ($invoices as $item)
                @php($total_invoices += $item->total)
            @endforeach
            Aprovar la present relació de quotes que puja un total de
            <b>{{ number_format((float) $total_invoices, 2, ',', '.') }}</b> euros.
        </p>
        <p style="line-height: 20px; margin-top:20px;">
            Així ho mano i signo en la data de la signatura electrònica, del que la Secretaria General en dóna fe.
        </p>
    </main>
</body>

</html>
