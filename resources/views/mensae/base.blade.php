@extends('base')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
@endsection

@section('content')
    <div class="container mensa-overview">
        <div class="row">
            <div class="col-xs-12 admin">
                <div class="panel panel-default">
                    <div class="panel-heading">Overzicht van de mensa op {{ formatDate($mensa->date) }}</div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified">
                            @if(!$mensa->closed)
                                @admin <a href="{{ route('mensa.newsignin', ['id' => $mensa->id]) }}" class="btn btn-default">Iemand inschrijven</a> @endadmin
                                <a href="{{ route('mensa.edit', ['id' => $mensa->id]) }}" class="btn btn-default">Mensagegevens wijzigen</a>
                            @else
                                @admin
                                    <a href="{{ route('mensa.open', ['id' => $mensa->id]) }}" class="btn btn-default">Mensa openen voor wijzigingen</a>
                                @else
                                    <span class="btn btn-default disabled">Mensa is gesloten en kan niet meer gewijzigd worden!</span>
                                @endadmin
                            @endif
                            @admin
                                <a href="{{ route('mensa.printstate', ['id' => $mensa->id]) }}" class="btn btn-default">Mensastaat printen</a>
                                <a href="{{ route('mensa.cancel', ['id' => $mensa->id]) }}" class="btn btn-default">Mensa annuleren</a>
                            @endadmin
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li{!! Route::is('mensa.overview') ? ' class="active"' : '' !!}>
                                <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}">Samenvatting</a>
                            </li>
                            <li{!! Route::is('mensa.signins') ? ' class="active"' : '' !!}>
                                <a href="{{ route('mensa.signins', ['id' => $mensa->id]) }}">Inschrijvingen</a>
                            </li>
                            @admin
                                <li{!! Route::is('mensa.logs') ? ' class="active"' : '' !!}>
                                    <a href="{{ route('mensa.logs', ['id' => $mensa->id]) }}">Logs</a>
                                </li>
                            @endadmin
                        </ul>
                    </div>
                    <div class="panel-body">
                        @yield('overview.content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection