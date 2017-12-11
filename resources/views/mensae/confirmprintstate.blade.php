@extends('base')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Weet je zeker dat je de mensastaat van {{ formatDate($mensa->date) }} wilt uitprinten?
                    </div>
                    <div class="panel-body">
                        Het printen van de mensastaat zal ook alle nog niet bevestigde gebruikers uitschrijven.<br /><br />
                        <form method="POST" action="{{ route('mensa.printstate', ['mensaId' => $mensa->id]) }}">
                            <input type="submit" class="btn btn-danger" value="Ja" />
                            <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-success">Nee</a>
                        </form><br />
                        <a href="{{ route('mensa.printstate.preview', ['id' => $mensa->id]) }}" target="_blank" class="btn btn-default">Alleen bekijken</a>
                        @if(!$mensa->closed)
                            <form action="{{ route('mensa.close', ['id' => $mensa->id]) }}" method="POST" style="display: inline">
                                {{ csrf_field() }}
                                <input type="submit" class="btn btn-default" value="Alleen sluiten" />
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
