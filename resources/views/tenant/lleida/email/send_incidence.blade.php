@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <!-- header here -->
        @endcomponent
    @endslot

    {{-- Body --}}
    @if($incidence->type === 'general_incidence')
    <p>Nova incidència al mercat <b>{{ $incidence->market->name }}</b></p>
    @else
    <p>Nova incidència al mercat <b>{{ $incidence->stall->market->name }}</b></p>
    <div><b>Parada:</b> {{ $incidence->stall->num }}</div>
    <div><b>Titular de la parada:</b> {{ $incidence->stall->getTitular()->name }}</div>
    @endif
    <div><b>Tipus: </b> {{ $incidence->transType() }}</div>
    <div><b>Títol incidència: </b> {{ $incidence->title }}</div>
    <div><b>Data incidència: </b> {{ $incidence->date_incidence->format('d-m-Y') }}</div>
    <div><b>Descripció incidència: </b> <p>{{ $incidence->description }}</p></div>
    
    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
        Gracies,<br>
        {{ \Setting::get('mail_subcopy', 'Mercagest') }}
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent