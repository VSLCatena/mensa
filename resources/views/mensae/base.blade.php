@extends('base')

@section('content')
    <div class="container mensa-overview">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Overzicht van de mensa op {{ formatDate($mensa->date) }}</div>
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified">
                            <form method="POST" action="#" class="d-inline btn-group">
                                <input type="submit" class="btn btn-default" value="// Iemand inschrijven" />
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                            </form>
                            <form method="POST" action="{{ route('mensa.edit') }}" class="d-inline btn-group">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                <input type="submit" class="btn btn-default" value="Mensagegevens wijzigen" />
                            </form>

                            <form method="POST" action="#" class="d-inline btn-group">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                <input type="submit" class="btn btn-default" value="// Mensastaat printen" />
                            </form>
                            <form method="POST" action="#" class="d-inline btn-group">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                <input type="submit" class="btn btn-default" value="// Mensa annuleren" />
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li{!! Route::is('mensa.overview') ? ' class="active"' : '' !!}>
                                <form method="POST" action="{{ route('mensa.overview') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                    <input type="submit" class="btn btn-link" value="Samenvatting" />
                                </form>
                            </li>
                            <li{!! Route::is('mensa.overview.signins') ? ' class="active"' : '' !!}>
                                <form method="POST" action="{{ route('mensa.overview.signins') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                    <input type="submit" class="btn btn-link" value="Inschrijvingen" />
                                </form>
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