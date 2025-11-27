<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-simplybuilt"></i> {{ __('sidebar.Select Markets')}}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="/admin/set-market/all"> <span>{{ trans('sidebar.all_maps') }}</span></a></li>
        @foreach(auth()->user()->markets as $market)
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/set-market/' . $market->id) }}">
                    <span>{{ $market->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</li>
<div class="dropdown-divider"></div>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@can('day_report')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('day-report/' . \Carbon\Carbon::now()->toDateString()) }}"><i class="la la-bug nav-icon"></i> {{ __('sidebar.DayReport') }}</a></li>
@endcan
@can('report')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('report/' . \Carbon\Carbon::now()->toDateString()) }}"><i class="nav-icon la la-file-invoice"></i>{{ __('sidebar.Reports') }}</a></li>
@endcan
@can('persons.list')
<li class='nav-item'><a class='nav-link' href='{{ route('person.check_accreditation.form') }}'><i class='nav-icon la la-clipboard-check'></i> {{ __('sidebar.check_accreditation') }}</a></li>
@endcan

<li class="header">{{ __('sidebar.header.admin_markets') }}</li>
@can('persons.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('person') }}'><i class='nav-icon la la-users'></i> {{ __('sidebar.Person') }}</a></li>
@endcan
@can('stalls.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('stall') }}'><i class='nav-icon la la-warehouse'></i> {{ __('sidebar.Stalls') }}</a></li>
@endcan
@can('maps')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('maps') }}'><i class='nav-icon la la-map'></i> {{ __('sidebar.Maps') }}</a></li>
@endcan
@can('markets.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('market') }}'><i class='nav-icon la la-landmark'></i> {{ __('sidebar.Markets') }}</a></li>
@endcan
@can('market_groups.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('market-group') }}'><i class='nav-icon la la-archway'></i> {{ __('sidebar.MarketGroup') }}</a></li>
@endcan
@can('reasons.list')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('reason') }}"><i class="nav-icon la la-tags"></i> {{ __('sidebar.reasons') }}</a></li>
@endcan
@can('calendar.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('market/'.(Cache::get('market'. auth()->user()->id) ?? 'all').'/calendar') }}'><i class='nav-icon la la-calendar'></i> {{ __('sidebar.Calendar') }}</a></li>
@endcan
@can('absences.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('absence') }}'><i class='nav-icon la la-user-alt-slash'></i> {{ __('sidebar.Absences') }}</a></li>
@endcan
@can('incidences.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('incidences') }}'><i class='nav-icon la la-exclamation-circle'></i> {{ __('sidebar.Incidences') }}</a></li>
@endcan
@can('rates.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('rate') }}'><i class='nav-icon la la-percentage'></i> {{ __('sidebar.Rates') }}</a></li>
@endcan
@can('historicals.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('historical') }}'><i class='nav-icon la la-history'></i> {{ __('sidebar.Historicals') }}</a></li>
@endcan
@can('expedientes.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('expediente') }}'><i class='nav-icon la la-file-contract'></i> {{ __('sidebar.Expedientes') }}</a></li>
@endcan
@can('classes.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('classe') }}'><i class='nav-icon la la-tags'></i> {{ __('sidebar.Classes') }}</a></li>
@endcan
@can('invoices.list')
{{-- <li class='nav-item'><a class='nav-link' href='{{ url('/admin/invoice/view') }}'><i class='nav-icon la la-receipt'></i> {{ __('sidebar.Invoices') }}</a></li> --}}
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('invoice') }}'><i class='nav-icon la la-receipt'></i> {{ __('sidebar.Invoices') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('form-invoice-date-range') }}"><i class="la la-ticket-alt nav-icon"></i> {{ __('sidebar.gtt') }}</a></li>
@endcan
@can('checklists.list')
{{--<li class='nav-item'><a class='nav-link' href='{{ backpack_url('checklist') }}'><i class='nav-icon la la-list-alt'></i> {{ __('sidebar.checklists') }}</a></li>--}}
@endcan

<li class="header">{{ __('sidebar.header.other_admin') }}</li>
@can('towns.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('town') }}'><i class='nav-icon la la-city'></i> {{ __('sidebar.Towns') }}</a></li>
@endcan
{{--@can('extensions.list')--}}
{{--<li class='nav-item'><a class='nav-link' href='{{ backpack_url('extension') }}'><i class='nav-icon la la-arrows'></i> {{ __('sidebar.Extensions') }}</a></li>--}}
{{--@endcan--}}
@can('bonuses.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('bonus') }}'><i class='nav-icon la la-star'></i> {{ __('sidebar.Bonuses') }}</a></li>
@endcan
@can('communications.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('communication') }}'><i class='nav-icon la la-comments'></i> {{ __('sidebar.Communications') }}</a></li>
@endcan
@can('sectors.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('sector') }}'><i class='nav-icon la la-road'></i> {{ __('sidebar.Sectors') }}</a></li>
@endcan
@can('auth_prods.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('auth-prod') }}'><i class='nav-icon la la-box'></i> {{ __('sidebar.Productes Autoritzats') }}</a></li>
@endcan
@can('bic.list')
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('bic-conversion') }}"><i class="nav-icon la la-bank"></i>Codis BIC</a></li>
@endcan
{{-- @can('concessions.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('concession') }}'><i class='nav-icon la la-file-contract'></i> {{ __('sidebar.Concessions') }}</a></li>
@endcan --}}


@canany(['users.list', 'roles.list', 'permissions.list'])
<li class="header">{{ __('sidebar.header.advanced') }}</li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users-cog"></i> {{ __('sidebar.auth') }}</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span> {{ __('sidebar.users') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>{{ __('sidebar.roles') }}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>{{ __('sidebar.permissions') }}</span></a></li>
    </ul>
</li>
@endcanany

@can('logs.manage')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> {{ __('sidebar.logs') }}</a></li>
@endcan

@can('settings.list')
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> {{ __('sidebar.configs') }}</a></li>
@endcan


<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>