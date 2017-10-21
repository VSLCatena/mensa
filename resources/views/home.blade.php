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
                    <table class="table mensae">
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
                                    <td>{{ formatDate($mensa->date, true) }}</td>
                                    <td>
                                        {{ $mensa->title }}
                                        @if(strlen($mensa->description) > 0)
                                            <br /><small>{{ $mensa->description }}</small>
                                        @endif
                                        @foreach($mensa->extraOptions()->get() as $option)
                                            <br /><strong><u>Extra optie:</u></strong> {{ $option->description }} voor &euro;{{ $option->price }}
                                        @endforeach
                                        @if(strlen($mensa->cooks()) > 0)
                                            <br />Gekookt door: {{ $mensa->cooks() }}
                                        @endif
                                    </td>
                                    <td>&euro;{{ $mensa->price }}</td>
                                    <td>
                                        {{ $mensa->users()->count() }}/{{ $mensa->max_users }}<br />
                                        @if($mensa->dishwashers() > 0)
                                            {{ $mensa->dishwashers() }} afwasser{{ (($mensa->dishwashers() > 1)?'s':'') }}*
                                        @endif
                                    </td>
                                    <td>{{ $mensa->closingTime(true) }}</td>
                                    <td>
                                        @if(Auth::check() && $mensa->users->where('lidnummer', Auth::user()->lidnummer)->count() > 0)
                                            <form method="POST" action="{{ route('signout') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                                <input type="submit" class="btn btn-danger" value="Uitschrijven" />
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('signup') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                                <input type="submit" class="btn btn-primary" value="Inschrijven" />
                                            </form>
                                        @endif
                                        @admin
                                            <form method="POST" action="{{ route('mensa.edit') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                                <input type="submit" class="btn btn-primary" value="Aanpassen*" />
                                            </form>
                                        @endadmin
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
