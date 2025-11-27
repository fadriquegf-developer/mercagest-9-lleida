<!-- This file is used to store topbar (right) items -->
{{--<li class="dropdown">
    <a class="nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="la la-language"></i>
        {{ Config::get('languages')[App::getLocale()] }}</a>
    <div
        class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1">
        @foreach (Config::get('languages') as $lang => $language)
            @if ($lang != App::getLocale())
                <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"><i class="la la-language"></i>
                    {{ $language }}</a>
            @endif
        @endforeach
    </div>
</li>--}}
{{-- <li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-bell"></i><span class="badge badge-pill badge-danger">5</span></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-list"></i></a></li>
<li class="nav-item d-md-down-none"><a class="nav-link" href="#"><i class="la la-map"></i></a></li> --}}
