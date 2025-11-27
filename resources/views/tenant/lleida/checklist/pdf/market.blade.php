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
        input[type=checkbox]
        {
            /* Double-sized Checkboxes */
            -ms-transform: scale(2); /* IE */
            -moz-transform: scale(2); /* FF */
            -webkit-transform: scale(2); /* Safari and Chrome */
            -o-transform: scale(2); /* Opera */
            transform: scale(2);
        }

        /* Might want to wrap a span around your checkbox text */
        .checkboxtext
        {
            /* Checkbox text */
            font-size: 110%;
            display: inline;
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
            <div class="col-xs-3" style="padding-top:16px;">
                <img class="img-responsive" src="{{ public_path('/images/logos/lleida/lleida-logo.jpg') }}" height="50px">
            </div>
            <div class="col-xs-2 col-xs-offset-5" style="padding-top:30px;">
                <img class="img-responsive" src="{{ public_path('/images/logos/lleida/mercats-lleida-logo.jpg') }}">
            </div>
        </div>
        <div class="row" style="padding-top:40px;">
            <div class="col-xs-11">
                <p style="text-transform: uppercase;">
                    <b>ACTA D’INSPECCIÓ DEL MERCAT DE VENDA NO SENDENTÀRIA {{$origin->name}}</b>
                </p>
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                A la ciutat de Lleida, en data <b>{{$entry->created_at->format('d-m-Y')}}</b>, l'inspector/a de Mercats de Lleida en/na <b>{{$entry->user->name}}</b>
                com a responsable del servei dels Mercats de venda no sedentària i treballador de la Regidoria de Consum, Comerç, Mercats i Participació de Lleida amb seu social a Av. Tortosa, 2.
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                De la inspecció practicada es comproven els següents fets rellevants
            </div>
        </div>
        <div class="row" style="padding-top:20px;">
            <div class="col-xs-11">
                <ul class="list-group mb-4">
                    @foreach ($groups as $group => $answers)
                        @foreach ($answers as $answer)
                            @php
                                $question = $answer->checklist_question;
                            @endphp
                            <li class="list-group-item rounded-0">
                                <div class="custom-control custom-checkbox">
                                    <label style="font-size: 80%" for="question_{{ $question->id }}" class="d-block custom-control-label">
                                        <input class="custom-control-input" id="question_{{ $question->id }}"
                                        name="question_{{ $question->id }}" type="checkbox" @checked(old('question_' . $question->id, $answer->is_check)) style="position:absolute; top:2px"> 
                                        <span style="padding-left: 18px;">{{ $question->text }}</span>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
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