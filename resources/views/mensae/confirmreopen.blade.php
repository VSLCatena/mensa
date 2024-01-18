@extends('base')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="{{ route('mensa.open', ['mensaId' => $mensa->id]) }}" class="panel panel-warning">
                    <div class="panel-heading">
                        Weet je zeker dat je de mensa op {{ formatDate($mensa->date) }} wilt heropenen?
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger" value="Ja" />
                        <a href="{{ route('mensa.overview', ['mensaId' => $mensa->id]) }}" class="btn btn-success">Nee</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
