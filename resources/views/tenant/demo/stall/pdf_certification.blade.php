<!DOCTYPE html>
<html style="margin: 0; padding: 0px;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Acreditació</title>
    <link type="text/css" href="{{ base_path() . '/public/css/bootstrap-3.css' }}" rel="stylesheet" />
</head>

<body style="padding-left:30px;">
    <div class="container">
        <div class="row">
            <div class="col-xs-3" style="padding-top:16px;">
                <img class="img-responsive" src="{{ public_path('/images/logos/logo.png') }}" height="50px">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-11">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-11">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Certificació de Parada per {{ $data['titular'] }}</h5>
                    </div>
                    <div class="panel-body">
                        <p>
                            Regidoria de Consum, Comerç, Mercats i Participació es fa constar que
                            <b>{{ $data['titular'] }}</b> és titular d’una llicència en el <b>{{ $data['market'] }}</b>
                            amb la parada nùmero <b>{{ $data['stall'] }}</b> en la data <b>{{ $data['created_at'] }}.</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row" style="position: absolute; bottom: 20px; left: 40px;">
            <div class="col-12 text-extra-small">
                Regidoria de Consum, Comerç, Mercats i Participació<br>
                Avinguda de Tortosa, 2-Edifici Mercolleida 25005 Lleida<br>
                Tel. 973 700 616 Fax. 973 700 484<br>
                imc@paeria.cat
            </div>
        </div> --}}
    </div>
</body>

</html>
