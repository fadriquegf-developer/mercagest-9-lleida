<!DOCTYPE html>
<html style="margin: 0; padding: 0px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('backpack.checklists.single') }}</title>
    <link type="text/css" href="{{ base_path() . '/public/css/bootstrap-3.css' }}" rel="stylesheet" />
    <style>
        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 1cm;
            margin-left: 80px;
            margin-right: 60px;
            margin-bottom: 1cm;
        }
        
        /** Define the header rules **/
        header {
            position: fixed;
            bottom: 510px;
            left: -327px;
            transform: rotate(-90deg);

            /** Extra personal styles **/
            color: black;
            line-height: 1.5cm;
            font-size: 60%;
        }
        
        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        
            /** Extra personal styles **/
            color: black;
            text-align: center;
            line-height: 1.5cm;
        }
        </style>
</head>
<body>
    {{-- <header>
        Empresa de Serveis i Promocions d’Iniciatives Municipals SA · CIF A-43096163 · Reg. Mercantil de Tarragona, tom 991, Secció Societats, Foli 81, full núm. T-4775, inscripció 21ª
    </header>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-xs-offset-1 col-xs-10" style="margin-top: 16px;">
                    <img src="{{ public_path('/images/logos/tarragona/footer-tarragona.png') }}" width="100%">
                </div>
            </div>
        </div>
    </footer> --}}

    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <img class="img-responsive" src="{{ public_path('/images/logos/logo.png') }}" style="position:absolute;left:20px; top:-1cm;" width="150px">
            </div>
        </div>
        <div class="row" style="padding-top:40px;">
            <div class="col-xs-11">
                <p style="text-transform: uppercase;">
                    <b>ACTA D’INSPECCIÓ DE LA PARADA NÚMERO {{$origin->num}} DEL MERCAT DE VENDA NO SENDENTÀRIA {{$origin->market->name}}</b>
                </p>
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                A la ciutat de Mercagest, en data <b>{{$entry->created_at->format('d-m-Y')}}</b> , l'inspector/a de Mercats de Mercagest en/na <b>{{$entry->user->name}}</b>
                com a responsable del servei dels Mercats de venda no sedentària i treballador de l'empresa ESPIMSA amb
                seu social al Passatge Cobos 4, es persona a la/les parada/es núm. <b>{{$origin->num}}</b>, de la qual n'és el/la
                titular en/na <b>{{$origin->titular}}</b> amb el document d'identitat <b>{{$origin->titular_info->dni}}</b> domicili <b>{{$origin->titular_info->address}}</b> i adreça
                electrònica a efectes de notificació <b>{{$origin->titular_info->email}}</b>
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                De la inspecció practicada es comproven els següents fets rellevants:
            </div>
        </div>
        <div class="row" style="padding-top:20px;">
            <div class="col-xs-11">
                    @foreach ($groups as $group => $answers)
                        @foreach ($answers as $answer)
                            @php
                                $question = $answer->checklist_question;
                            @endphp
                            @if($answer->is_check)
                                <table class="table table-bordered" style="font-size: 80%">
                                    <tbody>
                                    <tr>
                                        <th scope="row" width="30%">TIPUS</th>
                                        <td style="text-transform: capitalize;">{{ $group }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Descripció infracció</th>
                                        <td>{{ $question->text }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Comentari inspector/a</th>
                                        <td>{{ $answer->comment }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Normativa reguladora</th>
                                        <td>{{ $question->regulation }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tipus infracció</th>
                                        <td>{{ trans('backpack.checklists.severity.'.$question->severity) }}</td>
                                    </tr>
                                    @if ($answer->img)
                                    <tr>
                                        <th scope="row">Imatge</th>
                                        <td><img src="data:image/png;base64,{{ base64_encode(Storage::get($answer->img)) }}" width="100%"></td>
                                    </tr>
                                    @endif
                                </tbody>
                                </table>
                            @endif
                        @endforeach
                    @endforeach
            </div>
        </div>
        <div class="row" style="padding-top:60px;">
            <div class="col-xs-11" style="font-size: 80%">
                En conformitat amb els fets descrits anteriorment, l’inspector/a <b>{{$entry->user->name}}</b> signa la present acta a <b>{{ date("d-m-Y") }}</b>.
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-3">
                <img class="img-responsive" src="{{ storage_path('/app/'.$entry->user->signature) }}">
            </div>
        </div>
    </div>
</body>
</html>

