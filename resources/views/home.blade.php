@extends('base')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Mensae in de komende 2 weken</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Beschrijving</th>
                                <th>Prijs</th>
                                <th>Inschrijvingen</th>
                                <th>Sluittijd</th>
                                <th>Inschrijven</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mensae as $mensa)
                                <tr>
                                    <td>{{ $mensa->date }}</td>
                                    <td>
                                        {{ $mensa->title }}<br />
                                        <small>{{ $mensa->description }}</small>
                                    </td>
                                    <td>-</td>
                                    <td>{{ $mensa->users->count() }}/{{ $mensa->max_users }}</td>
                                    <td>{{ $mensa->closing_time }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('signup') }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                            <input type="submit" class="btn btn-primary" value="Inschrijven" />
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
