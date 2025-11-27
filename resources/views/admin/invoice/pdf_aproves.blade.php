<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 120px 25px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        footer{ position: fixed; bottom: 60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
        p:last-child { page-break-after: never; }
        table {
        border-collapse: collapse;
        }

        #footer { position: fixed; right: 0px; top: -100px; text-align: center;border-top: 1px solid black;}
        #footer .page:after { content: counter(page, decimal); }

        #company-name{
            position: fixed; 
            left: 0px; 
            top: -140px; 
            text-align: center;
        }

        .info{
        float: right;
        border: 1px solid black;
        width: 40%;
        margin-bottom: 20px;
        }

        .customer{
        }

        tr, td{
            border: 1px solid black;
        }

        .company-name{
            font-size: 40px;
        }

        .items {
            width: 100%;
            padding-top: 20px;
            border: 1px solid black;
        }

        .items td{
            text-align: center;
        }

        .company-info{
            line-height: 10px;
            padding: 6px 0px;
        }
        .firstline th{
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

    <p id="company-name" class="company-name"><img src="{{ public_path('/images/logos/lleida/lleida-logo.jpg') }}" alt="Logo Lleida" height="100px"></p>

    {{-- <div class="info">
                Adress
    </div>
 --}}
    <div id="footer">
        <p class="page">Plana </p>
    </div> 
    <div class="company-info">

    </div>
    @php($meses = ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Dicembre'])
    @php($fecha = \Carbon\Carbon::createFromDate($invoices->first()->years, $invoices->first()->month))
    <table class="items">
        
        <thead>
            <tr id="firstline" class="firstline">
                <th colspan="5">RELACIO DE REBUTS DE MERCATS MUNICIPALS QUE ES LIQUIDEN PER EL SEU COBRAMENT A * {{ strtoupper($invoices->first()->market_group->title) }}</th>
            </tr>
            <tr id="firstline" class="firstline">
                <th id="invoicenumber" colspan="2">PERIODE: {{ strtoupper($meses[$fecha->format('n') - 1]) }} {{ $invoices->first()->years }}</th>
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
                <td>{{$item->id}}</td>
                <td>{{$item->person->dni}}</td>
                <td colspan="2">{{$item->person->name}}</td>
                <td>{{number_format($item->total, 2,',','.')}}</td>
            </tr>
            @php($total_invoices = $total_invoices + $item->total)
            @empty
            <p>No hi ha rebuts</p>
            @endforelse
        </tbody>
        <tfoot class="global-footer">

            {{-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>BTW 21%</td>
                    <td>21 </td>
            </tr> --}}

            <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>TOTAL QUOTES</td>
                    <td>{{number_format($total_invoices, 2,',','.')}}</td>
            </tr>
        </tfoot>
    </table>
    <main>
        @php($page = floor($invoices->count()/36))
        @for($x = 0; $x <= $page; $x++)
            <div style="page-break-after: always;"></div>
        @endfor
        <div style="page-break-after: always;">
            <table class="items">
                <thead>
                    <tr id="firstline" class="firstline">
                        <th colspan="5">RELACIO DE REBUTS DE MERCATS MUNICIPALS QUE ES LIQUIDEN PER EL SEU COBRAMENT A * {{ strtoupper($invoices->first()->market_group->title) }}</th>
                    </tr>
                    <tr id="firstline" class="firstline">
                        <th id="invoicenumber" colspan="2">PERIODE: {{ strtoupper($meses[$fecha->format('n') - 1]) }} {{ $invoices->first()->years }}</th>
                        <th id="date" colspan="2">DATA PROCES: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            <p style="line-height: 20px; padding-top: 40px;">
                Vista l'anterior relació de quotes a càrrec dels titulars de parades municipals,
                liquidades d'acord amb les tarifes regulades per l'ordenança fiscal 2.9, taxa
                per mercats, que per part del responsable del departament i el Servei de Gestió
                Tributària i Inspecció es proposa per a la seva aprovació de l'Ill.m Sr.Alcalde,
                d'acord a allò que disposa el Reial decret legislatiu 2/2004, de 5 de març, que
                aprova el text refós de la Llei d'Hisendes Locals, Llei 7/1985, de 2 d'abril,
                reguladora de les bases del règim local i la Llei 58/2003, de 17 de desembre,
                general tributària.
            </p>
            <table class="items" style="border: none !important;">
                <tbody style="border: none !important;">
                    <tr style="border: none !important;">
                        <td style="border: none !important;">El Cap de la Unitat o responsable</td>
                        <td style="border: none !important;">La Cap de Servei de Gestió Tributària i Inspecció</td>
                    </tr>
                </tbody>
            </table>
            {{-- <p style="line-height: 20px;">El Cap de la Unitat o responsable</p>
            <p style="line-height: 20px;">La Cap de Servei de Gestió Tributària i Inspecció</p> --}}
            <p style="line-height: 20px; padding-top: 60px;">
                Vista la proposta anterior,en virtud de les facultats que pertanyen a l'Alcaldia,
                d'acord amb el que estableix el D.L. 2/2003 de 28 d'abril del Text Refós de la
                Llei Municipal i de Règim Local de Catalunya,
            </p>
            <p style="line-height: 20px;">RESOLC :</p>
            <p style="line-height: 20px;">Aprovar la present relació de quotes que puja un total de: {{ number_format($total_invoices, 2,',','.') }} euros</p>
            <p style="line-height: 20px;">Així ho mano i signo en la data de la signatura electrònica, del que la Secretaria General en dóna fe.</p>
        </div>
    </main>
</body>

</html>