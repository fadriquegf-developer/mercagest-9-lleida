@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
        trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
        $crud->entity_name_plural => url($crud->route),
        trans('backpack::crud.list') => false,
    ];
    
    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">Llistat de rebuts generats</span>
        </h2>
    </div>
@endsection

@section('content')
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="{{ $crud->getListContentClass() }}">

            <div class="row mb-0">
                <div class="card col-sm-12">
                    <div class="card-body">
                        <form method="post" action="{{ route('generate-gtt') }}">
                            {!! csrf_field() !!}
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rebut N.</th>
                                        <th>Titular</th>
                                        <th>NIF</th>
                                        <th>IBAN</th>
                                        <th>Periode</th>
                                        <th>Concepte</th>
                                        <th>Metres / Mòduls</th>
                                        <th>Dies</th>
                                        <th>Preu</th>
                                        <th>Subtotal</th>
                                        <th>Total</th>
                                        <th>Accions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($total = 0)
                                    @forelse ($invoices as $invoice)
                                        <tr class="invoice-{{ $invoice->id }} border-top-2">
                                            <td>
                                                <input type="hidden" name="invoices[]" value="{{ $invoice->id }}">
                                                #{{ $invoice->id }}
                                            </td>
                                            <td><a href="/admin/person/{{$invoice->person->id}}/show" target="_blank">{{ $invoice->person->name }}</a></td>
                                            <td>{{ $invoice->person->dni }}</td>
                                            <td>{{ $invoice->person->iban }}</td>
                                            <td>
                                                @if($invoice->type == 'mensual')
                                                    @php($meses = ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'])
                                                    {{$meses[$invoice->month-1]}} - {{$invoice->years}}
                                                @else 
                                                    {{$invoice->trimestral}} trimestre - {{$invoice->years}}
                                                @endif
                                            </td>
                                            <td colspan="6"></td>
                                            <td>
                                                <a href="#" class="btn btn-danger"
                                                    onclick='deleteInvoice({{ $invoice->id }})'><i
                                                        class="las la-trash"></i></a>
                                            </td>
                                        </tr>
                                        @foreach ($invoice->concepts as $invoice_concept)
                                            <tr class="invoice-{{ $invoice->id }}">
                                                <td colspan="5"></td>
                                                @switch($invoice_concept->concept)
                                                    @case('stall')
                                                        <td>{{ $invoice_concept->stall->market->name }} @if (isset($invoice_concept->stall->classe))
                                                                - {{ $invoice_concept->stall->classe->name }}
                                                            @endif - {{ $invoice_concept->stall->num }}</td>
                                                        <td>
                                                            @if ($invoice_concept->concept_type == 'meters')
                                                                {{ $invoice_concept->length }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($invoice_concept->type_rate == 'daily' || $invoice_concept->qty_days > 0)
                                                                {{ $invoice_concept->qty_days }}
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format((float)$invoice_concept->price, 2, ',', '.') }}</td>
                                                        <td class="text-success">{{ number_format((float)$invoice_concept->subtotal, 2, ',', '.') }}</td>
                                                    @break

                                                    @case('expenses')
                                                        <td>Despeses Mant. {{ $invoice_concept->stall->market->name }} @if (isset($invoice_concept->stall->classe))
                                                                - {{ $invoice_concept->stall->classe->name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($invoice_concept->concept_type == 'meters')
                                                                {{ $invoice_concept->length }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($invoice_concept->type_rate == 'daily' || $invoice_concept->qty_days > 0)
                                                                {{ $invoice_concept->qty_days }}
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format((float)$invoice_concept->price, 2, ',', '.') }}</td>
                                                        <td class="text-success">{{ number_format((float)$invoice_concept->subtotal, 2, ',', '.') }}</td>
                                                    @break

                                                    @case('bonuses')
                                                        @if($invoice_concept->title)
                                                            <td colspan="4">{{$invoice_concept->title}}</td>
                                                        @else 
                                                            <td colspan="4">Bonus</td>
                                                        @endif
                                                        <td class="text-danger">-{{ number_format((float)$invoice_concept->subtotal, 2, ',', '.') }}</td>
                                                    @break

                                                    @default
                                                @endswitch
                                                @if ($loop->last)
                                                    @php($total +=  $invoice->total)
                                                    <td class="text-success font-weight-bold" colspan="2">
                                                        {{ number_format((float)$invoice->total, 2, ',', '.') }}</td>
                                                @else
                                                    <td colspan="2"></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="11">
                                                    <div class="alert alert-info">
                                                        No hi han rebuts pendents per generar
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <th colspan="10">Total</th>
                                            <th colspan="2" id="total">{{number_format((float)$total, 2, ',', '.')}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- This makes sure that all field assets are loaded. -->
                                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
                                @if (count($invoices) > 0)
                                    <button type="submit" class="btn btn-success" name="action" value="excel">
                                        <span class="la la-table" role="presentation" aria-hidden="true"></span> &nbsp;
                                        <span>Descarregar Provisional</span>
                                    </button>
                                    <button type="submit" class="btn btn-success" name="action" value="gtt">
                                        <span class="la la-ticket-alt" role="presentation" aria-hidden="true"></span> &nbsp;
                                        <span>Descarregar GTT</span>
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('after_styles')
        <style>
            .removed-invoice {
                background: grey;
                color: white;
                text-decoration: line-through;
            }

            .removed-invoice .btn-danger {
                display: none;
            }
        </style>
    @endsection

    @push('after_scripts')
        <script>
            function deleteInvoice(invoice) {
                $.ajax({
                    type: 'POST',
                    url: `/admin/invoice/delete`,
                    data: {
                        id: invoice
                    },
                    success: function(result, status, xhr) {
                        if (result.status == 'ok') {
                            $('.invoice-' + invoice).addClass('removed-invoice');

                            //Obtenemos el total
                            var total =  $('#total').html();
                            
                            //Del total, cambiamos el punto de las miles, por nada, i la coma de los decimales, por punto, para poder trabajar como float
                            total = total.replace('.','');
                            total = total.replace(',','.');

                            //Restamos al total lo eliminado
                            total = parseFloat(total) - parseFloat(result.price);

                            //Transformamos el total, el un numero con milemios i decimales
                            total = total.toLocaleString('es-ES');

                            $('#total').html(total);
                        } else {
                            console.log('Error:' + result);
                        }
                    }
                });
            }
        </script>
    @endpush
