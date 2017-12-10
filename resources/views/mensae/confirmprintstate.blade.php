@extends('base')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="{{ route('mensa.printstate', ['mensaId' => $mensa->id]) }}" class="panel panel-warning">
                    <div class="panel-heading">
                        Weet je zeker dat je de mensastaat van {{ formatDate($mensa->date) }} wilt uitprinten?
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger" value="Ja" />
                        <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-success">Nee</a>
                        <a href="{{ route('mensa.printstate.preview', ['id' => $mensa->id]) }}" target="_blank" class="btn btn-default">Alleen bekijken</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
