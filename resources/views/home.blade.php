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
                                        @if(strlen($mensa->cooksFormatted()) > 0)
                                            <br />Gekookt door: {{ $mensa->cooksFormatted() }}
                                        @endif
                                    </td>
                                    <td>&euro;{{ $mensa->price }}</td>
                                    <td>
                                        {{ $mensa->users()->count() }}/{{ $mensa->max_users }}<br />
                                        @if(count($mensa->dishwashers()) > 0)
                                            {{ count($mensa->dishwashers()) }} afwasser{{ ((count($mensa->dishwashers()) > 1)?'s':'') }}*
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$mensa->closed)
                                            {{ $mensa->closingTime(true) }}
                                        @else
                                            <span class="text-danger">Gesloten</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical">
                                            @if(!Auth::check() || !Auth::user()->service_user)
                                                @if(Auth::check() && $mensa->users->where('lidnummer', Auth::user()->lidnummer)->count() > 0)
                                                    <form method="POST" @admin class="btn-group-vertical" @endadmin action="{{ route('signout', ['id' => $mensa->id]) }}">
                                                        <input type="submit" class="btn btn-danger {{ $mensa->closed?'disabled':'' }}" value="Uitschrijven" {{ $mensa->closed?'disabled':'' }} />
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{ $mensa->id }}" />
                                                    </form>
                                                @elseif(!$mensa->closed)
                                                    <a href="{{ route('signin', ['id' => $mensa->id]) }}" class="btn btn-primary">Inschrijven</a>
                                                @else
                                                    <span class="btn btn-primary disabled">Gesloten</span>
                                                @endif
                                            @endif
                                            @admin
                                                <a href="{{ route('mensa.overview', ['id' => $mensa->id]) }}" class="btn btn-primary">Bekijken</a>
                                            @endadmin
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    << >>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
