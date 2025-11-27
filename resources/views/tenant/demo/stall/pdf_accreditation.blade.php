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
                        <h4>Targeta identificació per {{ $stall->titular }}</h5>
                    </div>
                    <div class="panel-body">
                        <strong>Núm. Llicència:</strong> {{ $stall->id }}
                        <div class="pull-right">
                            <strong>Any:</strong> {{ \Carbon\Carbon::now()->format('Y') }}
                        </div>
                        <div>
                            <strong>Mercat:</strong> {{ $stall->market->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <strong>Titular principal:</strong><br> {{ $stall->titular }}
                    </div>
                    <div class="panel-body text-center" style="height: 100px;">
                        @if(isset($stall->titular_info->image))
                        <img src="{{ storage_path('app/' . $stall->titular_info->image) }}" alt=""
                            height="100px">
                        @endif
                    </div>
                    <div class="panel-footer">
                        <strong>Nif:</strong> {{ $stall->titular_info->dni_code }}
                    </div>
                </div>
            </div>
            @foreach ($stall->titular_info->substitutes ?? [] as $substitute)
                <div class="col-xs-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Treballador:</strong><br> {{ $substitute->name }}
                        </div>
                        <div class="panel-body" style="height: 100px;">
                            @if($substitute->image)
                            <img src="{{ storage_path('app/' . $substitute->image) }}" alt=""
                                height="100px">
                            @endif
                        </div>
                        <div class="panel-footer">
                            <strong>Nif:</strong> {{ $substitute->dni_code }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-xs-11">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Informació addicional
                    </div>
                    <div class="panel-body">
                        <div>
                            <strong>Tipologia:</strong> @if(isset($stall->marketGroup->title)) {{ $stall->marketGroup->title }} @endif
                        </div>
                        <div>
                            <strong>Llocs:</strong>
                        </div>
                        <div>
                            <strong>Producte:</strong> @if(isset($stall->auth_prod->name)) {{ $stall->auth_prod->name }} @endif
                        </div>
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
