@extends('base')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="{{ route('mensa.cancel', ['id' => $mensa->id]) }}" class="panel panel-warning">
                    <div class="panel-heading">
                        Weet je zeker dat je de mensa op {{ formatDate($mensa->date) }} wilt annuleren?
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger" value="Ja" />
                        <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-success">Nee</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
