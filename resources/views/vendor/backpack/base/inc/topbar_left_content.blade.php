<!-- This file is used to store topbar (left) items -->

{{-- <li class="nav-item px-3"><a class="nav-link" href="#">Dashboard</a></li>--}}

@if(auth()->user()->market)
    <li class="nav-item px-3 d-flex">
        <a class="nav-link mr-1" href="#">{{ auth()->user()->market }}</a>
        @if(auth()->user()->market_days)
            (<span>{{ auth()->user()->market_days }}</span>)
        @endif
    </li>
@endif
