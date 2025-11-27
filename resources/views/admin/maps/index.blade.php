@extends(backpack_view('blank'))

@php
$breadcrumbs = '\\';
@endphp

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize"></span>
        </h2>
    </div>
@endsection

@section('content')
    <h2>{{ __('backpack.maps.hint') }}</h2>
    <div class="row">
        @foreach (auth()->user()->markets as $market)
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $market->name }}</h5>
                        <a href="{{ url('/admin/set-market/' . $market->id) }}" class="btn btn-primary">{{ __('backpack.maps.select') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
