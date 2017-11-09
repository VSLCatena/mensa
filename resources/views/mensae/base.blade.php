@extends('base')

@section('content')
    <div class="container mensa-overview">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Overzicht van de mensa op {{ formatDate($mensa->date) }}</div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified">
                            <a href="#" class="btn btn-default">// Iemand inschrijven</a>
                            <a href="{{ route('mensa.edit', ['id' => $mensa->id]) }}" class="btn btn-default">Mensagegevens wijzigen</a>
                            <a href="#" class="btn btn-default">// Mensastaat printen</a>
                            <a href="#" class="btn btn-default">// Mensa annuleren</a>
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