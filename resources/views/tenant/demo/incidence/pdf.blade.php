<!DOCTYPE html>
<html style="margin: 0; padding: 0px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('backpack.incidences.single') }}</title>
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
                    <img src="{{ public_path('/images/logos/logo.png') }}" width="100%">
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
                    @if($entry->type === 'owner_incidence')
                    <b>ACTA D'INCIDÈNCIA DE LA PARADA NÚMERO {{$entry->stall->num}} DEL
                        MERCAT DE VENDA NO SENDENTÀRIA DE {{$entry->stall->market->name}}</b>
                    @elseif($entry->type === 'specific_activities')
                    <b>ACTA D'INCIDÈNCIA PER ACTIVITAT PUNTUAL AL MERCAT DE VENDA 
                        NO SENDENTÀRIA DE {{$entry->stall->market->name}}</b>
                    @elseif($entry->type === 'general_incidence')
                    <b>ACTA D'INCIDÈNCIA GENERAL DEL MERCAT DE VENDA NO SENDENTÀRIA {{$entry->market->name}}</b>
                    @endif
                </p>
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11">
                <b>{{$entry->title}}</b>
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                @if($entry->type === 'owner_incidence' || $entry->type === 'specific_activities')
                    A la ciutat de Mercagest, en data <b>{{$entry->date_incidence->format("d-m-Y")}}</b>, l'inspector/a de Mercagest en/na
                    <b>{{$entry->user->name}}</b> com a responsable del servei dels Mercats de venda no sedentària i treballador de l'empresa
                    ESPIMSA amb seu social al Passatge Cobos 4, es persona a la/les parada/es núm. <b>{{$entry->stall->num}}</b>, de la
                    qual n'és el/la titular en/na <b>{{$entry->stall->titular}}</b> amb el document d'identitat <b>{{$entry->stall->titular_info->dni}}</b> domicili <b>{{$entry->stall->titular_info->address}}</b> i
                    adreça electrònica a efectes de notificació <b>{{$entry->stall->titular_info->email}}</b>
                @elseif($entry->type === 'general_incidence')
                    A la ciutat de Mercagest,en data <b>{{$entry->date_incidence->format("d-m-Y")}}</b>, l'inspector/a de Mercagest en/na <b>{{$entry->user->name}}</b> 
                    com a responsable del servei dels Mercats de venda no sedentària i treballador de l'empresa ESPIMSA amb seu 
                    social al Passatge Cobos 4.
                @endif
            </div>
        </div>
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                Exposa que:<br>
                {{$entry->description}}
            </div>
        </div>
        @if($entry->images)
        <div class="row" style="padding-top:30px;">
            @foreach($entry->images as $image)
            <div class="col-xs-5">
                <img class="img-responsive" src="{{ storage_path('/app/'.$image) }}">
            </div>
            @endforeach
        </div>
        @endif

        <div class="row">
            <div class="col-xs-11" style="font-size: 80%; padding-top:30px;">
                Queda la incidència com a <b>{{$entry->status == 'pending' ? 'NO' : ''}}</b> resolta.
            </div>
        </div>

        @if($entry->contact_email_id != NULL)
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-11" style="font-size: 80%">
                I es remet comunicació a: <br>
                <b>{{$entry->contact_email->name}} - {{$entry->contact_email->email}}</b>
            </div>
        </div>
        @endif

        <div class="row" style="padding-top:60px;">
            <div class="col-xs-11" style="font-size: 80%">
                En conformitat amb els fets descrits anteriorment, l’inspector/a <b>{{$entry->user->name}}</b> signa la present acta a <b>{{date("d-m-Y")}}</b>.
            </div>
        </div>
        @if($entry->user->signature)
        <div class="row" style="padding-top:30px;">
            <div class="col-xs-3">
                <img class="img-responsive" src="{{ storage_path('/app/'.$entry->user->signature) }}">
            </div>
        </div>
        @endif
    </div>
</body>
</html>