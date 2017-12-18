@extends('base')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <form method="POST" action="{{ route('mensa.removesignin', ['mensaId' => $mUser->mensa->id, 'userId' => $mUser->id]) }}" class="panel panel-danger">
                    <div class="panel-heading">
                        Weet je zeker dat je {{ $mUser->user->name }} wilt uitschrijven
                        voor de mensa op {{ formatDate($mUser->mensa->date) }}?
                    </div>
                    <div class="panel-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $mUser->id }}" />
                        <input type="submit" class="btn btn-danger" value="Ja" />
                        <a href="{{ route('mensa.signins', ['id' => $mUser->mensa->id]) }}" class="btn btn-success">Nee</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
