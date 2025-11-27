@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <!-- header here -->
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <p>{{ $communication->message }}</p>
    </div>
    
    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
        Gràcies,<br>
        {{ \Setting::get('mail_subcopy', 'Mercagest') }}
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        {{-- © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') --}}
        @endcomponent
    @endslot
@endcomponent