<table class="table table-bordered">
    <thead>
        <tr>
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
        </tr>
    </thead>
    <tbody>
        @php($total = 0)
        @php($total_bonus = 0)
        @foreach ($invoices as $invoice)
            <tr class="invoice-{{ $invoice->id }} border-top-2">
                <td>{{ $invoice->person->name }}</td>
                <td>{{ $invoice->person->dni }}</td>
                <td>{{ $invoice->person->iban }}</td>
                <td>
                    @if ($invoice->type == 'mensual')
                        @php($meses = ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'])
                        {{ $meses[$invoice->month - 1] }} - {{ $invoice->years }}
                    @else
                        {{ $invoice->trimestral }} trimestre - {{ $invoice->years }}
                    @endif
                </td>
                <td colspan="6"></td>
                <td>
                    <a href="#" class="btn btn-danger" onclick='deleteInvoice({{ $invoice->id }})'><i
                            class="las la-trash"></i></a>
                </td>
            </tr>
            @foreach ($invoice->concepts as $invoice_concept)
                <tr class="invoice-{{ $invoice->id }}">
                    <td colspan="4"></td>
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
                            <td>{{ number_format((float) $invoice_concept->price, 2, ',', '.') }}</td>
                            <td class="text-success">{{ number_format((float) $invoice_concept->subtotal, 2, ',', '.') }}</td>
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
                            <td>{{ number_format((float) $invoice_concept->price, 2, ',', '.') }}</td>
                            <td class="text-success">{{ number_format((float) $invoice_concept->subtotal, 2, ',', '.') }}</td>
                        @break

                        @case('bonuses')
                            @php($total_bonus += $invoice_concept->subtotal)
                            @if ($invoice_concept->title)
                                <td colspan="4">{{ $invoice_concept->title }}</td>
                            @else
                                <td colspan="4">Bonus</td>
                            @endif
                            <td class="text-danger">-{{ number_format((float) $invoice_concept->subtotal, 2, ',', '.') }}</td>
                        @break

                        @default
                    @endswitch
                    @if ($loop->last)
                        @php($total += $invoice->total)
                        <td class="text-success font-weight-bold" colspan="2">
                            {{ number_format((float) $invoice->total, 2, ',', '.') }}</td>
                    @else
                        <td colspan="2"></td>
                    @endif
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="9">Total</td>
            <td>{{ number_format((float) $total, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="9">Total Bonificacions</td>
            <td>{{ number_format((float) $total_bonus, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
